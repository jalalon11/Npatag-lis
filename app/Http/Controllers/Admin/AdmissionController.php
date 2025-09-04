<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Section;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdmissionController extends Controller
{
    /**
     * Display a listing of student applications.
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['school', 'preferredSection', 'assignedSection', 'processedBy'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else {
            // Exclude approved, enrolled, and rejected students from the main table by default
            $query->whereNotIn('status', ['approved', 'enrolled', 'rejected']);
        }

        // Filter by school if provided
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('guardian_name', 'like', "%{$search}%");
            });
        }

        $admissions = $query->paginate(15);
        $schools = School::all();

        // Get statistics
        $stats = [
            'total' => Enrollment::count(),
            'pending' => Enrollment::where('status', 'pending')->count(),
            'approved' => Enrollment::whereIn('status', ['approved', 'enrolled'])->count(),
            'rejected' => Enrollment::where('status', 'rejected')->count(),
        ];

        return view('admin.admissions.index', compact('admissions', 'schools', 'stats'));
    }

    /**
     * Show the form for creating a new admission.
     */
    public function create()
    {
        $schools = School::all();
        $sections = Section::with('school')->where('is_active', true)->get();
        
        return view('admin.admissions.create', compact('schools', 'sections'));
    }

    /**
     * Store a newly created admission in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female',
            'student_id' => 'required|string|max:255|unique:admissions,student_id|unique:students,student_id',
            'lrn' => 'required|string|max:255|unique:admissions,lrn|unique:students,lrn',
            'address' => 'nullable|string|max:1000',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:50',
            'guardian_email' => 'required|email|max:255',
            'school_id' => 'required|exists:schools,id',
            'preferred_grade_level' => 'required|string|max:50',
            'preferred_section_id' => 'nullable|exists:sections,id',
            'school_year' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'medical_conditions' => 'nullable|string|max:1000',
            'medications' => 'nullable|string|max:1000',
            'special_needs' => 'nullable|string|max:1000',
            'previous_school' => 'nullable|string|max:255',
            'previous_grade_level' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:50',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'birth_certificate' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Handle birth certificate file upload
            $birthCertificatePath = null;
            if ($request->hasFile('birth_certificate')) {
                $file = $request->file('birth_certificate');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $birthCertificatePath = $file->storeAs('birth_certificates', $fileName);
            }

            // Create the admission record
            $admission = Enrollment::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'student_id' => $request->student_id,
                'lrn' => $request->lrn,
                'address' => $request->address,
                'guardian_name' => $request->guardian_name,
                'guardian_contact' => $request->guardian_contact,
                'guardian_email' => $request->guardian_email,
                'school_id' => $request->school_id,
                'preferred_grade_level' => $request->preferred_grade_level,
                'preferred_section_id' => $request->preferred_section_id,
                'school_year' => $request->school_year,
                'notes' => $request->notes,
                'medical_conditions' => $request->medical_conditions,
                'medications' => $request->medications,
                'special_needs' => $request->special_needs,
                'previous_school' => $request->previous_school,
                'previous_grade_level' => $request->previous_grade_level,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_number' => $request->emergency_contact_number,
                'emergency_contact_relationship' => $request->emergency_contact_relationship,
                'birth_certificate' => $birthCertificatePath,
                'status' => 'pending',
                'application_date' => now(),
            ]);

            // Create guardian user account if it doesn't exist
            $guardian = User::where('email', $request->guardian_email)->first();
            if (!$guardian) {
                $guardian = User::create([
                    'name' => $request->guardian_name,
                    'email' => $request->guardian_email,
                    'password' => bcrypt('password123'), // Default password
                    'role' => 'guardian',
                    'school_id' => $request->school_id,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.admissions.index')
                ->with('success', 'Student admission created successfully. Guardian account has been created with email: ' . $request->guardian_email);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error creating admission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified admission.
     */
    public function show(Enrollment $admission)
    {
        $admission->load(['school', 'preferredSection', 'assignedSection', 'processedBy']);
        $sections = Section::where('school_id', $admission->school_id)
            ->where('grade_level', $admission->preferred_grade_level)
            ->where('is_active', true)
            ->get();
            
        return view('admin.admissions.show', compact('admission', 'sections'));
    }

    /**
     * Show the form for editing the specified admission.
     */
    public function edit(Enrollment $admission)
    {
        if ($admission->status !== 'pending') {
            return redirect()->route('admin.admissions.index')
                ->with('error', 'Only pending admissions can be edited.');
        }

        $schools = School::all();
        $sections = Section::where('school_id', $admission->school_id)
            ->where('is_active', true)
            ->get();
            
        return view('admin.admissions.edit', compact('admission', 'schools', 'sections'));
    }

    /**
     * Update the specified admission in storage.
     */
    public function update(Request $request, Enrollment $admission)
    {
        if ($admission->status !== 'pending') {
            return redirect()->route('admin.admissions.index')
                ->with('error', 'Only pending admissions can be updated.');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female',
            'student_id' => 'required|string|max:255|unique:admissions,student_id,' . $admission->id . '|unique:students,student_id',
            'lrn' => 'required|string|max:255|unique:admissions,lrn,' . $admission->id . '|unique:students,lrn',
            'address' => 'nullable|string|max:1000',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:50',
            'guardian_email' => 'required|email|max:255',
            'school_id' => 'required|exists:schools,id',
            'preferred_grade_level' => 'required|string|max:50',
            'preferred_section_id' => 'nullable|exists:sections,id',
            'school_year' => 'required|string|max:20',
            'birth_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Handle birth certificate file upload
            if ($request->hasFile('birth_certificate')) {
                // Delete old birth certificate if exists
                if ($admission->birth_certificate && Storage::exists($admission->birth_certificate)) {
                    Storage::delete($admission->birth_certificate);
                }
                
                $file = $request->file('birth_certificate');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('birth_certificates', $fileName);
                $data['birth_certificate'] = $filePath;
            }
            
            $admission->update($data);

            return redirect()->route('admin.admissions.index')
                ->with('success', 'Admission updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating admission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display birth certificate file.
     */
    public function birthCertificate(Enrollment $admission)
    {
        if (!$admission->birth_certificate || !Storage::exists($admission->birth_certificate)) {
            abort(404, 'Birth certificate not found.');
        }

        return Storage::response($admission->birth_certificate);
    }

    /**
     * Approve the specified admission.
     */
    public function approve(Request $request, Enrollment $admission)
    {
        if ($admission->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending admissions can be approved.');
        }

        try {
            DB::beginTransaction();

            // Create guardian user account if it doesn't exist and guardian email is provided
            $guardianId = null;
            if ($admission->guardian_email) {
                $guardian = User::where('email', $admission->guardian_email)->first();
                if (!$guardian) {
                    $guardian = User::create([
                        'name' => $admission->guardian_name,
                        'email' => $admission->guardian_email,
                        'password' => bcrypt('password'), // Default password as requested
                        'role' => 'guardian',
                        'school_id' => $admission->school_id,
                    ]);
                }
                $guardianId = $guardian->id;
            }

            $admission->update([
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'assigned_section_id' => $request->assigned_section_id,
                'guardian_id' => $guardianId,
            ]);

            DB::commit();

            $message = 'Admission approved successfully. Student is now available in Learners Record.';
            if ($admission->guardian_email) {
                $message .= ' Guardian account created with email: ' . $admission->guardian_email . ' and default password: password';
            }

            return redirect()->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error approving admission: ' . $e->getMessage());
        }
    }

    /**
     * Reject the specified admission.
     */
    public function reject(Request $request, Enrollment $admission)
    {
        if ($admission->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending admissions can be rejected.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            $admission->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Admission rejected successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error rejecting admission: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified admission from storage.
     */
    public function destroy(Enrollment $admission)
    {
        if ($admission->status === 'enrolled') {
            return redirect()->back()
                ->with('error', 'Cannot delete enrolled admissions.');
        }

        try {
            $admission->delete();

            return redirect()->route('admin.admissions.index')
                ->with('success', 'Admission deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting admission: ' . $e->getMessage());
        }
    }

    /**
     * Get sections by school and grade level for AJAX requests.
     */
    public function getSections(Request $request)
    {
        $schoolId = $request->get('school_id');
        $gradeLevel = $request->get('grade_level');

        $query = Section::where('is_active', true);

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        if ($gradeLevel) {
            $query->where('grade_level', $gradeLevel);
        }

        $sections = $query->select('id', 'name', 'grade_level', 'school_id')
                         ->orderBy('name')
                         ->get();

        return response()->json($sections);
    }

    /**
     * Display approved students list.
     */
    public function approvedStudents(Request $request)
    {
        $query = Enrollment::with(['school', 'preferredSection', 'assignedSection', 'processedBy'])
            ->whereIn('status', ['approved', 'enrolled']) // Include both approved and enrolled students
            ->orderBy('updated_at', 'desc');

        // Filter by school if provided
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('guardian_name', 'like', "%{$search}%");
            });
        }

        $approvedStudents = $query->paginate(15);
        $schools = School::all();

        // Get statistics for approved students (including enrolled)
        $stats = [
            'total_approved' => Enrollment::whereIn('status', ['approved', 'enrolled'])->count(),
        ];

        return view('admin.admissions.approved', compact('approvedStudents', 'schools', 'stats'));
    }

    /**
     * Display rejected students list.
     */
    public function rejectedStudents(Request $request)
    {
        $query = Enrollment::with(['school', 'preferredSection', 'processedBy'])
            ->where('status', 'rejected')
            ->orderBy('updated_at', 'desc');

        // Filter by school if provided
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('guardian_name', 'like', "%{$search}%");
            });
        }

        $rejectedStudents = $query->paginate(15);
        $schools = School::all();

        // Get statistics for rejected students
        $stats = [
            'total_rejected' => Enrollment::where('status', 'rejected')->count(),
        ];

        return view('admin.admissions.rejected', compact('rejectedStudents', 'schools', 'stats'));
    }
}