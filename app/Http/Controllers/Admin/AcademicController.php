<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicController extends Controller
{
    /**
     * Display the academic management page
     */
    public function index()
    {
        // Get current settings
        $currentQuarter = SystemSetting::getSetting('global_quarter', 'Q1');
        $currentSchoolYear = SystemSetting::getSetting('school_year', date('Y') . '-' . (date('Y') + 1));
        
        // Get principal name with admin user as default
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        $defaultPrincipalName = $adminUser ? $adminUser->name : 'Principal Name';
        $principalName = SystemSetting::getSetting('principal_name', $defaultPrincipalName);
        
        // Get school information
        $schoolName = SystemSetting::getSetting('school_name', 'Patag Elementary School');
        $schoolAddress = SystemSetting::getSetting('school_address', 'Patag, Donsol, Sorsogon');
        
        // Available quarters
        $quarters = [
            'Q1' => '1st Quarter',
            'Q2' => '2nd Quarter', 
            'Q3' => '3rd Quarter',
            'Q4' => '4th Quarter'
        ];
        
        return view('admin.academics.index', compact(
            'currentQuarter',
            'currentSchoolYear', 
            'principalName',
            'schoolName',
            'schoolAddress',
            'quarters'
        ));
    }
    
    /**
     * Update the global quarter setting
     */
    public function updateQuarter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quarter' => 'required|in:Q1,Q2,Q3,Q4'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid quarter selected.'
            ], 400);
        }
        
        try {
            SystemSetting::setSetting('global_quarter', $request->quarter, 'Current active quarter');
            
            return response()->json([
                'success' => true,
                'message' => 'Quarter updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quarter.'
            ], 500);
        }
    }
    
    /**
     * Update the school year setting
     */
    public function updateSchoolYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_year' => 'required|string|regex:/^\d{4}-\d{4}$/'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid school year format. Use YYYY-YYYY format.'
            ], 400);
        }
        
        try {
            SystemSetting::setSetting('school_year', $request->school_year, 'Current school year');
            
            return response()->json([
                'success' => true,
                'message' => 'School year updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update school year.'
            ], 500);
        }
    }
    
    /**
     * Update the principal name
     */
    public function updatePrincipal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'principal_name' => 'required|string|max:255'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Principal name is required and must be less than 255 characters.'
            ], 400);
        }
        
        try {
            SystemSetting::setSetting('principal_name', $request->principal_name, 'School principal name');
            
            return response()->json([
                'success' => true,
                'message' => 'Principal name updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update principal name.'
            ], 500);
        }
    }
    
    /**
     * Update school details
     */
    public function updateSchoolDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string|max:500'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your input. ' . $validator->errors()->first()
            ], 400);
        }
        
        try {
            SystemSetting::setSetting('school_name', $request->school_name, 'School name');
            SystemSetting::setSetting('school_address', $request->school_address, 'School address');
            
            return response()->json([
                'success' => true,
                'message' => 'School details updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update school details.'
            ], 500);
        }
    }
}