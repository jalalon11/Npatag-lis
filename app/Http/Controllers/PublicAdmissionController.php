<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Section;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PublicAdmissionController extends Controller
{
    /**
     * Show the public admission application form.
     */
    public function apply()
    {
        $schools = School::all();
        return view('public.admission.apply', compact('schools'));
    }

    /**
     * Submit the admission application.
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female',
            'lrn' => 'nullable|string|max:255|unique:admissions,lrn|unique:students,lrn',
            'address' => 'required|string|max:1000',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:50',
            'guardian_email' => 'nullable|email|max:255',
            'school_id' => 'required|exists:schools,id',
            'preferred_grade_level' => 'required|string|max:50',
            'preferred_section_id' => 'nullable|exists:sections,id',
            'school_year' => 'required|string|max:20',
            'birth_certificate' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'medical_conditions' => 'nullable|string|max:1000',
            'medications' => 'nullable|string|max:1000',
            'special_needs' => 'nullable|string|max:1000',
            'previous_school' => 'nullable|string|max:255',
            'previous_grade_level' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:50',
            'emergency_contact_relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate student ID
            $studentId = $this->generateStudentId();

            // Handle birth certificate upload
            $birthCertificatePath = null;
            if ($request->hasFile('birth_certificate')) {
                $file = $request->file('birth_certificate');
                $fileName = $studentId . '_birth_certificate_' . time() . '.' . $file->getClientOriginalExtension();
                $birthCertificatePath = $file->storeAs('birth_certificates', $fileName);
            }

            // Create the admission record
            $admission = Enrollment::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'student_id' => $studentId,
                'lrn' => $request->lrn,
                'address' => $request->address,
                'guardian_name' => $request->guardian_name,
                'guardian_contact' => $request->guardian_contact,
                'guardian_email' => $request->guardian_email,
                'school_id' => $request->school_id,
                'preferred_grade_level' => $request->preferred_grade_level,
                'preferred_section_id' => $request->preferred_section_id,
                'school_year' => $request->school_year,
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

            DB::commit();

            return redirect()->route('admission.submitted', $admission->id)
                ->with('success', 'Your admission application has been submitted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error submitting application: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the submission confirmation page.
     */
    public function submitted(Enrollment $admission)
    {
        return view('public.admission.submitted', compact('admission'));
    }

    /**
     * Show the status check form.
     */
    public function statusForm()
    {
        return view('public.admission.status');
    }

    /**
     * Check admission status.
     */
    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'last_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $admission = Enrollment::where('student_id', $request->student_id)
            ->where('last_name', 'like', '%' . $request->last_name . '%')
            ->with(['school', 'preferredSection', 'assignedSection'])
            ->first();

        if (!$admission) {
            return redirect()->back()
                ->with('error', 'No admission record found with the provided information.')
                ->withInput();
        }

        return view('public.admission.status', compact('admission'));
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
     * Generate a unique student ID.
     */
    private function generateStudentId()
    {
        do {
            $year = date('Y');
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $studentId = $year . $random;
        } while (Enrollment::where('student_id', $studentId)->exists() || 
                 \App\Models\Student::where('student_id', $studentId)->exists());

        return $studentId;
    }
}