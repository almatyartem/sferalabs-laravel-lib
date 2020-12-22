<?php

namespace LaravelSferaTemplate\Exceptions;

use LaravelSferaTemplate\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class ValidationException extends \Exception
{
    protected Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;

        parent::__construct('Validation error: '.implode(",", $validator->messages()->all()));
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        $this->validator->passes(); // Triggering the validator to show the latest messages

        return $this->validator->errors()->toArray();
    }

    /**
     * @return JsonResponse
     */
    public function render()
    {
        return ApiResponse::errorResponse(400, ['validation_errors' => $this->getErrors()]);
    }
}
