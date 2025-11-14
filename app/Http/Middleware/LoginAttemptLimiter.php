<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\Log;

class LoginAttemptLimiter
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
        // Only apply to login requests
        if ($request->isMethod('post') && $request->routeIs('login.submit')) {
            $email = $request->input('email');
            $ipAddress = $request->ip();
            
            // Check if login attempts have been exceeded
            if (LoginAttempt::hasExceededMaxAttempts($email, $ipAddress)) {
                $remainingTime = LoginAttempt::getRemainingLockoutTime($email, $ipAddress);
                
                Log::warning('Login attempt blocked by middleware', [
                    'email' => $email,
                    'ip' => $ipAddress,
                    'remaining_minutes' => $remainingTime
                ]);
                
                return redirect()->back()
                    ->with('error', "Too many failed login attempts. Please try again in {$remainingTime} minutes.")
                    ->withInput($request->only('email'));
            }
        }

        return $next($request);
    }
}
