<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'successful',
        'attempted_at',
    ];

    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    /**
     * Check if an email/IP has exceeded the maximum login attempts
     */
    public static function hasExceededMaxAttempts(string $email, string $ipAddress, int $maxAttempts = 5, int $lockoutMinutes = 15): bool
    {
        $lockoutTime = Carbon::now()->subMinutes($lockoutMinutes);
        
        $query = self::where('ip_address', $ipAddress);
        
        // If email is provided, also check by email
        if (!empty($email)) {
            $query->orWhere('email', $email);
        }
        
        $attempts = $query->where('attempted_at', '>=', $lockoutTime)
                         ->where('successful', false)
                         ->count();

        return $attempts >= $maxAttempts;
    }

    /**
     * Get remaining lockout time in minutes
     */
    public static function getRemainingLockoutTime(string $email, string $ipAddress, int $maxAttempts = 5, int $lockoutMinutes = 15): int
    {
        $lockoutTime = Carbon::now()->subMinutes($lockoutMinutes);
        
        $query = self::where('ip_address', $ipAddress);
        
        // If email is provided, also check by email
        if (!empty($email)) {
            $query->orWhere('email', $email);
        }
        
        $oldestAttempt = $query->where('attempted_at', '>=', $lockoutTime)
                              ->where('successful', false)
                              ->orderBy('attempted_at', 'asc')
                              ->first();

        if (!$oldestAttempt) {
            return 0;
        }

        $unlockTime = $oldestAttempt->attempted_at->addMinutes($lockoutMinutes);
        $remaining = Carbon::now()->diffInMinutes($unlockTime, false);
        
        return max(0, $remaining);
    }

    /**
     * Record a login attempt
     */
    public static function recordAttempt(string $email, string $ipAddress, string $userAgent = null, bool $successful = false): self
    {
        return self::create([
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'successful' => $successful,
            'attempted_at' => Carbon::now(),
        ]);
    }

    /**
     * Clean up old login attempts (older than specified days)
     */
    public static function cleanup(int $days = 30): int
    {
        $cutoffDate = Carbon::now()->subDays($days);
        
        return self::where('attempted_at', '<', $cutoffDate)->delete();
    }

    /**
     * Get failed attempts count for email/IP in the last X minutes
     */
    public static function getFailedAttemptsCount(string $email, string $ipAddress, int $minutes = 15): int
    {
        $cutoffTime = Carbon::now()->subMinutes($minutes);
        
        $query = self::where('ip_address', $ipAddress);
        
        // If email is provided, also check by email
        if (!empty($email)) {
            $query->orWhere('email', $email);
        }
        
        return $query->where('attempted_at', '>=', $cutoffTime)
                    ->where('successful', false)
                    ->count();
    }

    /**
     * Clear all failed login attempts for a successful login
     * This resets the attempt counter for the email/IP combination
     */
    public static function clearFailedAttempts(string $email, string $ipAddress): int
    {
        // Clear all failed attempts for this email/IP combination
        // We don't delete successful attempts as they might be useful for audit trails
        return self::where(function ($query) use ($email, $ipAddress) {
            $query->where('email', $email)
                  ->orWhere('ip_address', $ipAddress);
        })
        ->where('successful', false)
        ->delete();
    }
}
