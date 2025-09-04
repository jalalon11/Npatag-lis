<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardianDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'guardian') {
                abort(403, 'Access denied. Guardian role required.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $guardian = Auth::user();
        
        // Get all students associated with this guardian through admissions
        $students = $guardian->guardianStudents()->with([
            'section',
            'admission.school',
            'grades' => function($query) {
                $query->latest()->limit(5); // Get latest 5 grades
            }
        ])->get();

        return view('guardian.dashboard', compact('students'));
    }

    public function studentDetails($studentId)
    {
        $guardian = Auth::user();
        
        // Ensure the student belongs to this guardian
        $student = $guardian->guardianStudents()
            ->with([
                'section',
                'admission.school',
                'grades.subject',
                'attendances' => function($query) {
                    $query->latest()->limit(30); // Get latest 30 attendance records
                }
            ])
            ->findOrFail($studentId);

        return view('guardian.student-details', compact('student'));
    }
}