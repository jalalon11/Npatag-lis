<?php

namespace App\Http\Controllers;

use App\Services\RoleSwitchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoleSwitchController extends Controller
{
    /**
     * Toggle between admin and teacher mode
     */
    public function toggle(Request $request): JsonResponse
    {
        if (!RoleSwitchService::canSwitchRoles()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to switch roles'
            ], 403);
        }

        $newMode = RoleSwitchService::toggleMode();
        $dashboardRoute = RoleSwitchService::getDashboardRoute();

        return response()->json([
            'success' => true,
            'mode' => $newMode,
            'dashboard_url' => $dashboardRoute,
            'message' => 'Successfully switched to ' . ucfirst($newMode) . ' mode'
        ]);
    }

    /**
     * Get current mode status
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'can_switch' => RoleSwitchService::canSwitchRoles(),
            'current_mode' => RoleSwitchService::getCurrentMode(),
            'is_admin_acting_as_teacher' => RoleSwitchService::isAdminActingAsTeacher()
        ]);
    }
}