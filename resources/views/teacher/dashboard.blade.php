@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    /* Global Design Tokens */
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

    /* Enhanced Resource Card Styles */
    .resource-card {
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
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
        border-radius: var(--border-radius) !important;
        box-shadow: var(--shadow-sm) !important;
        margin-right: var(--margin-sm) !important;
    }

    .resource-icon i {
        font-size: 1.25rem;
    }

    .resource-body {
        padding: var(--padding-md) !important;
    }

    .resource-count {
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-pill);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .resource-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        transition: var(--transition);
    }

    .resource-card:hover::after {
        height: 6px;
    }

    /* Welcome Header Styles */
    .welcome-header {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #1e2c38 0%, #2d3e4f 100%);
        border-radius: var(--border-radius);
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

    .transition-all {
        transition: var(--transition);
    }

    .hover-lift {
        transition: var(--transition);
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover) !important;
    }

    .hover-bg:hover {
        background-color: rgba(0,0,0,0.02);
        transform: translateX(5px);
    }

    .stat-card .card-body {
        padding: var(--padding-md);
    }

    .w-60 { width: 60%; }
    .w-40 { width: 40%; }
    .w-30 { width: 30%; }

    /* Consistent Table Styles */
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

    /* Quarter Indicator Styles */
    .quarter-indicator {
        cursor: default;
        transition: var(--transition);
    }

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
        border-color: #0dcaf0;
        box-shadow: 0 0 0 3px rgba(13, 202, 240, 0.2);
    }

    .quarter-indicator:not(.active) .quarter-circle {
        border-color: #e9ecef;
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
            <div class="card border-0 shadow-sm welcome-header text-white position-relative overflow-hidden h-100">
                <div class="position-absolute top-0 end-0 w-60 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.10; clip-path: polygon(20% 0, 100% 0%, 100% 100%, 0% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-50 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.45; clip-path: polygon(25% 0, 100% 0%, 100% 100%, 5% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-40 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.60; clip-path: polygon(30% 0, 100% 0%, 100% 100%, 10% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-30 h-100 z-0 d-none d-lg-block bg-primary" style="clip-path: polygon(35% 0, 100% 0%, 100% 100%, 15% 100%);"></div>
                
                <div class="card-body p-4 position-relative z-1 d-flex flex-column h-100">
                    <div class="d-flex flex-column align-items-start">
                        @if(Auth::user()->school && Auth::user()->school->logo_path)
                            <div class="mb-3">
                                <img src="{{ Auth::user()->school->logo_url }}" alt="{{ Auth::user()->school->name }} Logo" class="rounded" style="max-height: 50px;">
                            </div>
                        @else
                            <div class="avatar bg-white bg-opacity-25 rounded p-2 mb-3">
                                <i class="fas fa-chalkboard-teacher fa-lg"></i>
                            </div>
                        @endif
                        <h3 class="fw-bold mb-2 text-white display-6">Teacher Dashboard</h3>
                        <p class="text-white mb-0 lead opacity-90">Welcome back, {{ Auth::user()->name }}!</p>
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
        </div>

        <!-- System Status (Right Side) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm position-relative overflow-hidden h-100">
                <div class="bg-white p-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">System Status</h5>
                            <p class="text-muted mb-0 small">Current system state</p>
                        </div>
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

    <!-- Current Quarter Display -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
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
                                $currentQuarter = \App\Models\SystemSetting::getSetting('global_quarter', 'Q1');
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
                <div class="card bg-white resource-card position-relative overflow-hidden transition-all hover-lift">
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
                <div class="card bg-white resource-card position-relative overflow-hidden transition-all hover-lift">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <h6 class="text-uppercase fw-semibold mb-0 small">Subjects</h6>
                        </div>
                        <h4 class="fw-bold mb-0">{{ $stats['subjectsCount'] }}</h4>
                        <p class="small text-muted mb-0 mt-1">Assigned Subjects</p>
                    </div>
                </div>
            </div>

            <!-- Students Card -->
            <div class="col-3">
                <div class="card bg-white resource-card position-relative overflow-hidden transition-all hover-lift">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <h6 class="text-uppercase fw-semibold mb-0 small">Students</h6>
                        </div>
                        <h4 class="fw-bold mb-0">{{ $stats['studentsCount'] }}</h4>
                        <p class="small text-muted mb-0 mt-1">Enrolled Students</p>
                    </div>
                </div>
            </div>

            <!-- Today's Attendance Card -->
            <div class="col-3">
                <div class="card bg-white resource-card position-relative overflow-hidden transition-all hover-lift">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <h6 class="text-uppercase fw-semibold mb-0 small">Today's Attendance</h6>
                        </div>
                        <h4 class="fw-bold mb-0">{{ $stats['todayAttendance'] }}</h4>
                        <p class="small text-muted mb-0 mt-1">Attendance Recorded</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Learning Resources -->
    <div class="row g-4 mb-4">
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 position-relative overflow-hidden">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <div>
                            <h5 class="mb-1 fw-bold">Quick Actions</h5>
                            <p class="text-muted mb-0 small">Core teaching functions</p>
                        </div>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-semibold">
                        <i class="fas fa-shield-alt me-1"></i> Teacher Panel
                    </span>
                </div>
                <div class="card-body bg-white p-0">
                    <div class="row g-0">
                        <div class="col-md-12">
                            <a href="{{ route('teacher.students.create') }}" class="quick-action-item d-flex py-2 px-4 text-decoration-none transition-all hover-bg position-relative">
                                <div class="d-flex me-4 rounded">
                                    <i class="fas fa-user-plus text-primary fa-md"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-2 fw-bold text-dark">Add New Student</h5>
                                    <p class="text-muted mb-2">Register a new student to your section</p>
                                </div>
                                <div class="ms-3">
                                    <div class="rounded-circle transition-all hover-bg-primary">
                                        <i class="fas fa-arrow-right text-primary fa-lg"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('teacher.grades.index') }}" class="quick-action-item d-flex py-2 px-4 text-decoration-none transition-all hover-bg position-relative">
                                <div class="d-flex me-4 rounded">
                                    <i class="fas fa-star text-primary fa-md"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-2 fw-bold text-dark">Manage Grades</h5>
                                    <p class="text-muted mb-2">View and update student grades</p>
                                </div>
                                <div class="ms-3">
                                    <div class="transition-all hover-bg-primary">
                                        <i class="fas fa-arrow-right text-primary fa-lg"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('teacher.attendances.create') }}" class="quick-action-item d-flex py-2 px-4 text-decoration-none transition-all hover-bg position-relative">
                                <div class="d-flex me-4 rounded">
                                    <i class="fas fa-clipboard-list text-primary fa-md"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-2 fw-bold text-dark">Take Attendance</h5>
                                    <p class="text-muted mb-2">Record daily student attendance</p>
                                </div>
                                <div class="ms-3">
                                    <div class="transition-all hover-bg-primary">
                                        <i class="fas fa-arrow-right text-primary fa-lg"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('teacher.grade-approvals.index') }}" class="quick-action-item d-flex py-2 px-4 text-decoration-none transition-all hover-bg position-relative">
                                <div class="d-flex me-4 rounded">
                                    <i class="fas fa-check-circle text-primary fa-md"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-2 fw-bold text-dark">Grade Approvals</h5>
                                    <p class="text-muted mb-2">Approve or hide subject grades</p>
                                </div>
                                <div class="ms-3">
                                    <div class="transition-all hover-bg-primary">
                                        <i class="fas fa-arrow-right text-primary fa-lg"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="col-lg-8">
            @include('teacher.dashboard.attendance-charts')
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="row g-4">
        <!-- Recent Sections -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold">Assigned Sections</h5>
                        <p class="text-muted mb-0 small">Your assigned sections</p>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Section</th>
                                        <th>Grade Level</th>
                                        <th>Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSections as $section)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $section->name ?? 'N/A' }}</td>
                                        <td>{{ $section->grade_level ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $section->students->count() }} students
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
                            <h6 class="text-muted">No sections assigned</h6>
                            <p class="text-muted small">Contact your administrator to be assigned to sections.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Subjects -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold">Assigned Subjects</h5>
                        <p class="text-muted mb-0 small">Your assigned subjects</p>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentSubjects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Subject</th>
                                        <th>Code</th>
                                        <th>Grade Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSubjects as $subject)
                                        @php
                                            $gradeLevels = $subject->sections->pluck('grade_level')->unique()->sort();
                                        @endphp
                                        @foreach($gradeLevels as $gradeLevel)
                                        <tr>
                                            <td class="ps-4 fw-bold">{{ $subject->name ?? 'N/A' }}</td>
                                            <td><code>{{ $subject->code ?? 'N/A' }}</code></td>
                                            <td class="badge bg-primary text-white">{{ $gradeLevel ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-book text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No subjects assigned</h6>
                            <p class="text-muted small">Contact your administrator to be assigned subjects to teach.</p>
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
        // Define API routes
        const attendanceDataUrl = "{{ route('teacher.dashboard.attendance-data') }}";
        const performanceDataUrl = "{{ route('teacher.dashboard.performance-data') }}";

        // Initialize charts
        function initCharts() {
            const fontColor = '#666';
            const gridColor = 'rgba(0, 0, 0, 0.1)';
            const testChartCtx = document.getElementById('testChart');
            if (testChartCtx) {
                const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                const presentData = [5, 4, 3, 5, 4];
                const lateData = [0, 1, 0, 0, 1];
                const absentData = [0, 0, 2, 0, 0];

                new Chart(testChartCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Present',
                                data: presentData,
                                backgroundColor: '#28a745',
                                borderColor: '#28a745',
                                borderWidth: 1
                            },
                            {
                                label: 'Late',
                                data: lateData,
                                backgroundColor: '#ffc107',
                                borderColor: '#ffc107',
                                borderWidth: 1
                            },
                            {
                                label: 'Absent',
                                data: absentData,
                                backgroundColor: '#dc3545',
                                borderColor: '#dc3545',
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
                            x: { ticks: { color: fontColor }, grid: { color: gridColor } },
                            y: { ticks: { color: fontColor }, grid: { color: gridColor } }
                        }
                    }
                });
            }
        }

        initCharts();

        // Handle attendance period buttons
        const weeklyViewBtn = document.getElementById('weeklyViewBtn');
        const monthlyViewBtn = document.getElementById('monthlyViewBtn');
        const weeklyAttendanceView = document.getElementById('weeklyAttendanceView');
        const monthlyAttendanceView = document.getElementById('monthlyAttendanceView');

        if (weeklyViewBtn && monthlyViewBtn) {
            weeklyViewBtn.addEventListener('click', function() {
                weeklyViewBtn.classList.add('active');
                monthlyViewBtn.classList.remove('active');
                weeklyAttendanceView.style.display = 'block';
                monthlyAttendanceView.style.display = 'none';
            });

            monthlyViewBtn.addEventListener('click', function() {
                monthlyViewBtn.classList.add('active');
                weeklyViewBtn.classList.remove('active');
                monthlyAttendanceView.style.display = 'block';
                weeklyAttendanceView.style.display = 'none';
            });
        }

        // Section select change handler
        const sectionSelect = document.getElementById('performanceMetricSection');
        if (sectionSelect) {
            sectionSelect.addEventListener('change', function() {
                const sectionId = this.value;
                const activePeriodBtn = document.querySelector('.attendance-period-btn.active');
                const period = activePeriodBtn ? activePeriodBtn.dataset.period : 'week';

                const performanceTable = document.querySelector('.student-performance-table tbody');
                if (performanceTable) {
                    performanceTable.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 mb-0">Loading student data...</p>
                            </td>
                        </tr>
                    `;
                }

                fetch(`${attendanceDataUrl}?period=${period}&section_id=${sectionId}`)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        // Update attendance chart
                        attendanceChart.data.labels = data.labels;
                        attendanceChart.data.datasets[0].data = data.present;
                        attendanceChart.data.datasets[1].data = data.late;
                        attendanceChart.data.datasets[2].data = data.half_day;
                        attendanceChart.data.datasets[3].data = data.absent;
                        attendanceChart.data.datasets[4].data = data.excused;

                        const allValues = [...data.present, ...data.late, ...data.half_day, ...data.absent, ...data.excused];
                        const maxValue = Math.max(...allValues, 1);
                        const stepSize = maxValue <= 10 ? 1 : Math.ceil(maxValue / 10);
                        attendanceChart.options.scales.y.ticks.stepSize = stepSize;
                        attendanceChart.update();
                    })
                    .catch(error => console.error('Error fetching section attendance data:', error));

                fetch(`${performanceDataUrl}?section_id=${sectionId}`)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (performanceTable && data.students && data.students.length > 0) {
                            let tableHTML = '';
                            data.students.forEach(student => {
                                let stars = 0;
                                const score = student.grades_avg_score || 0;
                                if (score >= 94) stars = 5;
                                else if (score >= 87) stars = 4;
                                else if (score >= 82) stars = 3;
                                else if (score >= 78) stars = 2;
                                else if (score >= 75) stars = 1;

                                let starsHTML = '';
                                for (let i = 1; i <= 5; i++) {
                                    starsHTML += `<i class="fas fa-star ${i <= stars ? 'text-warning' : 'text-muted'} me-1"></i>`;
                                }

                                let badgeClass = 'bg-danger';
                                if (score >= 90) badgeClass = 'bg-success';
                                else if (score >= 80) badgeClass = 'bg-primary';
                                else if (score >= 70) badgeClass = 'bg-info';
                                else if (score >= 60) badgeClass = 'bg-warning';

                                tableHTML += `
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-container me-2">
                                                    <span class="avatar bg-primary text-white rounded" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                        ${student.first_name ? student.first_name.substring(0, 1) : 'S'}${student.last_name ? student.last_name.substring(0, 1) : ''}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">${student.first_name || 'Student'} ${student.last_name || ''}</div>
                                                    <small class="text-muted">ID: ${student.student_id || 'N/A'}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge ${badgeClass} px-3 py-2">
                                                ${student.grades_avg_score ? student.grades_avg_score.toFixed(1) : '0.0'}%
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="performance-progress progress-taller">
                                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="${student.attendance_rate || 0}" aria-valuemin="0" aria-valuemax="100" style="width: ${student.attendance_rate || 0}%"></div>
                                                </div>
                                                <span class="text-muted small">${student.attendance_rate || 0}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                ${starsHTML}
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="/teacher/students/${student.id}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                `;
                            });
                            performanceTable.innerHTML = tableHTML;
                        } else if (performanceTable) {
                            performanceTable.innerHTML = `
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="py-5">
                                            <div class="avatar bg-light rounded mx-auto mb-3" style="width: 60px; height: 60px;">
                                                <i class="fas fa-user-graduate text-muted fa-2x"></i>
                                            </div>
                                            <h6 class="text-muted">No student performance data available</h6>
                                            <p class="text-muted small mb-3">Add grades for students to see performance metrics</p>
                                            <a href="{{ route('teacher.grades.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Add Grades
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching performance data:', error);
                        if (performanceTable) {
                            performanceTable.innerHTML = `
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="py-5">
                                            <div class="avatar bg-light rounded mx-auto mb-3" style="width: 60px; height: 60px;">
                                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                            </div>
                                            <h6 class="text-danger">Error loading data</h6>
                                            <p class="text-muted small mb-3">There was a problem fetching student performance data: ${error.message}</p>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    });
            });
        }
    });

    // Quarter Synchronization Functionality
    function syncQuarterSelections() {
        const currentQuarter = '{{ $currentQuarter }}';
        
        // Auto-select quarter in all quarter dropdowns on the page
        const quarterSelects = document.querySelectorAll('select[name="quarter"], select#quarter, select#quarterFilter');
        quarterSelects.forEach(select => {
            // Map quarter values to match different formats used across the system
            const quarterValue = currentQuarter; // Q1, Q2, Q3, Q4
            const quarterNumber = currentQuarter.replace('Q', ''); // 1, 2, 3, 4
            
            // Try to find matching option by value
            let optionFound = false;
            Array.from(select.options).forEach(option => {
                if (option.value === quarterValue || option.value === quarterNumber) {
                    option.selected = true;
                    optionFound = true;
                }
            });
            
            // If no exact match found, try partial matching for text content
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
            
            // Trigger change event to update any dependent functionality
            select.dispatchEvent(new Event('change'));
        });
    }

    // Function to check for quarter updates from admin
    function checkQuarterUpdates() {
        fetch('{{ route("teacher.dashboard") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response to extract current quarter
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const quarterDisplay = doc.querySelector('#currentQuarterDisplay');
            
            if (quarterDisplay) {
                const currentDisplayQuarter = document.querySelector('#currentQuarterDisplay');
                if (currentDisplayQuarter && quarterDisplay.textContent !== currentDisplayQuarter.textContent) {
                    // Quarter has changed, reload the page to sync
                    location.reload();
                }
            }
        })
        .catch(error => console.log('Quarter sync check failed:', error));
    }

    // Initialize quarter synchronization when page loads
    document.addEventListener('DOMContentLoaded', function() {
        syncQuarterSelections();
        
        // Check for quarter updates every 30 seconds
        setInterval(checkQuarterUpdates, 30000);
    });

    // Also sync when page becomes visible (user switches back to tab)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            setTimeout(checkQuarterUpdates, 1000);
        }
    });
</script>
@endpush
@endsection