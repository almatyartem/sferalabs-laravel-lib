<?php

namespace Tests\App\Modules\WorkingExample\Controllers;


use Illuminate\Http\JsonResponse;
use LaravelSferaLibrary\Http\ApiResponse;
use LaravelSferaLibrary\Http\Controller;

class WorkingExampleOtherController extends Controller
{
    /**
     * @param string $method
     * @return JsonResponse
     */
    public function execute(string $method) : JsonResponse
    {
        if(!method_exists($this, $method)){
            return ApiResponse::notFoundResponse();
        }

        return ApiResponse::response($this->$method());
    }
}
