<?php

namespace LaravelSferaLibrary\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelSferaLibrary\Contracts\DbDataProvidersFactoryContract;
use LaravelSferaLibrary\Db\DbDataProviders\Eloquent\EloquentDbDataProvidersFactory;

class DbDataServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        DbDataProvidersFactoryContract::class => EloquentDbDataProvidersFactory::class,
    ];
}
