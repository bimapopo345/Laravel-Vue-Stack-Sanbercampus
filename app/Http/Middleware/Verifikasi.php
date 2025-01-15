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
        $user = JWTAuth::parseToken()->authenticate();

        // Misal check: user harus punya email_verified_at
        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Akun belum diverifikasi.'], 403);
        }

        return $next($request);
    }
}
