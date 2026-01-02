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
        
        // Modern CSP approach (more flexible than X-Frame-Options)
        // 'self' allows framing by same origin, 'none' blocks all framing
        // This is the recommended modern way to prevent clickjacking
        $response->headers->set('Content-Security-Policy', "frame-ancestors 'self'");
        
        // Additional security headers for defense in depth
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }
}
