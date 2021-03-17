<?php

namespace Tests;

use Illuminate\Foundation\Application;
use LaravelSferaLibrary\Exceptions\Handler;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @param Application $app
     * @return array|string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            TestPackageServiceProvider::class
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling();
        $this->originalExceptionHandler = $this->app->make(Handler::class);
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // and other test setup steps you need to perform
    }
}
