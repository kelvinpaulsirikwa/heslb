<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\Userstable;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogService;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm(Request $request)
    {
        $email = $request->old('email', '');
        $ipAddress = $request->ip();
        
        // Check if this IP address is currently locked out (regardless of email)
        $isLockedOut = false;
        $remainingTime = 0;
        
        // Check lockout status by IP address first
        if (LoginAttempt::hasExceededMaxAttempts('', $ipAddress)) {
            $remainingTime = LoginAttempt::getRemainingLockoutTime('', $ipAddress);
            // Only consider locked out if there's remaining time (lockout hasn't expired)
            $isLockedOut = $remainingTime > 0;
        }
        
        // If we have an email and it's not locked out by IP, check by email/IP combination
        if (!$isLockedOut && $email) {
            if (LoginAttempt::hasExceededMaxAttempts($email, $ipAddress)) {
                $remainingTime = LoginAttempt::getRemainingLockoutTime($email, $ipAddress);
                // Only consider locked out if there's remaining time (lockout hasn't expired)
                $isLockedOut = $remainingTime > 0;
            }
        }
        
        return response()->view('adminpages.auth.loginform', [
            'isLockedOut' => $isLockedOut,
            'remainingTime' => $remainingTime
        ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // Login with attempt limiting
    public function login(Request $request)
    {
        $email = $request->email;
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        
        Log::info('Login attempt received', ['email' => $email, 'ip' => $ipAddress]);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if login attempts have been exceeded
        if (LoginAttempt::hasExceededMaxAttempts($email, $ipAddress)) {
            $remainingTime = LoginAttempt::getRemainingLockoutTime($email, $ipAddress);
            
            // Only block if there's remaining lockout time (lockout hasn't expired)
            if ($remainingTime > 0) {
                Log::warning('Login blocked: too many attempts', [
                    'email' => $email, 
                    'ip' => $ipAddress,
                    'remaining_minutes' => $remainingTime
                ]);
                
                return back()->with('error', "Too many failed login attempts. Please try again in {$remainingTime} minutes.");
            }
        }

        $user = Userstable::where('email', $email)->first();
        $loginSuccessful = false;

        if (!$user) {
            Log::warning('Login failed: user not found', ['email' => $email]);
            $errorMessage = 'Invalid email or password';
        } elseif (!Hash::check($request->password, $user->password)) {
            // Fallback for legacy plaintext passwords to migrate them seamlessly
            if ($user->password === $request->password) {
                $user->password = $request->password; // hashed by mutator
                $user->must_change_password = true;   // force update on first login
                $user->save();
                Log::info('Migrated legacy plaintext password to hash', ['user_id' => $user->id]);
                $loginSuccessful = true;
            } else {
                Log::warning('Login failed: wrong password', ['email' => $email]);
                $errorMessage = 'Invalid email or password';
            }
        } else {
            $loginSuccessful = true;
        }

        // Record the login attempt
        LoginAttempt::recordAttempt($email, $ipAddress, $userAgent, $loginSuccessful);

        if (!$loginSuccessful) {
            // Get failed attempts count (includes the one we just recorded)
            $failedAttempts = LoginAttempt::getFailedAttemptsCount($email, $ipAddress);
            // Calculate remaining attempts (5 max - current failed attempts)
            $remainingAttempts = max(0, 5 - $failedAttempts);
            
            // If they've exceeded the limit, show lockout message
            if ($remainingAttempts <= 0) {
                $remainingTime = LoginAttempt::getRemainingLockoutTime($email, $ipAddress);
                // Only show lockout message if there's remaining time (lockout hasn't expired)
                if ($remainingTime > 0) {
                    return back()->with('error', "Too many failed login attempts. Please try again in {$remainingTime} minutes.");
                }
            }
            
            return back()->with('error', $errorMessage . " ({$remainingAttempts} attempts remaining)");
        }

        // Successful login - clear any failed attempts for this email/IP
        $clearedAttempts = LoginAttempt::clearFailedAttempts($email, $ipAddress);
        if ($clearedAttempts > 0) {
            Log::info('Cleared failed login attempts on successful login', [
                'email' => $email,
                'ip' => $ipAddress,
                'cleared_attempts' => $clearedAttempts
            ]);
        }

        auth()->login($user);
        $request->session()->regenerate();

        Log::info('Login successful', ['user_id' => $user->id, 'email' => $user->email]);

        // Audit log for successful login
        AuditLogService::log(
            'login',
            'System',
            $user->id,
            null,
            ['email' => $user->email, 'ip_address' => $ipAddress]
        );

        // Enforce password change if required
        if ($user->must_change_password) {
            return redirect()->route('password.change')->with('warning', 'You must change your password before continuing.');
        }

        return redirect()->route('dashboard');
    }

    // Show force change password form after login
    public function showChangePasswordForm()
    {
        return view('adminpages.auth.force-change-password');
    }

    // Handle password change submission
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var Userstable $user */
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login.form')->with('error', 'You must be logged in to change your password.');
        }
        
        $user->password = $request->password; // hashed by mutator
        $user->must_change_password = false;
        $user->save();

        // Regenerate session after password change to prevent session fixation
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Password changed successfully.');
    }



    // Handle logout
    public function logout(Request $request)
    {
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $userEmail = $user ? $user->email : null;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Audit log for logout - pass user_id explicitly since user is already logged out
        if ($userId) {
            AuditLogService::log(
                'logout',
                'System',
                $userId,
                null,
                ['email' => $userEmail, 'ip_address' => $request->ip()],
                null,
                $userId
            );
        }

        // Clear any cached data and prevent back button issues
        return redirect()->route('login.form')
            ->with('message', 'Logged out successfully')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
