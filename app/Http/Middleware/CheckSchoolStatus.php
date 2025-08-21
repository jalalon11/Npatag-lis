<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolStatus
{
    /**
     * Handle an incoming request.
     * Check if the user's associated school is active.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check maintenance mode using the SystemSetting model for consistency
        $isMaintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();

        // If user is admin, always allow access regardless of maintenance mode
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // If in maintenance mode and not admin, redirect to maintenance page
        if ($isMaintenanceMode && $user) {
            // Explicitly allowed routes
            $allowedRoutes = ['maintenance', 'maintenance/auth', 'logout', 'login'];
            $currentPath = $request->path();

            // Also allow admin routes
            if (strpos($currentPath, 'admin') === 0 || strpos($currentPath, 'admin/') === 0) {
                return $next($request);
            }

            if (!in_array($currentPath, $allowedRoutes)) {
                Log::info('Redirecting from CheckSchoolStatus middleware during maintenance', [
                    'user' => $user->email,
                    'role' => $user->role,
                    'path' => $request->path()
                ]);

                return redirect()->route('maintenance.auth');
            }
        }

        // Admin users are already handled above

        // For teachers, check if their school is active
        if ($user && $user->role === 'teacher' && $user->school_id) {
            $school = $user->school;

            // Check if school is inactive
            if (!$school || !$school->is_active) {
                // For regular teachers or other cases, log out
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your school account has been disabled. Please contact the administrator.');
            }
        }

        return $next($request);
    }
}