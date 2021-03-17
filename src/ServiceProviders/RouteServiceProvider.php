<?php

namespace LaravelSferaLibrary\ServiceProviders;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    const ROUTE_VERSION_ONE = 'v1';
    const ROUTE_VERSION_TWO = 'v2';

    public function boot()
    {
        parent::boot();

        Route::get(sprintf('api/%s/healthcheck', self::ROUTE_VERSION_ONE), function () {
            return response()->json([
                'version' => env('APP_VERSION', '0.0.0'),
            ]);
        });
    }

    public function registerApiRoutes(
        string $moduleName,
        callable $routes,
        $version = self::ROUTE_VERSION_ONE,
        array $middlewares = ['jwt.verify', 'jwt.login']
    ) {
        Route::prefix('api/'.$moduleName.'/'.$version)
            ->middleware($middlewares)
            ->group($routes);
    }
}
