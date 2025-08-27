@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
        --primary-color: #0d6efd;
        --text-muted: #6c757d;
        --bg-light: #f8f9fa;
    }

    /* Card Styles */
    .card {
        border: none !important;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .card-header {
        background: var(--bg-light);
        border-bottom: none;
        padding: var(--padding-md);
    }

    .card-body {
        padding: var(--padding-md);
    }

    /* Button Styles */
    .btn {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    /* Badge Styles */
    .badge {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
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
        background-color: var(--bg-light);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    /* Filter Bar */
    .filter-bar {
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        padding: var(--padding-md);
        background: #fff;
    }

    .form-floating > .form-select,
    .form-floating > .form-control {
        border-radius: 8px;
        border-color: #e0e0e0;
    }

    .form-floating > .form-select:focus,
    .form-floating > .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(108, 99, 255, 0.1);
    }

    /* Attendance Stats */
    .attendance-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 60px;
    }

    .attendance-stat .badge {
        font-size: 0.9rem;
        padding: 0.35rem 0.65rem;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Progress Circle */
    .progress-circle {
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
        transition: stroke-dasharray 0.5s ease;
    }

    /* Calendar Modal */
    .modal-content {
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
    }

    .modal-header, .modal-footer {
        background: #fff;
        border: none;
        padding: var(--padding-md);
    }

    .modal-body {
        padding: var(--padding-md);
        max-height: calc(100vh - 170px);
        overflow-y: auto;
    }

    .modal-mobile-fixed-header .modal-header,
    .modal-mobile-fixed-header .modal-footer {
        position: sticky;
        z-index: 1050;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-mobile-fixed-header .modal-header {
        top: 0;
    }

    .modal-mobile-fixed-header .modal-footer {
        bottom: 0;
    }

    /* Calendar Specific */
    .calendar-container .card {
        border-radius: var(--border-radius);
    }

    .calendar-container .card-header {
        padding: var(--padding-sm);
    }

    .calendar-container .table {
        margin-bottom: 0;
    }

    /* Mobile Responsive */
    @media (max-width: 767.98px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card-body {
            padding: var(--padding-sm);
        }

        .attendance-stat {
            min-width: 45px;
        }

        .attendance-stat .badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .stat-label {
            font-size: 0.65rem;
        }

        .modal-mobile-fixed-header .modal-body {
            max-height: calc(100vh - 200px);
            padding-bottom: var(--padding-sm);
        }

        .btn {
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
        }

        .filter-bar {
            padding: var(--padding-sm) !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex flex-column d-sm-flex flex-sm-row align-items-center align-items-sm-center justify-content-sm-between mb-4">
        <div class="text-center text-sm-start mb-4 mb-sm-0 w-100 w-sm-auto">
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Attendance Records</h1>
            <p class="text-muted mb-0">Track and manage student attendance across all sections</p>
        </div>
        <div class="d-flex flex-wrap justify-content-center justify-content-sm-end gap-2 w-100 w-sm-auto">
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
            <a href="{{ route('teacher.attendances.create') }}" class="btn btn-primary shadow-sm">Record Attendance</a>
        </div>
    </div>

    <!-- Attendance Overview -->
    <div class="card shadow-sm mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-white p-0">
            <div class="px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Attendance Overview for S.Y. {{ $currentSchoolYear ?? 'Current School Year' }}</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="showCalendarBtn">View Calendar</button>
                </div>
            </div>
        </div>
        <div class="card-body bg-white">
            <!-- School Days Information -->
            <div class="row mb-4">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="d-flex align-items-center h-100">
                        <div class="school-days-counter text-center w-100">
                            <div class="display-1 fw-bold text-primary mb-2">{{ $schoolDays }}</div>
                            <p class="lead mb-0">Total School Days Recorded</p>
                            <p class="text-muted">Unique days with attendance records</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="rounded  bg-light shadow-0">
                        <div class="p-4">
                            <h6 class="card-title">About School Days</h6>
                            <p class="card-text">School days are automatically recorded when attendance is taken. Each date is counted only once, regardless of how many students or sections have attendance records for that day.</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                            <a href="{{ route('teacher.attendances.monthly-summary') }}" class="btn btn-primary w-sm-auto">Monthly Summary</a>
                            <a href="{{ route('teacher.attendances.weekly-summary') }}" class="btn btn-outline-primary w-sm-auto">Weekly Summary</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- School Days Calendar Modal -->
            <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content modal-mobile-fixed-header">
                        <div class="modal-header bg-white p-0 position-sticky top-0 start-0 end-0 z-index-1020">
                            <div class="px-4 py-3 w-100 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">School Days Calendar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body overflow-auto">
                            <div id="schoolDaysCalendar" class="calendar-container"></div>
                        </div>
                        <div class="modal-footer position-sticky bottom-0 start-0 end-0 z-index-1020 bg-white">
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-2" style="width: 20px; height: 20px;"></span>
                                        <span>School Days</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="border border-primary me-2" style="width: 20px; height: 20px;"></span>
                                        <span>Current Day</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary text-white me-2 position-relative" style="width: 20px; height: 20px;">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">!</span>
                                        </span>
                                        <span>Today (School Day)</span>
                                    </div>
                                </div>
                                <div class="small text-muted mb-3">Only months with attendance records are displayed in the calendar.</div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Compact Filter Form -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('teacher.attendances.index') }}" class="row g-3 align-items-end">
                            <!-- Mobile Filter Toggle -->
                            <div class="col-12 d-md-none mb-2">
                                <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilters" aria-expanded="false" aria-controls="mobileFilters">
                                    Show/Hide Filters
                                </button>
                            </div>

                            <div class="collapse d-md-flex" id="mobileFilters">
                                <div class="row g-3 w-100">
                                    <div class="col-12 col-md-3 col-lg-2">
                                        <div class="form-floating">
                                            <select class="form-select" id="section_id" name="section_id">
                                                <option value="">All Sections</option>
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                        {{ $section->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="section_id">Section</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 col-lg-2">
                                        <div class="form-floating">
                                            <select class="form-select" id="month" name="month">
                                                <option value="">All Months</option>
                                                @foreach($availableMonths as $month)
                                                    <option value="{{ $month->month_value }}" {{ request('month') == $month->month_value ? 'selected' : '' }}>
                                                        {{ $month->month_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="month">Month</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 col-lg-2">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                                            <label for="date">Specific Date</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 col-lg-2 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                                        <a href="{{ route('teacher.attendances.index') }}" 
                                            class="btn btn-outline-secondary d-flex justify-content-center align-items-center" 
                                            title="Reset Filters">
                                                Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Monthly Summary Card -->
                    @if(request('month'))
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white p-0">
                            <div class="px-4 py-3 border-bottom">
                                <h5 class="mb-0">Monthly Attendance Summary: {{ \Carbon\Carbon::createFromFormat('Y-m', request('month'))->format('F Y') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                @php
                                    $totalPresent = 0;
                                    $totalLate = 0;
                                    $totalAbsent = 0;
                                    $totalExcused = 0;
                                    $totalHalfDay = 0;
                                    $totalStudents = 0;

                                    if (is_array($attendances)) {
                                        foreach ($attendances as $dateGroup) {
                                            if (is_array($dateGroup)) {
                                                foreach ($dateGroup as $attendance) {
                                                    if (is_array($attendance)) {
                                                        $totalPresent += isset($attendance['present_count']) ? $attendance['present_count'] : 0;
                                                        $totalLate += isset($attendance['late_count']) ? $attendance['late_count'] : 0;
                                                        $totalAbsent += isset($attendance['absent_count']) ? $attendance['absent_count'] : 0;
                                                        $totalExcused += isset($attendance['excused_count']) ? $attendance['excused_count'] : 0;
                                                        $totalHalfDay += isset($attendance['half_day_count']) ? $attendance['half_day_count'] : 0;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $totalStudents = $totalPresent + $totalLate + $totalAbsent + $totalExcused + $totalHalfDay;
                                    $attendanceRate = $totalStudents > 0 ?
                                        round((($totalPresent + $totalLate + ($totalHalfDay * 0.5)) / $totalStudents) * 100, 1) : 0;
                                @endphp

                                <!-- Overall Attendance Rate -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-3">Overall Attendance Rate</h6>
                                            <div class="d-flex justify-content-center">
                                                <div class="position-relative" style="width: 150px; height: 150px;">
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <h2 class="mb-0 fw-bold">{{ $attendanceRate }}%</h2>
                                                    </div>
                                                    <svg width="150" height="150" viewBox="0 0 36 36">
                                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f2f2f2" stroke-width="2.5"></circle>
                                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="var(--primary-color)" stroke-width="2.5"
                                                                stroke-dasharray="{{ $attendanceRate * 0.01 * 100 }} 100"
                                                                stroke-dashoffset="25" class="progress-circle"></circle>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="text-muted mt-2">
                                                <div>Total School Days: {{ is_array($attendances) ? count(array_keys($attendances)) : 0 }}</div>
                                                <div>Total Records: {{ $totalStudents }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Breakdown -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-muted mb-3 text-center">Attendance Breakdown</h6>
                                            <div class="d-flex flex-column gap-3 mt-4">
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Present</span>
                                                        <span class="fw-medium">{{ $totalPresent }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalPresent / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Late</span>
                                                        <span class="fw-medium">{{ $totalLate }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalLate / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Half Day</span>
                                                        <span class="fw-medium">{{ $totalHalfDay }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalHalfDay / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Absent</span>
                                                        <span class="fw-medium">{{ $totalAbsent }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalAbsent / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Excused</span>
                                                        <span class="fw-medium">{{ $totalExcused }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-secondary" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalExcused / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Streamlined Attendance Records -->
                    <div class="attendance-records mb-4">
                        <div class="card-header bg-white p-0 mb-3">
                            <div class="py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 fw-semibold">School Day Attendance Records</h5>
                                        <small class="text-muted">A summary of attendance logs for each school day</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ is_array($attendances) ? count(array_keys($attendances)) : 0 }} School Days
                                    </span>
                                </div>
                            </div>
                        </div>


                        <!-- Desktop Table View -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="ps-4">School Day & Section</th>
                                        <th class="text-center" width="40%">Attendance Summary</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-end pe-4" width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (is_array($attendances) ? $attendances : [] as $date => $dateGroup)
                                        @foreach (is_array($dateGroup) ? $dateGroup : [] as $sectionId => $attendance)
                                            @php
                                                $present_count = isset($attendance['present_count']) ? $attendance['present_count'] : 0;
                                                $late_count = isset($attendance['late_count']) ? $attendance['late_count'] : 0;
                                                $absent_count = isset($attendance['absent_count']) ? $attendance['absent_count'] : 0;
                                                $excused_count = isset($attendance['excused_count']) ? $attendance['excused_count'] : 0;
                                                $half_day_count = isset($attendance['half_day_count']) ? $attendance['half_day_count'] : 0;

                                                $totalStudents = $present_count + $late_count + $absent_count + $excused_count + $half_day_count;
                                                $attendanceRate = $totalStudents > 0 ?
                                                    round((($present_count + $late_count + ($half_day_count * 0.5)) / $totalStudents) * 100, 1) : 0;
                                            @endphp
                                            <tr class="border-bottom">
                                                <td class="ps-4">
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-medium">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                                                        <span class="text-muted small">{{ isset($attendance['section_name']) ? $attendance['section_name'] : 'Unknown' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                        <div class="attendance-stat">
                                                            <span class="badge bg-success rounded-pill">{{ $present_count }}</span>
                                                            <span class="stat-label">Present</span>
                                                        </div>
                                                        <div class="attendance-stat">
                                                            <span class="badge bg-danger rounded-pill">{{ $absent_count }}</span>
                                                            <span class="stat-label">Absent</span>
                                                        </div>
                                                        <div class="attendance-stat">
                                                            <span class="badge bg-warning text-dark rounded-pill">{{ $late_count }}</span>
                                                            <span class="stat-label">Late</span>
                                                        </div>
                                                        <div class="attendance-stat d-none d-md-flex">
                                                            <span class="badge bg-info text-dark rounded-pill">{{ $half_day_count }}</span>
                                                            <span class="stat-label">Half</span>
                                                        </div>
                                                        <div class="attendance-stat d-none d-md-flex">
                                                            <span class="badge bg-secondary rounded-pill">{{ $excused_count }}</span>
                                                            <span class="stat-label">Excused</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <div class="progress flex-grow-0" style="height: 8px; width: 60px;">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: {{ isset($attendanceRate) ? $attendanceRate : 0 }}%;"
                                                                aria-valuenow="{{ isset($attendanceRate) ? $attendanceRate : 0 }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <span class="fw-medium">{{ $attendanceRate }}%</span>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-flex gap-1 justify-content-end">
                                                        <a href="{{ route('teacher.attendances.edit', ['attendance' => $sectionId, 'date' => $date]) }}"
                                                           class="btn btn-sm btn-primary rounded-pill px-2 py-1"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit">
                                                            Edit
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <div class="empty-state">
                                                    <p class="mb-3">No attendance records found</p>
                                                    <a href="{{ route('teacher.attendances.create') }}" class="btn btn-primary">Record New Attendance</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="d-md-none">
                            @forelse (is_array($attendances) ? $attendances : [] as $date => $dateGroup)
                                @foreach (is_array($dateGroup) ? $dateGroup : [] as $sectionId => $attendance)
                                    @php
                                        $present_count = isset($attendance['present_count']) ? $attendance['present_count'] : 0;
                                        $late_count = isset($attendance['late_count']) ? $attendance['late_count'] : 0;
                                        $absent_count = isset($attendance['absent_count']) ? $attendance['absent_count'] : 0;
                                        $excused_count = isset($attendance['excused_count']) ? $attendance['excused_count'] : 0;
                                        $half_day_count = isset($attendance['half_day_count']) ? $attendance['half_day_count'] : 0;

                                        $totalStudents = $present_count + $late_count + $absent_count + $excused_count + $half_day_count;
                                        $attendanceRate = $totalStudents > 0 ?
                                            round((($present_count + $late_count + ($half_day_count * 0.5)) / $totalStudents) * 100, 1) : 0;
                                    @endphp
                                    <div class="card shadow-sm mb-3">
                                        <div class="card-header bg-light py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h6>
                                                    <small class="text-muted">{{ isset($attendance['section_name']) ? $attendance['section_name'] : 'Unknown' }}</small>
                                                </div>
                                                <div>
                                                    <a href="{{ route('teacher.attendances.edit', ['attendance' => $sectionId, 'date' => $date]) }}"
                                                       class="btn btn-sm btn-primary rounded-pill px-3">
                                                       Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Attendance Rate:</span>
                                                <span class="fw-medium">{{ $attendanceRate }}%</span>
                                            </div>
                                            <div class="progress mb-3" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                     style="width: {{ $attendanceRate }}%"
                                                     aria-valuenow="{{ $attendanceRate }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                            <div class="row g-2 text-center">
                                                <div class="col-4">
                                                    <div class="p-2 border rounded">
                                                        <div class="badge bg-success rounded-pill mb-1">{{ $present_count }}</div>
                                                        <div class="small">Present</div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="p-2 border rounded">
                                                        <div class="badge bg-danger rounded-pill mb-1">{{ $absent_count }}</div>
                                                        <div class="small">Absent</div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="p-2 border rounded">
                                                        <div class="badge bg-warning text-dark rounded-pill mb-1">{{ $late_count }}</div>
                                                        <div class="small">Late</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @empty
                                <div class="text-center py-5">
                                    <h5 class="fw-normal">No attendance records found</h5>
                                    <p class="text-muted">Try adjusting your filters or create a new attendance record.</p>
                                    <a href="{{ route('teacher.attendances.create') }}" class="btn btn-primary mt-2">Record New Attendance</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // School Days Calendar functionality
        const showCalendarBtn = document.getElementById('showCalendarBtn');
        const calendarModal = new bootstrap.Modal(document.getElementById('calendarModal'));
        const schoolDaysCalendar = document.getElementById('schoolDaysCalendar');

        // School day dates and available months from PHP
        const schoolDayDates = @json($schoolDayDates);
        const availableMonths = @json($availableMonths);

        if (showCalendarBtn && schoolDaysCalendar) {
            // Initialize the calendar when the button is clicked
            showCalendarBtn.addEventListener('click', function() {
                initializeCalendar();
                calendarModal.show();
            });

            function initializeCalendar() {
                // Clear previous calendar if any
                schoolDaysCalendar.innerHTML = '';

                // Get current date info
                const currentDate = new Date();
                const currentYear = currentDate.getFullYear();
                const currentMonth = currentDate.getMonth();

                // Create calendar for the current school year
                const startMonth = 5; // June (0-indexed)
                const endMonth = 2;   // March (0-indexed)

                // Create a container for all months
                const calendarContainer = document.createElement('div');
                calendarContainer.className = 'row g-4 calendar-container';

                // Only show months that have attendance records
                let monthsToShow = [];

                // Process available months from PHP
                if (availableMonths && availableMonths.length > 0) {
                    monthsToShow = availableMonths.map(item => {
                        return {
                            year: parseInt(item.year),
                            month: parseInt(item.month) - 1 // Convert from 1-indexed to 0-indexed
                        };
                    });

                    monthsToShow.sort((a, b) => {
                        if (a.year !== b.year) return a.year - b.year;
                        return a.month - b.month;
                    });
                } else {
                    monthsToShow.push({ year: currentYear, month: currentMonth });
                }

                // Check if we have any months to show
                if (monthsToShow.length > 0) {
                    monthsToShow.forEach(({ year, month }) => {
                        const monthCalendar = createMonthCalendar(year, month);
                        calendarContainer.appendChild(monthCalendar);
                    });

                    schoolDaysCalendar.appendChild(calendarContainer);
                } else {
                    const noRecordsMessage = document.createElement('div');
                    noRecordsMessage.className = 'alert alert-info text-center my-4';
                    noRecordsMessage.innerHTML = `
                        <h5>No Attendance Records Found</h5>
                        <p class="mb-0">There are no months with attendance records to display.</p>
                        <a href="{{ route('teacher.attendances.create') }}" class="btn btn-primary mt-3">Record New Attendance</a>
                    `;
                    schoolDaysCalendar.appendChild(noRecordsMessage);
                }
            }

            function createMonthCalendar(year, month) {
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const firstDay = new Date(year, month, 1).getDay();
                const currentDate = new Date();
                const isCurrentMonth = year === currentDate.getFullYear() && month === currentDate.getMonth();

                // Create month container
                const monthContainer = document.createElement('div');
                monthContainer.className = 'col-md-4 mb-4';

                // Create month card
                const monthCard = document.createElement('div');
                monthCard.className = isCurrentMonth ? 'card border-primary shadow h-100' : 'card border-0 shadow-sm h-100';

                // Create month header
                const monthHeader = document.createElement('div');
                monthHeader.className = isCurrentMonth ? 'card-header bg-primary text-white py-2' : 'card-header bg-white py-2';
                monthHeader.innerHTML = `<h6 class="mb-0">${monthNames[month]} ${year}${isCurrentMonth ? ' (Current)' : ''}</h6>`;

                // Create calendar body
                const calendarBody = document.createElement('div');
                calendarBody.className = 'card-body p-2';

                // Create table for calendar
                const table = document.createElement('table');
                table.className = 'table table-sm table-bordered mb-0';

                // Create table header with day names
                const thead = document.createElement('thead');
                thead.innerHTML = `
                    <tr class="text-center">
                        <th>Su</th>
                        <th>Mo</th>
                        <th>Tu</th>
                        <th>We</th>
                        <th>Th</th>
                        <th>Fr</th>
                        <th>Sa</th>
                    </tr>
                `;

                // Create table body with dates
                const tbody = document.createElement('tbody');
                let date = 1;
                let html = '';

                // Create calendar rows
                for (let i = 0; i < 6; i++) {
                    if (date > daysInMonth) break;

                    html += '<tr class="text-center">';

                    for (let j = 0; j < 7; j++) {
                        if ((i === 0 && j < firstDay) || date > daysInMonth) {
                            html += '<td></td>';
                        } else {
                            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                            const isSchoolDay = schoolDayDates.includes(formattedDate);
                            const currentDate = new Date();
                            const isCurrentDate = year === currentDate.getFullYear() &&
                                                month === currentDate.getMonth() &&
                                                date === currentDate.getDate();

                            if (isCurrentDate && isSchoolDay) {
                                html += `<td class="bg-primary text-white position-relative"><strong>${date}</strong><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">Today</span></td>`;
                            } else if (isCurrentDate) {
                                html += `<td class="border border-primary position-relative"><strong>${date}</strong><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">Today</span></td>`;
                            } else if (isSchoolDay) {
                                html += `<td class="bg-primary text-white">${date}</td>`;
                            } else {
                                html += `<td>${date}</td>`;
                            }

                            date++;
                        }
                    }

                    html += '</tr>';
                }

                tbody.innerHTML = html;

                // Assemble the table
                table.appendChild(thead);
                table.appendChild(tbody);
                calendarBody.appendChild(table);

                // Assemble the card
                monthCard.appendChild(monthHeader);
                monthCard.appendChild(calendarBody);
                monthContainer.appendChild(monthCard);

                return monthContainer;
            }
        }
    });
</script>
@endpush
@endsection