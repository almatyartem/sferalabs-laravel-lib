<?php

namespace Tests\App\Modules\WorkingExample\Services;

use LaravelSferaLibrary\DataLayer\BaseEntity;
use LaravelSferaLibrary\Exceptions\NotFoundException;
use LaravelSferaLibrary\Exceptions\ValidationException;
use Tests\App\Services\DataLayer\Entities\PostEntity;
use Tests\App\Services\DataLayer\Repositories\PostRepository;
use Tests\App\Services\DataLayer\Search\PostSearch;

class PostsService
{
    /**
     * @var PostSearch
     */
    protected PostSearch $search;

    /**
     * @var PostRepository
     */
    protected PostRepository $repository;

    /**
     * WorkingExampleCrudUserService constructor.
     * @param PostSearch $search
     * @param PostRepository $repository
     */
    public function __construct(PostSearch $search, PostRepository $repository)
    {
        $this->search = $search;
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return PostEntity|BaseEntity|null
     */
    public function read(int $id) : ?PostEntity
    {
        return $this->search->byId($id)->withUser()->first();
    }

    /**
     * @param array $data
     * @return PostEntity|BaseEntity|null
     * @throws ValidationException
     */
    public function createByArray(array $data) : ?PostEntity
    {
        return $this->repository->createFromArray($data);
    }

    /**
     * @param PostEntity $entity
     * @return PostEntity|null
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function createByEntity(PostEntity $entity) : ?PostEntity
    {
        return $this->repository->save($entity);
    }

    /**
     * @param int $userId
     * @param array $data
     * @return PostEntity|BaseEntity|null
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function updateByArray(int $userId, array $data) : ?PostEntity
    {
        return $this->repository->updateByArray($userId, $data);
    }

    /**
     * @param PostEntity $entity
     * @return PostEntity
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function saveEntity(PostEntity $entity) : PostEntity
    {
        return $this->repository->save($entity);
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function delete(int $userId) : bool
    {
        return $this->repository->delete($userId);
    }
}
