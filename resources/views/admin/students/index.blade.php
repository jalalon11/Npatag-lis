@extends('layouts.app')

@section('title', 'Student Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users me-2"></i>Student Management
            </h1>
            <p class="text-muted mb-0">Manage enrolled students and their information</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Student
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Inactive Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sections
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sections->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.students.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Students</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Name, Student ID, or Email">
                </div>
                
                <div class="col-md-3">
                    <label for="section_id" class="form-label">Section</label>
                    <select class="form-select" id="section_id" name="section_id">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" 
                                    {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }} ({{ $section->school->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Students List
            </h6>
            <span class="badge bg-info">{{ $students->total() }} students found</span>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="studentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Enrolled Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr class="{{ !$student->is_active ? 'table-warning' : '' }}">
                                    <td>
                                        <strong>{{ $student->student_id }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                @if($student->middle_name)
                                                    <br><small class="text-muted">{{ $student->middle_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if($student->section)
                                            <span class="badge bg-secondary">{{ $student->section->name }}</span>
                                            <br><small class="text-muted">{{ $student->section->school->name }}</small>
                                        @else
                                            <span class="text-muted">No Section</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $student->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.students.show', $student) }}" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student) }}" 
                                               class="btn btn-sm btn-warning" title="Edit Student">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.students.toggle-status', $student) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to {{ $student->is_active ? 'deactivate' : 'activate' }} this student?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $student->is_active ? 'btn-danger' : 'btn-success' }}" 
                                                        title="{{ $student->is_active ? 'Deactivate' : 'Activate' }} Student">
                                                    <i class="fas {{ $student->is_active ? 'fa-user-times' : 'fa-user-check' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} 
                            of {{ $students->total() }} results
                        </small>
                    </div>
                    <div>
                        {{ $students->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-muted">No Students Found</h5>
                    <p class="text-muted">No students match your current filters.</p>
                    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Student
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('#section_id, #status').change(function() {
        $(this).closest('form').submit();
    });
    
    // Search on Enter key
    $('#search').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush
@endsection