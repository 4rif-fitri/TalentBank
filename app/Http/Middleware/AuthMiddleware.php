<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware extends Authenticate
{
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return route('loginPage');
        }
    }
}
