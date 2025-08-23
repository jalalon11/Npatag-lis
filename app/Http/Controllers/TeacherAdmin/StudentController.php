<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Section;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
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
     * Display a listing of enrolled students in the school
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Student::with(['section', 'enrollment'])
            ->whereHas('section', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->enrolled(); // Only students created from enrollments

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        } else {
            $query->where('is_active', true); // Default to active students
        }

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->whereHas('section', function($q) use ($request) {
                $q->where('grade_level', $request->grade_level);
            });
        }

        // Filter by section
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        // Filter by school year
        if ($request->filled('school_year')) {
            $query->bySchoolYear($request->school_year);
        }

        // Search by name, student ID, or LRN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('is_active', 'desc')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(20);

        // Get filter options
        $sections = Section::where('school_id', $user->school_id)
            ->where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        $gradeLevels = Section::where('school_id', $user->school_id)
            ->distinct()
            ->pluck('grade_level')
            ->sort()
            ->values();

        $schoolYears = Student::whereHas('section', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->enrolled()
            ->whereNotNull('school_year')
            ->distinct()
            ->pluck('school_year')
            ->sort()
            ->values();

        return view('teacher_admin.students.index', compact(
            'students', 'sections', 'gradeLevels', 'schoolYears'
        ));
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        // Ensure student belongs to the teacher admin's school
        if ($student->section->school_id !== Auth::user()->school_id) {
            abort(404);
        }

        $student->load(['section', 'enrollment', 'grades.subject', 'attendances']);

        return view('teacher_admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        // Ensure student belongs to the teacher admin's school
        if ($student->section->school_id !== Auth::user()->school_id) {
            abort(404);
        }

        $sections = Section::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        return view('teacher_admin.students.edit', compact('student', 'sections'));
    }

    /**
     * Update the specified student in storage
     */
    public function update(Request $request, Student $student)
    {
        // Ensure student belongs to the teacher admin's school
        if ($student->section->school_id !== Auth::user()->school_id) {
            abort(404);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students')->ignore($student->id)
            ],
            'lrn' => [
                'required',
                'string',
                'size:12',
                'regex:/^[0-9]{12}$/',
                Rule::unique('students')->ignore($student->id)
            ],
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
            'address' => 'nullable|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:50',
            'guardian_email' => 'nullable|email|max:255',
            'section_id' => [
                'required',
                'exists:sections,id',
                function ($attribute, $value, $fail) {
                    $section = Section::find($value);
                    if (!$section || $section->school_id !== Auth::user()->school_id) {
                        $fail('The selected section is invalid.');
                    }
                }
            ],
        ]);

        $student->update($validated);

        return redirect()->route('teacher-admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Toggle student active status
     */
    public function toggleStatus(Student $student)
    {
        // Ensure student belongs to the teacher admin's school
        if ($student->section->school_id !== Auth::user()->school_id) {
            abort(404);
        }

        $student->update([
            'is_active' => !$student->is_active
        ]);

        $status = $student->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Student {$status} successfully.");
    }

    /**
     * Get students by section (AJAX endpoint)
     */
    public function getBySection(Section $section)
    {
        // Ensure section belongs to the teacher admin's school
        if ($section->school_id !== Auth::user()->school_id) {
            abort(404);
        }

        $students = Student::where('section_id', $section->id)
            ->enrolled()
            ->where('is_active', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'student_id']);

        return response()->json($students);
    }

    /**
     * Get student statistics
     */
    public function statistics()
    {
        $user = Auth::user();
        
        $totalStudents = Student::whereHas('section', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->enrolled()
            ->count();

        $activeStudents = Student::whereHas('section', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->enrolled()
            ->where('is_active', true)
            ->count();

        $inactiveStudents = $totalStudents - $activeStudents;

        $maleStudents = Student::whereHas('section', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->enrolled()
            ->where('is_active', true)
            ->where('gender', 'Male')
            ->count();

        $femaleStudents = Student::whereHas('section', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->enrolled()
            ->where('is_active', true)
            ->where('gender', 'Female')
            ->count();

        return response()->json([
            'total' => $totalStudents,
            'active' => $activeStudents,
            'inactive' => $inactiveStudents,
            'male' => $maleStudents,
            'female' => $femaleStudents,
        ]);
    }
}
