<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add X-Content-Type-Options header
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Optional: add other security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
