<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token not provided or invalid'], 401);
        }

        if (!$user || $user->role->name !== 'admin') {
            return response()->json(['message' => 'Access denied. Admin only!'], 403);
        }

        return $next($request);
    }
}
