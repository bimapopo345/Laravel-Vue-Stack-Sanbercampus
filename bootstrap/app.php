<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        /**
         * 1) Global Middleware
         */
        $middleware->global([
            // Contoh: \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        ]);

        /**
         * 2) Middleware Groups
         */
        $middleware->groups([
            'web' => [
                // Contoh: \App\Http\Middleware\EncryptCookies::class,
            ],
            'api' => [
                // Middleware untuk API group, bisa ditambahkan jika diperlukan
            ],
        ]);

        /**
         * 3) Route Aliases
         */
        $middleware->aliases([
            'auth'       => \Tymon\JWTAuth\Http\Middleware\Authenticate::class, // Middleware JWT Auth
            'isadmin'    => \App\Http\Middleware\IsAdmin::class,
            'verifikasi' => \App\Http\Middleware\Verifikasi::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
