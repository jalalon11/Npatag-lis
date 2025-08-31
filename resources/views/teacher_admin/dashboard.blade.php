@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    /* Import shared design tokens and styles from teacher dashboard */
    :root {

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

    .resource-card {
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        border: none !important;
        border-radius: var(--border-radius) !important;
    }

    .resource-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .resource-header {
        padding: var(--padding-md) !important;
        border-bottom: none !important;
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
    }

    .resource-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        margin-right: var(--margin-sm);
    }

    .resource-count {
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-pill);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .welcome-header {
        background: linear-gradient(135deg, #1e2c38 0%, #2d3e4f 100%);
        border-radius: var(--border-radius);
        position: relative;
        overflow: hidden;

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

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .hover-bg:hover {
        background-color: rgba(0,0,0,0.02);
        transform: translateX(5px);
    }
    .table {
        border-radius: var(--border-radius);
        overflow: hidden;
    }

    .table th, .table td {
        padding: var(--padding-sm);
        vertical-align: middle;
    }

    .table thead {
        background-color: #f8f9fa;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .badge {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .btn {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1.25rem;
    }


    .avatar {
        border-radius: var(--border-radius);

    }

    /* Custom Scrollbar */
    .activity-scroll {
        max-height: 300px;
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
        <!-- Welcome Header -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm welcome-header text-white animate__animated animate__fadeIn h-100">
                <div class="position-absolute top-0 end-0 w-60 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.10; clip-path: polygon(20% 0, 100% 0%, 100% 100%, 0% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-50 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.45; clip-path: polygon(25% 0, 100% 0%, 100% 100%, 5% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-40 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.60; clip-path: polygon(30% 0, 100% 0%, 100% 100%, 10% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-30 h-100 z-0 d-none d-lg-block bg-primary" style="clip-path: polygon(35% 0, 100% 0%, 100% 100%, 15% 100%);"></div>
                <div class="card-body p-4 position-relative z-1 d-flex flex-column h-100">
                    <div class="d-flex flex-column align-items-start">
                        @if($school->logo_path)
                            <div class="mb-3">
                                <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="rounded" style="max-height: 50px;">
                            </div>
                        @else
                            <div class="avatar bg-white bg-opacity-25 rounded p-2 mb-3">
                                <i class="fas fa-chalkboard-teacher fa-lg"></i>
                            </div>
                        @endif
                        <h3 class="fw-bold mb-2 text-white display-6">Admin Dashboard</h3>
                        <p class="text-white mb-0 lead opacity-90">Welcome, {{ Auth::user()->name }}!</p>
                        <div class="d-flex align-items-center mt-3">
                            <span class="badge bg-primary bg-opacity-10 text-white border border-primary border-opacity-20 px-3 py-2">
                                <i class="fas fa-school me-1"></i> {{ $school->name }}
                            </span>
                            <div class="ms-3 d-flex align-items-center text-white text-opacity-70">
                                <i class="far fa-calendar-alt text-info me-2"></i>
                                <span>{{ now()->format('F d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm animate__animated animate__fadeIn h-100" style="animation-delay: 0.3s;">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold">System Status</h5>
                        <p class="text-muted mb-0 small">Current system state</p>
                    </div>
                    <span class="badge {{ $maintenanceMode ? 'bg-danger bg-opacity-10 text-danger' : 'bg-primary bg-opacity-10 text-primary' }} px-3 py-2 fw-semibold">
                        <i class="fas fa-{{ $maintenanceMode ? 'exclamation-circle' : 'check-circle' }} me-1"></i>
                        {{ $maintenanceMode ? 'Maintenance' : 'Online' }}
                    </span>
                </div>
                <div class="card-body bg-white p-4 d-flex flex-column justify-content-center h-100">
                    <div class="text-center mb-4">
                        <div class="mx-auto mb-3">
                            <div class="p-4 rounded-circle {{ $maintenanceMode ? 'bg-danger' : 'bg-primary' }} bg-opacity-10 d-inline-block position-relative">
                                <i class="fas fa-{{ $maintenanceMode ? 'tools' : 'shield-alt' }} {{ $maintenanceMode ? 'text-danger' : 'text-primary' }} fa-3x"></i>
                                @if(!$maintenanceMode)
                                    <div class="position-absolute top-0 end-0">
                                        <span class="badge bg-primary rounded-circle p-2">
                                            <i class="fas fa-check text-white"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>
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

    <!-- Stats Overview -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-white resource-card hover-lift">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="text-uppercase fw-semibold mb-0 small">Sections</h6>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $stats['sectionsCount'] }}</h4>
                    <p class="small text-muted mb-0 mt-1">Active Sections</p>
                    <a href="{{ route('teacher-admin.sections.index') }}" class="btn btn-sm btn-outline-primary mt-2">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white resource-card hover-lift">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="text-uppercase fw-semibold mb-0 small">Subjects</h6>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $stats['subjectsCount'] }}</h4>
                    <p class="small text-muted mb-0 mt-1">Active Subjects</p>
                    <a href="{{ route('teacher-admin.subjects.index') }}" class="btn btn-sm btn-outline-success mt-2">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white resource-card hover-lift">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="text-uppercase fw-semibold mb-0 small">Teachers</h6>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $stats['teachersCount'] }}</h4>
                    <p class="small text-muted mb-0 mt-1">Active Staff</p>
                    <a href="#" class="btn btn-sm btn-outline-info mt-2 disabled">View All</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white resource-card hover-lift">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="text-uppercase fw-semibold mb-0 small">Students</h6>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $stats['studentsCount'] }}</h4>
                    <p class="small text-muted mb-0 mt-1">Enrolled Students</p>
                    <a href="#" class="btn btn-sm btn-outline-warning mt-2 disabled">View All</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-white resource-card hover-lift">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="text-uppercase fw-semibold mb-0 small">Pending Applications</h6>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $enrollmentStats['pending'] ?? 0 }}</h4>
                    <p class="small text-muted mb-0 mt-1">{{ $enrollmentStats['pending'] > 0 ? 'Needs Review' : 'All Reviewed' }}</p>
                    <a href="{{ route('teacher-admin.enrollments.index') }}?status=pending" class="btn btn-sm btn-outline-danger mt-2">Review</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white resource-card hover-lift">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="text-uppercase fw-semibold mb-0 small">Approved Applications</h6>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $enrollmentStats['approved'] ?? 0 }}</h4>
                    <p class="small text-muted mb-0 mt-1">Ready for Enrollment</p>
                    <a href="{{ route('teacher-admin.enrollments.index') }}?status=approved" class="btn btn-sm btn-outline-success mt-2">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white resource-card hover-lift">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="text-uppercase fw-semibold mb-0 small">Enrolled Students</h6>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $enrollmentStats['enrolled'] ?? 0 }}</h4>
                    <p class="small text-muted mb-0 mt-1">Active Students</p>
                    <a href="{{ route('teacher-admin.enrollments.index') }}?status=enrolled" class="btn btn-sm btn-outline-primary mt-2">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white resource-card hover-lift">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="text-uppercase fw-semibold mb-0 small">Total Applications</h6>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $enrollmentStats['total'] ?? 0 }}</h4>
                    <p class="small text-muted mb-0 mt-1">All Time</p>
                    <a href="{{ route('teacher-admin.enrollments.index') }}" class="btn btn-sm btn-outline-info mt-2">Manage</a>

                </div>
            </div>
        </div>

    <!-- Analytics Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">School Attendance Trends</h5>
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
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn">
                <div class="p-4 bg-white">
                    <h5 class="mb-0 fw-bold">School Grade Distribution</h5>
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

    <!-- Teacher Performance & Recent Activity -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Teacher Performance</h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="topPerformersOnly">
                        <label class="form-check-label text-muted small" for="topPerformersOnly">Top Performers Only</label>
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
                                    <th class="text-center">Actions</th>
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
                                                <h6 class="mb-0 fw-bold">{{ $teacher['name'] }}</h6>
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
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" title="Assign Subject">
                                                    <i class="fas fa-book"></i>
                                                </button>
                                            </div>

                                        </td>
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

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent School Activity</h5>
                    <span class="badge bg-primary rounded-pill">{{ count($recentActivity) }}</span>
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

    <!-- Recent Sections and Subjects -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Sections</h5>
                    <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary">Add Section</a>
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="table-responsive activity-scroll">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Section</th>
                                        <th>Grade Level</th>
                                        <th>Subjects</th>
                                        <th>Adviser</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSections as $section)
                                        <tr>
                                            <td class="ps-4 fw-bold">
                                                <a href="{{ route('teacher-admin.sections.show', $section) }}">{{ $section->name }}</a>
                                            </td>
                                            <td>Grade {{ $section->grade_level }}</td>
                                            <td><span class="badge bg-primary">{{ $section->subjects->count() }}</span></td>
                                            <td>{{ $section->adviser ? $section->adviser->name : 'Unassigned' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users text-muted fa-3x mb-3"></i>
                            <h6 class="text-muted">No sections found</h6>
                            <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary mt-2">Create Section</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Subjects</h5>
                    <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-primary">Add Subject</a>
                </div>
                <div class="card-body p-0">
                    @if($recentSubjects->count() > 0)
                        <div class="table-responsive activity-scroll">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Subject</th>
                                        <th>Code</th>
                                        <th>Sections</th>
                                        <th>Teachers</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSubjects as $subject)
                                        <tr>
                                            <td class="ps-4 fw-bold">
                                                <a href="{{ route('teacher-admin.subjects.show', $subject) }}">{{ $subject->name }}</a>
                                            </td>
                                            <td><code>{{ $subject->code }}</code></td>
                                            <td><span class="badge bg-primary">{{ $subject->sections_count }}</span></td>
                                            <td>{{ $subject->teachers ? $subject->teachers->count() : 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book text-muted fa-3x mb-3"></i>
                            <h6 class="text-muted">No subjects found</h6>
                            <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-primary mt-2">Create Subject</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm animate__animated animate__fadeIn">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Enrollment Applications</h5>
                    <div>
                        @if($pendingEnrollmentsCount > 0)
                            <span class="badge bg-danger bg-opacity-10 text-danger me-2">{{ $pendingEnrollmentsCount }} Pending</span>
                        @endif
                        <a href="{{ route('teacher-admin.enrollments.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentEnrollments->count() > 0)
                        <div class="table-responsive activity-scroll">
                            <table class="table table-hover mb-0">
                                <thead class="sticky-header">
                                    <tr>
                                        <th class="ps-4">Student Name</th>
                                        <th>Grade Level</th>
                                        <th>Preferred Section</th>
                                        <th>Status</th>
                                        <th>Applied</th>
                                        <th class="text-end pe-4">Actions</th>
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
                                                        <h6 class="mb-0 fw-bold">{{ $enrollment->getFullNameAttribute() }}</h6>
                                                        <small class="text-muted">{{ $enrollment->guardian_name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-secondary">Grade {{ $enrollment->preferred_grade_level }}</span></td>
                                            <td>{{ $enrollment->preferredSection ? $enrollment->preferredSection->name : 'Any Section' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $enrollment->status == 'pending' ? 'warning' : ($enrollment->status == 'approved' ? 'success' : ($enrollment->status == 'enrolled' ? 'primary' : 'danger')) }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                            <td><small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small></td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group">
                                                    <a href="{{ route('teacher-admin.enrollments.show', $enrollment) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($enrollment->status == 'pending')
                                                        <form method="POST" action="{{ route('teacher-admin.enrollments.approve', $enrollment) }}" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approve" onclick="return confirm('Are you sure you want to approve this enrollment?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-plus text-muted fa-3x mb-3"></i>
                            <h6 class="text-muted">No Enrollment Applications Yet</h6>
                            <p class="text-muted mb-3">When students submit applications, they will appear here.</p>
                            <a href="{{ route('enrollment.create') }}" class="btn btn-sm btn-primary">View Application Form</a>
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
        // Chart data
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
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Absent',
                        data: weeklyAttendanceData.map(item => item.absent),
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Late',
                        data: weeklyAttendanceData.map(item => item.late),
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } }
                },
                plugins: { legend: { position: 'top' } }
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
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                }
            }
        });

        // Period Selection for Attendance Chart
        document.querySelectorAll('.attendance-period-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.attendance-period-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                const period = this.getAttribute('data-period');
                // TODO: Fetch new data via AJAX for the selected period
                console.log(`Switch to ${period} view`);
            });
        });

        // Toggle for top performers
        document.getElementById('topPerformersOnly').addEventListener('change', function() {

            console.log('Show top performers only:', this.checked);
            // TODO: Filter table rows via AJAX or client-side logic

        });
    });
</script>
@endpush

<!-- Hidden data elements for JS -->
<div id="weekly-attendance-data" data-attendance="{{ json_encode($attendanceStats['weeklyAttendance']) }}" style="display: none;"></div>
<div id="grade-distribution-data" data-grades="{{ json_encode($gradeDistribution) }}" style="display: none;"></div>
@endsection