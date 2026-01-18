<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecureCookies
{
    /**
     * Handle an incoming request.
     *
     * Ensures all cookies have Secure + HttpOnly flags in non-local environments.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log before processing
        $isProduction = app()->environment('production');
        $appUrlIsHttps = str_starts_with(env('APP_URL', ''), 'https://');
        $shouldProcess = $request->secure() || app()->environment('local') || ($isProduction && $appUrlIsHttps);
        
        Log::channel('daily')->debug('=== SecureCookies Middleware START ===', [
            'request_secure' => $request->secure(),
            'is_https' => $request->isSecure(),
            'environment' => app()->environment(),
            'is_local' => app()->environment('local'),
            'is_production' => $isProduction,
            'app_url' => env('APP_URL'),
            'app_url_is_https' => $appUrlIsHttps,
            'will_process' => $shouldProcess,
        ]);

        // Enforce Secure + HttpOnly for all cookies when using HTTPS
        // In local environment, allow HTTP cookies for development
        // In production, this prevents session hijacking and cookie theft
        // NOTE: In production, we process cookies if:
        // 1. Request is secure (HTTPS), OR
        // 2. We're in local environment, OR  
        // 3. APP_URL uses https (production environment) - handles proxy cases
        
        if ($shouldProcess) {
            $cookies = $response->headers->getCookies();
            
            Log::channel('daily')->debug('=== SecureCookies: Processing cookies ===', [
                'cookie_count' => count($cookies),
            ]);

            foreach ($cookies as $cookie) {
                $cookieName = $cookie->getName();
                $isSessionCookie = $cookieName === config('session.cookie');
                
                Log::channel('daily')->debug("SecureCookies: Processing cookie '{$cookieName}'", [
                    'is_session_cookie' => $isSessionCookie,
                    'original_secure' => $cookie->isSecure(),
                    'original_http_only' => $cookie->isHttpOnly(),
                    'original_same_site' => $cookie->getSameSite(),
                    'original_domain' => $cookie->getDomain(),
                    'original_path' => $cookie->getPath(),
                ]);

                $secure = true;   // Always enforce Secure
                $httpOnly = true; // Enforce HttpOnly for all cookies
                $sameSite = $cookie->getSameSite() ?: 'Lax';

                // Remove original cookie
                $response->headers->removeCookie(
                    $cookie->getName(),
                    $cookie->getPath(),
                    $cookie->getDomain()
                );

                // Re-add cookie with secure + HttpOnly
                $newCookie = cookie(
                    $cookie->getName(),
                    $cookie->getValue(),
                    $cookie->getExpiresTime() / 60, // minutes
                    $cookie->getPath(),
                    $cookie->getDomain(),
                    $secure,
                    $httpOnly,
                    false, // raw
                    $sameSite
                );
                
                $response->headers->setCookie($newCookie);
                
                Log::channel('daily')->debug("SecureCookies: Updated cookie '{$cookieName}'", [
                    'new_secure' => $secure,
                    'new_http_only' => $httpOnly,
                    'new_same_site' => $sameSite,
                    'new_domain' => $cookie->getDomain(),
                    'new_path' => $cookie->getPath(),
                ]);
            }
        } else {
            Log::channel('daily')->warning('=== SecureCookies: NOT PROCESSING COOKIES ===', [
                'reason' => 'Request not secure and not local environment',
                'request_secure' => $request->secure(),
                'is_https' => $request->isSecure(),
                'environment' => app()->environment(),
            ]);
        }

        Log::channel('daily')->debug('=== SecureCookies Middleware END ===');

        return $response;
    }
}
