<?php

namespace LaravelSferaTemplate\Contracts;

use LaravelSferaTemplate\Exceptions\NotFoundException;
use LaravelSferaTemplate\Exceptions\ValidationException;
use LaravelSferaTemplate\Db\BaseEntity;

interface DbDataProviderContract
{
    /**
     * @param array $data
     * @return BaseEntity|null
     * @throws ValidationException
     */
    public function create(array $data) : ?BaseEntity;

    /**
     * @param int $id
     * @return BaseEntity|null
     */
    public function read(int $id): ?BaseEntity;

    /**
     * @return BaseEntity[]|null
     */
    public function all(): ?array;

    /**
     * @param int $id
     * @param array $data
     * @return BaseEntity|null
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function update(int $id, array $data) : ?BaseEntity;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id) : bool;

    /**
     * @param int $id
     * @param string $relation
     * @param array $ids
     * @return BaseEntity|null
     */
    public function sync(int $id, string $relation, array $ids) : ?BaseEntity;

    /**
     * @return QueryBuilderContract
     */
    public function getBuilder();

    /**
     * @param QueryBuilderContract $builder
     * @return BaseEntity[]|null
     */
    public function get($builder) : ?array;

    /**
     * @param QueryBuilderContract $builder
     * @return BaseEntity|null
     */
    public function first($builder) : ?BaseEntity;

    /**
     * @param QueryBuilderContract $builder
     * @param string[] $columns
     * @return array
     */
    public function getAsArray($builder, $columns = ['*']) : array;

    /**
     * @param QueryBuilderContract $builder
     * @param string[] $columns
     * @return array
     */
    public function firstAsArray($builder, $columns = ['*']) : array;

    /**
     * @param QueryBuilderContract $builder
     * @return int
     */
    public function count($builder) : int;
}
