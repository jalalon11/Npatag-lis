@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    /* Global Design Tokens */
    :root {
        --primary-color: #1e2c38;
        --secondary-color: #2d3e4f;
        --accent-color: #0dcaf0;
        --text-color: #333;
        --light-bg: #f8f9fa;
        --border-radius: 12px;
        --border-radius-pill: 50px;
        --padding-sm: 1rem;
        --padding-md: 1.5rem;
        --margin-sm: 1rem;
        --margin-md: 1.5rem;
        --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-hover: 0 12px 24px rgba(0, 0, 0, 0.12);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: var(--shadow-hover);
    }

    .card-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        border-bottom: none;
        padding: var(--padding-md);
    }

    .card-body {
        padding: var(--padding-md);
    }

    /* Welcome Header Styles */
    .welcome-header {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: var(--border-radius);
        color: #fff;
    }

    .welcome-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at top right, rgba(52, 152, 219, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Stat Card Styles */
    .stat-card {
        background: #fff;
        border-radius: var(--border-radius);
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    /* Table Styles */
    .table {
        border-radius: var(--border-radius);
        overflow: hidden;
    }

    .table th, .table td {
        padding: var(--padding-sm);
        vertical-align: middle;
    }

    .table thead {
        background-color: var(--light-bg);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    /* Badge Styles */
    .badge {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Button Styles */
    .btn {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1.25rem;
    }

    /* Quarter Indicator Styles */
    .quarter-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: var(--transition);
        border: 2px solid transparent;
    }

    .quarter-indicator.active .quarter-circle {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(13, 202, 240, 0.2);
    }

    .quarter-indicator:not(.active) .quarter-circle {
        border-color: #e9ecef;
    }

    /* Custom Scrollbar */
    .activity-scroll {
        max-height: 400px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
    }

    .activity-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .activity-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .activity-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    .activity-scroll::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.3);
    }

    /* Sticky Header */
    .sticky-header th {
        position: sticky;
        top: 0;
        background-color: var(--light-bg);
        z-index: 1;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@php
    $maintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();
    $maintenanceMessage = \App\Models\SystemSetting::getMaintenanceMessage();
    $maintenanceDuration = \App\Models\SystemSetting::getMaintenanceDuration();
@endphp

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Header and System Status -->
    <div class="row g-4 mb-4">
        <!-- Welcome Header (Left Side) -->
        <div class="col-lg-8">
            <div class="card border-0 welcome-header">
                <div class="card-body p-4">
                    @if($school->logo_path)
                        <div class="mb-3">
                            <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="rounded" style="max-height: 50px;">
                        </div>
                    @else
                        <div class="avatar bg-white bg-opacity-25 rounded p-2 mb-3">
                            <i class="fas fa-user-shield fa-lg"></i>
                        </div>
                    @endif
                    <h3 class="fw-bold mb-2 display-6">Teacher Admin Dashboard</h3>
                    <p class="lead mb-0 opacity-90">Welcome back, {{ Auth::user()->name }}!</p>
                    <div class="d-flex align-items-center mt-3">
                        <span class="badge bg-primary bg-opacity-10 text-white border border-primary border-opacity-20 px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i> System Online
                        </span>
                        <div class="ms-3 d-flex align-items-center text-white text-opacity-70">
                            <i class="far fa-calendar-alt text-info me-2"></i>
                            <span>{{ now()->format('F d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status (Right Side) -->
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">System Status</h5>
                        <p class="text-muted mb-0 small">Current system state</p>
                    </div>
                    <span class="badge {{ $maintenanceMode ? 'bg-danger bg-opacity-10 text-danger' : 'bg-primary bg-opacity-10 text-primary' }} px-3 py-2 fw-semibold">
                        <i class="fas fa-{{ $maintenanceMode ? 'exclamation-circle' : 'check-circle' }} me-1"></i>
                        {{ $maintenanceMode ? 'Maintenance' : 'Online' }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="p-4 rounded-circle {{ $maintenanceMode ? 'bg-danger' : 'bg-primary' }} bg-opacity-10 d-inline-block">
                            <i class="fas fa-{{ $maintenanceMode ? 'tools' : 'shield-alt' }} {{ $maintenanceMode ? 'text-danger' : 'text-primary' }} fa-3x"></i>
                            @if(!$maintenanceMode)
                            <div class="position-absolute top-0 end-0">
                                <span class="badge bg-primary rounded-circle p-2">
                                    <i class="fas fa-check text-white"></i>
                                </span>
                            </div>
                            @endif
                        </div>
                        <h5 class="fw-bold mb-2">{{ $maintenanceMode ? 'Maintenance Active' : 'All Systems Operational' }}</h5>
                        <p class="text-muted mb-0">
                            {{ $maintenanceMode
                                ? 'System is in maintenance mode. Only administrators have access.'
                                : 'All systems are running smoothly and accessible to users.'
                            }}
                        </p>
                    </div>
                    @if($maintenanceMode)
                    <div class="maintenance-details bg-danger bg-opacity-5 border border-danger border-opacity-20 rounded-3 p-3 mb-0">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-danger me-2"></i>
                            <span class="text-danger fw-semibold">Duration: {{ $maintenanceDuration }} minutes</span>
                        </div>
                        @if($maintenanceMessage)
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-danger me-2 mt-1"></i>
                            <span class="text-danger">{{ $maintenanceMessage }}</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Current Quarter Display -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-info bg-opacity-10 me-3">
                                <i class="fas fa-calendar-alt text-info fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">Current Active Quarter</h5>
                                <p class="text-muted mb-0 small">System-wide quarter selection set by administrator</p>
                            </div>
                        </div>
                        <div class="text-end">
                            @php
                                $globalQuarter = App\Models\SystemSetting::where('key', 'global_quarter')->first();
                                $currentQuarter = $globalQuarter ? $globalQuarter->value : 'Q1';
                                $quarterNames = [
                                    'Q1' => '1st Quarter',
                                    'Q2' => '2nd Quarter', 
                                    'Q3' => '3rd Quarter',
                                    'Q4' => '4th Quarter'
                                ];
                            @endphp
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="quarter-indicator {{ $currentQuarter == 'Q1' ? 'active' : '' }}" data-quarter="Q1">
                                    <div class="quarter-circle {{ $currentQuarter == 'Q1' ? 'bg-info' : 'bg-light' }} text-{{ $currentQuarter == 'Q1' ? 'white' : 'muted' }}">
                                        <span class="fw-bold">Q1</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">1st Quarter</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="quarter-indicator {{ $currentQuarter == 'Q2' ? 'active' : '' }}" data-quarter="Q2">
                                    <div class="quarter-circle {{ $currentQuarter == 'Q2' ? 'bg-info' : 'bg-light' }} text-{{ $currentQuarter == 'Q2' ? 'white' : 'muted' }}">
                                        <span class="fw-bold">Q2</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">2nd Quarter</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="quarter-indicator {{ $currentQuarter == 'Q3' ? 'active' : '' }}" data-quarter="Q3">
                                    <div class="quarter-circle {{ $currentQuarter == 'Q3' ? 'bg-info' : 'bg-light' }} text-{{ $currentQuarter == 'Q3' ? 'white' : 'muted' }}">
                                        <span class="fw-bold">Q3</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">3rd Quarter</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="quarter-indicator {{ $currentQuarter == 'Q4' ? 'active' : '' }}" data-quarter="Q4">
                                    <div class="quarter-circle {{ $currentQuarter == 'Q4' ? 'bg-info' : 'bg-light' }} text-{{ $currentQuarter == 'Q4' ? 'white' : 'muted' }}">
                                        <span class="fw-bold">Q4</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">4th Quarter</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-lg-12 mb-4">
        <div class="row g-3">
            <!-- Sections Card -->
            <div class="col-3">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <h6 class="text-uppercase fw-semibold mb-0 small">Sections</h6>
                        </div>
                        <h4 class="fw-bold mb-0">{{ $stats['sectionsCount'] }}</h4>
                        <p class="small text-muted mb-0 mt-1">Active Sections</p>
                    </div>
                </div>
            </div>

            <!-- Subjects Card -->
            <div class="col-3">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <h6 class="text-uppercase fw-semibold mb-0 small">Subjects</h6>
                        </div>
                        <h4 class="fw-bold mb-0">{{ $stats['subjectsCount'] }}</h4>
                        <p class="small text-muted mb-0 mt-1">Active Subjects</p>
                    </div>
                </div>
            </div>

            <!-- Teachers Card -->
            <div class="col-3">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <h6 class="text-uppercase fw-semibold mb-0 small">Teachers</h6>
                        </div>
                        <h4 class="fw-bold mb-0">{{ $stats['teachersCount'] }}</h4>
                        <p class="small text-muted mb-0 mt-1">Active Staff</p>
                    </div>
                </div>
            </div>

            <!-- Students Card -->
            <div class="col-3">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <h6 class="text-uppercase fw-semibold mb-0 small">Students</h6>
                        </div>
                        <h4 class="fw-bold mb-0">{{ $stats['studentsCount'] }}</h4>
                        <p class="small text-muted mb-0 mt-1">Enrolled Students</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Analytics -->
    <div class="row g-4 mb-4">
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Quick Actions</h5>
                        <p class="text-muted mb-0 small">Admin functions</p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-semibold">
                        <i class="fas fa-shield-alt me-1"></i> Admin Panel
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-12">
                            <a href="{{ route('teacher-admin.sections.create') }}" class="list-group-item list-group-item-action d-flex py-2 px-4 text-decoration-none">
                                <div class="me-3">
                                    <i class="fas fa-door-open text-primary fa-md"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fw-bold">Add New Section</h5>
                                    <p class="text-muted mb-0">Create a new section for students</p>
                                </div>
                                <i class="fas fa-arrow-right text-primary fa-lg"></i>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('teacher-admin.subjects.create') }}" class="list-group-item list-group-item-action d-flex py-2 px-4 text-decoration-none">
                                <div class="me-3">
                                    <i class="fas fa-book text-primary fa-md"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fw-bold">Add New Subject</h5>
                                    <p class="text-muted mb-0">Create a new subject for classes</p>
                                </div>
                                <i class="fas fa-arrow-right text-primary fa-lg"></i>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('teacher-admin.enrollments.index') }}?status=pending" class="list-group-item list-group-item-action d-flex py-2 px-4 text-decoration-none">
                                <div class="me-3">
                                    <i class="fas fa-user-plus text-primary fa-md"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fw-bold">Review Enrollments</h5>
                                    <p class="text-muted mb-0">Manage pending student applications</p>
                                </div>
                                <i class="fas fa-arrow-right text-primary fa-lg"></i>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('teacher-admin.enrollments.index') }}" class="list-group-item list-group-item-action d-flex py-2 px-4 text-decoration-none">
                                <div class="me-3">
                                    <i class="fas fa-list text-primary fa-md"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fw-bold">View All Enrollments</h5>
                                    <p class="text-muted mb-0">See all enrollment applications</p>
                                </div>
                                <i class="fas fa-arrow-right text-primary fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">School Analytics</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary active attendance-period-btn" data-period="week">Week</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary attendance-period-btn" data-period="month">Month</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="attendance-chart-container" style="height: 300px;">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="grade-chart-container d-flex justify-content-center align-items-center" style="height: 300px;">
                                <canvas id="gradeDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Enrollment Statistics</h5>
                        <p class="text-muted mb-0 small">Overview of enrollment applications</p>
                    </div>
                    <a href="{{ route('teacher-admin.enrollments.index') }}" class="btn btn-md btn-primary fw-semibold">
                        View All Enrollments
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Pending Applications -->
                        <div class="col-3">
                            <div class="card stat-card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="text-uppercase fw-semibold mb-0 small">Pending Applications</h6>
                                    </div>
                                    <h4 class="fw-bold mb-0">{{ $enrollmentStats['pending'] ?? 0 }}</h4>
                                    <p class="small text-muted mb-0 mt-1">Needs Review</p>
                                </div>
                            </div>
                        </div>
                        <!-- Approved Applications -->
                        <div class="col-3">
                            <div class="card stat-card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="text-uppercase fw-semibold mb-0 small">Approved Applications</h6>
                                    </div>
                                    <h4 class="fw-bold mb-0">{{ $enrollmentStats['approved'] ?? 0 }}</h4>
                                    <p class="small text-muted mb-0 mt-1">Ready for Enrollment</p>
                                </div>
                            </div>
                        </div>
                        <!-- Enrolled Students -->
                        <div class="col-3">
                            <div class="card stat-card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="text-uppercase fw-semibold mb-0 small">Enrolled Students</h6>
                                    </div>
                                    <h4 class="fw-bold mb-0">{{ $enrollmentStats['enrolled'] ?? 0 }}</h4>
                                    <p class="small text-muted mb-0 mt-1">Active Students</p>
                                </div>
                            </div>
                        </div>
                        <!-- Total Applications -->
                        <div class="col-3">
                            <div class="card stat-card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="text-uppercase fw-semibold mb-0 small">Total Applications</h6>
                                    </div>
                                    <h4 class="fw-bold mb-0">{{ $enrollmentStats['total'] ?? 0 }}</h4>
                                    <p class="small text-muted mb-0 mt-1">All Time</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="row g-4">
        <!-- Teacher Performance -->
        <div class="col-lg-6">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Teacher Performance</h5>
                        <p class="text-muted mb-0 small">Performance metrics for teachers</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="topPerformersOnly">
                        <label class="form-check-label text-muted small" for="topPerformersOnly">Show Top Performers Only</label>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive activity-scroll">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="sticky-header">
                                <tr>
                                    <th class="ps-4">Teacher</th>
                                    <th class="text-center">Subjects</th>
                                    <th class="text-center">Sections</th>
                                    <th class="text-center">Avg. Grade</th>
                                    <th class="text-center">Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teacherPerformance as $teacher)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-info bg-opacity-10 text-info rounded p-2 me-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $teacher['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $teacher['subjectsCount'] }}</td>
                                    <td class="text-center">{{ $teacher['sectionsCount'] }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $teacher['averageGrade'] >= 85 ? 'bg-success' : ($teacher['averageGrade'] >= 75 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $teacher['averageGrade'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $teacher['attendanceCount'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$teacherPerformance->count())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-user-check text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No teacher performance data available</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Recent School Activity</h5>
                        <p class="text-muted mb-0 small">Latest updates and actions</p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-semibold">
                        {{ count($recentActivity) }} Activities
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush activity-scroll">
                        @foreach($recentActivity as $activity)
                        <div class="list-group-item border-0 py-3 px-3 {{ $loop->even ? 'bg-light bg-opacity-50' : '' }}">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar rounded {{ $activity['type'] == 'grade' ? 'bg-success bg-opacity-10 text-success' : 'bg-info bg-opacity-10 text-info' }} p-2 me-3">
                                    <i class="fas {{ $activity['type'] == 'grade' ? 'fa-star' : 'fa-clipboard-check' }}"></i>
                                </div>
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <h6 class="mb-0 fw-bold">
                                        <span class="badge {{ $activity['type'] == 'grade' ? 'bg-success' : 'bg-info' }} rounded-pill me-2">
                                            {{ ucfirst($activity['type']) }}
                                        </span>
                                        {{ $activity['user'] }}
                                    </h6>
                                    <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                </div>
                            </div>
                            <p class="ms-5 mb-0 text-dark">{{ $activity['description'] }}</p>
                        </div>
                        @endforeach
                    </div>
                    @if(!count($recentActivity))
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-history text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No recent activity</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="row g-4">
        <!-- Recent Sections -->
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Recent Sections</h5>
                        <p class="text-muted mb-0 small">Latest created sections</p>
                    </div>
                    <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-md btn-primary fw-semibold">
                        Add Section
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Section</th>
                                        <th>Grade Level</th>
                                        <th>Subjects</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSections as $section)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $section->name ?? 'N/A' }}</td>
                                        <td>{{ $section->grade_level ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $section->subjects->count() }} subjects
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-folder-open text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No sections found</h6>
                            <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary mt-2">
                                Create Section
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Subjects -->
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Recent Subjects</h5>
                        <p class="text-muted mb-0 small">Latest created subjects</p>
                    </div>
                    <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-md btn-primary fw-semibold">
                        Add Subject
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentSubjects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Subject</th>
                                        <th>Code</th>
                                        <th>Sections</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSubjects as $subject)
                                        <tr>
                                            <td class="ps-4 fw-bold">{{ $subject->name ?? 'N/A' }}</td>
                                            <td><code>{{ $subject->code ?? 'N/A' }}</code></td>
                                            <td>
                                                <span class="badge bg-primary">{{ $subject->sections_count }} sections</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-book text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No subjects found</h6>
                            <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-primary mt-2">
                                Create Subject
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Recent Enrollment Applications</h5>
                        <p class="text-muted mb-0 small">Latest student enrollment applications</p>
                    </div>
                    <div>
                        @if($pendingEnrollmentsCount > 0)
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 fw-semibold me-2">
                                {{ $pendingEnrollmentsCount }} Pending
                            </span>
                        @endif
                        <a href="{{ route('teacher-admin.enrollments.index') }}" class="btn btn-md btn-primary fw-semibold">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentEnrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="sticky-header">
                                    <tr>
                                        <th class="ps-4">Student Name</th>
                                        <th>Grade Level</th>
                                        <th>Preferred Section</th>
                                        <th>Status</th>
                                        <th>Applied</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEnrollments as $enrollment)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-{{ $enrollment->status == 'pending' ? 'warning' : ($enrollment->status == 'approved' ? 'success' : ($enrollment->status == 'enrolled' ? 'primary' : 'danger')) }} bg-opacity-10 text-{{ $enrollment->status == 'pending' ? 'warning' : ($enrollment->status == 'approved' ? 'success' : ($enrollment->status == 'enrolled' ? 'primary' : 'danger')) }} rounded p-2 me-3">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $enrollment->getFullNameAttribute() }}</h6>
                                                        <small class="text-muted">{{ $enrollment->guardian_name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">Grade {{ $enrollment->preferred_grade_level }}</span>
                                            </td>
                                            <td>{{ $enrollment->preferredSection ? $enrollment->preferredSection->name : 'Any Section' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $enrollment->status == 'pending' ? 'warning' : ($enrollment->status == 'approved' ? 'success' : ($enrollment->status == 'enrolled' ? 'primary' : 'danger')) }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-user-plus text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No Enrollment Applications Yet</h6>
                            <p class="text-muted mb-3">When students submit enrollment applications, they will appear here.</p>
                            <a href="{{ route('enrollment.create') }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                View Application Form
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get data from data attributes for charts
        const weeklyAttendanceData = JSON.parse(document.getElementById('weekly-attendance-data').getAttribute('data-attendance'));
        const gradeDistributionData = JSON.parse(document.getElementById('grade-distribution-data').getAttribute('data-grades'));
        
        // Attendance Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: weeklyAttendanceData.map(item => item.date),
                datasets: [
                    {
                        label: 'Present',
                        data: weeklyAttendanceData.map(item => item.present),
                        backgroundColor: '#28a745',
                        borderColor: '#28a745',
                        borderWidth: 1
                    },
                    {
                        label: 'Absent',
                        data: weeklyAttendanceData.map(item => item.absent),
                        backgroundColor: '#dc3545',
                        borderColor: '#dc3545',
                        borderWidth: 1
                    },
                    {
                        label: 'Late',
                        data: weeklyAttendanceData.map(item => item.late),
                        backgroundColor: '#ffc107',
                        borderColor: '#ffc107',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    x: { ticks: { color: '#666' }, grid: { color: 'rgba(0, 0, 0, 0.1)' } },
                    y: { ticks: { color: '#666' }, grid: { color: 'rgba(0, 0, 0, 0.1)' } }
                }
            }
        });

        // Grade Distribution Chart
        const gradeCtx = document.getElementById('gradeDistributionChart').getContext('2d');
        const gradeChart = new Chart(gradeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Excellent (90-100)', 'Very Good (85-89)', 'Good (80-84)', 'Satisfactory (75-79)', 'Needs Improvement (<75)'],
                datasets: [{
                    data: [
                        gradeDistributionData.excellent,
                        gradeDistributionData.veryGood,
                        gradeDistributionData.good,
                        gradeDistributionData.satisfactory,
                        gradeDistributionData.needsImprovement
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#0d6efd',
                        '#00c3dc',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderColor: [
                        '#28a745',
                        '#0d6efd',
                        '#00c3dc',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // Period Selection for Attendance Chart
        document.querySelectorAll('.attendance-period-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                document.querySelectorAll('.attendance-period-btn').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // TODO: Fetch new data for selected period
                var period = this.getAttribute('data-period');
                fetch(`/teacher-admin/dashboard/attendance-data?period=${period}`)
                    .then(response => response.json())
                    .then(data => {
                        attendanceChart.data.labels = data.labels;
                        attendanceChart.data.datasets[0].data = data.present;
                        attendanceChart.data.datasets[1].data = data.absent;
                        attendanceChart.data.datasets[2].data = data.late;
                        attendanceChart.update();
                    })
                    .catch(error => console.error('Error fetching attendance data:', error));
            });
        });

        // Toggle for top performers
        document.getElementById('topPerformersOnly').addEventListener('change', function() {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                const avgGrade = parseFloat(row.querySelector('.badge').textContent);
                row.style.display = this.checked && avgGrade < 85 ? 'none' : '';
            });
        });

        // Quarter synchronization functionality
        function syncQuarterSelections() {
            const currentQuarter = '{{ $currentQuarter }}';
            const quarterSelects = document.querySelectorAll('select[name="quarter"], select#quarter, select#quarterFilter');
            quarterSelects.forEach(select => {
                const quarterValue = currentQuarter;
                const quarterNumber = currentQuarter.replace('Q', '');
                let optionFound = false;
                Array.from(select.options).forEach(option => {
                    if (option.value === quarterValue || option.value === quarterNumber) {
                        option.selected = true;
                        optionFound = true;
                    }
                });
                if (!optionFound) {
                    Array.from(select.options).forEach(option => {
                        const optionText = option.textContent.toLowerCase();
                        const quarterText = (quarterNumber === '1' ? 'first' : 
                                           quarterNumber === '2' ? 'second' : 
                                           quarterNumber === '3' ? 'third' : 'fourth');
                        if (optionText.includes(quarterText) || optionText.includes(quarterNumber)) {
                            option.selected = true;
                        }
                    });
                }
                select.dispatchEvent(new Event('change'));
            });
        }

        // Check for quarter updates
        function checkQuarterUpdates() {
            fetch('{{ route("teacher-admin.dashboard") }}', {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const quarterDisplay = doc.querySelector('#currentQuarterDisplay');
                if (quarterDisplay) {
                    const currentDisplayQuarter = document.querySelector('#currentQuarterDisplay');
                    if (currentDisplayQuarter && quarterDisplay.textContent !== currentDisplayQuarter.textContent) {
                        location.reload();
                    }
                }
            })
            .catch(error => console.log('Quarter sync check failed:', error));
        }

        // Initialize quarter sync
        syncQuarterSelections();
        setInterval(checkQuarterUpdates, 30000);
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                setTimeout(checkQuarterUpdates, 1000);
            }
        });
    });
</script>
@endpush

<!-- Hidden data elements for JS -->
<div id="weekly-attendance-data" data-attendance="{{ json_encode($attendanceStats['weeklyAttendance']) }}" style="display: none;"></div>
<div id="grade-distribution-data" data-grades="{{ json_encode($gradeDistribution) }}" style="display: none;"></div>
@endsection