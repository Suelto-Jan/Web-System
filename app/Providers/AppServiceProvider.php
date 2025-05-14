<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure pagination to use Tailwind CSS
        Paginator::useTailwind();

        // Configure URL generation to use the current domain
        // This ensures that route() helper generates URLs for the current domain
        // instead of the central domain
        if (app()->bound('tenant')) {
            $tenant = app('tenant');
            if ($tenant) {
                $domain = request()->getHost();
                config(['app.url' => request()->getScheme() . '://' . $domain]);
            }
        }
    }
}
