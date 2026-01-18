<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        Log::info('Authenticate: User not authenticated, redirecting to login', [
            'path' => $request->path(),
            'session_id' => session()->getId(),
            'auth_check' => auth()->check()
        ]);
        
        return $request->expectsJson() ? null : route('login.form');
    }
}
