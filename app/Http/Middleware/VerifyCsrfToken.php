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
        Log::info('CSRF Middleware: Processing request', [
            'method' => $request->method(),
            'path' => $request->path(),
            'has_token' => $request->has('_token'),
            'token_value' => $request->input('_token'),
            'session_token' => session('_token'),
            'headers' => $request->headers->all()
        ]);

        return parent::handle($request, $next);
    }
}
