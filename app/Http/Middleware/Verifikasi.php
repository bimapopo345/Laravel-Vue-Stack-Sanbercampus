<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class Verifikasi
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token not provided or invalid'], 401);
        }

        if (!$user || !$user->email_verified_at) {
            return response()->json(['message' => 'Akun belum diverifikasi.'], 403);
        }

        return $next($request);
    }
}
