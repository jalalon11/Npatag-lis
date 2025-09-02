<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
// Removed SchoolDivisionController and SchoolController - moved to hardcoded school system
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherAdminController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\AccountsController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SchoolController as AdminSchoolController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\HelpController;
// RegistrationKeyController import removed - account creation is now admin-only

use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\CertificateController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Teacher\ReportController;
use App\Http\Controllers\Teacher\StudentController;
use App\Http\Controllers\Teacher\GradeConfigurationController;
use App\Http\Controllers\Teacher\GradeApprovalController;
use App\Http\Controllers\Teacher\ResourceController as TeacherResourceController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ResourceCategoryController;

use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\SupportController as AdminSupportController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\RoleSwitchController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Section;
use App\Models\Student;
use App\Http\Middleware\CheckSchoolStatus;

Route::get('/', function () {
    $school = \App\Models\School::single();
    return view('welcome', compact('school'));
});

// Announcement routes for public access
Route::get('/api/announcements', [\App\Http\Controllers\AnnouncementViewController::class, 'getActiveAnnouncements'])->name('api.announcements');

// Public enrollment application routes
Route::prefix('enrollment')->name('enrollment.')->group(function () {
    // New enrollment form that submits to teacher admin panel
    Route::get('/apply', [\App\Http\Controllers\EnrollmentApplicationController::class, 'apply'])->name('apply');
    Route::post('/submit', [\App\Http\Controllers\EnrollmentApplicationController::class, 'submit'])->name('submit');
    Route::get('/submitted/{enrollment}', [\App\Http\Controllers\EnrollmentApplicationController::class, 'submitted'])->name('submitted');
    
    // Legacy routes (keeping for backward compatibility)
    Route::get('/create', [\App\Http\Controllers\EnrollmentApplicationController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\EnrollmentApplicationController::class, 'store'])->name('store');
    Route::get('/success/{enrollment}', [\App\Http\Controllers\EnrollmentApplicationController::class, 'success'])->name('success');
    
    // Status checking routes
    Route::get('/status', [\App\Http\Controllers\EnrollmentApplicationController::class, 'statusForm'])->name('status.form');
    Route::post('/status', [\App\Http\Controllers\EnrollmentApplicationController::class, 'status'])->name('status');
    
    // AJAX route for school sections
    Route::get('/schools/{school}/sections', [\App\Http\Controllers\EnrollmentApplicationController::class, 'getSectionsBySchool'])->name('schools.sections');
});

// Maintenance mode routes - these must be accessible during maintenance
Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
Route::get('/maintenance/auth', [MaintenanceController::class, 'authenticatedIndex'])->name('maintenance.auth');

// Debug route to check maintenance mode status (admin only)
Route::get('/maintenance/status', [MaintenanceController::class, 'checkStatus'])
    ->middleware(['auth', 'check.role:admin'])
    ->name('maintenance.status');

// AJAX route to check maintenance status for authenticated users
Route::get('/maintenance/check-status-ajax', [MaintenanceController::class, 'checkStatusAjax'])
    ->middleware(['auth'])
    ->name('maintenance.check-status-ajax');

// AJAX route to get maintenance progress information
Route::get('/maintenance/progress', [MaintenanceController::class, 'getMaintenanceProgress'])
    ->name('maintenance.progress');

// Test route to check if maintenance middleware is working
Route::get('/test-maintenance', function() {
    return 'If you see this, maintenance mode is NOT working correctly!';
})->middleware([\App\Http\Middleware\MaintenanceModeMiddleware::class])->name('test.maintenance');

// Image proxy route
Route::get('/image-proxy/{path}', [\App\Http\Controllers\ImageProxyController::class, 'proxyImage'])
    ->where('path', '.*')
    ->name('image.proxy');

// Custom auth routes instead of Auth::routes()
// Login routes
Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Register routes - now admin-only account creation
Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])
    ->middleware('auth', 'check.role:admin')
    ->name('register');
Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])
    ->middleware('auth', 'check.role:admin');

// Password reset routes
Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth', CheckSchoolStatus::class])->group(function () {
    Route::get('/home', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } else {
            return redirect()->route('login');
        }
    })->name('home');

    // Role switching routes (admin only)
    Route::post('/role-switch/toggle', [RoleSwitchController::class, 'toggle'])
        ->middleware(['auth', 'check.role:admin'])
        ->name('role.switch.toggle');
    Route::get('/role-switch/status', [RoleSwitchController::class, 'status'])
        ->middleware(['auth', 'check.role:admin'])
        ->name('role.switch.status');

    // Maintenance mode toggle route (admin only)
    Route::post('/maintenance/toggle', [MaintenanceController::class, 'toggleMaintenanceMode'])
        ->middleware(['auth', 'check.role:admin'])
        ->name('maintenance.toggle');

    // Debug route to check role switch status and provide solution
    Route::get('/check-teacher-mode', function() {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Not logged in',
                'solution' => 'Please log in first at /login'
            ]);
        }
        
        $user = Auth::user();
        $isAdminActingAsTeacher = \App\Services\RoleSwitchService::isAdminActingAsTeacher();
        $currentMode = \App\Services\RoleSwitchService::getCurrentMode();
        
        return response()->json([
            'user_email' => $user->email,
            'user_role' => $user->role,
            'current_mode' => $currentMode,
            'is_admin_acting_as_teacher' => $isAdminActingAsTeacher,
            'can_access_admin_pages' => $user->role === 'admin' && !$isAdminActingAsTeacher,
            'solution' => $isAdminActingAsTeacher ? 
                'You are in Teacher Mode. Look for the role switch toggle in your navigation bar (Admin/Teacher toggle) and switch back to Admin Mode to access admin pages.' :
                'You should be able to access admin pages. If not, try logging out and logging back in.'
        ]);
    });
    
    // Route to switch back to admin mode
    Route::post('/switch-to-admin', function() {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        \App\Services\RoleSwitchService::switchToAdminMode();
        return response()->json([
            'success' => true,
            'message' => 'Switched back to Admin Mode',
            'redirect_url' => route('admin.dashboard')
        ]);
    });

    // Admin Routes - explicitly excluded from maintenance mode
    Route::prefix('admin')->middleware(['auth', 'check.role:admin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/update-quarter', [AdminDashboardController::class, 'updateQuarter'])->name('update-quarter');
        
        // Academic Management Routes
        Route::get('/academics', [\App\Http\Controllers\Admin\AcademicController::class, 'index'])->name('academics.index');
        Route::post('/academics/update-quarter', [\App\Http\Controllers\Admin\AcademicController::class, 'updateQuarter'])->name('academics.update-quarter');
        Route::post('/academics/update-school-year', [\App\Http\Controllers\Admin\AcademicController::class, 'updateSchoolYear'])->name('academics.update-school-year');
        Route::post('/academics/update-principal', [\App\Http\Controllers\Admin\AcademicController::class, 'updatePrincipal'])->name('academics.update-principal');
        Route::post('/academics/update-school-details', [\App\Http\Controllers\Admin\AcademicController::class, 'updateSchoolDetails'])->name('academics.update-school-details');
        Route::get('/profile', function() {
            return view('admin.profile');
        })->name('profile');
        Route::put('/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AdminDashboardController::class, 'updatePassword'])->name('password.update');
        // School management routes removed - moved to hardcoded school system




        // Accounts management routes
        Route::resource('accounts', AccountsController::class);
        Route::post('accounts/{account}/reset-password', [AccountsController::class, 'resetPassword'])->name('accounts.reset-password');
        Route::post('accounts/{account}/promote-to-admin', [AccountsController::class, 'promoteToAdmin'])->name('accounts.promote-to-admin');
        Route::post('accounts/{account}/demote-to-teacher', [AccountsController::class, 'demoteToTeacher'])->name('accounts.demote-to-teacher');
        
        // Legacy routes for backward compatibility
        Route::resource('teachers', TeacherController::class);
        Route::post('teachers/{teacher}/reset-password', [TeacherController::class, 'resetPassword'])->name('teachers.reset-password');

        // Support Routes
        Route::get('support', [AdminSupportController::class, 'index'])->name('support.index');
        Route::get('support/{id}', [AdminSupportController::class, 'show'])->name('support.show');
        Route::post('support/{id}/reply', [AdminSupportController::class, 'reply'])->name('support.reply');
        Route::post('support/{id}/close', [AdminSupportController::class, 'close'])->name('support.close');
        Route::post('support/{id}/reopen', [AdminSupportController::class, 'reopen'])->name('support.reopen');

        // API Routes
        // School-specific teacher API removed - using hardcoded school system

        // Support API Routes
        Route::post('/api/support/messages/{message}/read', [AdminSupportController::class, 'markAsRead']);
        Route::get('/api/support/tickets/{id}/messages', [AdminSupportController::class, 'getMessages']);

        // Support API Routes with admin prefix for JavaScript
        Route::post('/admin/api/support/messages/{message}/read', [AdminSupportController::class, 'markAsRead']);
        Route::get('/admin/api/support/tickets/{id}/messages', [AdminSupportController::class, 'getMessages']);

        // Registration key management removed - now admin-only account creation

        // Announcement routes
        Route::resource('announcements', AnnouncementController::class);
        Route::patch('announcements/{announcement}/toggle-status', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle-status');

        // Resource Materials Management
        Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
        Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
        Route::put('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
        Route::post('/resources/{resource}/toggle-status', [ResourceController::class, 'toggleStatus'])->name('resources.toggle-status');

        // Resource Categories Management
        Route::post('/resource-categories', [ResourceCategoryController::class, 'store'])->name('resource-categories.store');
        Route::put('/resource-categories/{category}', [ResourceCategoryController::class, 'update'])->name('resource-categories.update');
        Route::delete('/resource-categories/{category}', [ResourceCategoryController::class, 'destroy'])->name('resource-categories.destroy');
        Route::post('/resource-categories/{category}/toggle-status', [ResourceCategoryController::class, 'toggleStatus'])->name('resource-categories.toggle-status');

        // Subjects Management (formerly teacher admin)
        Route::resource('subjects', AdminSubjectController::class);
        Route::post('subjects/{subject}/assign-teachers', [AdminSubjectController::class, 'assignTeachers'])
            ->name('subjects.assign-teachers');
        Route::patch('subjects/{subject}/toggle-status', [AdminSubjectController::class, 'toggleStatus'])
            ->name('subjects.toggle-status');

        // Rooms Management (formerly sections)
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);
        Route::get('rooms/{room}/students', [\App\Http\Controllers\Admin\RoomController::class, 'students'])
            ->name('rooms.students');
        Route::get('rooms/{room}/subjects', [\App\Http\Controllers\Admin\RoomController::class, 'subjects'])
            ->name('rooms.subjects');
        Route::post('rooms/{room}/assign-subjects', [\App\Http\Controllers\Admin\RoomController::class, 'assignSubjects'])
            ->name('rooms.assign-subjects');
        Route::post('rooms/{room}/subjects/assign', [\App\Http\Controllers\Admin\RoomController::class, 'assignSubject'])
            ->name('rooms.subjects.assign');
        Route::delete('rooms/{room}/subjects/{subject}/unassign', [\App\Http\Controllers\Admin\RoomController::class, 'unassignSubject'])
            ->name('rooms.subjects.unassign');
        Route::patch('rooms/{room}/toggle-status', [\App\Http\Controllers\Admin\RoomController::class, 'toggleStatus'])
            ->name('rooms.toggle-status');
        Route::patch('rooms/{room}/update-adviser', [\App\Http\Controllers\Admin\RoomController::class, 'updateAdviser'])
            ->name('rooms.update-adviser');

        // Homeroom Advising Management
        Route::get('/homeroom', [\App\Http\Controllers\Admin\HomeroomController::class, 'index'])
            ->name('homeroom.index');
        Route::get('/homeroom/{room}/assign', [\App\Http\Controllers\Admin\HomeroomController::class, 'assign'])
            ->name('homeroom.assign');
        Route::patch('/homeroom/{room}/update-adviser', [\App\Http\Controllers\Admin\HomeroomController::class, 'updateAdviser'])
            ->name('homeroom.update-adviser');
        Route::post('/homeroom/bulk-assign', [\App\Http\Controllers\Admin\HomeroomController::class, 'bulkAssign'])
            ->name('homeroom.bulk-assign');

        // Buildings Management
        Route::resource('buildings', \App\Http\Controllers\Admin\BuildingController::class);
        Route::post('buildings/{building}/assign-room', [\App\Http\Controllers\Admin\BuildingController::class, 'assignRoom'])
            ->name('buildings.assign-room');
        Route::delete('buildings/{building}/unassign-room', [\App\Http\Controllers\Admin\BuildingController::class, 'unassignRoom'])
            ->name('buildings.unassign-room');

        // Legacy Sections Management (kept for backward compatibility)
        Route::resource('sections', AdminSectionController::class);
        Route::post('sections/{section}/assign-subjects', [AdminSectionController::class, 'assignSubjects'])
            ->name('sections.assign-subjects');
        Route::patch('sections/{section}/toggle-status', [AdminSectionController::class, 'toggleStatus'])
            ->name('sections.toggle-status');
        Route::patch('sections/{section}/update-adviser', [AdminSectionController::class, 'updateAdviser'])
            ->name('sections.update-adviser');

        // Reports Management (formerly teacher admin)
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/consolidated-grades', [AdminReportController::class, 'consolidatedGrades'])->name('reports.consolidated-grades');
        Route::post('/reports/generate-consolidated-grades', [AdminReportController::class, 'generateConsolidatedGrades'])->name('reports.generate-consolidated-grades');
        Route::get('/reports/generate-consolidated-grades', [AdminReportController::class, 'generateConsolidatedGrades'])->name('reports.generate-consolidated-grades-get');
        Route::get('/reports/attendance-summary', [AdminReportController::class, 'attendanceSummary'])->name('reports.attendance-summary');
        Route::post('/reports/generate-attendance-summary', [AdminReportController::class, 'generateAttendanceSummary'])->name('reports.generate-attendance-summary');
        Route::get('/reports/generate-attendance-summary', [AdminReportController::class, 'generateAttendanceSummary'])->name('reports.generate-attendance-summary-get');

        // School Overview (formerly teacher admin)
        Route::get('/school', [AdminSchoolController::class, 'index'])->name('school.index');

        // Student Management Routes
        Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
        Route::get('/students/by-section/{section}', [\App\Http\Controllers\Admin\StudentController::class, 'getBySection'])->name('students.by-section');
        Route::get('/students/statistics', [\App\Http\Controllers\Admin\StudentController::class, 'statistics'])->name('students.statistics');
        Route::post('/students/{student}/toggle-status', [\App\Http\Controllers\Admin\StudentController::class, 'toggleStatus'])->name('students.toggle-status');

        // Enrollment Management Routes
        Route::get('/admissions', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('admissions.index');
        Route::get('/admissions/{enrollment}', [\App\Http\Controllers\Admin\EnrollmentController::class, 'show'])->name('admissions.show');
        Route::post('/admissions/{enrollment}/verify', [\App\Http\Controllers\Admin\EnrollmentController::class, 'verify'])->name('admissions.verify');
        Route::post('/admissions/{enrollment}/approve', [\App\Http\Controllers\Admin\EnrollmentController::class, 'approve'])->name('admissions.approve');
        Route::post('/admissions/{enrollment}/reject', [\App\Http\Controllers\Admin\EnrollmentController::class, 'reject'])->name('admissions.reject');
        Route::post('/admissions/{enrollment}/assign-section', [\App\Http\Controllers\Admin\EnrollmentController::class, 'assignSection'])->name('admissions.assign-section');
        Route::get('/admissions/sections/{school}', [\App\Http\Controllers\Admin\EnrollmentController::class, 'getSections'])->name('admissions.sections');
        Route::post('/admissions/quick-assign', [\App\Http\Controllers\Admin\EnrollmentController::class, 'quickAssign'])->name('admissions.quick-assign');
        Route::post('/admissions/bulk-approve', [\App\Http\Controllers\Admin\EnrollmentController::class, 'bulkApprove'])->name('admissions.bulk-approve');
        Route::get('/admissions/statistics', [\App\Http\Controllers\Admin\EnrollmentController::class, 'statistics'])->name('admissions.statistics');

        // Help Routes (formerly teacher admin)
        Route::get('/help', [HelpController::class, 'index'])->name('help.index');
        Route::get('/help/tutorials/{topic}', [HelpController::class, 'tutorial'])->name('help.tutorial');
    });

    // Teacher Routes
    Route::prefix('teacher')->middleware(['auth', 'check.role:teacher', \App\Http\Middleware\MaintenanceModeMiddleware::class])->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/attendance-data', [TeacherDashboardController::class, 'getAttendanceData'])->name('dashboard.attendance-data');
        Route::get('/dashboard/performance-data', [TeacherDashboardController::class, 'getPerformanceData'])->name('dashboard.performance-data');
        Route::get('/profile', function() {
            return view('teacher.profile');
        })->name('profile');
        Route::put('/profile/update', [TeacherDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [TeacherDashboardController::class, 'updatePassword'])->name('password.update');

        // Gender distribution endpoint for AJAX
        Route::get('/students/gender-distribution', [StudentController::class, 'genderDistribution'])->name('students.gender-distribution');

        // Student reactivation route
        Route::post('/students/{student}/reactivate', [StudentController::class, 'reactivate'])->name('students.reactivate');

        // Regular teacher functionality - all teachers including teacher admins
        Route::resource('students', StudentController::class);
        Route::get('grades/assessment-setup', [GradeController::class, 'assessmentSetup'])->name('grades.assessment-setup');
        Route::post('grades/store-assessment-setup', [GradeController::class, 'storeAssessmentSetup'])->name('grades.store-assessment-setup');
        Route::get('grades/batch-create', [GradeController::class, 'batchCreate'])->name('grades.batch-create');
        Route::post('grades/batch-store', [GradeController::class, 'batchStore'])->name('grades.batch-store');

        // New Configure Grades routes
        Route::get('grades/configure', [GradeController::class, 'showConfigureForm'])->name('grades.configure');
        Route::post('grades/configure', [GradeController::class, 'configureGrades'])->name('grades.store-configure');

        // Grades routes
        Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
        Route::get('grades/create', [GradeController::class, 'create'])->name('grades.create');
        Route::post('grades', [GradeController::class, 'store'])->name('grades.store');
        Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
        Route::put('grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
        Route::delete('grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
        Route::get('grades/{grade}', [GradeController::class, 'show'])->name('grades.show');
        Route::post('grades/lock-transmutation-table', [GradeController::class, 'lockTransmutationTable'])->name('grades.lock-transmutation');
        Route::post('grades/update-transmutation-preference', [GradeController::class, 'updateTransmutationPreference'])->name('grades.update-transmutation-preference');

        // Reports Routes
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/class-record', [ReportController::class, 'classRecord'])->name('reports.class-record');
        Route::post('reports/generate-class-record', [ReportController::class, 'generateClassRecord'])->name('reports.generate-class-record');
        Route::get('reports/generate-class-record', [ReportController::class, 'generateClassRecord'])->name('reports.generate-class-record-get');
        Route::get('reports/section-subjects', [ReportController::class, 'getSectionSubjects'])->name('reports.section-subjects');
        Route::post('reports/students-by-grade-ranges', [ReportController::class, 'getStudentsByGradeRanges'])->name('reports.students-by-grade-ranges');

        // Grade Slip Routes
        Route::get('reports/grade-slips', [ReportController::class, 'gradeSlips'])->name('reports.grade-slips');
        Route::post('reports/generate-grade-slips', [ReportController::class, 'generateGradeSlips'])->name('reports.generate-grade-slips');
        Route::get('reports/generate-grade-slips', [ReportController::class, 'generateGradeSlips'])->name('reports.generate-grade-slips-get');
        Route::get('reports/grade-slip-preview', [ReportController::class, 'previewGradeSlip'])->name('reports.grade-slip-preview');
        Route::get('reports/preview-grade-slip', [ReportController::class, 'previewGradeSlip'])->name('reports.preview-grade-slip');

        // Certificates Routes (now under reports)
        Route::get('reports/certificates', [CertificateController::class, 'index'])->name('reports.certificates.index');
        Route::get('reports/certificates/generate', [CertificateController::class, 'generate'])->name('reports.certificates.generate');
        Route::get('reports/certificates/preview', [CertificateController::class, 'preview'])->name('reports.certificates.preview');
        Route::get('reports/certificates/bulk-preview', [CertificateController::class, 'generateBulk'])->name('reports.certificates.bulk-preview');

        // Legacy routes for backward compatibility
        Route::get('certificates', function() {
            return redirect()->route('teacher.reports.certificates.index');
        })->name('certificates.index');
        Route::get('certificates/generate', function() {
            return redirect()->route('teacher.reports.certificates.generate');
        })->name('certificates.generate');
        Route::get('certificates/preview', function() {
            return redirect()->route('teacher.reports.certificates.preview');
        })->name('certificates.preview');

        Route::resource('attendances', AttendanceController::class);
        Route::get('attendance/weekly-summary', [AttendanceController::class, 'weeklySummary'])->name('attendances.weekly-summary');
        Route::get('attendance/monthly-summary', [AttendanceController::class, 'monthlySummary'])->name('attendances.monthly-summary');
        Route::post('attendance/check-exists', [AttendanceController::class, 'checkAttendanceExists'])->name('attendances.check-exists');

        // API endpoint to get students by section ID
        Route::get('/sections/{section}/students', function($section) {
            $section = Section::where('id', $section)
                ->where('adviser_id', Auth::id())
                ->where('is_active', true)
                ->firstOrFail();

            $students = Student::where('section_id', $section->id)
                ->where('is_active', true) // Only include active students
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();

            return response()->json(['students' => $students]);
        });

        // Grade Configuration routes
        Route::get('grade-configurations/{subject}', [GradeConfigurationController::class, 'edit'])->name('grade-configurations.edit');
        Route::put('grade-configurations/{subject}', [GradeConfigurationController::class, 'update'])->name('grade-configurations.update');

        // Legacy teacher admin redirect routes removed - functionality merged into admin panel

        // Add bulk update route
        Route::post('/grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulk-update');
        // Add edit assessment routes
        Route::post('/reports/edit-assessment', [GradeController::class, 'editAssessment'])->name('reports.edit-assessment');
        Route::get('/reports/edit-assessment', [GradeController::class, 'editAssessment'])->name('reports.edit-assessment-get');
        Route::post('/grades/update-assessment', [GradeController::class, 'updateAssessment'])->name('grades.update-assessment');

        // Grade Approval Routes
        Route::get('/grade-approvals', [GradeApprovalController::class, 'index'])->name('grade-approvals.index');
        Route::post('/grade-approvals/update', [GradeApprovalController::class, 'update'])->name('grade-approvals.update');

        // Learning Resource Materials
        Route::get('/resources', [TeacherResourceController::class, 'index'])->name('resources.index');

        // Help Routes
        Route::get('/help', [\App\Http\Controllers\Teacher\HelpController::class, 'index'])->name('help.index');
        Route::get('/help/tutorials/{topic}', [\App\Http\Controllers\Teacher\HelpController::class, 'tutorial'])->name('help.tutorial');
    });

    // Teacher Admin Routes - REMOVED: Functionality merged into admin panel


});
