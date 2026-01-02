<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Check if user role is 'admin' (case-insensitive)
        if (strtolower($user->role) !== 'admin') {
            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Access denied. Admin privileges required.',
                    'status' => 'forbidden'
                ], 403);
            }

            // Regular web request - redirect to dashboard with error message
            return redirect()->route('dashboard')->with('error', 'Access denied. You do not have permission to access this page.');
        }

        return $next($request);
    }
}

