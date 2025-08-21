<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\School;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EnrollmentApplicationController extends Controller
{
    /**
     * Show the enrollment application form
     * Automatically uses the single school in the system
     */
    public function create(Request $request)
    {
        // Get the single school in the system
        $school = School::where('is_active', true)->first();
        
        if (!$school) {
            return redirect()->back()
                ->with('error', 'No active school is available for enrollment at this time.');
        }
        
        // Get sections for the school (for reference only, not for selection)
        $sections = Section::where('school_id', $school->id)
            ->where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();
            
        $gradeLevels = $sections->pluck('grade_level')
            ->unique()
            ->sort()
            ->values();
        
        // For single school system, we don't need schools array
        $schools = collect([$school]);
            
        return view('enrollment.create', compact('school', 'schools', 'sections', 'gradeLevels'));
    }
    
    /**
     * Show the new enrollment application form
     */
    public function apply()
    {
        // Get the single school in the system
        $school = School::where('is_active', true)->first();
        
        if (!$school) {
            return redirect()->back()
                ->with('error', 'No active school is available for enrollment at this time.');
        }
            
        return view('enrollment.apply', compact('school'));
    }
    
    /**
     * Store the enrollment application and create student directly
     * Automatically uses the single school in the system
     */
    public function store(Request $request)
    {
        // Get the single school in the system
        $school = School::where('is_active', true)->first();
        
        if (!$school) {
            return back()->withErrors(['error' => 'No active school is available for enrollment at this time.']);
        }
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date|before:today',
            'student_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('enrollments', 'student_id')
            ],
            'lrn' => [
                'required',
                'string',
                'size:12',
                'regex:/^[0-9]{12}$/',
                Rule::unique('enrollments', 'lrn')
            ],
            'address' => 'nullable|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:50',
            'guardian_email' => 'nullable|email|max:255',
            'student_email' => 'nullable|email|max:255',
            'preferred_grade_level' => 'nullable|string|max:50',
            'preferred_section' => 'nullable|string|max:100',
            'previous_school' => 'nullable|string|max:255',
            'previous_grade_level' => 'nullable|string|max:50',
            'medical_conditions' => 'nullable|string|max:1000',
            'medications' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:50',
            'emergency_contact_relationship' => 'nullable|string|max:100',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Prepare data for enrollment creation - only include fields that exist in DB
            $enrollmentData = [
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'student_id' => $validated['student_id'],
                'lrn' => $validated['lrn'],
                'address' => $validated['address'] ?? null,
                'guardian_name' => $validated['guardian_name'],
                'guardian_contact' => $validated['guardian_contact'],
                'guardian_email' => $validated['guardian_email'] ?? null,
                'preferred_grade_level' => $validated['preferred_grade_level'] ?? null,
            ];
            
            // Create enrollment application
            $enrollment = Enrollment::create(array_merge($enrollmentData, [
                'school_id' => $school->id,
                'status' => 'pending',
                'application_date' => now(),
                'school_year' => date('Y') . '-' . (date('Y') + 1),
            ]));
            
            DB::commit();
            
            return redirect()->route('enrollment.success', ['enrollment' => $enrollment->id])
                ->with('success', 'Your enrollment application has been submitted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the actual error for debugging
            \Log::error('Enrollment submission error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'error' => 'There was an error submitting your application. Please try again.'
            ])->withInput();
        }
    }
    
    /**
     * Submit enrollment application to teacher admin panel
     */
    public function submit(Request $request)
    {
        // Get the single school in the system
        $school = School::where('is_active', true)->first();
        
        if (!$school) {
            return back()->withErrors(['error' => 'No active school is available for enrollment at this time.']);
        }
        

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date|before:today',
            'student_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('enrollments', 'student_id')
            ],
            'lrn' => [
                'required',
                'string',
                'size:12',
                'regex:/^[0-9]{12}$/',
                Rule::unique('enrollments', 'lrn')
            ],
            'address' => 'nullable|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:50',
            'guardian_email' => 'nullable|email|max:255',
            'preferred_grade_level' => 'nullable|string|max:50',
            'preferred_section' => 'nullable|string|max:100',
            'previous_school' => 'nullable|string|max:255',
            'previous_grade_level' => 'nullable|string|max:50',
            'medical_conditions' => 'nullable|string|max:1000',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Prepare data for enrollment creation - only include fields that exist in DB
            $enrollmentData = [
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'student_id' => $validated['student_id'],
                'lrn' => $validated['lrn'],
                'address' => $validated['address'] ?? null,
                'guardian_name' => $validated['guardian_name'],
                'guardian_contact' => $validated['guardian_contact'],
                'guardian_email' => $validated['guardian_email'] ?? null,
                'preferred_grade_level' => $validated['preferred_grade_level'] ?? null,
            ];
            
            // Note: preferred_section, previous_school, previous_grade_level, medical_conditions
            // are not in the current database schema, so they're excluded from insertion
            
            // Create enrollment application for teacher admin review
            $enrollment = Enrollment::create(array_merge($enrollmentData, [
                'school_id' => $school->id,
                'status' => 'pending',
                'application_date' => now(),
                'school_year' => date('Y') . '-' . (date('Y') + 1), // Add school year
            ]));
            
            DB::commit();
            
            return redirect()->route('enrollment.submitted', ['enrollment' => $enrollment->id])
                ->with('success', 'Your enrollment application has been submitted for review!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'There was an error submitting your application. Please try again.'
            ])->withInput();
        }
    }
    
    /**
     * Show enrollment success page
     */
    public function success(Enrollment $enrollment)
    {
        $enrollment->load('school');
        return view('enrollment.success', compact('enrollment'));
    }
    
    /**
     * Show enrollment submitted page with teacher admin info
     */
    public function submitted(Enrollment $enrollment)
    {
        $enrollment->load('school');
        return view('enrollment.submitted', compact('enrollment'));
    }
    
    /**
     * Check enrollment status
     */
    public function status(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'last_name' => 'required|string',
            'birth_date' => 'required|date',
        ]);
        
        $enrollment = Enrollment::where('id', $validated['enrollment_id'])
            ->where('last_name', $validated['last_name'])
            ->where('birth_date', $validated['birth_date'])
            ->with('school')
            ->first();
            
        if (!$enrollment) {
            return back()->withErrors([
                'not_found' => 'No enrollment found with the provided information.'
            ]);
        }
        
        return view('enrollment.status', compact('enrollment'));
    }
    
    /**
     * Show enrollment status check form
     */
    public function statusForm()
    {
        return view('enrollment.status-form');
    }
    
    /**
     * Get sections by school (AJAX)
     */
    public function getSectionsBySchool(School $school)
    {
        if (!$school->is_active) {
            return response()->json(['error' => 'School is not active'], 404);
        }
        
        $sections = Section::where('school_id', $school->id)
            ->where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get(['id', 'name', 'grade_level']);
            
        $gradeLevels = $sections->pluck('grade_level')
            ->unique()
            ->sort()
            ->values();
            
        return response()->json([
            'sections' => $sections,
            'grade_levels' => $gradeLevels
        ]);
    }
}
