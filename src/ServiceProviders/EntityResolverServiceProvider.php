<?php

namespace LaravelSferaLibrary\ServiceProviders;

use Illuminate\Support\ServiceProvider;

class EntityResolverServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    protected array $classesMap = [];

    public function boot()
    {
        foreach ($this->classesMap as $class => $entity) {
            $this->app->when($class)
                ->needs('$entityClassName')
                ->give($entity);
        }
    }
}
