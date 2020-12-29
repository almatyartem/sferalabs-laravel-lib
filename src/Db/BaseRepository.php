<?php

namespace LaravelSferaLibrary\Db;

use LaravelSferaLibrary\Contracts\BaseRepositoryContract;
use LaravelSferaLibrary\Exceptions\NotFoundException;
use LaravelSferaLibrary\Exceptions\ValidationException;
use LaravelSferaLibrary\Contracts\DbDataProviderContract;

abstract class BaseRepository implements BaseRepositoryContract
{
    /**
     * @var DbDataProviderContract
     */
    protected $dbProvider;

    /**
     * BaseCRUDProvider constructor.
     * @param DbDataProviderContract $dbProvider
     */
    public function __construct(DbDataProviderContract $dbProvider)
    {
        $this->dbProvider = $dbProvider;
    }

    /**
     * @param int $id
     * @return BaseEntity|null
     */
    public function read(int $id) : ?BaseEntity
    {
        return $this->dbProvider->read($id);
    }

    /**
     * @param BaseEntity $entity
     * @return BaseEntity|null
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function save($entity) : ?BaseEntity
    {
        $data = $entity->toArray();

        if ($data['id'] ?? null) {
            return $this->updateByArray($data['id'], $data);
        } else {
            return $this->createFromArray($data);
        }
    }

    /**
     * @param array $entities
     * @return BaseEntity|null
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function saveMany(array $entitities) : ?array
    {
        $result = [];
        foreach ($entitities as $key => $entity) {
            $result[] = $this->save($entity);
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
        return $this->dbProvider->sync($id, $relation, $ids);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id) : bool
    {
        return $this->dbProvider->delete($id);
    }

    /**
     * @param array $data
     * @return BaseEntity|null
     * @throws ValidationException
     */
    protected function createFromArray(array $data) : ?BaseEntity
    {
        $data = $this->getOnlyDataWithRules($data);

        return $this->dbProvider->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return BaseEntity|null
     * @throws ValidationException|NotFoundException
     */
    protected function updateByArray(int $id, array $data) : ?BaseEntity
    {
        $data = $this->getOnlyDataWithRules($data);

        return $this->dbProvider->update($id, $data);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getOnlyDataWithRules(array $data) : array
    {
        return array_intersect_key($data, call_user_func([static::getEntityClass(), 'getRules']));
    }
}
