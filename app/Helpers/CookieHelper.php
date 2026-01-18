<?php

namespace App\Helpers;

use App\Services\CustomCookieManager;

class CookieHelper
{
    /**
     * Get the CustomCookieManager instance
     */
    private static function manager(): CustomCookieManager
    {
        return app(CustomCookieManager::class);
    }

    /**
     * Set a secure cookie
     */
    public static function set(string $name, $value, int $minutes = null): void
    {
        self::manager()->setSecureCookie($name, $value, $minutes);
    }

    /**
     * Get a cookie value
     */
    public static function get(string $name, $default = null)
    {
        return self::manager()->getSecureCookie($name, $default);
    }

    /**
     * Remove a cookie
     */
    public static function forget(string $name): void
    {
        self::manager()->forgetCookie($name);
    }

    /**
     * Check if cookie exists
     */
    public static function has(string $name): bool
    {
        return self::get($name) !== null;
    }

    /**
     * Set flash message
     */
    public static function flash(string $type, string $message): void
    {
        self::manager()->setFlashMessage($type, $message);
    }

    /**
     * Get flash messages
     */
    public static function getFlash(): ?array
    {
        return self::manager()->getFlashMessages();
    }

    /**
     * Get CSRF token for forms
     */
    public static function csrfToken(): string
    {
        $token = self::get('csrf_token');
        if (!$token) {
            $token = self::manager()->generateCSRFToken();
        }
        return $token;
    }

    /**
     * Get current authenticated user
     */
    public static function user()
    {
        return self::manager()->getAuthUser();
    }

    /**
     * Check if user is authenticated
     */
    public static function check(): bool
    {
        return self::user() !== null;
    }

    /**
     * Get user ID
     */
    public static function id()
    {
        $user = self::user();
        return $user ? $user->id : null;
    }

    /**
     * Store session data
     */
    public static function put(string $key, $value): void
    {
        $data = self::manager()->getSessionData();
        $data[$key] = $value;
        self::manager()->setSessionData($data);
    }

    /**
     * Get session data
     */
    public static function pull(string $key, $default = null)
    {
        $data = self::manager()->getSessionData();
        $value = $data[$key] ?? $default;
        
        if (isset($data[$key])) {
            unset($data[$key]);
            self::manager()->setSessionData($data);
        }
        
        return $value;
    }

    /**
     * Get session data without removing
     */
    public static function getFromSession(string $key, $default = null)
    {
        $data = self::manager()->getSessionData();
        return $data[$key] ?? $default;
    }

    /**
     * Check if session has key
     */
    public static function hasSession(string $key): bool
    {
        $data = self::manager()->getSessionData();
        return isset($data[$key]);
    }

    /**
     * Clear all session data
     */
    public static function flush(): void
    {
        self::manager()->setSessionData([]);
    }

    /**
     * Logout user
     */
    public static function logout(): void
    {
        self::manager()->clearAuth();
    }

    /**
     * Get all custom cookies
     */
    public static function all(): array
    {
        return self::manager()->getAllCookies();
    }
}
