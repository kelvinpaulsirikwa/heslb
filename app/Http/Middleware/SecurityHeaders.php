<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Prevent clickjacking attacks
        // X-Frame-Options: Prevents page from being displayed in a frame/iframe
        // 'SAMEORIGIN' allows framing by same origin only, 'DENY' blocks all framing
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // Content Security Policy (CSP)
        // Comprehensive CSP to prevent XSS attacks while allowing necessary functionality
        // 
        // SECURITY NOTE: 'unsafe-eval' is included because CKEditor requires it for some features.
        // This allows eval() and similar functions. While this reduces security, it's necessary
        // for CKEditor to function properly. Consider migrating to a newer version of CKEditor
        // that doesn't require unsafe-eval, or use nonces/hashes for inline scripts in the future.
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.bootstrapcdn.com", // unsafe-eval required for CKEditor
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.bootstrapcdn.com https://fonts.googleapis.com",
            "font-src 'self' data: https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.bootstrapcdn.com https://fonts.gstatic.com",
            "img-src 'self' data: blob: https: http:",
            "connect-src 'self' https:",
            "frame-src 'self'",
            "frame-ancestors 'self'", // Prevent clickjacking
            "object-src 'none'", // Block plugins
            "base-uri 'self'",
            "form-action 'self'",
            "upgrade-insecure-requests" // Upgrade HTTP to HTTPS
        ];
        
        $cspHeader = implode('; ', $cspDirectives);
        $response->headers->set('Content-Security-Policy', $cspHeader);
        
        // Additional security headers for defense in depth
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // HTTP Strict Transport Security (HSTS)
        // Tells browsers to only access the site over HTTPS, even if user types HTTP
        // Only set on HTTPS connections and in non-local environments
        if ($request->secure() && !app()->environment('local')) {
            // max-age: 1 year (31536000 seconds)
            // includeSubDomains: Apply to all subdomains
            // preload: Allow inclusion in browser HSTS preload lists
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        return $response;
    }
}
