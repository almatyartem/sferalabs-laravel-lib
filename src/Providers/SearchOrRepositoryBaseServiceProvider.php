<?php

namespace LaravelSferaLibrary\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelSferaLibrary\Contracts\DbDataProviderContract;
use LaravelSferaLibrary\Contracts\DbDataProvidersFactoryContract;

abstract class SearchOrRepositoryBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $bindings = $this->getMap();
        $factory = app(DbDataProvidersFactoryContract::class);

        foreach($bindings as $contract => $implementation){

            $this->app->bind($contract, $implementation);
            $this->app->when($implementation)
                ->needs(DbDataProviderContract::class)
                ->give(function () use ($factory, $implementation) {
                    return $factory->getDataProviderByEntityClass(call_user_func([$implementation, 'getEntityClass']));
                });
        }
    }

    /**
     * @return array
     */
    protected abstract function getMap() : array;
}
