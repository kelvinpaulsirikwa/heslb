<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class CustomCookieManager
{
    /**
     * Cookie configuration
     */
    private const COOKIE_PREFIX = 'heslb_';
    private const DEFAULT_EXPIRY = 120; // minutes
    private const SECURE_EXPIRY = 480; // 8 hours for secure cookies
    
    /**
     * Set a secure cookie with encryption
     */
    public function setSecureCookie(string $name, $value, int $minutes = null, bool $encrypt = true): void
    {
        $cookieName = self::COOKIE_PREFIX . $name;
        $expiry = $minutes ?? (app()->environment('production') ? self::SECURE_EXPIRY : self::DEFAULT_EXPIRY);
        
        if ($encrypt) {
            $value = Crypt::encrypt($value);
        }
        
        Cookie::queue(
            $cookieName,
            $value,
            $expiry,
            '/',
            $this->getDomain(),
            $this->shouldBeSecure(),
            true, // httpOnly
            false, // raw
            'strict' // sameSite
        );
    }
    
    /**
     * Get a secure cookie value
     */
    public function getSecureCookie(string $name, $default = null, bool $decrypt = true)
    {
        $cookieName = self::COOKIE_PREFIX . $name;
        $value = request()->cookie($cookieName, $default);
        
        if ($value && $decrypt) {
            try {
                return Crypt::decrypt($value);
            } catch (\Exception $e) {
                return $default;
            }
        }
        
        return $value;
    }
    
    /**
     * Remove a cookie
     */
    public function forgetCookie(string $name): void
    {
        $cookieName = self::COOKIE_PREFIX . $name;
        Cookie::queue(
            Cookie::forget($cookieName, '/', $this->getDomain())
        );
    }
    
    /**
     * Set authentication cookie
     */
    public function setAuthCookie($user, bool $remember = false): void
    {
        $token = $this->generateAuthToken($user);
        $expiry = $remember ? self::SECURE_EXPIRY * 7 : self::SECURE_EXPIRY; // 7 days if remember
        
        $this->setSecureCookie('auth_token', [
            'user_id' => $user->id,
            'token' => $token,
            'expires' => now()->addMinutes($expiry)->timestamp
        ], $expiry, false);
        
        // Store token hash in user session/database for validation
        $user->update([
            'remember_token' => Hash::make($token)
        ]);
    }
    
    /**
     * Get authenticated user from cookie
     */
    public function getAuthUser()
    {
        $authData = $this->getSecureCookie('auth_token');
        
        if (!$authData || !is_array($authData)) {
            return null;
        }
        
        $user = \App\Models\User::find($authData['user_id']);
        
        if (!$user || !$user->remember_token) {
            return null;
        }
        
        // Validate token
        if (!Hash::check($authData['token'], $user->remember_token)) {
            return null;
        }
        
        // Check expiry
        if ($authData['expires'] < now()->timestamp) {
            return null;
        }
        
        return $user;
    }
    
    /**
     * Clear authentication
     */
    public function clearAuth(): void
    {
        $this->forgetCookie('auth_token');
        $this->forgetCookie('csrf_token');
        $this->forgetCookie('session_data');
    }
    
    /**
     * Generate CSRF token
     */
    public function generateCSRFToken(): string
    {
        $token = Str::random(40);
        $this->setSecureCookie('csrf_token', $token, 120, false);
        return $token;
    }
    
    /**
     * Validate CSRF token
     */
    public function validateCSRFToken(string $token): bool
    {
        $storedToken = $this->getSecureCookie('csrf_token', null, false);
        return hash_equals($storedToken, $token);
    }
    
    /**
     * Store session data in cookie
     */
    public function setSessionData(array $data, int $minutes = null): void
    {
        $this->setSecureCookie('session_data', $data, $minutes);
    }
    
    /**
     * Get session data from cookie
     */
    public function getSessionData(): array
    {
        return $this->getSecureCookie('session_data', []);
    }
    
    /**
     * Flash message storage
     */
    public function setFlashMessage(string $type, string $message): void
    {
        $flash = $this->getSessionData();
        $flash['flash'] = [
            'type' => $type,
            'message' => $message,
            'timestamp' => now()->timestamp
        ];
        $this->setSessionData($flash);
    }
    
    /**
     * Get and clear flash messages
     */
    public function getFlashMessages(): ?array
    {
        $data = $this->getSessionData();
        $flash = $data['flash'] ?? null;
        
        if ($flash) {
            // Clear flash after reading
            unset($data['flash']);
            $this->setSessionData($data);
        }
        
        return $flash;
    }
    
    /**
     * Generate authentication token
     */
    private function generateAuthToken($user): string
    {
        return Hash::make($user->id . $user->email . time() . Str::random(20));
    }
    
    /**
     * Get cookie domain based on environment
     */
    private function getDomain(): ?string
    {
        if (app()->environment('production')) {
            return '.heslb.go.tz';
        }
        
        return null; // localhost
    }
    
    /**
     * Determine if cookie should be secure
     */
    private function shouldBeSecure(): bool
    {
        return app()->environment('production') || request()->secure();
    }
    
    /**
     * Get all custom cookies
     */
    public function getAllCookies(): array
    {
        $cookies = [];
        $allCookies = request()->cookie();
        
        foreach ($allCookies as $name => $value) {
            if (str_starts_with($name, self::COOKIE_PREFIX)) {
                $cleanName = str_replace(self::COOKIE_PREFIX, '', $name);
                $cookies[$cleanName] = $this->getSecureCookie($cleanName);
            }
        }
        
        return $cookies;
    }
}
