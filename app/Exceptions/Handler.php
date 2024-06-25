<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        // $this->reportable(function (Throwable $e) {
        //     //
        // });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/product/*')) {
                return response()->json([
                    'message' => 'Product not found.'
                ], 404);
            } else if ($request->is('api/category/*')) {
                return response()->json([
                    'message' => 'Category not found.'
                ], 404);
            } else if ($request->is('api/order/*')) {
                return response()->json([
                    'message' => 'Order not found.'
                ], 404);
            } else if ($request->is('api/user/*')) {
                return response()->json([
                    'message' => 'User not found.'
                ], 404);
            }
        });

        $this->renderable(function (UnauthorizedException $e, $request) {
            return response(["message" => "Unauthorized"], 403);

        });
    }
}
