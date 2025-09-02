@extends('layouts.app')

@section('title', 'Student Details')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2"></i>{{ $student->full_name }}
            </h1>
            <p class="text-muted mb-0">Student ID: {{ $student->student_id }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Student
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-id-card me-2"></i>Personal Information
                    </h6>
                    <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'warning' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Full Name:</strong><br>
                            <span class="h5">{{ $student->full_name }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Student ID:</strong><br>
                            <span class="h6 text-muted">{{ $student->student_id }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Date of Birth:</strong><br>
                            <span>{{ $student->date_of_birth?->format('F d, Y') ?? 'Not provided' }}</span>
                            @if($student->date_of_birth)
                                <small class="text-muted d-block">
                                    Age: {{ $student->date_of_birth->age }} years old
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Gender:</strong><br>
                            <span>{{ $student->gender ? ucfirst($student->gender) : 'Not specified' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Email:</strong><br>
                            <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                <i class="fas fa-envelope me-1"></i>{{ $student->email }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Phone:</strong><br>
                            @if($student->phone)
                                <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i>{{ $student->phone }}
                                </a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                        @if($student->address)
                            <div class="col-12 mb-3">
                                <strong class="text-primary">Address:</strong><br>
                                <span>{{ $student->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-graduation-cap me-2"></i>Academic Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="text-success">Section:</strong><br>
                            @if($student->section)
                                <span class="h6">{{ $student->section->name }}</span><br>
                                <small class="text-muted">{{ $student->section->school->name }}</small>
                            @else
                                <span class="text-warning">Not assigned to any section</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-success">Enrolled Date:</strong><br>
                            <span>{{ $student->enrolled_at?->format('F d, Y') ?? 'Not enrolled' }}</span>
                            @if($student->enrolled_at)
                                <small class="text-muted d-block">
                                    {{ $student->enrolled_at->diffForHumans() }}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-success">Attendance Rate:</strong><br>
                            @php
                                $attendanceRate = 85; // This would come from actual calculation
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ $attendanceRate >= 80 ? 'success' : ($attendanceRate >= 60 ? 'warning' : 'danger') }}" 
                                     role="progressbar" style="width: {{ $attendanceRate }}%">
                                    {{ $attendanceRate }}%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-success">Academic Status:</strong><br>
                            <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'warning' }} p-2">
                                {{ $student->status === 'active' ? 'Active Student' : 'Inactive Student' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            @if($student->guardian_name || $student->guardian_phone || $student->guardian_email)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-users me-2"></i>Guardian Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($student->guardian_name)
                            <div class="col-md-6 mb-3">
                                <strong class="text-info">Guardian Name:</strong><br>
                                <span>{{ $student->guardian_name }}</span>
                            </div>
                        @endif
                        @if($student->guardian_phone)
                            <div class="col-md-6 mb-3">
                                <strong class="text-info">Guardian Phone:</strong><br>
                                <a href="tel:{{ $student->guardian_phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i>{{ $student->guardian_phone }}
                                </a>
                            </div>
                        @endif
                        @if($student->guardian_email)
                            <div class="col-md-6 mb-3">
                                <strong class="text-info">Guardian Email:</strong><br>
                                <a href="mailto:{{ $student->guardian_email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i>{{ $student->guardian_email }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-history me-2"></i>Recent Activity
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Student Enrolled</h6>
                                <p class="text-muted mb-0">{{ $student->enrolled_at?->format('F d, Y g:i A') ?? 'Date not available' }}</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Profile Created</h6>
                                <p class="text-muted mb-0">{{ $student->created_at->format('F d, Y g:i A') }}</p>
                            </div>
                        </div>
                        @if($student->updated_at != $student->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Updated</h6>
                                <p class="text-muted mb-0">{{ $student->updated_at->format('F d, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Edit Student
                        </a>
                        <form action="{{ route('admin.students.toggle-status', $student) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $student->status === 'active' ? 'warning' : 'success' }} btn-sm w-100">
                                <i class="fas fa-{{ $student->status === 'active' ? 'pause' : 'play' }} me-2"></i>
                                {{ $student->status === 'active' ? 'Deactivate' : 'Activate' }} Student
                            </button>
                        </form>
                        <hr>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-chart-line me-2"></i>View Grades
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-calendar-check me-2"></i>View Attendance
                        </a>
                        <a href="#" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-file-alt me-2"></i>Generate Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Student Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-chart-bar me-2"></i>Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">85%</h4>
                                <small class="text-muted">Attendance</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success mb-0">B+</h4>
                            <small class="text-muted">Avg Grade</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-info mb-0">12</h4>
                                <small class="text-muted">Subjects</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-0">3</h4>
                            <small class="text-muted">Assignments</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            @if($student->email || $student->phone)
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-address-book me-2"></i>Contact
                    </h6>
                </div>
                <div class="card-body">
                    @if($student->email)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                {{ $student->email }}
                            </a>
                        </div>
                    @endif
                    @if($student->phone)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-success me-2"></i>
                            <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                {{ $student->phone }}
                            </a>
                        </div>
                    @endif
                    @if($student->guardian_phone)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-friends text-info me-2"></i>
                            <a href="tel:{{ $student->guardian_phone }}" class="text-decoration-none">
                                {{ $student->guardian_phone }} (Guardian)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.gap-2 {
    gap: 0.5rem;
}

.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
}

.badge-success {
    background-color: #1cc88a;
    color: white;
}

.badge-warning {
    background-color: #f6c23e;
    color: #5a5c69;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    height: calc(100% + 10px);
    width: 2px;
    background-color: #e3e6f0;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-content h6 {
    color: #5a5c69;
    font-weight: 600;
}

.card {
    border: none;
    border-radius: 0.35rem;
}

.btn {
    border-radius: 0.35rem;
}

.progress {
    border-radius: 10px;
}

.border-end {
    border-right: 1px solid #e3e6f0 !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Confirm status toggle
    $('form[action*="toggle-status"]').on('submit', function(e) {
        const action = $(this).find('button').text().trim();
        if (!confirm(`Are you sure you want to ${action.toLowerCase()} this student?`)) {
            e.preventDefault();
        }
    });
    
    // Add tooltips to action buttons
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush
@endsection