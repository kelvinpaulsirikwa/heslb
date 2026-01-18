<?php

namespace App\Http\Middleware;

use App\Services\CustomCookieManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    protected $cookieManager;

    public function __construct(CustomCookieManager $cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $guard = null): Response
    {
        // Try to authenticate user using custom cookies
        $user = $this->cookieManager->getAuthUser();
        
        if ($user) {
            // Log the user in using Laravel's Auth system
            Auth::guard($guard)->login($user);
            
            // Refresh token expiry
            $this->cookieManager->setAuthCookie($user);
        }

        return $next($request);
    }
}
