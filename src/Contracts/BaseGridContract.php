<?php

namespace LaravelSferaLibrary\Contracts;

use LaravelSferaLibrary\Http\GridApiRequest;
use Illuminate\Http\JsonResponse;

interface BaseGridContract
{
    /**
     * @param GridApiRequest $request
     * @return JsonResponse
     */
    public function items(GridApiRequest $request): JsonResponse;

    /**
     * @return array|int[]
     */
    public function getPageSizes() : array;
}
