<?php

namespace LaravelSferaLibrary\DataLayer;

use LaravelSferaLibrary\DataLayer\DbDataProviders\Eloquent\BaseEloquentModel;
use LaravelSferaLibrary\Exceptions\NotFoundException;
use LaravelSferaLibrary\Exceptions\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

abstract class BaseRepository
{
    /**
     * @var string
     */
    protected string $entityClassName;

    /**
     * BaseRepository constructor.
     * @param string $entityClassName
     */
    public function __construct(string $entityClassName)
    {
        $this->entityClassName = $entityClassName;
    }

    /**
     * @param int $id
     * @return BaseEntity|null
     * @throws \Exception
     */
    public function read(int $id) : ?BaseEntity
    {
        return $this->toDtoFromRow($this->readRow($id));
    }

    /**
     * @param BaseEntity|array $entity
     * @return BaseEntity|mixed
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function save($entity)
    {

        if ($entity instanceof BaseEntity) {
            $data = $entity->except(...array_keys($entity::getRelations()))->toArray();
        } else {
            $data = $entity;
        }

        if ($data['id'] ?? null) {
            return $this->updateByArray($data['id'], $data);
        } else {
            return $this->createFromArray($data);
        }
    }

    /**
     * @param array $entities
     * @return BaseEntity[]|null
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function saveMany(array $entities) : ?array
    {
        $result = [];
        foreach ($entities as $key => $entity) {
            $result[] = $this->save($entity);
        }
        return $result;
    }

    /**
     * @param array $entities
     * @return BaseEntity[]|null
     * @throws ValidationException
     */
    public function createMany(array $entities) : ?array
    {
        $result = [];
        foreach ($entities as $key => $entity) {
            $result[] = $this->createFromArray($entity);
        }
        return $result;
    }

    /**
     * @param int $id
     * @param string $relation
     * @param array $ids
     * @return BaseEntity|null
     */
    protected function syncManyToMany(int $id, string $relation, array $ids) : ?BaseEntity
    {
        $model = new BaseEloquentModel([], $this->entityClassName);
        $record = $model->find($id);

        $record->$relation()->sync($ids);

        return $this->toDtoFromRow($record);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id) : bool
    {
        return (bool)DB::table($this->callEntity('getTable'))
            ->delete($id);
    }

    /**
     * @param array $data
     * @return BaseEntity|null
     * @throws ValidationException
     */
    public function createFromArray(array $data) : ?BaseEntity
    {
        unset($data['id']);

        $this->validate($data);

        if($createdAt = $this->callEntity('getCreatedAtColumnName')){
            $data[$createdAt] = date('Y-m-d H:i:s');
        }
        if($updatedAt = $this->callEntity('getUpdatedAtColumnName')){
            $data[$updatedAt] = date('Y-m-d H:i:s');
        }

        $id = DB::table($this->callEntity('getTable'))
            ->insertGetId($data);

        return $this->toDtoFromRow($this->readRow($id));
    }

    /**
     * @param int $id
     * @param array $data
     * @return BaseEntity|null
     * @throws ValidationException|NotFoundException
     */
    public function updateByArray(int $id, array $data) : ?BaseEntity
    {
        $this->validate($data, $id);

        if($updatedAt = $this->callEntity('getUpdatedAtColumnName')){
            $data[$updatedAt] = date('Y-m-d H:i:s');
        }

        DB::table($this->getTable())
            ->where('id', $id)
            ->update($data);

        return $this->read($id);
    }

    public function beginTransaction()
    {
        \DB::beginTransaction();
    }

    /**
     * @throws \Exception
     */
    public function commitTransaction()
    {
        \DB::commit();
    }

    /**
     * @throws \Exception
     */
    public function rollbackTransaction()
    {
        \DB::rollBack();
    }

    /**
     * @param array $data
     * @param int|null $id
     * @return $this
     * @throws ValidationException
     */
    protected function validate(array $data, int $id = null)
    {
        $rules = $this->getRules($id);

        if ($id) {
            $rules = array_intersect_key($rules, $data);
        }
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this;
    }

    /**
     * @param int|null $id
     * @return array
     */
    protected function getRules(int $id = null) : array
    {
        return $this->callEntity('getRules', [$id]);
    }

    /**
     * @return string
     */
    protected function getTable() : string
    {
        return $this->callEntity('getTable');
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    protected function callEntity(string $method, array $parameters = [])
    {
        return call_user_func([$this->entityClassName, $method], ...$parameters);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    protected function readRow(int $id)
    {
        return DB::table($this->callEntity('getTable'))
            ->find($id);
    }

    /**
     * @param $result
     * @return BaseEntity|BaseEntity[]|null
     */
    protected function toDtoFromRow($result)
    {
        return $this->callEntity('fromArray', [(array) $result]);
    }
}
