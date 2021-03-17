<?php

namespace Tests\App\Services\DataLayer\ServiceProviders;

use LaravelSferaLibrary\ServiceProviders\EntityResolverServiceProvider;
use Tests\App\Services\DataLayer\Entities\PostEntity;
use Tests\App\Services\DataLayer\Search\PostSearch;

class SearchServiceProvider extends EntityResolverServiceProvider
{
    /**
     * @var array|string[]
     */
    protected array $classesMap = [
        PostSearch::class => PostEntity::class
    ];
}
