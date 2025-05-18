<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $centralDomains = $this->centralDomains();

        // Register routes for central domains
        foreach($centralDomains as $domain) {
            Route::domain($domain)->group(function () {
                Route::middleware('web')->group(base_path('routes/web.php'));

                if (file_exists(base_path('routes/api.php'))) {
                    Route::middleware('api')
                        ->prefix('api')
                        ->group(base_path('routes/api.php'));
                }
            });
        }

        // Register routes for tenant domains
        if (file_exists(base_path('routes/tenant.php'))) {
            Route::middleware(['web', 'tenant'])
                ->group(base_path('routes/tenant.php'));
        }

        // Register auth routes
        if (file_exists(base_path('routes/auth.php'))) {
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        }
    }
    protected function centralDomains(): array
    {
        $domains = config('tenancy.central_domains');
        $result = [];

        // Add domains with and without port numbers
        foreach ($domains as $domain) {
            $result[] = $domain;

            // Add domain with port 8000 for local development
            if (!str_contains($domain, ':')) {
                $result[] = $domain . ':8000';
            }
        }

        return $result;
    }
}