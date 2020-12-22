<?php

namespace LaravelSferaTemplate\Http;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param bool $success
     * @param null $data
     * @return JsonResponse
     */
    public static function response($data = null, bool $success = true) : JsonResponse
    {
        return self::rawResponse(200, ['success' => $success, 'data' => $data]);
    }

    /**
     * @param int $statusCode
     * @param array|null $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function errorResponse(int $statusCode = 500, array $errors = null, string $message = '') : JsonResponse
    {
        return self::rawResponse($statusCode, ['success' => false, 'errors' => $errors, 'message' => $message]);
    }

    /**
     * @param int $statusCode
     * @param array $data
     * @param array $headers
     * @return JsonResponse
     */
    public static function rawResponse(int $statusCode, array $data, array $headers = []) : JsonResponse
    {
        return response()->json($data, $statusCode, $headers);
    }
}
