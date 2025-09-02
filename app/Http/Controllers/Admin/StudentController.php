<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Section;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        try {
            $query = Student::with(['section.school'])
                ->enrolled(); // Only students created from enrollments

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter by section
            if ($request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }

            // Filter by status
            $status = $request->get('status', 'active');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
            // 'all' shows both active and inactive

            $students = $query->orderBy('last_name')
                            ->orderBy('first_name')
                            ->paginate(15)
                            ->withQueryString();

            // Get sections for filter dropdown
            $sections = Section::with('school')
                             ->where('is_active', true)
                             ->orderBy('name')
                             ->get();

            // Statistics
            $stats = [
                'total' => Student::admitted()->count(),
                'active' => Student::admitted()->where('is_active', true)->count(),
                'inactive' => Student::admitted()->where('is_active', false)->count(),
            ];

            return view('admin.students.index', compact('students', 'sections', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error in student index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading students. Please try again.');
        }
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $sections = Section::with('school')
                         ->where('is_active', true)
                         ->orderBy('name')
                         ->get();

        return view('admin.students.create', compact('sections'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'section_id' => 'required|exists:sections,id',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email',
        ]);

        try {
            DB::beginTransaction();

            // Generate unique student ID
            $studentId = $this->generateStudentId();

            $student = Student::create([
                'student_id' => $studentId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'section_id' => $request->section_id,
                'guardian_name' => $request->guardian_name,
                'guardian_phone' => $request->guardian_phone,
                'guardian_email' => $request->guardian_email,
                'is_active' => true,
                'admission_id' => null, // Manual creation, not from admission
            ]);

            DB::commit();

            return redirect()->route('admin.students.show', $student)
                           ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating student: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating student. Please try again.');
        }
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load(['section.school', 'grades.subject', 'attendances' => function($query) {
            $query->latest()->limit(10);
        }]);

        // Get recent grades
        $recentGrades = $student->grades()
                              ->with('subject')
                              ->latest()
                              ->limit(10)
                              ->get();

        // Calculate attendance rate for current month
        $attendanceRate = $this->calculateAttendanceRate($student);

        return view('admin.students.show', compact('student', 'recentGrades', 'attendanceRate'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        $sections = Section::with('school')
                         ->where('is_active', true)
                         ->orderBy('name')
                         ->get();

        return view('admin.students.edit', compact('student', 'sections'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('students')->ignore($student->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'section_id' => 'required|exists:sections,id',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email',
        ]);

        try {
            $student->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'section_id' => $request->section_id,
                'guardian_name' => $request->guardian_name,
                'guardian_phone' => $request->guardian_phone,
                'guardian_email' => $request->guardian_email,
            ]);

            return redirect()->route('admin.students.show', $student)
                           ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating student. Please try again.');
        }
    }

    /**
     * Toggle student status (active/inactive)
     */
    public function toggleStatus(Student $student)
    {
        try {
            $student->update([
                'is_active' => !$student->is_active
            ]);

            $status = $student->is_active ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "Student {$status} successfully.");
        } catch (\Exception $e) {
            Log::error('Error toggling student status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating student status.');
        }
    }

    /**
     * Get students by section (AJAX)
     */
    public function getBySection(Section $section)
    {
        $students = $section->students()
                          ->enrolled()
                          ->where('is_active', true)
                          ->orderBy('last_name')
                          ->orderBy('first_name')
                          ->get(['id', 'first_name', 'last_name', 'student_id']);

        return response()->json($students);
    }

    /**
     * Get student statistics (AJAX)
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_students' => Student::admitted()->count(),
                'active_students' => Student::admitted()->where('is_active', true)->count(),
                'inactive_students' => Student::admitted()->where('is_active', false)->count(),
                'students_by_section' => Section::withCount(['students' => function($query) {
                    $query->admitted()->where('is_active', true);
                }])->where('is_active', true)->get(),
                'recent_admissions' => Student::admitted()
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Error getting student statistics: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading statistics'], 500);
        }
    }

    /**
     * Generate unique student ID
     */
    private function generateStudentId()
    {
        $year = date('Y');
        $prefix = 'STU' . $year;
        
        // Get the last student ID for this year
        $lastStudent = Student::where('student_id', 'like', $prefix . '%')
                            ->orderBy('student_id', 'desc')
                            ->first();
        
        if ($lastStudent) {
            $lastNumber = (int) substr($lastStudent->student_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate attendance rate for student
     */
    private function calculateAttendanceRate(Student $student)
    {
        $totalDays = $student->attendances()->count();
        if ($totalDays === 0) {
            return 0;
        }
        
        $presentDays = $student->attendances()->where('status', 'present')->count();
        return round(($presentDays / $totalDays) * 100, 1);
    }
}