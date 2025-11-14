<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnforcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->must_change_password) {
                if (!$request->routeIs('password.change') && !$request->routeIs('password.change.submit') && !$request->is('logout')) {
                    return redirect()->route('password.change')->with('warning', 'You must change your password before continuing.');
                }
            }
        }

        return $next($request);
    }
}


