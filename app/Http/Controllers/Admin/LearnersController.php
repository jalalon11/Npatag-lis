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

class LearnersController extends Controller
{
    /**
     * Display a listing of approved admission students (Learners Record).
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['school', 'assignedSection', 'processedBy'])
            ->where('status', 'approved')
            ->whereDoesntHave('students') // Exclude students who are already enrolled
            ->orderBy('processed_at', 'desc');

        // Filter by school if provided
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        // Filter by grade level if provided
        if ($request->has('grade_level') && $request->grade_level) {
            $query->where('preferred_grade_level', $request->grade_level);
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
        
        // Get available grade levels from approved students
        $gradeLevels = Enrollment::where('status', 'approved')
            ->distinct()
            ->pluck('preferred_grade_level')
            ->sort()
            ->values();

        // Get statistics
        $stats = [
            'total_approved' => Enrollment::where('status', 'approved')->count(),
            'enrolled_students' => Student::where('admission_id', '!=', null)->count(),
            'pending_enrollment' => Enrollment::where('status', 'approved')
                ->whereDoesntHave('students')
                ->count(),
        ];

        return view('admin.learners.index', compact('approvedStudents', 'schools', 'gradeLevels', 'stats'));
    }

    /**
     * Get available sections for a specific student based on their grade level.
     */
    public function getSections(Request $request, $studentId)
    {
        try {
            $admission = Enrollment::findOrFail($studentId);
            
            $sections = Section::where('school_id', $admission->school_id)
                ->where('grade_level', $admission->preferred_grade_level)
                ->where('is_active', true)
                ->with(['adviser', 'students'])
                ->get()
                ->map(function($section) {
                    return [
                        'id' => $section->id,
                        'name' => $section->name,
                        'adviser_name' => $section->adviser ? $section->adviser->name : 'No Adviser',
                        'student_count' => $section->students->count(),
                        'capacity' => $section->capacity ?? 'No Limit',
                        'is_full' => $section->capacity ? ($section->students->count() >= $section->capacity) : false,
                    ];
                });

            return response()->json([
                'success' => true,
                'sections' => $sections,
                'student_name' => $admission->first_name . ' ' . $admission->last_name,
                'grade_level' => $admission->preferred_grade_level,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sections: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enroll an approved student into a section.
     */
    public function enroll(Request $request, $studentId)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:sections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid section selected.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get the approved admission
            $admission = Enrollment::where('id', $studentId)
                ->where('status', 'approved')
                ->firstOrFail();

            // Check if student is already enrolled
            $existingStudent = Student::where('admission_id', $admission->id)->first();
            if ($existingStudent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is already enrolled in the system.'
                ], 400);
            }

            // Get the selected section
            $section = Section::findOrFail($request->section_id);

            // Check if section belongs to the same school and grade level
            if ($section->school_id !== $admission->school_id || 
                $section->grade_level !== $admission->preferred_grade_level) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section does not match student\'s school or grade level.'
                ], 400);
            }

            // Check section capacity
            if ($section->capacity && $section->students()->count() >= $section->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected section is at full capacity.'
                ], 400);
            }

            // Create the student record
            $student = Student::create([
                'first_name' => $admission->first_name,
                'middle_name' => $admission->middle_name,
                'last_name' => $admission->last_name,
                'birth_date' => $admission->birth_date,
                'gender' => strtolower($admission->gender),
                'student_id' => $admission->student_id,
                'lrn' => $admission->lrn,
                'address' => $admission->address,
                'guardian_name' => $admission->guardian_name,
                'guardian_contact' => $admission->guardian_contact,
                'guardian_email' => $admission->guardian_email,
                'section_id' => $section->id,
                'school_id' => $admission->school_id,
                'admission_id' => $admission->id,
                'school_year' => $admission->school_year,
                'is_active' => true,
            ]);

            // Update admission status to enrolled
            $admission->update([
                'status' => 'enrolled',
                'assigned_section_id' => $section->id,
                'processed_at' => now(),
            ]);

            // Ensure guardian user account exists
            $guardian = User::where('email', $admission->guardian_email)->first();
            if (!$guardian) {
                $guardian = User::create([
                    'name' => $admission->guardian_name,
                    'email' => $admission->guardian_email,
                    'password' => bcrypt('password123'), // Default password
                    'role' => 'guardian',
                    'school_id' => $admission->school_id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student enrolled successfully into ' . $section->name . '.',
                'student' => [
                    'id' => $student->id,
                    'name' => $student->full_name,
                    'section' => $section->name,
                    'student_id' => $student->student_id,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error enrolling student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show details of an approved student.
     */
    public function show($studentId)
    {
        try {
            $admission = Enrollment::with(['school', 'assignedSection', 'processedBy'])
                ->where('status', 'approved')
                ->findOrFail($studentId);

            // Check if already enrolled
            $enrolledStudent = Student::where('admission_id', $admission->id)->first();

            // Get available sections for this student
            $sections = Section::where('school_id', $admission->school_id)
                ->where('grade_level', $admission->preferred_grade_level)
                ->where('is_active', true)
                ->with(['adviser', 'students'])
                ->get();

            return view('admin.learners.show', compact('admission', 'enrolledStudent', 'sections'));

        } catch (\Exception $e) {
            return redirect()->route('admin.learners.index')
                ->with('error', 'Student not found or not approved.');
        }
    }

    /**
     * Show all enrolled students.
     */
    public function enrolled(Request $request)
    {
        $query = Student::with(['section.school', 'section.adviser', 'admission'])
            ->whereNotNull('admission_id')
            ->where('students.is_active', true)
            ->orderBy('created_at', 'desc');

        // Filter by school if provided
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        // Filter by grade level if provided
        if ($request->has('grade_level') && $request->grade_level) {
            $query->whereHas('section', function($q) use ($request) {
                $q->where('grade_level', $request->grade_level);
            });
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

        $enrolledStudents = $query->paginate(15);
        $schools = School::all();
        
        // Get available grade levels from enrolled students
        $gradeLevels = Student::whereNotNull('admission_id')
            ->join('sections', 'students.section_id', '=', 'sections.id')
            ->distinct()
            ->pluck('sections.grade_level')
            ->sort()
            ->values();

        // Get statistics
        $stats = [
            'total_enrolled' => Student::whereNotNull('admission_id')->where('students.is_active', true)->count(),
            'by_school' => Student::whereNotNull('admission_id')
                ->where('students.is_active', true)
                ->join('schools', 'students.school_id', '=', 'schools.id')
                ->where('schools.is_active', true)
                ->select('schools.name', DB::raw('count(*) as count'))
                ->groupBy('schools.id', 'schools.name')
                ->get(),
            'by_grade' => Student::whereNotNull('admission_id')
                ->where('students.is_active', true)
                ->join('sections', 'students.section_id', '=', 'sections.id')
                ->where('sections.is_active', true)
                ->select('sections.grade_level', DB::raw('count(*) as count'))
                ->groupBy('sections.grade_level')
                ->orderBy('sections.grade_level')
                ->get(),
        ];

        return view('admin.learners.enrolled', compact('enrolledStudents', 'schools', 'gradeLevels', 'stats'));
    }

    /**
     * Show details of an enrolled student.
     */
    public function showEnrolled($studentId)
    {
        try {
            $student = Student::with([
                'section.school', 
                'section.adviser', 
                'admission', 
                'grades.subject',
                'attendances' => function($query) {
                    $query->orderBy('date', 'desc')->limit(10);
                }
            ])
            ->whereNotNull('admission_id')
            ->findOrFail($studentId);

            // Get grade statistics
            $gradeStats = [
                'total_subjects' => $student->grades()->distinct('subject_id')->count(),
                'average_grade' => $student->grades()->avg('score'),
                'highest_grade' => $student->grades()->max('score'),
                'lowest_grade' => $student->grades()->min('score'),
            ];

            // Get attendance statistics
            $attendanceStats = [
                'total_days' => $student->attendances()->count(),
                'present_days' => $student->attendances()->where('status', 'present')->count(),
                'absent_days' => $student->attendances()->where('status', 'absent')->count(),
                'late_days' => $student->attendances()->where('status', 'late')->count(),
            ];

            if ($attendanceStats['total_days'] > 0) {
                $attendanceStats['attendance_rate'] = round(($attendanceStats['present_days'] / $attendanceStats['total_days']) * 100, 2);
            } else {
                $attendanceStats['attendance_rate'] = 0;
            }

            return response()->json([
                'success' => true,
                'student' => $student,
                'gradeStats' => $gradeStats,
                'attendanceStats' => $attendanceStats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or error loading data.'
            ], 404);
        }
    }

    /**
     * Get enrollment statistics for dashboard.
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_approved' => Enrollment::where('status', 'approved')->count(),
                'total_enrolled' => Student::whereNotNull('admission_id')->count(),
                'pending_enrollment' => Enrollment::where('status', 'approved')
                    ->whereDoesntHave('students')
                    ->count(),
                'by_grade_level' => Enrollment::where('status', 'approved')
                    ->select('preferred_grade_level', DB::raw('count(*) as count'))
                    ->groupBy('preferred_grade_level')
                    ->orderBy('preferred_grade_level')
                    ->get()
                    ->pluck('count', 'preferred_grade_level'),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display birth certificate file for enrolled student.
     */
    public function birthCertificate($studentId)
    {
        try {
            $student = Student::with('admission')
                ->whereNotNull('admission_id')
                ->findOrFail($studentId);

            if (!$student->admission || !$student->admission->birth_certificate || !Storage::exists($student->admission->birth_certificate)) {
                abort(404, 'Birth certificate not found.');
            }

            return Storage::response($student->admission->birth_certificate);
        } catch (\Exception $e) {
            abort(404, 'Birth certificate not found.');
        }
    }
}