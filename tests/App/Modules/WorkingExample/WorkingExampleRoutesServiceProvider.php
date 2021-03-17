<?php

namespace Tests\App\Modules\WorkingExample;

use LaravelSferaLibrary\ServiceProviders\RouteServiceProvider;
use Tests\App\Modules\WorkingExample\Controllers\WorkingExamplePostsCrudController;
use Tests\App\Modules\WorkingExample\Controllers\WorkingExampleOtherController;
use Illuminate\Routing\Router;

class WorkingExampleRoutesServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
        $this->map();

        parent::boot();
    }

    public function map()
    {
        $this->registerApiRoutes('WorkingExample', function(Router $router){
            $router->get('/posts/{id}',[WorkingExamplePostsCrudController::class, 'read']);
            $router->post('/posts',[WorkingExamplePostsCrudController::class, 'create']);
            $router->post('/posts/{id}',[WorkingExamplePostsCrudController::class, 'update']);
            $router->delete('/posts/{id}',[WorkingExamplePostsCrudController::class, 'delete']);

            $router->get('/other/{method}',[WorkingExampleOtherController::class, 'execute']);

        }, self::ROUTE_VERSION_ONE, []);
    }
}
