<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of admission applications
     */
    public function index(Request $request)
    {
        try {
            $query = Enrollment::with(['section.school']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Filter by status
            $status = $request->get('status', 'pending');
            if ($status !== 'all') {
                $query->where('status', $status);
            }

            // Filter by grade level
            if ($request->filled('grade_level')) {
                $query->where('grade_level', $request->grade_level);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $enrollments = $query->orderBy('created_at', 'desc')
                                ->paginate(15)
                                ->withQueryString();

            // Get available sections for assignment
            $sections = Section::with('school')
                             ->where('is_active', true)
                             ->orderBy('name')
                             ->get();

            // Get schools for filtering
            $schools = School::where('is_active', true)
                           ->orderBy('name')
                           ->get();

            // Statistics
            $stats = [
                'total' => Enrollment::count(),
                'pending' => Enrollment::where('status', 'pending')->count(),
                'verified' => Enrollment::where('status', 'verified')->count(),
                'approved' => Enrollment::where('status', 'approved')->count(),
                'rejected' => Enrollment::where('status', 'rejected')->count(),
                'today' => Enrollment::whereDate('created_at', today())->count(),
            ];

            return view('admin.admissions.index', compact('enrollments', 'sections', 'schools', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error in enrollment index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading enrollments. Please try again.');
        }
    }

    /**
     * Display the specified admission application
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['section.school', 'student']);

        // Get available sections for assignment
        $sections = Section::with('school')
                         ->where('is_active', true)
                         ->orderBy('name')
                         ->get();

        return view('admin.admissions.show', compact('enrollment', 'sections'));
    }

    /**
     * Verify an admission application
     */
    public function verify(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:1000',
        ]);

        try {
            if ($enrollment->status !== 'pending') {
                return redirect()->back()->with('error', 'Only pending admissions can be verified.');
            }

            $enrollment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verification_notes' => $request->verification_notes,
            ]);

            return redirect()->back()->with('success', 'Admission verified successfully.');
        } catch (\Exception $e) {
            Log::error('Error verifying admission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error verifying admission.');
        }
    }

    /**
     * Assign section to admission application
     */
    public function assignSection(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'assignment_notes' => 'nullable|string|max:1000',
        ]);

        try {
            if (!in_array($enrollment->status, ['verified', 'pending'])) {
                return redirect()->back()->with('error', 'Cannot assign section to this enrollment.');
            }

            $enrollment->update([
                'section_id' => $request->section_id,
                'assignment_notes' => $request->assignment_notes,
                'status' => 'verified', // Ensure status is at least verified
            ]);

            return redirect()->back()->with('success', 'Section assigned successfully.');
        } catch (\Exception $e) {
            Log::error('Error assigning section: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error assigning section.');
        }
    }

    /**
     * Approve admission application and create student record
     */
    public function approve(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        try {
            if ($enrollment->status !== 'verified') {
                return redirect()->back()->with('error', 'Only verified admissions can be approved.');
            }

            if (!$enrollment->section_id) {
                return redirect()->back()->with('error', 'Please assign a section before approving.');
            }

            DB::beginTransaction();

            // Generate unique student ID
            $studentId = $this->generateStudentId();

            // Create student record
            $student = Student::create([
                'student_id' => $studentId,
                'first_name' => $enrollment->first_name,
                'last_name' => $enrollment->last_name,
                'middle_name' => $enrollment->middle_name,
                'email' => $enrollment->email,
                'phone' => $enrollment->phone,
                'address' => $enrollment->address,
                'date_of_birth' => $enrollment->date_of_birth,
                'gender' => $enrollment->gender,
                'section_id' => $enrollment->section_id,
                'guardian_name' => $enrollment->guardian_name,
                'guardian_phone' => $enrollment->guardian_phone,
                'guardian_email' => $enrollment->guardian_email,
                'is_active' => true,
                'admission_id' => $enrollment->id,
            ]);

            // Update enrollment status
            $enrollment->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes,
                'student_id' => $student->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Admission approved and student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving admission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error approving admission.');
        }
    }

    /**
     * Reject admission application
     */
    public function reject(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        try {
            if ($enrollment->status === 'approved') {
                return redirect()->back()->with('error', 'Cannot reject an approved admission.');
            }

            $enrollment->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            return redirect()->back()->with('success', 'Admission rejected.');
        } catch (\Exception $e) {
            Log::error('Error rejecting admission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error rejecting admission.');
        }
    }

    /**
     * Get sections for a specific grade level (AJAX)
     */
    public function getSections(Request $request, Enrollment $enrollment)
    {
        $sections = Section::with('school')
                         ->where('is_active', true)
                         ->where('grade_level', $enrollment->grade_level)
                         ->orderBy('name')
                         ->get(['id', 'name', 'capacity', 'current_students']);

        return response()->json($sections);
    }

    /**
     * Quick assign section and approve admission (AJAX)
     */
    public function quickAssign(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
        ]);

        try {
            if ($enrollment->status !== 'verified') {
                return response()->json(['error' => 'Only verified admissions can be quick assigned.'], 400);
            }

            DB::beginTransaction();

            // Assign section
            $enrollment->update([
                'section_id' => $request->section_id,
            ]);

            // Generate student ID and create student
            $studentId = $this->generateStudentId();
            $student = Student::create([
                'student_id' => $studentId,
                'first_name' => $enrollment->first_name,
                'last_name' => $enrollment->last_name,
                'middle_name' => $enrollment->middle_name,
                'email' => $enrollment->email,
                'phone' => $enrollment->phone,
                'address' => $enrollment->address,
                'date_of_birth' => $enrollment->date_of_birth,
                'gender' => $enrollment->gender,
                'section_id' => $enrollment->section_id,
                'guardian_name' => $enrollment->guardian_name,
                'guardian_phone' => $enrollment->guardian_phone,
                'guardian_email' => $enrollment->guardian_email,
                'is_active' => true,
                'admission_id' => $enrollment->id,
            ]);

            // Approve enrollment
            $enrollment->update([
                'status' => 'approved',
                'approved_at' => now(),
                'student_id' => $student->id,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Admission approved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in quick assign: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing admission.'], 500);
        }
    }

    /**
     * Bulk approve admissions
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'admission_ids' => 'required|array',
            'admission_ids.*' => 'exists:admissions,id',
        ]);

        try {
            $successCount = 0;
            $errorCount = 0;

            foreach ($request->admission_ids as $enrollmentId) {
                try {
                    $enrollment = Enrollment::findOrFail($enrollmentId);
                    
                    if ($enrollment->status === 'verified' && $enrollment->section_id) {
                        DB::beginTransaction();
                        
                        $studentId = $this->generateStudentId();
                        $student = Student::create([
                            'student_id' => $studentId,
                            'first_name' => $enrollment->first_name,
                            'last_name' => $enrollment->last_name,
                            'middle_name' => $enrollment->middle_name,
                            'email' => $enrollment->email,
                            'phone' => $enrollment->phone,
                            'address' => $enrollment->address,
                            'date_of_birth' => $enrollment->date_of_birth,
                            'gender' => $enrollment->gender,
                            'section_id' => $enrollment->section_id,
                            'guardian_name' => $enrollment->guardian_name,
                            'guardian_phone' => $enrollment->guardian_phone,
                            'guardian_email' => $enrollment->guardian_email,
                            'is_active' => true,
                            'admission_id' => $enrollment->id,
                        ]);

                        $enrollment->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'student_id' => $student->id,
                        ]);

                        DB::commit();
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    $errorCount++;
                    Log::error('Error in bulk approve for admission ' . $enrollmentId . ': ' . $e->getMessage());
                }
            }

            $message = "Bulk approval completed. {$successCount} approved";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} failed";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error in bulk approve: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing bulk approval.');
        }
    }

    /**
     * Get admission statistics (AJAX)
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_enrollments' => Enrollment::count(),
                'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
                'verified_enrollments' => Enrollment::where('status', 'verified')->count(),
                'approved_enrollments' => Enrollment::where('status', 'approved')->count(),
                'rejected_enrollments' => Enrollment::where('status', 'rejected')->count(),
                'today_enrollments' => Enrollment::whereDate('created_at', today())->count(),
                'this_week_enrollments' => Enrollment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month_enrollments' => Enrollment::whereMonth('created_at', now()->month)->count(),
                'enrollments_by_grade' => Enrollment::select('grade_level', DB::raw('count(*) as count'))
                    ->groupBy('grade_level')
                    ->get(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Error getting admission statistics: ' . $e->getMessage());
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
}