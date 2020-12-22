<?php

namespace LaravelSferaTemplate\Contracts;

use LaravelSferaTemplate\Exceptions\NotFoundException;
use LaravelSferaTemplate\Exceptions\ValidationException;
use LaravelSferaTemplate\Db\BaseEntity;

interface BaseRepositoryContract
{
    /**
     * @param int $id
     * @return BaseEntity|null
     */
    public function read(int $id) : ?BaseEntity;

    /**
     * @param BaseEntity $entity
     * @return BaseEntity|null
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function save($entity) : ?BaseEntity;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id) : bool;
}
