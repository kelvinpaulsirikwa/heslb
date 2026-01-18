<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionDebugMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Comprehensive debugging for session and cookie issues
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log session configuration
        $this->logSessionConfig();
        
        // Log request details
        $this->logRequestDetails($request);
        
        // Log existing cookies
        $this->logExistingCookies($request);
        
        // Process request
        $response = $next($request);
        
        // CRITICAL: Check cookies BEFORE and AFTER session save
        $this->logCookiesBeforeSessionSave($response);
        
        // Force session save and check again
        // Note: Session might be closed by this point, but we can still get the ID
        try {
            $sessionId = Session::getId();
            if (Session::isStarted()) {
                Session::save();
                Log::channel('daily')->debug('=== SESSION SAVE CALLED ===', [
                    'session_id' => $sessionId,
                ]);
            } else {
                Log::channel('daily')->debug('=== SESSION NOT STARTED (may be closed) ===', [
                    'session_id' => $sessionId,
                    'has_session_id' => !empty($sessionId),
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error saving session', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
        
        // Log response cookies
        $this->logResponseCookies($response);
        
        // Log session status after request
        $this->logSessionStatus();
        
        // Check if cookie was added after session save
        $this->logCookiesAfterSessionSave($response);
        
        return $response;
    }
    
    private function logCookiesBeforeSessionSave(Response $response): void
    {
        $cookies = $response->headers->getCookies();
        $sessionCookieName = config('session.cookie');
        
        Log::channel('daily')->debug('=== COOKIES BEFORE SESSION SAVE ===', [
            'total_cookies' => count($cookies),
            'session_cookie_name' => $sessionCookieName,
            'has_session_cookie' => $this->hasCookie($cookies, $sessionCookieName),
        ]);
    }
    
    private function logCookiesAfterSessionSave(Response $response): void
    {
        $cookies = $response->headers->getCookies();
        $sessionCookieName = config('session.cookie');
        
        Log::channel('daily')->debug('=== COOKIES AFTER SESSION SAVE ===', [
            'total_cookies' => count($cookies),
            'session_cookie_name' => $sessionCookieName,
            'has_session_cookie' => $this->hasCookie($cookies, $sessionCookieName),
        ]);
        
        // If still no cookie, try to manually add it
        // Check if we have a session ID (even if session is closed, we can still set the cookie)
        $sessionId = Session::getId();
        if (!$this->hasCookie($cookies, $sessionCookieName) && !empty($sessionId)) {
            Log::channel('daily')->warning('=== ATTEMPTING TO MANUALLY SET SESSION COOKIE ===', [
                'session_id' => $sessionId,
                'session_started' => Session::isStarted(),
                'has_session_id' => !empty($sessionId),
            ]);
            $this->attemptManualCookieSet($response);
        } elseif (!$this->hasCookie($cookies, $sessionCookieName)) {
            Log::channel('daily')->error('=== CANNOT SET SESSION COOKIE: NO SESSION ID ===', [
                'session_id' => $sessionId,
                'session_started' => Session::isStarted(),
            ]);
        }
    }
    
    private function hasCookie(array $cookies, string $name): bool
    {
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === $name) {
                return true;
            }
        }
        return false;
    }
    
    private function attemptManualCookieSet(Response $response): void
    {
        try {
            $sessionConfig = config('session');
            $sessionId = Session::getId();
            
            if (empty($sessionId)) {
                Log::channel('daily')->error('=== CANNOT SET COOKIE: EMPTY SESSION ID ===');
                return;
            }
            
            // Get the domain - handle www subdomain issue
            $cookieDomain = $sessionConfig['domain'];
            $requestHost = request()->getHost();
            
            // CRITICAL FIX: If request is to www subdomain but domain is base domain,
            // we MUST use leading dot to make cookie work for subdomains
            // Example: request to www.heslb.go.tz but domain is heslb.go.tz
            // Solution: Use .heslb.go.tz (leading dot) to work for all subdomains
            
            // Check if request host is different from cookie domain
            if ($requestHost !== $cookieDomain) {
                // Check if request is to a subdomain of the configured domain
                // e.g., www.heslb.go.tz ends with .heslb.go.tz
                if (!empty($cookieDomain) && str_ends_with($requestHost, '.' . $cookieDomain)) {
                    // Request is to a subdomain - use leading dot for cookie domain
                    $cookieDomain = '.' . $cookieDomain;
                    Log::channel('daily')->info('=== FIXING DOMAIN FOR SUBDOMAIN ===', [
                        'original_domain' => $sessionConfig['domain'],
                        'new_domain' => $cookieDomain,
                        'request_host' => $requestHost,
                        'reason' => 'Request to subdomain requires leading dot',
                    ]);
                } elseif (!empty($cookieDomain) && strpos($requestHost, $cookieDomain) === false) {
                    // Domain doesn't match at all - use null to let browser handle it (exact host match)
                    Log::channel('daily')->warning('=== DOMAIN MISMATCH: USING NULL DOMAIN ===', [
                        'cookie_domain' => $cookieDomain,
                        'request_host' => $requestHost,
                    ]);
                    $cookieDomain = null;
                } elseif (strpos($requestHost, 'www.') === 0 && !empty($cookieDomain)) {
                    // Request is to www but domain is base - add leading dot
                    $cookieDomain = '.' . $cookieDomain;
                    Log::channel('daily')->info('=== FIXING DOMAIN: ADDING LEADING DOT FOR WWW ===', [
                        'original_domain' => $sessionConfig['domain'],
                        'new_domain' => $cookieDomain,
                        'request_host' => $requestHost,
                    ]);
                }
            } elseif (empty($cookieDomain) && strpos($requestHost, 'www.') === 0) {
                // No domain configured but request is to www subdomain
                // Extract base domain and use leading dot
                $baseDomain = substr($requestHost, 4); // Remove 'www.'
                $cookieDomain = '.' . $baseDomain;
                Log::channel('daily')->info('=== SETTING DOMAIN FOR WWW SUBDOMAIN ===', [
                    'request_host' => $requestHost,
                    'cookie_domain' => $cookieDomain,
                ]);
            }
            
            Log::channel('daily')->debug('=== MANUAL COOKIE SET ATTEMPT ===', [
                'session_id' => $sessionId,
                'cookie_name' => $sessionConfig['cookie'],
                'cookie_domain' => $cookieDomain ?? '(null - will use request host)',
                'request_host' => $requestHost,
                'cookie_path' => $sessionConfig['path'],
                'cookie_secure' => $sessionConfig['secure'],
                'cookie_http_only' => $sessionConfig['http_only'],
                'cookie_same_site' => $sessionConfig['same_site'],
                'session_lifetime' => $sessionConfig['lifetime'],
            ]);
            
            // Create cookie manually using Laravel's cookie helper
            $cookie = cookie(
                $sessionConfig['cookie'],
                $sessionId,
                $sessionConfig['lifetime'], // minutes
                $sessionConfig['path'],
                $cookieDomain,
                $sessionConfig['secure'],
                $sessionConfig['http_only'],
                false, // raw
                $sessionConfig['same_site']
            );
            
            // Set the cookie in response headers
            $response->headers->setCookie($cookie);
            
            // Verify it was set
            $cookiesAfter = $response->headers->getCookies();
            $wasSet = $this->hasCookie($cookiesAfter, $sessionConfig['cookie']);
            
            Log::channel('daily')->info('=== MANUAL COOKIE SET ' . ($wasSet ? 'SUCCESS' : 'FAILED') . ' ===', [
                'cookie_name' => $sessionConfig['cookie'],
                'cookie_domain' => $cookieDomain,
                'cookie_was_set' => $wasSet,
                'total_cookies_after' => count($cookiesAfter),
            ]);
            
            if ($wasSet) {
                // Log the actual cookie that was set
                foreach ($cookiesAfter as $setCookie) {
                    if ($setCookie->getName() === $sessionConfig['cookie']) {
                        Log::channel('daily')->info('=== MANUAL COOKIE DETAILS ===', [
                            'name' => $setCookie->getName(),
                            'domain' => $setCookie->getDomain(),
                            'path' => $setCookie->getPath(),
                            'secure' => $setCookie->isSecure(),
                            'http_only' => $setCookie->isHttpOnly(),
                            'same_site' => $setCookie->getSameSite(),
                            'expires' => $setCookie->getExpiresTime() ? date('Y-m-d H:i:s', $setCookie->getExpiresTime()) : 'session',
                        ]);
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error manually setting cookie', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
    
    private function logSessionConfig(): void
    {
        $config = config('session');
        Log::channel('daily')->debug('=== SESSION CONFIGURATION ===', [
            'driver' => $config['driver'] ?? 'not set',
            'lifetime' => $config['lifetime'] ?? 'not set',
            'cookie_name' => $config['cookie'] ?? 'not set',
            'cookie_path' => $config['path'] ?? 'not set',
            'cookie_domain' => $config['domain'] ?? 'not set',
            'cookie_secure' => $config['secure'] ?? 'not set',
            'cookie_http_only' => $config['http_only'] ?? 'not set',
            'cookie_same_site' => $config['same_site'] ?? 'not set',
            'encrypt' => $config['encrypt'] ?? 'not set',
            'files_path' => $config['files'] ?? 'not set',
            'env_APP_ENV' => env('APP_ENV'),
            'env_APP_DEBUG' => env('APP_DEBUG'),
            'env_SESSION_DRIVER' => env('SESSION_DRIVER'),
            'env_SESSION_DOMAIN' => env('SESSION_DOMAIN'),
            'env_SESSION_SECURE_COOKIE' => env('SESSION_SECURE_COOKIE'),
            'env_SESSION_LIFETIME' => env('SESSION_LIFETIME'),
        ]);
    }
    
    private function logRequestDetails(Request $request): void
    {
        Log::channel('daily')->debug('=== REQUEST DETAILS ===', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'scheme' => $request->getScheme(),
            'secure' => $request->secure(),
            'host' => $request->getHost(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_https' => $request->isSecure(),
            'server_https' => $_SERVER['HTTPS'] ?? 'not set',
            'server_port' => $_SERVER['SERVER_PORT'] ?? 'not set',
            'http_host' => $_SERVER['HTTP_HOST'] ?? 'not set',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'not set',
            'forwarded_proto' => $request->header('X-Forwarded-Proto'),
            'forwarded_host' => $request->header('X-Forwarded-Host'),
            'forwarded_port' => $request->header('X-Forwarded-Port'),
        ]);
    }
    
    private function logExistingCookies(Request $request): void
    {
        $cookies = $request->cookies->all();
        $sessionCookieName = config('session.cookie');
        
        Log::channel('daily')->debug('=== EXISTING COOKIES IN REQUEST ===', [
            'all_cookies' => $cookies,
            'session_cookie_name' => $sessionCookieName,
            'session_cookie_exists' => isset($cookies[$sessionCookieName]),
            'session_cookie_value' => $cookies[$sessionCookieName] ?? 'NOT FOUND',
            'cookie_count' => count($cookies),
        ]);
        
        // Log each cookie individually
        foreach ($cookies as $name => $value) {
            Log::channel('daily')->debug("Cookie: {$name}", [
                'value' => substr($value, 0, 50) . (strlen($value) > 50 ? '...' : ''),
                'length' => strlen($value),
            ]);
        }
    }
    
    private function logResponseCookies(Response $response): void
    {
        $cookies = $response->headers->getCookies();
        $sessionCookieName = config('session.cookie');
        
        Log::channel('daily')->debug('=== RESPONSE COOKIES ===', [
            'total_cookies' => count($cookies),
            'session_cookie_name' => $sessionCookieName,
        ]);
        
        foreach ($cookies as $cookie) {
            $isSessionCookie = $cookie->getName() === $sessionCookieName;
            
            Log::channel('daily')->debug(($isSessionCookie ? '*** SESSION COOKIE ***' : 'Cookie') . ": {$cookie->getName()}", [
                'name' => $cookie->getName(),
                'value' => substr($cookie->getValue(), 0, 50) . (strlen($cookie->getValue()) > 50 ? '...' : ''),
                'domain' => $cookie->getDomain(),
                'path' => $cookie->getPath(),
                'secure' => $cookie->isSecure(),
                'http_only' => $cookie->isHttpOnly(),
                'same_site' => $cookie->getSameSite(),
                'expires' => $cookie->getExpiresTime() ? date('Y-m-d H:i:s', $cookie->getExpiresTime()) : 'session',
                'is_session_cookie' => $isSessionCookie,
            ]);
            
            // Extra detailed logging for session cookie
            if ($isSessionCookie) {
                Log::channel('daily')->debug('=== SESSION COOKIE DETAILS ===', [
                    'name' => $cookie->getName(),
                    'domain' => $cookie->getDomain(),
                    'path' => $cookie->getPath(),
                    'secure' => $cookie->isSecure(),
                    'http_only' => $cookie->isHttpOnly(),
                    'same_site' => $cookie->getSameSite(),
                    'expires_timestamp' => $cookie->getExpiresTime(),
                    'expires_date' => $cookie->getExpiresTime() ? date('Y-m-d H:i:s', $cookie->getExpiresTime()) : 'session',
                    'raw_cookie_string' => $cookie->__toString(),
                ]);
            }
        }
        
        // Check if session cookie is missing
        $hasSessionCookie = false;
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === $sessionCookieName) {
                $hasSessionCookie = true;
                break;
            }
        }
        
        if (!$hasSessionCookie) {
            Log::channel('daily')->warning('=== SESSION COOKIE NOT FOUND IN RESPONSE ===', [
                'expected_name' => $sessionCookieName,
                'session_id' => Session::getId(),
                'session_started' => Session::isStarted(),
            ]);
        }
    }
    
    private function logSessionStatus(): void
    {
        try {
            Log::channel('daily')->debug('=== SESSION STATUS ===', [
                'session_id' => Session::getId(),
                'session_started' => Session::isStarted(),
                'session_name' => session_name(),
                'session_save_path' => session_save_path(),
                'session_status' => $this->getSessionStatusText(),
                'all_session_data' => Session::all(),
            ]);
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error getting session status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
    
    private function getSessionStatusText(): string
    {
        $status = session_status();
        return match($status) {
            PHP_SESSION_DISABLED => 'PHP_SESSION_DISABLED',
            PHP_SESSION_NONE => 'PHP_SESSION_NONE',
            PHP_SESSION_ACTIVE => 'PHP_SESSION_ACTIVE',
            default => "Unknown ({$status})",
        };
    }
}

