<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // ambil user dari token
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->role->name !== 'admin') {
            return response()->json(['message' => 'Access denied. Admin only!'], 403);
        }
        return $next($request);
    }
}
