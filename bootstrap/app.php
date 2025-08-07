<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware; // 👈 Add this line

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register route middleware here
        $middleware->alias([
            'role' => RoleMiddleware::class, // 👈 Now Laravel knows about your 'role' middleware
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
