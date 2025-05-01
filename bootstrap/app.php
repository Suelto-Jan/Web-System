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
        // Define tenant middleware aliases
        $middleware->alias([
            'tenant' => \App\Http\Middleware\CustomInitializeTenancyByDomain::class,
            'tenancy.enforce' => \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
        ]);

        // Configure web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\SetTenantAppUrl::class,
        ]);

        // Configure global middleware
        $middleware->append([
            \App\Http\Middleware\SetTenantAppUrl::class,
        ]);

        // Ensure tenant middleware has highest priority
        $middleware->priority([
            \App\Http\Middleware\SetTenantAppUrl::class,
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
            \App\Http\Middleware\CustomInitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
