<?php

namespace Tests;

use Illuminate\Support\ServiceProvider;
use Tests\App\Modules\WorkingExample\WorkingExampleServiceProvider;

class TestPackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(WorkingExampleServiceProvider::class);
    }
}
