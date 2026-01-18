<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Log CSRF token details for POST requests
        if ($request->isMethod('POST')) {
            $sessionToken = Session::token();
            $requestToken = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
            $sessionId = Session::getId();
            $cookies = $request->cookies->all();
            $hasSessionCookie = isset($cookies[config('session.cookie')]);
            
            Log::channel('daily')->debug('=== CSRF TOKEN VERIFICATION ===', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'session_token' => $sessionToken,
                'request_token' => $requestToken,
                'tokens_match' => hash_equals($sessionToken, $requestToken),
                'session_id' => $sessionId,
                'session_started' => Session::isStarted(),
                'has_session_cookie' => $hasSessionCookie,
                'session_cookie_value' => $cookies[config('session.cookie')] ?? 'NOT FOUND',
                'all_cookies' => array_keys($cookies),
                'session_data' => Session::all(),
            ]);
        }
        
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            Log::channel('daily')->error('=== CSRF TOKEN MISMATCH (419 ERROR) ===', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'session_token' => Session::token(),
                'request_token' => $request->input('_token') ?: $request->header('X-CSRF-TOKEN'),
                'session_id' => Session::getId(),
                'has_session_cookie' => isset($request->cookies->all()[config('session.cookie')]),
                'all_cookies' => array_keys($request->cookies->all()),
                'session_data' => Session::all(),
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}
