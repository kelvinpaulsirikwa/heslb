<?php

namespace App\Http\Middleware;

use App\Services\CustomCookieManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CustomCSRFMiddleware
{
    protected $cookieManager;

    public function __construct(CustomCookieManager $cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate CSRF token for all requests (including GET)
        $token = $this->cookieManager->getSecureCookie('csrf_token', null, false);
        if (!$token) {
            $this->cookieManager->generateCSRFToken();
            // Debug: Log token generation
            \Log::info('CSRF token generated for: ' . $request->path());
        }

        // Skip CSRF validation for safe HTTP methods
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            $response = $next($request);
            $response->headers->set('X-CSRF-Debug', 'CustomCSRFMiddleware-Active');
            return $response;
        }

        // Check if request has CSRF token
        $token = $request->input('_token') ?? $request->header('X-CSRF-TOKEN');

        if (!$token) {
            return $this->csrfErrorResponse('CSRF token missing');
        }

        // Validate CSRF token
        if (!$this->cookieManager->validateCSRFToken($token)) {
            return $this->csrfErrorResponse('CSRF token mismatch');
        }

        $response = $next($request);
        $response->headers->set('X-CSRF-Debug', 'CustomCSRFMiddleware-Active');
        return $response;
    }

    /**
     * Generate CSRF error response
     */
    private function csrfErrorResponse(string $message): Response
    {
        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'error' => 'CSRF_TOKEN_INVALID'
            ], 419);
        }

        // For web requests, show a user-friendly error page
        return response()->view('errors.csrf', [
            'message' => $message
        ], 419);
    }
}
