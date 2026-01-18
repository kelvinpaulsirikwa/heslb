<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SimpleCSRFMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip CSRF validation for safe HTTP methods
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        // For POST requests, validate CSRF token
        $token = $request->input('_token') ?? $request->header('X-CSRF-TOKEN');
        $sessionToken = session('_token');

        Log::info('Simple CSRF Validation', [
            'method' => $request->method(),
            'submitted_token' => $token,
            'session_token' => $sessionToken,
            'tokens_match' => hash_equals((string)$sessionToken, (string)$token)
        ]);

        if (!$token || !hash_equals((string)$sessionToken, (string)$token)) {
            Log::warning('CSRF token mismatch', [
                'submitted' => $token,
                'session' => $sessionToken
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CSRF token mismatch',
                    'error' => 'CSRF_TOKEN_INVALID'
                ], 419);
            }

            return response()->view('errors.csrf', [
                'message' => 'CSRF token mismatch'
            ], 419);
        }

        return $next($request);
    }
}
