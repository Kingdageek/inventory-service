<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Constants\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

     /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // For Public API Endpoints Error Rendering
        if ($exception instanceof UnprocessableEntityHttpException) {
            $message = $exception->getMessage();
            $messageArray = json_decode($message, true);

            // set the pointer to point to the first element
            reset($messageArray);
            $first = current($messageArray);

            // Get first validation error message
            $error = $first[0];
            return response()->json([
                'message' => $error,
                'error' => $message,
                'status' => false
            ], 422);
        }

        if ($exception instanceof BadRequestException) {
            $message = $exception->getMessage();
            return response()->json([
                'message' => $message,
                'error' => Response::ERR_NOT_SUCCESSFUL,
                'status' => false
            ], 400);
        }

        if ($exception instanceof ModelNotFoundException) {
            $message = $exception->getMessage();
            return response()->json([
                'message' => $message,
                'error' => Response::MODEL_NOT_FOUND,
                'status' => false
            ], 404);
        }

        // if ($exception instanceof HttpException) {
        //     return response()->json([
        //         'message' => $exception->getMessage() . '. Please add a token to continue',
        //         'error' => Response::NOT_AUTHORIZED,
        //         'status' => false
        //     ], 401);
        // }

        // base exception
        // if ($exception instanceof \Exception) {
        //     return response()->json([
        //         'message' => "Oops... Something went terribly wrong",
        //         'error' => $exception->getMessage(),
        //         'status' => false
        //     ], 500);
        // }

        return parent::render($request, $exception);
    }
}
