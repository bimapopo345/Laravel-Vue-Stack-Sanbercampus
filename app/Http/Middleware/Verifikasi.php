<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class Verifikasi
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Verifikasi Middleware: Memulai proses verifikasi.');

        try {
            $user = JWTAuth::parseToken()->authenticate();
            Log::info('Verifikasi Middleware: Pengguna terautentikasi.', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Verifikasi Middleware: Token tidak valid atau tidak disediakan.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Token not provided or invalid'], 401);
        }

        if (!$user || !$user->email_verified_at) {
            Log::warning('Verifikasi Middleware: Pengguna belum diverifikasi.', ['user_id' => $user->id]);
            return response()->json(['message' => 'Akun belum diverifikasi.'], 403);
        }

        Log::info('Verifikasi Middleware: Pengguna telah diverifikasi.', ['user_id' => $user->id]);

        return $next($request);
    }
}
