<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        // Log CSRF validation attempt
        Log::info('CSRF Check', [
            'method' => $request->method(),
            'path' => $request->path(),
            'has_token' => $request->has('_token'),
            'session_token' => session('_token'),
            'submitted_token' => $request->input('_token')
        ]);

        return parent::handle($request, $next);
    }
}
