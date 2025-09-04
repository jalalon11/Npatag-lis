@extends('layouts.app')

@section('title', 'Enrolled Students')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-graduate text-primary me-2"></i>Enrolled Students
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.learners.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Learners Record
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Enrolled
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_enrolled'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Schools
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['by_school']->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Grade Levels
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['by_grade']->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Active Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_enrolled'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Enrolled Students</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.learners.enrolled') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="school_id" class="form-label">School</label>
                    <select class="form-select" id="school_id" name="school_id">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="grade_level" class="form-label">Grade Level</label>
                    <select class="form-select" id="grade_level" name="grade_level">
                        <option value="">All Grades</option>
                        @foreach($gradeLevels as $grade)
                            <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                Grade {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Name, Student ID, LRN...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enrolled Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Enrolled Students 
                <span class="badge bg-primary ms-2">{{ $enrolledStudents->total() }} Total</span>
            </h6>
        </div>
        <div class="card-body">
            @if($enrolledStudents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student Info</th>
                                <th>Guardian</th>
                                <th>School & Section</th>
                                <th>Enrollment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrolledStudents as $student)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</div>
                                        <small class="text-muted">
                                            ID: {{ $student->student_id }} | LRN: {{ $student->lrn }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            {{ ucfirst($student->gender) }} | {{ $student->birth_date->format('M d, Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $student->guardian_name }}</div>
                                        <small class="text-muted">{{ $student->guardian_contact }}</small>
                                        <br>
                                        <small class="text-muted">{{ $student->guardian_email }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $student->section->school->name ?? 'N/A' }}</div>
                                        <small class="text-success">
                                            <i class="fas fa-users"></i> {{ $student->section->name ?? 'N/A' }} 
                                            (Grade {{ $student->section->grade_level ?? 'N/A' }})
                                        </small>
                                        @if($student->section->adviser)
                                            <br><small class="text-muted">
                                                <i class="fas fa-user-tie"></i> {{ $student->section->adviser->name }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $student->created_at->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $student->created_at->format('h:i A') }}</small>
                                        <br>
                                        <small class="text-muted">SY {{ $student->school_year }}</small>
                                    </td>
                                    <td>
                                        @if($student->is_active)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-pause-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewStudent({{ $student->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="viewGrades({{ $student->id }})" title="View Grades">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="viewAttendance({{ $student->id }})" title="View Attendance">
                                                <i class="fas fa-calendar-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $enrolledStudents->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No Enrolled Students Found</h5>
                    <p class="text-muted">No enrolled students match your current filters.</p>
                    <a href="{{ route('admin.learners.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Learners Record
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics by Grade Level -->
    @if($stats['by_grade']->count() > 0)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Students by Grade Level</h6>
                </div>
                <div class="card-body">
                    @foreach($stats['by_grade'] as $grade)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Grade {{ $grade->grade_level }}</span>
                            <span class="badge bg-primary">{{ $grade->count }} students</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Students by School</h6>
                </div>
                <div class="card-body">
                    @foreach($stats['by_school'] as $school)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $school->name }}</span>
                            <span class="badge bg-success">{{ $school->count }} students</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

<!-- Student Details Modal -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentDetailsModalLabel">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="studentDetailsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewStudent(studentId) {
    console.log('viewStudent called with ID:', studentId);
    
    // Show modal and load student details
    const modal = new bootstrap.Modal(document.getElementById('studentDetailsModal'));
    modal.show();
    
    // Reset modal content to loading state
    document.getElementById('studentDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    // Check if CSRF token exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        document.getElementById('studentDetailsContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> CSRF token not found. Please refresh the page.
            </div>
        `;
        return;
    }
    
    const url = `/admin/learners/enrolled/${studentId}`;
    console.log('Fetching from URL:', url);
    
    // Fetch student details
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response body:', text);
                    throw new Error(`HTTP error! status: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                displayStudentDetails(data.student, data.gradeStats, data.attendanceStats);
            } else {
                document.getElementById('studentDetailsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('studentDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error loading student details: ${error.message}
                </div>
            `;
        });
}

function displayStudentDetails(student, gradeStats, attendanceStats) {
    const birthDate = new Date(student.birth_date).toLocaleDateString();
    const enrollmentDate = student.admission ? new Date(student.admission.created_at).toLocaleDateString() : 'N/A';
    
    const content = `
        <div class="row">
            <!-- Personal Information -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-user"></i> Personal Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-bold">Full Name:</td>
                                <td>${student.first_name} ${student.middle_name} ${student.last_name}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Student ID:</td>
                                <td>${student.student_id || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">LRN:</td>
                                <td>${student.lrn || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Birth Date:</td>
                                <td>${birthDate}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Gender:</td>
                                <td>${student.gender}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Address:</td>
                                <td>${student.address || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">School Year:</td>
                                <td>${student.school_year}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status:</td>
                                <td>
                                    <span class="badge ${student.is_active ? 'bg-success' : 'bg-secondary'}">
                                        ${student.is_active ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Guardian Information -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Guardian Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-bold">Guardian Name:</td>
                                <td>${student.guardian_name || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Contact Number:</td>
                                <td>${student.guardian_contact || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>${student.guardian_email || 'N/A'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Birth Certificate Section -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-file-alt"></i> Birth Certificate</h6>
                    </div>
                    <div class="card-body">
                        ${student.admission && student.admission.birth_certificate ? 
                            `<a href="/admin/learners/enrolled/${student.id}/birth-certificate" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> View Birth Certificate
                            </a>` : 
                            '<span class="text-muted">Birth certificate not available</span>'
                        }
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <!-- School Information -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-school"></i> School Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-bold">Section:</td>
                                <td>${student.section ? student.section.name : 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Grade Level:</td>
                                <td>${student.section ? student.section.grade_level : 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">School:</td>
                                <td>${student.section && student.section.school ? student.section.school.name : 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Adviser:</td>
                                <td>${student.section && student.section.adviser ? student.section.adviser.name : 'N/A'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Enrollment Date:</td>
                                <td>${enrollmentDate}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Academic Statistics -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-line"></i> Academic Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h6>Grades</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-bold">Subjects:</td>
                                        <td>${gradeStats.total_subjects}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Average:</td>
                                        <td>${gradeStats.average_grade ? parseFloat(gradeStats.average_grade).toFixed(2) : 'N/A'}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Highest:</td>
                                        <td>${gradeStats.highest_grade ? parseFloat(gradeStats.highest_grade).toFixed(2) : 'N/A'}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Lowest:</td>
                                        <td>${gradeStats.lowest_grade ? parseFloat(gradeStats.lowest_grade).toFixed(2) : 'N/A'}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6">
                                <h6>Attendance</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-bold">Total Days:</td>
                                        <td>${attendanceStats.total_days}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Present:</td>
                                        <td>${attendanceStats.present_days}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Absent:</td>
                                        <td>${attendanceStats.absent_days}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Rate:</td>
                                        <td>
                                            <span class="badge ${attendanceStats.attendance_rate >= 90 ? 'bg-success' : attendanceStats.attendance_rate >= 75 ? 'bg-warning' : 'bg-danger'}">
                                                ${attendanceStats.attendance_rate}%
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('studentDetailsContent').innerHTML = content;
}

function viewGrades(studentId) {
    // Implement view grades functionality
    alert('View grades functionality - Student ID: ' + studentId);
}

function viewAttendance(studentId) {
    // Implement view attendance functionality
    alert('View attendance functionality - Student ID: ' + studentId);
}
</script>
@endpush