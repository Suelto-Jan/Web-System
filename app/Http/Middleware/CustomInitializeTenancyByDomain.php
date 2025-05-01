<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;
use Stancl\Tenancy\Tenancy;
use Symfony\Component\HttpFoundation\Response;

class CustomInitializeTenancyByDomain extends InitializeTenancyByDomain
{
    public function __construct(Tenancy $tenancy, DomainTenantResolver $resolver)
    {
        parent::__construct($tenancy, $resolver);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the domain from the request
        $domain = $request->getHost();

        // Remove port number if present
        $domainWithoutPort = preg_replace('/:\d+$/', '', $domain);

        // Check if X-Forwarded-Host header is set (from SetTenantAppUrl middleware)
        if ($request->headers->has('X-Forwarded-Host')) {
            $domainWithoutPort = $request->headers->get('X-Forwarded-Host');
        }

        // Try to find tenant by domain without port
        return $this->initializeTenancy(
            $request,
            $next,
            $domainWithoutPort
        );
    }
}
