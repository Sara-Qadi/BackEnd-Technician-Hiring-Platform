<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAbilityMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()->tokenCan($role)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}

