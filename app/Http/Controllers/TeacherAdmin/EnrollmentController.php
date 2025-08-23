<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->is_teacher_admin) {
                abort(403, 'Access denied. Teacher Admin privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of enrollment requests
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $school = $user->school;
        
        if (!$school) {
            return redirect()->back()->with('error', 'No school associated with your account.');
        }

        $query = Enrollment::with(['preferredSection', 'assignedSection', 'processedBy'])
            ->forSchool($school->id)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->forGradeLevel($request->grade_level);
        }

        // Search by student name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->paginate(15);
        
        // Get available sections for assignment
        $sections = Section::where('school_id', $school->id)
            ->where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        // Get grade levels for filtering
        $gradeLevels = Section::where('school_id', $school->id)
            ->distinct()
            ->pluck('grade_level')
            ->sort()
            ->values();

        return view('teacher_admin.enrollments.index', compact(
            'enrollments', 'sections', 'gradeLevels'
        ));
    }

    /**
     * Show the specified enrollment request
     */
    public function show(Enrollment $enrollment)
    {
        $user = Auth::user();
        
        // Ensure the enrollment belongs to the teacher admin's school
        if ($enrollment->school_id !== $user->school->id) {
            abort(403, 'Access denied.');
        }

        $enrollment->load(['preferredSection', 'assignedSection', 'processedBy', 'school']);
        
        // Get available sections for assignment
        $sections = Section::where('school_id', $enrollment->school_id)
            ->where('is_active', true)
            ->where('grade_level', $enrollment->preferred_grade_level)
            ->orderBy('name')
            ->get();

        return view('teacher_admin.enrollments.show', compact('enrollment', 'sections'));
    }

    /**
     * Verify an enrollment request (first step)
     */
    public function verify(Request $request, Enrollment $enrollment)
    {
        $user = Auth::user();
        
        // Ensure the enrollment belongs to the teacher admin's school
        if ($enrollment->school_id !== $user->school->id) {
            abort(403, 'Access denied.');
        }

        if (!$enrollment->isPending()) {
            return redirect()->back()->with('error', 'This enrollment request has already been processed.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $enrollment->update([
            'status' => 'verified',
            'processed_by' => $user->id,
            'processed_at' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->route('teacher-admin.enrollments.index')
            ->with('success', 'Enrollment verified successfully. You can now assign the student to a section.');
    }

    /**
     * Assign student to section (second step)
     */
    public function assignSection(Request $request, Enrollment $enrollment)
    {
        $user = Auth::user();
        
        // Ensure the enrollment belongs to the teacher admin's school
        if ($enrollment->school_id !== $user->school->id) {
            abort(403, 'Access denied.');
        }

        if (!$enrollment->isVerified()) {
            return redirect()->back()->with('error', 'This enrollment must be verified first before section assignment.');
        }

        $request->validate([
            'assigned_section_id' => [
                'required',
                'exists:sections,id',
                Rule::exists('sections', 'id')->where(function ($query) use ($enrollment) {
                    $query->where('school_id', $enrollment->school_id)
                          ->where('is_active', true);
                })
            ],
            'notes' => 'nullable|string|max:1000'
        ]);

        // Check section capacity before assignment
        $assignedSection = Section::find($request->assigned_section_id);
        if ($assignedSection && !$assignedSection->canAccommodate(1)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cannot assign student to this section. Section is at full capacity (' . 
                    $assignedSection->getCurrentStudentCount() . '/' . $assignedSection->student_limit . ').');
        }

        DB::transaction(function () use ($request, $enrollment, $user) {
            // Update enrollment with section assignment
            $enrollment->update([
                'assigned_section_id' => $request->assigned_section_id,
                'notes' => $request->notes
            ]);

            // Create student record
            $student = Student::create([
                'first_name' => $enrollment->first_name,
                'middle_name' => $enrollment->middle_name,
                'last_name' => $enrollment->last_name,
                'birth_date' => $enrollment->birth_date,
                'gender' => $enrollment->gender,
                'student_id' => $enrollment->student_id,
                'lrn' => $enrollment->lrn,
                'address' => $enrollment->address,
                'guardian_name' => $enrollment->guardian_name,
                'guardian_contact' => $enrollment->guardian_contact,
                'section_id' => $request->assigned_section_id,
                'school_id' => $enrollment->school_id,
                'enrollment_id' => $enrollment->id,
                'school_year' => now()->year . '-' . (now()->year + 1),
                'is_active' => true
            ]);

            // Update enrollment with student_id
            $enrollment->update(['student_id' => $student->id]);

            // Update enrollment status to enrolled
            $enrollment->update(['status' => 'enrolled']);
        });

        return redirect()->route('teacher-admin.enrollments.index')
            ->with('success', 'Student has been successfully enrolled and assigned to section.');
    }

    /**
     * Approve an enrollment request (legacy method - now combines verify and assign)
     */
    public function approve(Request $request, Enrollment $enrollment)
    {
        $user = Auth::user();
        
        // Ensure the enrollment belongs to the teacher admin's school
        if ($enrollment->school_id !== $user->school->id) {
            abort(403, 'Access denied.');
        }

        if (!$enrollment->isPending()) {
            return redirect()->back()->with('error', 'This enrollment request has already been processed.');
        }

        $request->validate([
            'assigned_section_id' => [
                'required',
                'exists:sections,id',
                Rule::exists('sections', 'id')->where(function ($query) use ($enrollment) {
                    $query->where('school_id', $enrollment->school_id)
                          ->where('is_active', true);
                })
            ],
            'notes' => 'nullable|string|max:1000'
        ]);

        // Check section capacity before approval
        $assignedSection = Section::find($request->assigned_section_id);
        if ($assignedSection && !$assignedSection->canAccommodate(1)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cannot assign student to this section. Section is at full capacity (' . 
                    $assignedSection->getCurrentStudentCount() . '/' . $assignedSection->student_limit . ').');
        }

        DB::transaction(function () use ($request, $enrollment, $user) {
            // Update enrollment status
            $enrollment->update([
                'status' => 'approved',
                'assigned_section_id' => $request->assigned_section_id,
                'processed_by' => $user->id,
                'processed_at' => now(),
                'notes' => $request->notes
            ]);

            // Create student record
            Student::create([
                'first_name' => $enrollment->first_name,
                'middle_name' => $enrollment->middle_name,
                'last_name' => $enrollment->last_name,
                'birth_date' => $enrollment->birth_date,
                'gender' => $enrollment->gender,
                'student_id' => $enrollment->student_id,
                'lrn' => $enrollment->lrn,
                'address' => $enrollment->address,
                'guardian_name' => $enrollment->guardian_name,
                'guardian_contact' => $enrollment->guardian_contact,
                'section_id' => $request->assigned_section_id
            ]);

            // Update enrollment status to enrolled
            $enrollment->update(['status' => 'enrolled']);
        });

        return redirect()->route('teacher-admin.enrollments.index')
            ->with('success', 'Enrollment approved successfully. Student has been enrolled.');
    }

    /**
     * Reject an enrollment request
     */
    public function reject(Request $request, Enrollment $enrollment)
    {
        $user = Auth::user();
        
        // Ensure the enrollment belongs to the teacher admin's school
        if ($enrollment->school_id !== $user->school->id) {
            abort(403, 'Access denied.');
        }

        if (!$enrollment->isPending()) {
            return redirect()->back()->with('error', 'This enrollment request has already been processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $enrollment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'processed_by' => $user->id,
            'processed_at' => now()
        ]);

        return redirect()->route('teacher-admin.enrollments.index')
            ->with('success', 'Enrollment request has been rejected.');
    }

    /**
     * Bulk approve enrollments
     */
    public function bulkApprove(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'enrollment_ids' => 'required|array',
            'enrollment_ids.*' => 'exists:enrollments,id',
            'section_assignments' => 'required|array',
            'section_assignments.*' => 'exists:sections,id'
        ]);

        $enrollments = Enrollment::whereIn('id', $request->enrollment_ids)
            ->where('school_id', $user->school->id)
            ->where('status', 'pending')
            ->get();

        if ($enrollments->count() !== count($request->enrollment_ids)) {
            return redirect()->back()->with('error', 'Some enrollments could not be processed.');
        }

        DB::transaction(function () use ($request, $enrollments, $user) {
            foreach ($enrollments as $enrollment) {
                $sectionId = $request->section_assignments[$enrollment->id];
                
                // Update enrollment
                $enrollment->update([
                    'status' => 'approved',
                    'assigned_section_id' => $sectionId,
                    'processed_by' => $user->id,
                    'processed_at' => now()
                ]);

                // Create student record
                Student::create([
                    'first_name' => $enrollment->first_name,
                    'middle_name' => $enrollment->middle_name,
                    'last_name' => $enrollment->last_name,
                    'birth_date' => $enrollment->birth_date,
                    'gender' => $enrollment->gender,
                    'student_id' => $enrollment->student_id,
                    'lrn' => $enrollment->lrn,
                    'address' => $enrollment->address,
                    'guardian_name' => $enrollment->guardian_name,
                    'guardian_contact' => $enrollment->guardian_contact,
                    'section_id' => $sectionId
                ]);

                // Update to enrolled status
                $enrollment->update(['status' => 'enrolled']);
            }
        });

        return redirect()->route('teacher-admin.enrollments.index')
            ->with('success', count($enrollments) . ' enrollments approved successfully.');
    }

    /**
     * Get enrollment statistics
     */
    public function statistics()
    {
        $user = Auth::user();
        $school = $user->school;
        
        if (!$school) {
            return response()->json(['error' => 'No school associated'], 404);
        }

        $stats = [
            'pending' => Enrollment::forSchool($school->id)->pending()->count(),
            'approved' => Enrollment::forSchool($school->id)->where('status', 'approved')->count(),
            'enrolled' => Enrollment::forSchool($school->id)->where('status', 'enrolled')->count(),
            'rejected' => Enrollment::forSchool($school->id)->where('status', 'rejected')->count(),
            'total' => Enrollment::forSchool($school->id)->count()
        ];

        return response()->json($stats);
    }

    /**
     * Get available sections for an enrollment
     */
    public function getSections(Enrollment $enrollment)
    {
        $user = Auth::user();
        $school = $user->school;
        
        if (!$school || $enrollment->school_id !== $school->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sections = Section::where('school_id', $school->id)
            ->where('grade_level', $enrollment->preferred_grade_level)
            ->with(['students'])
            ->get()
            ->map(function($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'grade_level' => $section->grade_level,
                    'student_limit' => $section->student_limit,
                    'current_student_count' => $section->getCurrentStudentCount()
                ];
            });

        return response()->json(['sections' => $sections]);
    }

    /**
     * Quick assign section and approve enrollment
     */
    public function quickAssign(Request $request, Enrollment $enrollment)
    {
        $user = Auth::user();
        $school = $user->school;
        
        if (!$school || $enrollment->school_id !== $school->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        if ($enrollment->status !== 'pending') {
            return redirect()->back()->with('error', 'This enrollment has already been processed.');
        }

        $request->validate([
            'section_id' => [
                'required',
                'exists:sections,id',
                Rule::exists('sections', 'id')->where(function ($query) use ($school) {
                    $query->where('school_id', $school->id);
                })
            ],
            'notes' => 'nullable|string|max:1000'
        ]);

        $section = Section::findOrFail($request->section_id);
        
        // Verify section belongs to the same school and grade level
        if ($section->school_id !== $school->id) {
            return redirect()->back()->with('error', 'Invalid section selected.');
        }
        
        if ($section->grade_level !== $enrollment->preferred_grade_level) {
            return redirect()->back()->with('error', 'Section grade level does not match enrollment request.');
        }

        // Check section capacity
        $currentCount = $section->getCurrentStudentCount();
        if ($section->student_limit && $currentCount >= $section->student_limit) {
            return redirect()->back()->with('error', 'Selected section is at full capacity.');
        }

        DB::beginTransaction();
        try {
            // Update enrollment status and assign section
            $enrollment->update([
                'status' => 'approved',
                'assigned_section_id' => $section->id,
                'processed_by' => $user->id,
                'processed_at' => now(),
                'notes' => $request->notes
            ]);

            // Create student record
            $student = Student::create([
                'first_name' => $enrollment->first_name,
                'middle_name' => $enrollment->middle_name,
                'last_name' => $enrollment->last_name,
                'student_id' => $enrollment->student_id,
                'lrn' => $enrollment->lrn,
                'gender' => $enrollment->gender,
                'birth_date' => $enrollment->birth_date,
                'address' => $enrollment->address,
                'guardian_name' => $enrollment->guardian_name,
                'guardian_contact' => $enrollment->guardian_contact,
                'section_id' => $section->id,
                'school_id' => $school->id,
                'enrollment_id' => $enrollment->id,
                'school_year' => now()->year . '-' . (now()->year + 1),
                'status' => 'active'
            ]);

            // Update enrollment to enrolled status
            $enrollment->update([
                'status' => 'enrolled',
                'student_id' => $student->id
            ]);

            DB::commit();
            
            return redirect()->route('teacher-admin.enrollments.index')
                ->with('success', "Enrollment approved and {$enrollment->first_name} {$enrollment->last_name} has been assigned to {$section->name}.");
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while processing the enrollment. Please try again.');
        }
    }
}
