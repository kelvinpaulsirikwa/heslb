<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\RolePermission;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, string $permission): Response|RedirectResponse|JsonResponse
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Authentication required.',
                    'status' => 'unauthorized'
                ], 401);
            }
            return redirect()->route('login.form')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Check if user has the required permission
        if (!$this->hasPermission($user, $permission)) {
            // Log unauthorized access attempt
            \App\Services\AuditLogService::log(
                'unauthorized_access',
                'System',
                null,
                null,
                ['permission' => $permission, 'route' => $request->route()?->getName()],
                "Unauthorized access attempt to '{$permission}' by {$user->username}"
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Insufficient permissions. You do not have permission to perform this action.',
                    'status' => 'forbidden'
                ], 403);
            }

            // Don't redirect to dashboard if we're already trying to access it (prevents redirect loop)
            if ($request->route()?->getName() === 'dashboard') {
                // If trying to access dashboard without permission, redirect to login
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login.form')
                    ->with('error', 'You do not have permission to access the dashboard. Please contact an administrator.');
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. You do not have permission to perform this action.');
        }

        return $next($request);
    }

    /**
     * Check if user has the required permission
     */
    private function hasPermission($user, string $permission): bool
    {
        // Use the model's hasPermission method which checks both role and user permissions
        return $user->hasPermission($permission);
    }
}

