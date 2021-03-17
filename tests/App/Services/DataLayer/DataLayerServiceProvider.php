<?php

namespace Tests\App\Services\DataLayer;

use Tests\App\Services\DataLayer\ServiceProviders\RepositoriesServiceProvider;
use Tests\App\Services\DataLayer\ServiceProviders\SearchServiceProvider;
use Illuminate\Support\ServiceProvider;

class DataLayerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(SearchServiceProvider::class);
        $this->app->register(RepositoriesServiceProvider::class);
    }
}
