<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];
            return response()->json(['error' => $message], $code);
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return response()->json(['error' => "No existe un {$model} con ese ID"], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ValidationException) {
            return response()->json(['error' => $exception->validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return response()->json(['error' => 'Error inesperado. Intenta más tarde'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
