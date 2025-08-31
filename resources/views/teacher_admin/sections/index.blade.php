@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    /* Shared design tokens from manage-subjects */
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
        transition: var(--transition);
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
        max-height: 500px;
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

    .scrollable-table::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0));
        pointer-events: none;
        border-bottom-left-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
    }

    .modal-content {
        border-radius: var(--border-radius);
    }

    .modal-header, .modal-footer {
        border: none;
    }

    .modal-footer .btn {
        padding: 0.5rem 1.25rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Main Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <h2 class="mb-0 fw-bold">
            Manage Sections
        </h2>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Summary -->
    <div class="card border-0 mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-white d-flex align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-chart-pie text-primary me-2"></i> Section Summary</h5>
        </div>
        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-3 mb-3 border-bottom pb-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="fas fa-list-alt text-primary me-2"></i> Sections:</span>
                    <span class="fw-bold">{{ $sections->count() }}</span>
                    <span class="text-muted ms-2">({{ $sections->where('is_active', true)->count() }} active)</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="fas fa-users text-info me-2"></i> Students:</span>
                    <span class="fw-bold">{{ $sections->sum('students_count') }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="fas fa-graduation-cap text-warning me-2"></i> Grade Levels:</span>
                    <span class="fw-bold">{{ $sections->pluck('grade_level')->unique()->count() }}</span>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Section
                    </a>
                </div>
            </div>
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group" style="max-width: 400px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search sections...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select id="gradeLevelFilter" class="form-select">
                        <option value="">All Grade Levels</option>
                        @php
                            $school = Auth::user()->school;
                            $gradeLevels = [];
                            if ($school) {
                                $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels :
                                            (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);
                                sort($gradeLevels, SORT_NATURAL);
                            }
                        @endphp
                        @foreach($gradeLevels as $grade)
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
            </div>
        </div>
    </div>

    <!-- Sections Table -->
    <div class="card border-0 animate__animated animate__fadeIn">
        <div class="card-header bg-white d-flex align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list text-primary me-2"></i> Section List</h5>
        </div>
        <div class="card-body p-0">
            <div class="scrollable-table">
                <table class="table table-hover mb-0" id="sectionsTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Section</th>
                            <th>Grade</th>
                            <th>Adviser</th>
                            <th class="text-center">Students</th>
                            <th class="text-center">Student Limit</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('teacher-admin.sections.show', $section) }}"
                                       class="text-decoration-none">
                                        {{ $section->name }}
                                    </a>
                                    <div class="small text-muted">{{ $section->school_year }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $section->grade_level }}</span>
                                </td>
                                <td>
                                    @if($section->adviser)
                                        {{ $section->adviser->name }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $section->student_limit && $section->students_count >= $section->student_limit ? 'bg-warning text-dark' : 'bg-info' }}">
                                        {{ $section->students_count }}
                                        @if($section->student_limit && $section->students_count >= $section->student_limit)
                                            <i class="fas fa-exclamation-triangle ms-1" title="At capacity"></i>
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($section->student_limit)
                                        <span class="badge bg-secondary">{{ $section->student_limit }}</span>
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
                                    <a href="{{ route('teacher-admin.sections.show', $section) }}"
                                       class="btn btn-sm btn-outline-primary action-btn"
                                       data-bs-toggle="tooltip" title="View Section">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher-admin.sections.edit', $section) }}"
                                       class="btn btn-sm btn-outline-secondary action-btn"
                                       data-bs-toggle="tooltip" title="Edit Section">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger action-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $section->id }}"
                                            data-bs-toggle="tooltip" title="Delete Section">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $section->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Section</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>{{ $section->name }}</strong>?</p>
                                                    @if($section->students_count > 0)
                                                        <div class="alert alert-warning small">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            This section has {{ $section->students_count }} {{ Str::plural('student', $section->students_count) }} enrolled.
                                                        </div>
                                                    @endif
                                                    <p class="text-danger small">This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('teacher-admin.sections.destroy', $section) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="avatar bg-light p-3 mb-3">
                                        <i class="fas fa-folder-open text-secondary fa-2x"></i>
                                    </div>
                                    <h5 class="text-muted">No Sections Found</h5>
                                    <p class="text-muted">Add a new section to get started.</p>
                                    <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i> Add New Section
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sections->count() > 10)
                <div class="text-center py-2 border-top">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Scroll to see all {{ $sections->count() }} sections</small>
                </div>
            @endif
        </div>
    </div>

    <!-- No Results Message -->
    <div id="noResultsMessage" class="text-center py-5 d-none">
        <h5 class="text-muted">No Matching Sections</h5>
        <p class="text-muted">Try adjusting your search or filters.</p>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Table filtering function
    function filterTable() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const gradeValue = document.getElementById('gradeLevelFilter').value;
        const statusValue = document.getElementById('statusFilter').value;
        let hasResults = false;

        document.querySelectorAll('#sectionsTable tbody tr').forEach(row => {
            let shouldShow = true;

            // Search filter
            if (searchValue) {
                const textContent = row.textContent.toLowerCase();
                shouldShow = textContent.includes(searchValue);
            }

            // Grade filter
            if (shouldShow && gradeValue) {
                const gradeText = row.querySelector('td:nth-child(2)').textContent.trim();
                shouldShow = gradeText.includes(gradeValue);
            }

            // Status filter
            if (shouldShow && statusValue) {
                const statusText = row.querySelector('td:nth-child(6)').textContent.trim();
                shouldShow = statusText.includes(statusValue);
            }

            row.style.display = shouldShow ? '' : 'none';
            if (shouldShow) hasResults = true;
        });

        // Show/hide no results message
        const noResultsMessage = document.getElementById('noResultsMessage');
        if (!hasResults && (searchValue || gradeValue || statusValue)) {
            noResultsMessage.classList.remove('d-none');
        } else {
            noResultsMessage.classList.add('d-none');
        }
    }

    // Debounce search input
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterTable, 300);
    });

    // Apply filters on change
    document.getElementById('gradeLevelFilter').addEventListener('change', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);

    // Reset filters
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('gradeLevelFilter').value = '';
        document.getElementById('statusFilter').value = '';
        filterTable();
    });
});
</script>
@endpush
@endsection