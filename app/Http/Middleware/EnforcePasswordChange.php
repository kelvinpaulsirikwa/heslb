<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnforcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('EnforcePasswordChange: Starting', [
            'path' => $request->path(),
            'auth_check' => Auth::check(),
            'user_id' => Auth::user() ? Auth::user()->id : null
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            
            Log::info('EnforcePasswordChange: User found', [
                'user_id' => $user->id,
                'must_change_password' => $user->must_change_password
            ]);
            
            if ($user->must_change_password) {
                Log::info('EnforcePasswordChange: Redirecting to password change', [
                    'user_id' => $user->id,
                    'current_route' => $request->path()
                ]);
                
                if (!$request->routeIs('password.change') && !$request->routeIs('password.change.submit') && !$request->is('logout')) {
                    return redirect()->route('password.change')->with('warning', 'You must change your password before continuing.');
                }
            }
        }

        Log::info('EnforcePasswordChange: Allowing to continue');
        return $next($request);
    }
}


