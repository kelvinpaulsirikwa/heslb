<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visit;
use Carbon\Carbon;

class LogUniqueVisits
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $userAgent = substr($request->userAgent() ?? 'unknown', 0, 255); // Limit size

        // Define the timeframe to consider a "unique" visit (e.g., 1 hour)
        $timeframe = Carbon::now()->subHour();

        // Check if the same visitor has been logged recently
        $recentVisit = Visit::where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->where('visited_at', '>=', $timeframe)
            ->first();

        if (!$recentVisit) {
            Visit::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'visited_at' => now(),
            ]);
        }

        return $next($request);
    }
}
