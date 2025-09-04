<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    /**
     * Show the initial setup form for creating the first admin account.
     */
    public function showSetup()
    {
        // Double-check that no admin exists
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('login')->with('error', 'System setup has already been completed.');
        }
        
        return view('setup.admin-registration');
    }
    
    /**
     * Process the initial admin account creation.
     */
    public function createAdmin(Request $request)
    {
        // Double-check that no admin exists
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('login')->with('error', 'System setup has already been completed.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create the first admin user
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'school_id' => null, // Admins don't belong to a specific school
            ]);
            
            DB::commit();
            
            // Log in the newly created admin
            Auth::login($admin);
            
            return redirect()->route('admin.dashboard')
                ->with('success', 'Administrator account created successfully! Welcome to the grading system.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred during setup: ' . $e->getMessage()])
                        ->withInput();
        }
    }
}