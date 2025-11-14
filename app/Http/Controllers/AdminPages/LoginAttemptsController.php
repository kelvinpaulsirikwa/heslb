<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginAttemptsController extends Controller
{
    /**
     * Display login attempts with filtering
     */
    public function index(Request $request)
    {
        $query = LoginAttempt::query();

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('attempted_at', '>=', Carbon::parse($request->date_from));
        }
        
        if ($request->filled('date_to')) {
            $query->where('attempted_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Filter by success status
        if ($request->filled('status')) {
            $query->where('successful', $request->status === 'successful');
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by IP
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        $attempts = $query->orderBy('attempted_at', 'desc')->paginate(50);

        // Get statistics
        $stats = [
            'total_attempts' => LoginAttempt::count(),
            'successful_attempts' => LoginAttempt::where('successful', true)->count(),
            'failed_attempts' => LoginAttempt::where('successful', false)->count(),
            'today_attempts' => LoginAttempt::whereDate('attempted_at', today())->count(),
            'blocked_ips' => $this->getBlockedIPs(),
        ];

        return view('adminpages.login-attempts.index', compact('attempts', 'stats'));
    }

    /**
     * Get currently blocked IP addresses
     */
    private function getBlockedIPs()
    {
        $blockedIPs = [];
        $cutoffTime = Carbon::now()->subMinutes(15);
        
        $failedAttempts = LoginAttempt::where('attempted_at', '>=', $cutoffTime)
            ->where('successful', false)
            ->selectRaw('ip_address, COUNT(*) as attempt_count')
            ->groupBy('ip_address')
            ->having('attempt_count', '>=', 5)
            ->get();

        foreach ($failedAttempts as $attempt) {
            $remainingTime = LoginAttempt::getRemainingLockoutTime('', $attempt->ip_address);
            if ($remainingTime > 0) {
                $blockedIPs[] = [
                    'ip_address' => $attempt->ip_address,
                    'attempt_count' => $attempt->attempt_count,
                    'remaining_minutes' => $remainingTime
                ];
            }
        }

        return $blockedIPs;
    }

    /**
     * Clear all login attempts
     */
    public function clearAll()
    {
        $deletedCount = LoginAttempt::truncate();
        
        return redirect()->back()->with('success', 'All login attempts have been cleared.');
    }

    /**
     * Clear old login attempts
     */
    public function clearOld()
    {
        $deletedCount = LoginAttempt::cleanup(7); // Clear attempts older than 7 days
        
        return redirect()->back()->with('success', "Cleared {$deletedCount} old login attempts.");
    }
}
