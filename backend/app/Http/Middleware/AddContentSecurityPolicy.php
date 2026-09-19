<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sends the admin's Content-Security-Policy. See config/security.php.
 *
 * The nonce is created before the request is handled, so every view rendered for it can
 * print the same value with the @nonce directive.
 */
class AddContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('security.csp.enabled')) {
            return $next($request);
        }

        $nonce = Vite::useCspNonce();

        $response = $next($request);

        // Only HTML documents need a policy; JSON and file responses are left alone.
        if (! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        $header = config('security.csp.report_only')
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $response->headers->set($header, $this->policy($nonce));

        return $response;
    }

    private function policy(string $nonce): string
    {
        $hosts = fn (string $key): string => implode(' ', config("security.csp.{$key}", []));

        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' ".$hosts('script_hosts'),
            "style-src 'self' 'unsafe-inline' ".$hosts('style_hosts'),
            "font-src 'self' data: ".$hosts('font_hosts'),
            // Bootstrap, DataTables and toastr draw some icons with data: URIs.
            "img-src 'self' data: blob:",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ]);
    }
}
