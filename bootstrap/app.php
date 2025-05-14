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
    ->withProviders([
        App\Providers\RouteServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'tenant' => \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);

        // Configure web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\SetTenantAppUrl::class,
            \App\Http\Middleware\CheckTenantStatus::class,
            \App\Http\Middleware\TenantAuthentication::class,
        ]);

        // Configure global middleware
        $middleware->append([
            \App\Http\Middleware\SetTenantAppUrl::class,
            \App\Http\Middleware\CheckTenantStatus::class,
            \App\Http\Middleware\TenantAuthentication::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
