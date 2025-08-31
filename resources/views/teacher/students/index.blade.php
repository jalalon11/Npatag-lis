@extends('layouts.app')

@push('styles')
<style>
    /* Existing variables and modal fixes remain unchanged */
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --info-color: #4895ef;
        --warning-color: #f72585;
        --danger-color: #e63946;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --gray-800: #343a40;
        --gray-900: #212529;
        --font-family: 'Inter', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        --border-radius: 0.5rem;
        --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --transition: all 0.2s ease-in-out;
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

    .modal {
        z-index: 1055 !important;
    }
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

    .modal-backdrop {
        z-index: 1054 !important;
    }

    .modal-content, .modal-dialog {
        position: relative;
        z-index: 1056 !important;
    }

    /* Modified Stat Cards - Smaller Height */
    .counter-card {
        border-radius: var(--border-radius);
        padding: 1rem;
        color: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
        height: 120px; /* Reduced height */
    }

    .counter-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.18);
    }

    .counter-card::before, .counter-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.12);
        clip-path: circle(60px at 85% 25%);
        z-index: -1;
    }

    .counter-card::after {
        top: -20px;
        right: -20px;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .counter-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.95;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .counter-value {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .counter-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .counter-subtitle {
        font-size: 0.75rem;
        opacity: 0.85;
    }

    /* Sidebar Styles */
    .sidebar {
        border-radius: var(--border-radius);
        height: fit-content;
    }

    /* Search and Filter Enhancements */
    .search-filter-card {
        margin-bottom: 1.5rem;
    }

    .search-wrapper {
        position: relative;
    }

    .search-input {
        padding-left: 3rem;
        height: 2.5rem;
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-300);
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-500);
        font-size: 1rem;
    }

    .filter-dropdown {
        height: 2.5rem;
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-300);
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .filter-dropdown:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    /* Gender Distribution Card */
    .gender-distribution-card {
        border-radius: var(--border-radius);
        background: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--gray-200);
    }

    .gender-distribution-header {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .gender-distribution-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0;
        display: flex;
        align-items: center;
    }

    .gender-distribution-body {
        padding: 1rem;
    }

    .gender-stat {
        border-radius: 8px;
        padding: 0.75rem;
        background-color: rgba(67, 97, 238, 0.05);
        margin-bottom: 0.75rem;
        border: 1px solid rgba(67, 97, 238, 0.1);
    }

    .gender-stat:last-child {
        margin-bottom: 0;
    }

    .gender-stat-label {
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.4rem;
    }

    .gender-stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--gray-800);
    }

    /* Table Styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        border: 1px solid var(--gray-200);
    }

    .table th {
        font-weight: 600;
        color: var(--gray-700);
    }

    .table td {
        vertical-align: middle;
        border-color: var(--gray-200);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.03);
    }

    .student-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        margin-right: 10px;
        border: 2px solid rgba(255,255,255,0.9);
    }

    /* Dark mode adjustments (simplified) */
    .dark .sidebar, .dark .table, .dark .gender-distribution-card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .search-input, .dark .filter-dropdown {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .search-input:focus, .dark .filter-dropdown:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .dark .table th {
        background-color: var(--bg-card-header);
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .table td {
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .gender-stat {
        background-color: rgba(67, 97, 238, 0.1);
        border-color: rgba(67, 97, 238, 0.2);
    }

    .dark .gender-stat-value {
        color: var(--text-color);
    }
        /* Button Styles */

</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex flex-column d-sm-flex flex-sm-row align-items-center justify-content-between mb-4">
        <div class="text-center text-sm-start mb-4 mb-sm-0">
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                Student Management
            </h1>
            <p class="text-muted mb-0">Manage, monitor, and organize your student records effectively</p>
        </div>
        <div class="d-flex justify-content-center justify-content-sm-end gap-2">
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-home me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <div class="p-2 me-3 bg-success bg-opacity-25 rounded-circle">
                    <i class="fas fa-check text-success"></i>
                </div>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <div class="p-2 me-3 bg-danger bg-opacity-25 rounded-circle">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                </div>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stat Cards -->
    @if($students->count() > 0)
        <div class="row mb-1">
            <div class="col-md-4 mb-3">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-body">
                        <div class="">Total Students</div>
                        <div class="text-primary fs-2 fw-bold">{{ $students->count() }}</div>
                        <div class="text-muted">Across all sections</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-body">
                        <div class="">Active Sections</div>
                        <div class="text-primary fs-2 fw-bold">{{ $students->pluck('section.name')->unique()->count() }}</div>
                        <div class="text-muted">Under your supervision</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-body">
                        <div class="">Grade Levels</div>
                        <div class="text-primary fs-2 fw-bold">{{ $students->pluck('section.grade_level')->unique()->count() }}</div>
                        <div class="text-muted">Active curriculum levels</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content with Sidebar -->
        <div class="row">
            <!-- Sidebar: Search, Filters, and Gender Distribution -->
            <div class="col-lg-3 mb-4">
                <div class="sidebar">
                    <!-- Search and Filter Section -->
                    <div class="search-filter-card card border-0 shadow-sm ">
                        <div class="card-body bg-white">
                            <div class=" d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="fas fa-filter text-primary me-2"></i> Search & Filter</h6>
                                <button id="resetFiltersBtn" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-redo-alt me-1"></i> Reset
                                </button>
                            </div>
                            <div class="mb-3">
                                <label for="studentSearch" class="form-label fw-medium mb-2">Search Students</label>
                                <div class="search-wrapper">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" id="studentSearch" class="form-control search-input" placeholder="Search by name or ID...">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="gradeFilter" class="form-label fw-medium mb-2">Grade Level</label>
                                <select id="gradeFilter" class="form-select filter-dropdown">
                                    <option value="">All Grade Levels</option>
                                    @foreach($students->pluck('section.grade_level')->unique()->sort() as $gradeLevel)
                                        <option value="{{ $gradeLevel }}">Grade {{ $gradeLevel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sectionFilter" class="form-label fw-medium mb-2">Section</label>
                                <select id="sectionFilter" class="form-select filter-dropdown">
                                    <option value="">All Sections</option>
                                    @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="statusFilter" class="form-label fw-medium mb-2">Status</label>
                                <select id="statusFilter" class="form-select filter-dropdown">
                                    <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="disabled" {{ $statusFilter === 'disabled' ? 'selected' : '' }}>Disabled</option>
                                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Gender Distribution -->
                    <div class="gender-distribution-card">
                        <div class="gender-distribution-header">
                            <h6 class="gender-distribution-title">
                                <span class="bg-primary bg-opacity-10 p-2 rounded-circle me-2 text-primary d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    <i class="fas fa-venus-mars"></i>
                                </span>
                                Gender Distribution
                            </h6>
                        </div>
                        <div class="gender-distribution-body">
                            <div class="mb-3">
                                <select id="genderSectionFilter" class="form-select filter-dropdown">
                                    <option value="all">All Sections</option>
                                    @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @php
                                $maleCount = $students->filter(function($student) {
                                    return strtolower($student->gender) === 'male';
                                })->count();
                                $femaleCount = $students->filter(function($student) {
                                    return strtolower($student->gender) === 'female';
                                })->count();
                                $totalStudents = $students->count();
                                $malePercentage = $totalStudents > 0 ? round(($maleCount / $totalStudents) * 100) : 0;
                                $femalePercentage = $totalStudents > 0 ? round(($femaleCount / $totalStudents) * 100) : 0;
                            @endphp
                            <div id="gender-stats-container">
                                <div class="gender-stat">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="gender-stat-label">
                                            <i class="fas fa-male text-primary me-2"></i> Male Students
                                        </div>
                                        <div class="gender-stat-value">{{ $maleCount }}</div>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $malePercentage }}%"></div>
                                    </div>
                                    <div class="text-end mt-1">
                                        <small class="text-muted">{{ $malePercentage }}%</small>
                                    </div>
                                </div>
                                <div class="gender-stat">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="gender-stat-label">
                                            <i class="fas fa-female text-danger me-2"></i> Female Students
                                        </div>
                                        <div class="gender-stat-value">{{ $femaleCount }}</div>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $femalePercentage }}%"></div>
                                    </div>
                                    <div class="text-end mt-1">
                                        <small class="text-muted">{{ $femalePercentage }}%</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Student Table -->
            <div class="col-lg-9">
                @php
                    $studentsByGradeLevel = $students->groupBy(function ($student) {
                        return $student->section->grade_level ?? 'Unassigned';
                    })->sortKeys();
                @endphp

                @foreach($studentsByGradeLevel as $gradeLevel => $gradeStudents)
                    @php
                        $studentsBySection = $gradeStudents->groupBy(function ($student) {
                            return $student->section->name ?? 'Unassigned';
                        })->sortKeys();
                    @endphp

                    <div class="grade-level-container section-content" id="grade-content-{{ $gradeLevel }}" data-grade="{{ $gradeLevel }}">
                        @foreach($studentsBySection as $sectionName => $sectionStudents)
                            @php
                                $sectionId = $sectionStudents->first()->section->id ?? 'section-' . Str::slug($sectionName);
                            @endphp
                            <div class="section-header shadow-sm rounded mb-3 bg-white p-2" data-section-id="{{ $sectionId }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 d-flex align-items-center">
                                        <span class="bg-primary bg-opacity-10 p-2 rounded me-2 text-primary">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        {{ $sectionName }}
                                        <span class="badge bg-primary ms-2 badge-count">{{ $sectionStudents->count() }} students</span>
                                    </h5>
                                </div>
                            </div>

                            <div class="section-content" id="section-content-{{ $sectionId }}">
                                <div class="card table-responsive bg-white mb-4">
                                    <table class="table table-hover bg-white" data-grade="{{ $gradeLevel }}" data-section-id="{{ $sectionId }}" style="background-color: white;">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 30%">Student</th>
                                                <th>ID</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>LRN</th>
                                                <th>Guardian</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sectionStudents as $student)
                                                <tr class="student-item {{ !$student->is_active ? 'table-secondary' : '' }}"
                                                    data-name="{{ strtolower($student->last_name . ' ' . $student->first_name) }}"
                                                    data-student-id="{{ strtolower($student->student_id) }}"
                                                    {{ !$student->is_active ? 'data-bs-toggle="tooltip" data-bs-placement="left" title="This student is disabled"' : '' }}>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @php
                                                                $avatarColors = [
                                                                    'bg-primary' => '#4361ee',
                                                                    'bg-success' => '#4cc9f0',
                                                                    'bg-info' => '#4895ef',
                                                                    'bg-warning' => '#f72585',
                                                                    'bg-danger' => '#e63946'
                                                                ];
                                                                $hash = crc32($student->id . $student->first_name);
                                                                $colorIndex = abs($hash) % count($avatarColors);
                                                                $colorKey = array_keys($avatarColors)[$colorIndex];
                                                                $bgColor = $avatarColors[$colorKey];
                                                            @endphp
                                                            <div class="student-avatar" style="background-color: {{ $bgColor }}; {{ !$student->is_active ? 'opacity: 0.6;' : '' }}">
                                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 fw-bold">
                                                                    {{ $student->last_name }}, {{ $student->first_name }}
                                                                    @if(!$student->is_active)
                                                                        <span class="badge bg-secondary ms-1">Disabled</span>
                                                                    @endif
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $student->student_id }}</td>
                                                    <td>{{ $student->gender }}</td>
                                                    <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->age : 'N/A' }}</td>
                                                    <td>{{ $student->lrn ?? 'N/A' }}</td>
                                                    <td>{{ $student->guardian_name ?: 'Not specified' }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary me-1 btn-action" title="View Student">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning me-1 btn-action" title="Edit Student">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($student->is_active)
                                                            <button type="button" class="btn btn-sm btn-outline-warning btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}" title="Disable Student">
                                                                <i class="fas fa-user-slash"></i>
                                                            </button>
                                                        @else
                                                            <form action="{{ route('teacher.students.reactivate', $student->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-success btn-action" title="Reactivate Student">
                                                                    <i class="fas fa-user-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <!-- Assigned Subjects Sections -->
                @if($assignedStudents->count() > 0)
                    <div class="assigned-section-header mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0 d-flex align-items-center">
                                <span class="bg-white bg-opacity-25 p-2 rounded-circle me-3 text-white">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </span>
                                Assigned Subject Sections
                            </h4>
                        </div>
                        <p class="mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Sections where you teach subjects but are not the adviser.
                        </p>
                    </div>

                    @php
                        $assignedStudentsBySection = $assignedStudents->groupBy(function ($student) {
                            return $student->section_id;
                        });
                    @endphp

                    @foreach($assignedSections as $section)
                        @if(isset($assignedStudentsBySection[$section->id]))
                            <div class="section-header shadow-sm border-start border-primary border-4 rounded mb-3" data-section-id="{{ $section->id }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 d-flex align-items-center">
                                        <span class="bg-primary bg-opacity-10 p-2 rounded-circle me-2 text-primary">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        Section: {{ $section->name }} {{ $section->grade_level }}
                                        <span class="badge bg-primary ms-2 badge-count">{{ $assignedStudentsBySection[$section->id]->count() }} students</span>
                                    </h5>
                                    <button class="btn btn-sm toggle-section-btn rounded-pill" data-section="{{ $section->id }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="section-content" id="section-content-{{ $section->id }}">
                                <div class="table-responsive mb-4">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 30%">Student</th>
                                                <th>ID</th>
                                                <th>Gender</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assignedStudentsBySection[$section->id] as $student)
                                                <tr class="{{ !$student->is_active ? 'table-secondary' : '' }}"
                                                    {{ !$student->is_active ? 'data-bs-toggle="tooltip" data-bs-placement="left" title="This student is disabled"' : '' }}>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @php
                                                                $avatarColors = [
                                                                    'bg-primary' => '#4361ee',
                                                                    'bg-success' => '#4cc9f0',
                                                                    'bg-info' => '#4895ef',
                                                                    'bg-warning' => '#f72585',
                                                                    'bg-danger' => '#e63946'
                                                                ];
                                                                $hash = crc32($student->id . $student->first_name);
                                                                $colorIndex = abs($hash) % count($avatarColors);
                                                                $colorKey = array_keys($avatarColors)[$colorIndex];
                                                                $bgColor = $avatarColors[$colorKey];
                                                            @endphp
                                                            <div class="student-avatar" style="background-color: {{ $bgColor }}; {{ !$student->is_active ? 'opacity: 0.6;' : '' }}">
                                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 fw-bold">
                                                                    {{ $student->last_name }}, {{ $student->first_name }}
                                                                    @if(!$student->is_active)
                                                                        <span class="badge bg-secondary ms-1">Disabled</span>
                                                                    @endif
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $student->student_id }}</td>
                                                    <td>{{ $student->gender }}</td>
                                                    <td class="text-end">
                                                        @if(count($assignedSubjectsBySection[$section->id] ?? []) > 1)
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm {{ !$student->is_active ? 'btn-secondary' : 'btn-primary' }} dropdown-toggle"
                                                                        type="button" id="dropdownMenu-{{ $student->id }}"
                                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                                        {{ !$student->is_active ? 'data-bs-toggle="tooltip" data-bs-placement="top" title="This student is disabled"' : '' }}>
                                                                    View Subject Grades
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenu-{{ $student->id }}">
                                                                    @foreach($assignedSubjectsBySection[$section->id] ?? [] as $subject)
                                                                        <li>
                                                                            <a class="dropdown-item {{ !$student->is_active ? 'text-muted' : '' }}" href="{{ route('teacher.students.show', [
                                                                                'student' => $student->id,
                                                                                'from_assigned' => 1,
                                                                                'subject_id' => $subject->id
                                                                            ]) }}">
                                                                                {{ $subject->name }} ({{ $subject->code }})
                                                                                @if(!$student->is_active)
                                                                                    <i class="fas fa-user-slash ms-1 small"></i>
                                                                                @endif
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @elseif(count($assignedSubjectsBySection[$section->id] ?? []) == 1)
                                                            @php $subject = $assignedSubjectsBySection[$section->id][0]; @endphp
                                                            <a href="{{ route('teacher.students.show', [
                                                                'student' => $student->id,
                                                                'from_assigned' => 1,
                                                                'subject_id' => $subject->id
                                                            ]) }}" class="btn btn-sm {{ !$student->is_active ? 'btn-secondary' : 'btn-primary' }}"
                                                               {{ !$student->is_active ? 'data-bs-toggle="tooltip" data-bs-placement="top" title="This student is disabled"' : '' }}>
                                                                View {{ $subject->name }} Grades
                                                                @if(!$student->is_active)
                                                                    <i class="fas fa-user-slash ms-1 small"></i>
                                                                @endif
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                <!-- No Results Message -->
                <div id="noResults" class="text-center py-5 d-none">
                    <div class="card shadow-sm border-0 p-4">
                        <div class="card-body empty-state">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                                <i class="fas fa-search text-primary fa-3x"></i>
                            </div>
                            <h4 class="text-primary">No Students Found</h4>
                            <p class="text-muted mb-4">Try adjusting your search or filter criteria.</p>
                            <button id="resetFilters" class="btn btn-primary px-4">
                                <i class="fas fa-undo me-1"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="card shadow-sm border-0 p-5">
                <div class="card-body empty-state">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-flex mb-3">
                        <i class="fas fa-user-graduate text-primary fa-3x"></i>
                    </div>
                    <h3 class="text-primary mb-3">No Students Found</h3>
                    <p class="text-muted mb-4">You haven't added any students to your sections yet.</p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const studentSearch = document.getElementById('studentSearch');
        const gradeFilter = document.getElementById('gradeFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const genderSectionFilter = document.getElementById('genderSectionFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const noResults = document.getElementById('noResults');
        const studentItems = document.querySelectorAll('.student-item');
        const studentSections = document.querySelectorAll('.student-section');
        const gradeLevelHeaders = document.querySelectorAll('.grade-level-header');
        const gradeLevelContainers = document.querySelectorAll('.grade-level-container');
        const sectionHeaders = document.querySelectorAll('.section-header');

        // Function to filter students
        function filterStudents() {
            const searchTerm = studentSearch.value.toLowerCase();
            const selectedGrade = gradeFilter.value;
            const selectedSection = sectionFilter.value;

            let visibleCount = 0;
            let visibleSections = new Set();
            let visibleGrades = new Set();

            studentItems.forEach(item => {
                const studentName = item.getAttribute('data-name');
                const studentId = item.getAttribute('data-student-id');
                const studentGrade = item.closest('.student-section').getAttribute('data-grade');
                const studentSectionId = item.closest('.student-section').getAttribute('data-section-id');

                const matchesSearch = searchTerm === '' ||
                    studentName.includes(searchTerm) ||
                    studentId.includes(searchTerm);
                const matchesGrade = selectedGrade === '' || studentGrade === selectedGrade;
                const matchesSection = selectedSection === '' || studentSectionId === selectedSection;

                if (matchesSearch && matchesGrade && matchesSection) {
                    item.classList.remove('d-none');
                    visibleCount++;
                    visibleSections.add(studentSectionId);
                    visibleGrades.add(studentGrade);
                } else {
                    item.classList.add('d-none');
                }
            });

            studentSections.forEach(section => {
                const sectionId = section.getAttribute('data-section-id');
                const hasVisibleStudents = visibleSections.has(sectionId);
                section.closest('.section-content').classList.toggle('d-none', !hasVisibleStudents);
            });

            sectionHeaders.forEach(header => {
                const sectionId = header.getAttribute('data-section-id');
                const hasVisibleStudents = visibleSections.has(sectionId);
                header.classList.toggle('d-none', !hasVisibleStudents);
            });

            gradeLevelHeaders.forEach(header => {
                const grade = header.querySelector('h4').textContent.trim();
                const hasVisibleStudents = visibleGrades.has(grade);
                header.classList.toggle('d-none', !hasVisibleStudents);
            });

            gradeLevelContainers.forEach(container => {
                const grade = container.getAttribute('data-grade');
                const hasVisibleStudents = visibleGrades.has(grade);
                container.classList.toggle('d-none', !hasVisibleStudents);
            });

            noResults.classList.toggle('d-none', visibleCount > 0);
        }

        // Gender distribution update
        function updateGenderDistribution() {
            const selectedSectionId = genderSectionFilter.value;
            fetch('/teacher/students/gender-distribution?section_id=' + selectedSectionId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('gender-stats-container').innerHTML = `
                        <div class="gender-stat">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="gender-stat-label">
                                    <i class="fas fa-male text-primary me-2"></i> Male Students
                                </div>
                                <div class="gender-stat-value">${data.male_count}</div>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: ${data.male_percentage}%"></div>
                            </div>
                            <div class="text-end mt-1">
                                <small class="text-muted">${data.male_percentage}%</small>
                            </div>
                        </div>
                        <div class="gender-stat">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="gender-stat-label">
                                    <i class="fas fa-female text-danger me-2"></i> Female Students
                                </div>
                                <div class="gender-stat-value">${data.female_count}</div>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: ${data.female_percentage}%"></div>
                            </div>
                            <div class="text-end mt-1">
                                <small class="text-muted">${data.female_percentage}%</small>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error fetching gender distribution:', error);
                    document.getElementById('gender-stats-container').innerHTML = `
                        <div class="alert alert-danger">
                            <p>Unable to load gender distribution data.</p>
                        </div>
                    `;
                });
        }

        if (genderSectionFilter) {
            updateGenderDistribution();
            genderSectionFilter.addEventListener('change', updateGenderDistribution);
        }

        // Toggle section content
        document.querySelectorAll('.toggle-section-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-section');
                const contentEl = document.getElementById(`section-content-${sectionId}`);
                if (contentEl) {
                    contentEl.classList.toggle('collapsed');
                    this.classList.toggle('collapsed');
                    localStorage.setItem(`section-${sectionId}-collapsed`, contentEl.classList.contains('collapsed'));
                }
            });

            const sectionId = btn.getAttribute('data-section');
            const contentEl = document.getElementById(`section-content-${sectionId}`);
            if (localStorage.getItem(`section-${sectionId}-collapsed`) === 'true' && contentEl) {
                contentEl.classList.add('collapsed');
                btn.classList.add('collapsed');
            }
        });

        // Event listeners for filters
        if (studentSearch) studentSearch.addEventListener('input', filterStudents);
        if (gradeFilter) gradeFilter.addEventListener('change', filterStudents);
        if (sectionFilter) sectionFilter.addEventListener('change', filterStudents);
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                window.location.href = '{{ route('teacher.students.index') }}?status=' + this.value;
            });
        }
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', function() {
                studentSearch.value = '';
                gradeFilter.value = '';
                sectionFilter.value = '';
                genderSectionFilter.value = 'all';
                if (statusFilter.value !== 'active') {
                    window.location.href = '{{ route('teacher.students.index') }}?status=active';
                    return;
                }
                filterStudents();
                updateGenderDistribution();
            });
        }

        // Modal handling
        document.querySelectorAll('.modal').forEach(modalEl => {
            modalEl.addEventListener('shown.bs.modal', function() {
                this.style.zIndex = '1055';
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.style.zIndex = '1054';
                const dialog = this.querySelector('.modal-dialog');
                if (dialog) dialog.style.zIndex = '1056';
                const content = this.querySelector('.modal-content');
                if (content) content.style.zIndex = '1056';
            });
        });

        document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target^="#deleteModal"]').forEach(button => {
            button.addEventListener('click', function() {
                forceCloseAllModals();
                setTimeout(() => {
                    const targetId = this.getAttribute('data-bs-target');
                    const modalEl = document.querySelector(targetId);
                    if (modalEl) {
                        const modalInstance = new bootstrap.Modal(modalEl);
                        modalEl._bsModal = modalInstance;
                        modalInstance.show();
                        modalEl.style.display = 'block';
                        modalEl.style.zIndex = '1055';
                        const buttons = modalEl.querySelectorAll('button');
                        buttons.forEach(btn => {
                            btn.style.position = 'relative';
                            btn.style.zIndex = '1057';
                        });
                    }
                }, 50);
            });
        });

        function forceCloseAllModals() {
            const openModals = document.querySelectorAll('.modal');
            openModals.forEach(modal => {
                modal.classList.remove('show');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                modal.removeAttribute('aria-modal');
                modal.removeAttribute('role');
            });
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.parentNode.removeChild(backdrop));
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }

        document.querySelectorAll('.cancel-delete-btn, .close-delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modalEl = this.closest('.modal');
                if (modalEl && modalEl._bsModal) {
                    modalEl._bsModal.hide();
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                    modalEl.setAttribute('aria-hidden', 'true');
                    modalEl.removeAttribute('aria-modal');
                    modalEl.removeAttribute('role');
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => backdrop.parentNode.removeChild(backdrop));
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            });
        });
    });
</script>
@endpush

<!-- Student Delete Modals -->
@foreach($students as $student)
<div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">Confirm Disable Student</h5>
                <button type="button" class="btn-close close-delete-btn" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x"></i>
                    </div>
                    <h5>Are you sure you want to disable this student?</h5>
                </div>
                <div class="alert alert-warning">
                    <p class="mb-0"><strong>{{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }}</strong> will be disabled and will no longer appear in reports or grade entries.</p>
                </div>
                <p class="text-info small"><i class="fas fa-info-circle me-1"></i> The student's records will be preserved but hidden from reports.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-delete-btn">Cancel</button>
                <form action="{{ route('teacher.students.destroy', $student->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning">Yes, Disable Student</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection