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
        // Force HTTPS redirect for all non-local environments
        // This ensures staging and production always use HTTPS
        if (!$request->secure() && !app()->environment('local')) {
            // Redirect to HTTPS version of the same URL with 301 permanent redirect
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
