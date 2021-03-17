<?php

namespace Tests\App\Modules\WorkingExample;

use Illuminate\Support\ServiceProvider;
use Tests\App\Services\DataLayer\DataLayerServiceProvider;

class WorkingExampleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(WorkingExampleRoutesServiceProvider::class);
        $this->app->register(DataLayerServiceProvider::class);
    }
}
