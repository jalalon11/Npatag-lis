@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --border-radius: 12px;
        --border-radius-pill: 50px;
        --padding-sm: 1rem;
        --padding-md: 1.5rem;
        --margin-sm: 1rem;
        --margin-md: 1.5rem;
    }

    .card {
        border: none !important;
        border-radius: var(--border-radius) !important;
    }

    .card-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        border-bottom: none !important;
        padding: var(--padding-md) !important;
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
        transition: all 0.3s ease;
    }

    .action-btn {
        padding: 0.4rem 0.8rem;
        margin: 0 0.2rem;
    }

    .input-group .form-control, .input-group .btn, .input-group .input-group-text {
        border-radius: var(--border-radius-pill);
    }

    .table {
        border-radius: var(--border-radius);
        overflow: hidden;
        font-size: 1rem;
        color: #333;
    }

    .table thead {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 1;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
    }

    .table th, .table td {
        padding: var(--padding-sm);
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .table a.text-decoration-none {
        color: #0d6efd;
        font-weight: 500;
    }

    .table a.text-decoration-none:hover {
        text-decoration: underline !important;
    }

    .small {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .scrollable-table {
        max-height: 600px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
        position: relative;
    }

    .scrollable-table::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-table::-webkit-scrollbar-track {
        background: transparent;
    }

    .scrollable-table::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    .scrollable-table::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.3);
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }

    .stats-card.success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stats-card.warning {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stats-card.info {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .school-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Main Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <div>
            <h2 class="mb-1 fw-bold">Sections Management</h2>
            <p class="text-muted mb-0">Manage sections across all schools in the system</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportSections()">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $totalSections ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Total Sections</p>
                        </div>
                        <i class="fas fa-list-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $activeSections ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Active Sections</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $totalStudents ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Total Students</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $schoolsCount ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Schools</p>
                        </div>
                        <i class="fas fa-school fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-white d-flex align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-filter text-primary me-2"></i> Filters</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search sections...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select id="schoolFilter" class="form-select">
                        <option value="">All Schools</option>
                        @foreach($schools ?? [] as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="gradeLevelFilter" class="form-select">
                        <option value="">All Grade Levels</option>
                        @foreach(['K', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'] as $grade)
                            <option value="{{ $grade }}">Grade {{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="capacityFilter" class="form-select">
                        <option value="">All Capacity</option>
                        <option value="full">At Capacity</option>
                        <option value="available">Available Space</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections Table -->
    <div class="card border-0 animate__animated animate__fadeIn">
        <div class="card-header bg-white d-flex align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list text-primary me-2"></i> Sections List</h5>
        </div>
        <div class="card-body p-0">
            <div class="scrollable-table">
                <table class="table table-hover mb-0" id="sectionsTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Section</th>
                            <th>School</th>
                            <th>Grade</th>
                            <th>Adviser</th>
                            <th class="text-center">Students</th>
                            <th class="text-center">Capacity</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections ?? [] as $section)
                            <tr data-school-id="{{ $section->school_id }}" data-grade="{{ $section->grade_level }}" data-status="{{ $section->is_active ? 'Active' : 'Inactive' }}">
                                <td class="ps-4">
                                    <a href="{{ route('admin.sections.show', $section->id) }}"
                                       class="text-decoration-none">
                                        {{ $section->name }}
                                    </a>
                                    <div class="small text-muted">{{ $section->school_year }}</div>
                                </td>
                                <td>
                                    <span class="badge school-badge">{{ $section->school->name ?? 'Unknown School' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">Grade {{ $section->grade_level }}</span>
                                </td>
                                <td>
                                    @if($section->adviser)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $section->adviser->name }}</div>
                                                <div class="small text-muted">{{ $section->adviser->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $section->student_limit && $section->students_count >= $section->student_limit ? 'bg-warning text-dark' : 'bg-info' }}">
                                        {{ $section->students_count ?? 0 }}
                                        @if($section->student_limit && $section->students_count >= $section->student_limit)
                                            <i class="fas fa-exclamation-triangle ms-1" title="At capacity"></i>
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($section->student_limit)
                                        <span class="badge bg-secondary">{{ $section->student_limit }}</span>
                                        <div class="small text-muted">{{ $section->student_limit - ($section->students_count ?? 0) }} available</div>
                                    @else
                                        <span class="text-muted">No limit</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $section->is_active ? 'success' : 'danger' }}">
                                        {{ $section->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.sections.show', $section->id) }}"
                                       class="btn btn-sm btn-outline-primary action-btn"
                                       data-bs-toggle="tooltip" title="View Section">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.sections.edit', $section->id) }}"
                                       class="btn btn-sm btn-outline-secondary action-btn"
                                       data-bs-toggle="tooltip" title="Edit Section">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle action-btn" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.sections.students', $section->id) }}">
                                                <i class="fas fa-users me-2"></i>Manage Students
                                            </a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.sections.subjects', $section->id) }}">
                                                <i class="fas fa-book me-2"></i>Manage Subjects
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-warning" href="#" onclick="toggleStatus({{ $section->id }})">
                                                <i class="fas fa-{{ $section->is_active ? 'pause' : 'play' }} me-2"></i>{{ $section->is_active ? 'Deactivate' : 'Activate' }}
                                            </a></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $section->id }})">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light p-3 rounded-circle mb-3">
                                            <i class="fas fa-list-alt text-secondary fa-2x"></i>
                                        </div>
                                        <h5>No Sections Found</h5>
                                        <p class="text-muted">There are no sections in the system yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Search and filter functionality
document.getElementById('searchInput').addEventListener('input', filterSections);
document.getElementById('schoolFilter').addEventListener('change', filterSections);
document.getElementById('gradeLevelFilter').addEventListener('change', filterSections);
document.getElementById('statusFilter').addEventListener('change', filterSections);
document.getElementById('capacityFilter').addEventListener('change', filterSections);

function filterSections() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const schoolFilter = document.getElementById('schoolFilter').value;
    const gradeFilter = document.getElementById('gradeLevelFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const capacityFilter = document.getElementById('capacityFilter').value;
    
    const rows = document.querySelectorAll('#sectionsTable tbody tr');
    
    rows.forEach(row => {
        if (row.cells.length === 1) return; // Skip empty state row
        
        const sectionName = row.cells[0].textContent.toLowerCase();
        const schoolId = row.dataset.schoolId;
        const grade = row.dataset.grade;
        const status = row.dataset.status;
        
        const matchesSearch = sectionName.includes(searchTerm);
        const matchesSchool = !schoolFilter || schoolId === schoolFilter;
        const matchesGrade = !gradeFilter || grade === gradeFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        let matchesCapacity = true;
        if (capacityFilter) {
            const capacityCell = row.cells[5];
            const isAtCapacity = capacityCell.textContent.includes('0 available') || capacityCell.querySelector('.fa-exclamation-triangle');
            matchesCapacity = (capacityFilter === 'full' && isAtCapacity) || (capacityFilter === 'available' && !isAtCapacity);
        }
        
        if (matchesSearch && matchesSchool && matchesGrade && matchesStatus && matchesCapacity) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('schoolFilter').value = '';
    document.getElementById('gradeLevelFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('capacityFilter').value = '';
    filterSections();
}

function exportSections() {
    window.location.href = '{{ route("admin.sections.export") }}';
}

function toggleStatus(sectionId) {
    if (confirm('Are you sure you want to change the status of this section?')) {
        // Handle status toggle
        console.log('Toggle status for section:', sectionId);
    }
}

function confirmDelete(sectionId) {
    if (confirm('Are you sure you want to delete this section? This action cannot be undone.')) {
        // Handle deletion
        console.log('Delete section:', sectionId);
    }
}
</script>
@endsection