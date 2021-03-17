<?php

namespace LaravelSferaLibrary\Http;

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
     * @param string|null $exceptionClass
     * @param array|null $trace
     * @return JsonResponse
     */
    public static function errorResponse(
        int $statusCode = 500,
        array $errors = null,
        string $message = '',
        string $exceptionClass = null,
        array $trace = null
    ) : JsonResponse
    {
        $result = ['success' => false, 'errors' => $errors, 'message' => $message];

        if($trace){
            $result['trace'] = $trace;
        }
        if($exceptionClass){
            $result['exception'] = $exceptionClass;
        }

        return self::rawResponse($statusCode, $result);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public static function notFoundResponse(string $message = '') : JsonResponse
    {
        return self::errorResponse(404, null, $message);
    }

    /**
     * @param int $statusCode
     * @param string|array|object $data
     * @param array $headers
     * @return JsonResponse
     */
    public static function rawResponse(int $statusCode, $data, array $headers = []) : JsonResponse
    {
        $headers["Access-Control-Expose-Headers"] = [
            'X-Pagination-Current-Page',
            'X-Pagination-Page-Count',
            'X-Pagination-Per-Page',
            'X-Pagination-Total-Count'
        ];
        return response()->json($data, $statusCode, $headers);
    }
}
