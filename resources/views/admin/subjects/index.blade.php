@extends('layouts.app')

@push('styles')
<style>
    /* Adapted styles from account-management */
    :root {
        --border-radius: 8px;
        --border-radius-pill: 50px;
        --padding-sm: 0.75rem;
        --padding-md: 1rem;
        --margin-sm: 1rem;
        --margin-md: 1.5rem;
        --transition: all 0.2s ease-in-out;
    }

    .table {
        border-radius: var(--border-radius);
        font-size: 0.95rem;
        color: #333;
    }

    .table thead {
        background-color: #f8f9fa;
    }

    .table th, .table td {
        padding: var(--padding-sm);
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .small {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .modal-content {
        border-radius: var(--border-radius);
    }

    .modal-header, .modal-footer {
        border: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Main Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Subject Management</h2>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-book text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Subjects</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $subjects->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Active Subjects</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $subjects->where('is_active', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-graduation-cap text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Grade Levels</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $subjects->pluck('grade_level')->filter()->unique()->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-link text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Assignments</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $subjects->sum('sections_count') ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card bg-white border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.subjects.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search subjects..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort by...</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="grade_level" {{ request('sort') == 'grade_level' ? 'selected' : '' }}>Grade Level</option>
                        <option value="is_active" {{ request('sort') == 'is_active' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="order" class="form-select">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary fw-bold">
                        <i class="fas fa-plus-circle me-1"></i> Add New Subject
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card border-0 bg-white shadow-sm">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <h5 class="mb-0 fw-bold">Subjects</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="background-color: white;">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Subject</th>
                            <th scope="col">Grade Level</th>
                            <th scope="col" class="text-center">Sections</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: white;">
                        @php
                            $filteredSubjects = $subjects;
                            
                            // Apply search filter if provided
                            if (request('search')) {
                                $searchTerm = strtolower(request('search'));
                                $filteredSubjects = $filteredSubjects->filter(function($subject) use ($searchTerm) {
                                    return str_contains(strtolower($subject->name), $searchTerm) || 
                                           str_contains(strtolower($subject->code ?? ''), $searchTerm) ||
                                           str_contains(strtolower($subject->grade_level ?? ''), $searchTerm);
                                });
                            }
                            
                            // Apply sorting if provided
                            if (request('sort')) {
                                $sortField = request('sort');
                                $sortOrder = request('order', 'asc');
                                
                                $filteredSubjects = $filteredSubjects->sortBy(function($subject) use ($sortField) {
                                    switch ($sortField) {
                                        case 'name':
                                            return strtolower($subject->name);
                                        case 'grade_level':
                                            return $subject->grade_level;
                                        case 'is_active':
                                            return $subject->is_active;
                                        default:
                                            return strtolower($subject->name);
                                    }
                                }, SORT_REGULAR, $sortOrder === 'desc');
                            }
                        @endphp
                        
                        @forelse($filteredSubjects as $subject)
                            @php
                                $isComponent = $subject->is_component ?? false;
                                if ($isComponent) continue;
                                $isMAPEH = isset($subject->components) && $subject->components->count() > 0 &&
                                           $subject->components->pluck('name')->filter(function($name) {
                                               return in_array(strtolower(substr($name, 0, 5)), ['music', 'arts', 'physi', 'healt']);
                                           })->count() == 4;
                            @endphp
                            <tr style="background-color: white;">
                                <td>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">
                                                <a href="{{ route('admin.subjects.show', $subject) }}" class="text-decoration-none">
                                                    {{ $subject->name }}
                                                </a>
                                                @if($isMAPEH)
                                                    <span class="badge bg-info">MAPEH</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $subject->code ?? 'No code' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        Grade {{ $subject->grade_level }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        {{ $subject->sections_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $subject->is_active ? 'success' : 'danger' }}">
                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-outline-primary" title="Edit Subject">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-primary" title="Delete Subject"
                                                data-bs-toggle="modal" data-bs-target="#deleteSubjectModal{{ $subject->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4" style="background-color: white;">
                                    <div class="text-muted">
                                        <i class="fas fa-book fa-2x mb-3"></i>
                                        <h5>No Subjects Found</h5>
                                        @if(request('search'))
                                            <p>No subjects match your search criteria.</p>
                                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary me-2">
                                                <i class="fas fa-times me-1"></i> Clear Search
                                            </a>
                                        @else
                                            <p>Start by adding a new subject.</p>
                                        @endif
                                        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-1"></i> Add New Subject
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Subject Modals -->
    @foreach($subjects as $subject)
        <div class="modal fade" id="deleteSubjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="deleteSubjectModalLabel{{ $subject->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteSubjectModalLabel{{ $subject->id }}">Delete Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the subject "{{ $subject->name }}"?</p>
                        @if($subject->sections_count > 0)
                            <p class="text-warning"><i class="fas fa-exclamation-circle me-1"></i> This subject is assigned to {{ $subject->sections_count }} {{ Str::plural('section', $subject->sections_count) }}.</p>
                        @endif
                        <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Delete Subject
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection