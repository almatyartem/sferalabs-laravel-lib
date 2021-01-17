<?php

namespace LaravelSferaLibrary\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelSferaLibrary\Contracts\DbDataProviderContract;
use LaravelSferaLibrary\Contracts\DbDataProvidersFactoryContract;

abstract class SearchOrRepositoryBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $classes = $this->getMap();
        $factory = app(DbDataProvidersFactoryContract::class);

        foreach($classes as $class){
            $this->app->when($class)
                ->needs(DbDataProviderContract::class)
                ->give(function () use ($factory, $class) {
                    return $factory->getDataProviderByEntityClass(call_user_func([$class, 'getEntityClass']));
                });
        }
    }

    /**
     * @return array
     */
    protected abstract function getMap() : array;
}
