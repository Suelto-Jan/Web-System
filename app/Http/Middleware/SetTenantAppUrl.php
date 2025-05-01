<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantAppUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set the app.url configuration to the current domain
        // This ensures that all URL generation uses the current domain
        $domain = $request->getHost();
        $scheme = $request->getScheme();
        $port = $request->getPort();

        // Include port in URL if it's not the default port for the scheme
        $url = $scheme . '://' . $domain;
        if (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)) {
            $url .= ':' . $port;
        }

        // Update app.url configuration
        config(['app.url' => $url]);

        // Ensure session domain is properly set
        config(['session.domain' => null]);

        // Get the domain without port for identification purposes
        $domainWithoutPort = preg_replace('/:\d+$/', '', $domain);

        // Check if this is a tenant domain (subdomain of localhost or other configured domain)
        $isLocalTenantDomain = strpos($domainWithoutPort, '.localhost') !== false;
        $isConfiguredTenantDomain = strpos($domainWithoutPort, '.' . preg_replace('/:\d+$/', '', config('app.domain'))) !== false;

        if ($isLocalTenantDomain || $isConfiguredTenantDomain) {
            // For local development with tenant domains
            // Make sure the domain is recognized correctly
            config(['tenancy.domain_identification' => true]);

            // Store the domain without port for tenant identification
            $request->headers->set('X-Forwarded-Host', $domainWithoutPort);

            // Debug information
            // Log::info('Tenant domain detected: ' . $domainWithoutPort);
        }

        // Debug information to help troubleshoot
        // Log::info('Request URL: ' . $request->url());
        // Log::info('App URL set to: ' . config('app.url'));

        return $next($request);
    }
}
