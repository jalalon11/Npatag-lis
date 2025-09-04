<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
        
        // Get school information from database
        $school = School::first();
        
        if (!$school) {
            // Fallback if no school exists
            $schoolName = 'Patag Elementary School';
            $schoolAddress = 'Patag, Donsol, Sorsogon';
            $principalName = 'Principal Name';
        } else {
            $schoolName = $school->name;
            $schoolAddress = $school->address;
            $principalName = $school->principal ?: 'Principal Name';
        }
        
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
            'quarters',
            'school'
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
            $school = School::first();
            if ($school) {
                $school->update(['principal' => $request->principal_name]);
            }
            
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
            $school = School::first();
            if ($school) {
                $school->update([
                    'name' => $request->school_name,
                    'address' => $request->school_address
                ]);
            }
            
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

    /**
     * Display the school details management page
     */
    public function schoolDetails()
    {
        $school = School::first();
        
        if (!$school) {
            // Create a default school if none exists with all required fields
            $school = School::create([
                'name' => 'School Name',
                'code' => 'SCH001',
                'address' => 'School Address',
                'region' => 'Region',
                'grade_levels' => json_encode(['K', '1', '2', '3', '4', '5', '6']),
                'division_name' => 'Division Name',
                'division_code' => 'DIV001',
                'division_address' => 'Division Address',
                'principal' => 'Principal Name'
            ]);
        }
        
        return view('admin.academics.school-details', compact('school'));
    }

    /**
     * Update all school details including logo
     */
    public function updateAllSchoolDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'region' => 'required|string|max:255',
            'grade_levels' => 'required|string|max:255',
            'division_name' => 'required|string|max:255',
            'division_code' => 'required|string|max:100',
            'division_address' => 'required|string|max:500',
            'principal' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your input. ' . $validator->errors()->first()
            ], 400);
        }
        
        try {
            $school = School::first();
            
            $schoolData = [
                'name' => $request->name,
                'code' => $request->code,
                'address' => $request->address,
                'region' => $request->region,
                'grade_levels' => is_array($request->grade_levels) ? json_encode($request->grade_levels) : $request->grade_levels,
                'division_name' => $request->division_name,
                'division_code' => $request->division_code,
                'division_address' => $request->division_address,
                'principal' => $request->principal
            ];
            
            if (!$school) {
                $school = School::create($schoolData);
            } else {
                $school->update($schoolData);
            }
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($school->logo_path) {
                    Storage::disk('r2')->delete($school->logo_path);
                }
                
                $logoFile = $request->file('logo');
                $logoName = 'school_logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
                $logoPath = 'images/' . $logoName;
                
                // Upload to R2 storage
                Storage::disk('r2')->putFileAs('images', $logoFile, $logoName, 'public');
                
                // Update the logo path in the school record
                $school->update(['logo_path' => $logoPath]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'School details updated successfully.',
                'logo_url' => $school->logo_url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update school details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete school logo
     */
    public function deleteSchoolLogo()
    {
        try {
            $school = School::first();
            if ($school && $school->logo_path) {
                // Delete the file from R2 storage
                Storage::disk('r2')->delete($school->logo_path);
                
                // Update the school record
                $school->update(['logo_path' => null]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'School logo deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete school logo: ' . $e->getMessage()
            ], 500);
        }
    }
}