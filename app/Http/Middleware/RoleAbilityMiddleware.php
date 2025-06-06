<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAbilityMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth('api')->user();

        if (! $user || ! $user->role || $user->role->name !== $role) {
            return response()->json(['message' => 'Unauthorized - Role'], 403);
        }

        // Optional: If you're using token abilities too
        if (! $request->user()->tokenCan($role)) {
            return response()->json(['message' => 'Unauthorized - Token Ability'], 403);
        }

        return $next($request);
    }
}
