<?php

namespace Tests\App\Modules\WorkingExample\Requests;

use LaravelSferaLibrary\Http\ApiRequest;

/**
 * Class CrudCreateRequest
 * @package Tests\App\Modules\WorkingExample\Requests
 */
class TestRequest extends ApiRequest
{
    public function getUserId() : ?int
    {
        return $this->user_id;
    }

    public function getUniqueStringExample() : ?string
    {
        return $this->unique_string_example;
    }

    public function getStringExample() : ?string
    {
        return $this->string_example;
    }

    public function getIntExample() : ?int
    {
        return $this->int_example;
    }

    public function getEnumExample() : ?string
    {
        return $this->enum_example;
    }
}
