<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use Override;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware extends Authenticate
{
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return route('loginPage');
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            throw new Exception('Unauthenticated.', Response::HTTP_UNAUTHORIZED);
        }

        parent::unauthenticated($request, $guards);
    }
}
