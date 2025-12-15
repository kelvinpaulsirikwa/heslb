<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        // Only enforce Secure + HttpOnly in non-local environments over HTTPS
        if (!app()->environment('local') && $request->secure()) {
            $cookies = $response->headers->getCookies();

            foreach ($cookies as $cookie) {
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
                $response->headers->setCookie(
                    cookie(
                        $cookie->getName(),
                        $cookie->getValue(),
                        $cookie->getExpiresTime() / 60, // minutes
                        $cookie->getPath(),
                        $cookie->getDomain(),
                        $secure,
                        $httpOnly,
                        false, // raw
                        $sameSite
                    )
                );
            }
        }

        return $response;
    }
}
