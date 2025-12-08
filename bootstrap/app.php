<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'rate.limit.api' => \App\Http\Middleware\RateLimitApi::class,
        ]);

        // Rate limiting for production security
        $middleware->throttleApi();

        // Add security headers for all requests
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        // Add locale middleware for internationalization
        $middleware->append(\App\Http\Middleware\SetLocale::class);

        // Add response compression for better performance
        $middleware->append(\App\Http\Middleware\CompressResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
