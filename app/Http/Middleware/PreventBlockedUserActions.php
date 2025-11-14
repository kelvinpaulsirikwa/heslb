<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventBlockedUserActions
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
            
            // Check if user is blocked and trying to make changes
            if ($user->status === 'blocked' && $this->isModifyingRequest($request)) {
                // Allow admin users to unblock other users even if they are blocked themselves
                if ($this->isUnblockAction($request)) {
                    return $next($request);
                }
                
                $message = 'You are blocked and cannot make any changes. Please contact administrator.';
                
                // Handle AJAX requests
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => $message,
                        'status' => 'blocked'
                    ], 403);
                }
                
                // Regular web request
                return redirect()->back()->with('error', $message);
            }
        }

        return $next($request);
    }

    /**
     * Check if the request is trying to modify data
     */
    private function isModifyingRequest(Request $request): bool
    {
        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    /**
     * Check if the request is an unblock action
     */
    private function isUnblockAction(Request $request): bool
    {
        // Check if this is a DELETE request to the users resource (block/unblock action)
        if ($request->method() === 'DELETE' && str_contains($request->path(), 'admin/users/')) {
            return true;
        }
        
        return false;
    }
}
