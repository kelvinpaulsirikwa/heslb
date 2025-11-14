<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckUserStatus
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
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is blocked or suspended
            if ($user->status === 'blocked' || $user->status === 'suspended') {
                // Logout the user
                Auth::logout();
                
                // Clear the session
                Session::flush();
                
                // Determine message based on status
                $message = $user->status === 'blocked' 
                    ? 'You are blocked. Please contact administrator.' 
                    : 'Your account has been suspended. Please contact administrator.';
                
                // Handle AJAX requests differently
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => $message,
                        'redirect' => route('login.form')
                    ], 401);
                }
                
                // Regular web request - redirect to login
                return redirect()->route('login.form')->with('error', $message);
            }
        }

        return $next($request);
    }
}
