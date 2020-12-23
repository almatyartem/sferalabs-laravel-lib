<?php

namespace LaravelSferaLibrary\Db\DbDataProviders\Eloquent;

use Illuminate\Database\Eloquent\Model;
use LaravelSferaLibrary\Contracts\DbDataProviderContract;
use LaravelSferaLibrary\Contracts\QueryBuilderContract;
use LaravelSferaLibrary\Db\BaseEntity;
use LaravelSferaLibrary\Exceptions\NotFoundException;
use LaravelSferaLibrary\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class EloquentDbDataProvider implements DbDataProviderContract
{
    /**
     * @var string
     */
    protected string $entityClassName;

    /**
     * @var BaseEloquentModel|Model
     */
    protected $eloquentModel;

    /**
     * @var ModelToDTOConverter
     */
    protected ModelToDTOConverter $converter;

    /**
     * EloquentDbDataProvider constructor.
     * @param string $entityClassName
     */
    public function __construct(string $entityClassName)
    {
        $this->entityClassName = $entityClassName;
        $this->eloquentModel = new BaseEloquentModel($entityClassName);
        $this->converter = app(ModelToDTOConverter::class);
    }

    /**
     * @param int $id
     * @return BaseEntity|null
     */
    public function read(int $id): ?BaseEntity
    {
        return $this->toDto($this->eloquentModel->find($id));
    }

    /**
     * @return BaseEntity[]|null
     */
    public function all(): ?array
    {
        return $this->toDto($this->eloquentModel->all());
    }

    /**
     * @param int $id
     * @param array $data
     * @return BaseEntity|null
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function update(int $id, array $data) : ?BaseEntity
    {
        $record = $this->eloquentModel->find($id);

        if (!$record) {
            throw new NotFoundException();
        }

        $this->validate($data, false);

        try {
            $record->update($data);
        } catch (\Exception $exception) {
            return null;
        }

        return $this->toDto($record);
    }

    /**
     * @param array $data
     * @return BaseEntity|null
     * @throws ValidationException
     */
    public function create(array $data) : ?BaseEntity
    {
        $this->validate($data, true);

        return $this->toDto($this->eloquentModel->create($data));
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id) : bool
    {
        return $this->eloquentModel->destroy($id);
    }

    /**
     * @param int $id
     * @param string $relation
     * @param array $ids
     * @return BaseEntity|null
     */
    public function sync(int $id, string $relation, array $ids) : ?BaseEntity
    {
        $record = $this->eloquentModel->find($id);

        $record->$relation()->sync($ids);

        return $this->toDto($record);
    }

    /**
     * @return QueryBuilderContract|Builder
     */
    public function getBuilder()
    {
        return $this->eloquentModel->newQuery();
    }

    /**
     * @param QueryBuilderContract $builder
     * @return BaseEntity[]|null
     */
    public function get($builder) : ?array
    {
        return $this->toDto($builder->get());
    }

    /**
     * @param QueryBuilderContract $builder
     * @return BaseEntity|null
     */
    public function first($builder) : ?BaseEntity
    {
        return $this->toDto($builder->first());
    }

    /**
     * @param QueryBuilderContract $builder
     * @param string[] $columns
     * @return array
     */
    public function getAsArray($builder, $columns = ['*']) : array
    {
        $result = $builder->get($columns);

        return $result ? $result->toArray() : [];
    }

    /**
     * @param QueryBuilderContract $builder
     * @param string[] $columns
     * @return array
     */
    public function firstAsArray($builder, $columns = ['*']) : array
    {
        $result = $builder->first($columns);

        return $result ? $result->toArray() : [];
    }

    /**
     * @param QueryBuilderContract $builder
     * @return int
     */
    public function count($builder) : int
    {
        return $builder->count();
    }

    /**
     * @param $result
     * @return BaseEntity|BaseEntity[]|null
     */
    protected function toDto($result)
    {
        return $this->converter->fromModelOrCollection($result);
    }

    /**
     * @param array $data
     * @param bool $isCreation
     * @return $this
     * @throws ValidationException
     */
    protected function validate(array $data, bool $isCreation)
    {
        $rules = $this->getRules();

        if (!$isCreation) {
            $rules = array_intersect_key($rules, $data);
        }
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getRules() : array
    {
        /**
         * @var $entityClassName BaseEntity
         */
        $entityClassName = $this->entityClassName;

        return $entityClassName::getRules();
    }
}
