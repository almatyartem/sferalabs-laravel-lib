<?php

namespace LaravelSferaLibrary\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use LaravelSferaLibrary\Http\RouteData;

abstract class BaseRoutesServiceProvider extends RouteServiceProvider
{
    /**
     * @var RouteData[]
     */
    protected array $routesData;

    public function boot()
    {
        $this->setRoutesData();
        $this->map();

        parent::boot();
    }

    /**
     * @return string
     */
    abstract function getModuleName() : string;

    /**
     * @return int
     */
    abstract function getVersion() : int;

    /**
     *
     */
    abstract function setRoutesData() : void;

    /**
     * @return void
     */
    function map()
    {
        Route::prefix('api/'.$this->getModuleName().'/v'.$this->getVersion())
            ->group(function(Router $router){
                foreach($this->routesData as $routeData){
                    $method = $routeData->httpMethod;
                    $router->$method($routeData->uri, [$routeData->controllerClass, $routeData->methodName]);
                }
            });
    }

    /**
     * @param RouteData $routeData
     */
    protected function addRouteData(RouteData $routeData) : void
    {
        $this->routesData[] = $routeData;
    }
}
