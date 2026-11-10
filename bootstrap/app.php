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

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'secure.session' => \App\Http\Middleware\SecureSession::class,
            'admin.tab.security' => \App\Http\Middleware\AdminTabSecurity::class,
        ]);

        // Trust Railway proxies for HTTPS detection
        $middleware->trustProxies(at: '*');
        
        // SecureSession TIDAK dipasang secara global.
        // Login dan session Laravel menggunakan mekanisme standar.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
