<?php

namespace LaravelSferaLibrary\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use LaravelSferaLibrary\Http\ApiResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param Throwable $exception
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Throwable $e)
    {
        if($e instanceof NotFoundException or $e instanceof ValidationException){
            return parent::render($request, $e);
        }

        return ApiResponse::errorResponse(
            $this->resolveHttpCode($e),
            method_exists($e, 'errors')  ? $e->errors() : null,
            $e->getMessage(),
            env('APP_DEBUG') ? get_class($e) : null,
            env('APP_DEBUG') ? $e->getTrace() : null
        );
    }

    /**
     * @param Throwable $e
     * @return int
     */
    protected function resolveHttpCode(Throwable $e) : int
    {
        $code = 500;

        if($e instanceof NotFoundHttpException) {
            $code = 404;
        }
        if($e instanceof \Illuminate\Validation\ValidationException) {
            $code = 422;
        }
        elseif(strpos($e->getMessage(), 'Routing/Controller.php')){
            $code = 400;
        }

        return $code;
    }
}
