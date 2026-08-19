<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userRoles = session('roles', []);

        if (empty(array_intersect($roles, $userRoles))) {
            return ApiResponse::error('Unauthorized access.', HttpResponse::HTTP_FORBIDDEN)->toJsonResponse();
        }

        return $next($request);
    }
}
