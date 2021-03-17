<?php

namespace Tests\App\Services\DataLayer\ServiceProviders;

use LaravelSferaLibrary\ServiceProviders\EntityResolverServiceProvider;
use Tests\App\Services\DataLayer\Entities\PostEntity;
use Tests\App\Services\DataLayer\Repositories\PostRepository;

class RepositoriesServiceProvider extends EntityResolverServiceProvider
{
    /**
     * @var array|string[]
     */
    protected array $classesMap = [
        PostRepository::class => PostEntity::class
    ];
}
