<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\News;
use App\Models\Contact;
use App\Models\Publication;
use App\Models\Link;
 use App\Models\Videopodcast;
use App\Models\Visit;
use App\Models\WindowApplication;

class DashboardController extends Controller
{
    public function showdashboard()
    {
        Log::info('Auth check:', ['user' => Auth::user()]);

        if (!Auth::check()) {
            Log::warning('User is not authenticated. Redirecting...');
            return redirect()->route('login.form');
        }

        Log::info('User is authenticated.', ['user_id' => Auth::id()]);

        // Get current date for calculations
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();
        $thisWeekStart = now()->startOfWeek();
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        // Calculate visitor statistics for different time periods
        $visitStats = [
            'today' => Visit::whereDate('visited_at', $today)->count(),
            'yesterday' => Visit::whereDate('visited_at', $yesterday)->count(),
            'this_week' => Visit::where('visited_at', '>=', $thisWeekStart)->count(),
            'this_month' => Visit::where('visited_at', '>=', $thisMonthStart)->count(),
            'last_month' => Visit::whereBetween('visited_at', [$lastMonthStart, $lastMonthEnd])->count(),
            'total_all' => Visit::count(),
        ];

        // Get monthly visitor data for the last 12 months
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthlyStats[] = [
                'month' => $month->format('M Y'),
                'visits' => Visit::whereBetween('visited_at', [$monthStart, $monthEnd])->count(),
            ];
        }

        $stats = [
            'total_news'          => News::count(),
            'total_feedbacks'     => Contact::count(),
            'total_publications'  => Publication::count(),
            'total_links'         => Link::count(),
            'total_videos'        => Videopodcast::count(),
            'total_visits'        => Visit::count(),
            'total_applications'  => WindowApplication::count(),
            'visit_stats'         => $visitStats,
            'monthly_stats'       => $monthlyStats,
        ];

        return view('adminpages.dashboard.dashboard', compact('stats'));
    }
}
                          