@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    /* Shared design tokens from manage-sections */
    :root {
        --border-radius: 12px;
        --border-radius-pill: 50px;
        --padding-sm: 1rem;
        --padding-md: 1.5rem;
        --margin-sm: 1rem;
        --margin-md: 1.5rem;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card {
        border: none !important;
        border-radius: var(--border-radius) !important;
        transition: var(--transition);  
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

    .mapeh-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
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
            Manage Subjects
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
            <h5 class="mb-0 fw-bold"><i class="fas fa-chart-pie text-primary me-2"></i> Subject Summary</h5>
        </div>
        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-3 mb-3 border-bottom pb-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="fas fa-book text-primary me-2"></i> Subjects:</span>
                    <span class="fw-bold">{{ $subjects->count() }}</span>
                    <span class="text-muted ms-2">({{ $subjects->where('is_active', true)->count() }} active)</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="fas fa-chalkboard text-info me-2"></i> Assignments:</span>
                    <span class="fw-bold">{{ $subjects->sum('sections_count') ?? 0 }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="fas fa-graduation-cap text-warning me-2"></i> Grade Levels:</span>
                    <span class="fw-bold">{{ $subjects->pluck('grade_level')->filter()->unique()->count() }}</span>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Subject
                    </a>
                </div>
            </div>
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group" style="max-width: 400px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search subjects...">
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

    <!-- Subjects Table -->
    <div class="card border-0 animate__animated animate__fadeIn">
        <div class="card-header bg-white d-flex align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list text-primary me-2"></i> Subject List</h5>
        </div>
        <div class="card-body p-0">
            <div class="scrollable-table">
                <table class="table table-hover mb-0" id="subjectsTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Subject</th>
                            <th>Grade</th>
                            <th class="text-center">Sections</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            @php
                                $isComponent = $subject->is_component ?? false;
                                if ($isComponent) continue;

                                // Check if this is a MAPEH subject
                                $hasComponents = isset($subject->components) && $subject->components->count() > 0;
                                $isMAPEH = $hasComponents && $subject->components->pluck('name')->filter(function($name) {
                                    return in_array(strtolower($name), ['music', 'arts', 'physical education', 'health']) ||
                                           in_array(strtolower(substr($name, 0, 5)), ['music', 'arts', 'physi', 'healt']);
                                })->count() == 4;
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="text-decoration-none">
                                        {{ $subject->name }}
                                    </a>
                                    @if($isMAPEH)
                                        <span class="badge bg-info text-white mapeh-badge ms-1">MAPEH</span>
                                    @endif
                                    <div class="small text-muted">{{ $subject->code ?? 'No code' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">Grade {{ $subject->grade_level }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $subject->sections_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $subject->is_active ? 'success' : 'danger' }}">
                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('teacher-admin.subjects.show', $subject) }}"
                                       class="btn btn-sm btn-outline-primary action-btn"
                                       data-bs-toggle="tooltip" title="View Subject">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher-admin.subjects.edit', $subject) }}"
                                       class="btn btn-sm btn-outline-secondary action-btn"
                                       data-bs-toggle="tooltip" title="Edit Subject">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger action-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $subject->id }}"
                                            data-bs-toggle="tooltip" title="Delete Subject">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $subject->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Subject</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>{{ $subject->name }}</strong>?</p>
                                                    @if($subject->sections_count > 0)
                                                        <div class="alert alert-warning small">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            This subject is assigned to {{ $subject->sections_count }} {{ Str::plural('section', $subject->sections_count) }}.
                                                        </div>
                                                    @endif
                                                    <p class="text-danger small">This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('teacher-admin.subjects.destroy', $subject) }}" method="POST">
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
                                <td colspan="5" class="text-center py-5">
                                    <div class="avatar bg-light p-3 mb-3">
                                        <i class="fas fa-book text-secondary fa-2x"></i>
                                    </div>
                                    <h5 class="text-muted">No Subjects Found</h5>
                                    <p class="text-muted">Add a new subject to get started.</p>
                                    <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i> Add New Subject
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($subjects->count() > 10)
                <div class="text-center py-2 border-top">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Scroll to see all {{ $subjects->count() }} subjects</small>
                </div>
            @endif
        </div>
    </div>

    <!-- No Results Message -->
    <div id="noResultsMessage" class="text-center py-5 d-none">
        <div class="avatar bg-light p-3 mb-3">
            <i class="fas fa-search text-secondary fa-2x"></i>
        </div>
        <h5 class="text-muted">No Matching Subjects</h5>
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

        document.querySelectorAll('#subjectsTable tbody tr').forEach(row => {
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
                const statusText = row.querySelector('td:nth-child(4)').textContent.trim();
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