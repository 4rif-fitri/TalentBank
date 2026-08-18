<?php

use App\Http\Helpers\ApiResponse;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => AuthMiddleware::class,
            'guest' => GuestMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->wantsJson()) {
                $statusCode = ApiResponse::getValidatedStatusCode($e);
                return ApiResponse::error($e->getMessage(), $statusCode)->toJsonResponse();
            }
        });
    })->create();
