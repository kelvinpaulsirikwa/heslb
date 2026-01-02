<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Force HTTPS redirect for production/staging environments only
        // Allow HTTP in local development for easier testing
        // In production, this ensures credentials are NEVER transmitted over HTTP
        if (!$request->secure() && !app()->environment('local')) {
            // Get the full URL with query string
            $url = $request->getRequestUri();
            
            // Redirect to HTTPS version with 301 permanent redirect
            // This prevents credentials from being sent over HTTP in production
            return redirect()->secure($url, 301);
        }

        return $next($request);
    }
}
