<?php

namespace App\Exceptions;

use App\Http\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Dingo\Api\Exception\StoreResourceFailedException;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            $statusCode = 500;
            $responseContent = [
                'message' => '500, An Error has Occurred',
                'http_response' => $statusCode,
                'status_code' => 0,
            ];

            if ($exception instanceof AuthenticationException) {
                $statusCode = 401;
                $responseContent['message'] = 'Unauthenticated.';
            } elseif ($exception instanceof BodyTooLargeException) {
                $statusCode = 413;
                $responseContent['message'] = 'The body is too large';
            } elseif ($exception instanceof ValidationException) {
                $statusCode = 422;
                $responseContent['message'] = $exception->getMessage();
                $responseContent['errors'] = $exception->errors();
            } elseif ($exception instanceof StoreResourceFailedException) {
                $statusCode = 422;
                $responseContent['message'] = $exception->getMessage();
                $responseContent['errors'] = $exception->errors;
            } elseif ($exception instanceof NotFoundHttpException) {
                $statusCode = 404;
                $responseContent['message'] = '404 Not Found';
                $responseContent['errors'] = $exception->getMessage();
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                $statusCode = 405;
                $responseContent['message'] = '405 Method not allowed';
            } elseif ($exception instanceof QueryException) {
                $statusCode = 422;
                $responseContent['message'] = '422 Unprocessable Entity';
                $responseContent['error'] = $exception->getMessage();
            }

            // Include more detailed error information
            $responseContent['exception'] = get_class($exception);
            $responseContent['message'] = $exception->getMessage();
            $responseContent['trace'] = $exception->getTrace();

            return response()->json($responseContent, $statusCode);
        } else {
            // If it's not an API request, use the default handler
            return parent::render($request, $exception);
        }
    }
}
