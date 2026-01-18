<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        Log::info('CSRF Middleware: Processing request', [
            'method' => $request->method(),
            'path' => $request->path(),
            'has_token' => $request->has('_token'),
            'token_value' => $request->input('_token'),
            'session_token' => session('_token'),
            'headers' => $request->headers->all()
        ]);

        // Skip CSRF validation for safe HTTP methods
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        // For POST requests, validate CSRF token
        $token = $request->input('_token') ?? $request->header('X-CSRF-TOKEN');
        $sessionToken = session('_token');

        Log::info('CSRF Validation', [
            'submitted_token' => $token,
            'session_token' => $sessionToken,
            'tokens_match' => hash_equals($sessionToken, $token)
        ]);

        if (!$token || !hash_equals($sessionToken, $token)) {
            Log::warning('CSRF token mismatch', [
                'submitted' => $token,
                'session' => $sessionToken
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CSRF token mismatch',
                    'error' => 'CSRF_TOKEN_INVALID',
                    'debug' => [
                        'submitted' => $token,
                        'session' => $sessionToken
                    ]
                ], 419);
            }

            return response()->view('errors.csrf', [
                'message' => 'CSRF token mismatch',
                'debug' => [
                    'submitted' => $token,
                    'session' => $sessionToken
                ]
            ], 419);
        }

        return $next($request);
    }
}
