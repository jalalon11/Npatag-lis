<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
// Removed SchoolDivision dependency for single school system
use App\Models\Student;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'teachersCount' => User::where('role', 'teacher')->count(),
            'teacherAdminsCount' => User::where('role', 'teacher_admin')->count(),
            'schoolsCount' => School::count(),
            'studentsCount' => Student::count()
        ];

        // Get pending support tickets count
        $pendingSupportCount = \App\Models\SupportTicket::where('status', 'open')->count();

        // Sales data removed - payment functionality has been disabled
        $currentMonthSales = 0;
        $currentYearSales = 0;
        $monthlySales = array_fill(1, 12, 0);
        $yearlySales = array_fill(now()->year - 4, 5, 0);

        return view('admin.dashboard', compact(
            'stats',
            'pendingSupportCount',
            'currentMonthSales',
            'currentYearSales',
            'monthlySales',
            'yearlySales'
        ));
    }

    /**
     * Update the global quarter selection
     */
    public function updateQuarter(Request $request)
    {
        $request->validate([
            'quarter' => 'required|in:Q1,Q2,Q3,Q4'
        ]);

        try {
            SystemSetting::setSetting('global_quarter', $request->quarter);
            
            return response()->json([
                'success' => true,
                'message' => 'Quarter updated successfully',
                'quarter' => $request->quarter
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quarter: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the admin profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the admin password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Password changed successfully.');
    }
}
