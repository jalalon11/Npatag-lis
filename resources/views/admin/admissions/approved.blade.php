@extends('layouts.app')

@section('title', 'Approved Students')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Approved Students</h1>
        <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline-primary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Applications
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Approved Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.admissions.approved') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="school_id" class="form-label">School</label>
                    <select class="form-select" id="school_id" name="school_id">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Name, Student ID, LRN, Guardian...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <a href="{{ route('admin.admissions.approved') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Approved Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Approved Students List</h6>
        </div>
        <div class="card-body">
            @if($approvedStudents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student Info</th>
                                <th>Guardian</th>
                                <th>School & Grade</th>
                                <th>Approval Date</th>
                                <th>Status</th>
                                <th>Processed By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedStudents as $admission)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $admission->first_name }} {{ $admission->last_name }}</div>
                                                <div class="text-muted small">
                                                    ID: {{ $admission->student_id }}<br>
                                                    LRN: {{ $admission->lrn }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $admission->guardian_name }}</div>
                                        <div class="text-muted small">
                                            {{ $admission->guardian_contact }}<br>
                                            {{ $admission->guardian_email }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $admission->school->name }}</div>
                                        <div class="text-muted small">
                                            Grade: {{ $admission->preferred_grade_level }}<br>
                                            SY: {{ $admission->school_year }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            {{ $admission->updated_at->format('M d, Y') }}<br>
                                            {{ $admission->updated_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Approved</span>
                                    </td>
                                    <td>
                                        @if($admission->processedBy)
                                            <div class="fw-bold">{{ $admission->processedBy->name }}</div>
                                            <div class="text-muted small">{{ $admission->processedBy->email }}</div>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.admissions.show', $admission->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.learners.show', $admission->id) }}" 
                                               class="btn btn-sm btn-outline-success" title="Enroll Student">
                                                <i class="fas fa-user-plus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $approvedStudents->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-user-check fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No Approved Students Found</h5>
                    <p class="text-muted">No approved students match your current filters.</p>
                    <a href="{{ route('admin.admissions.index') }}" class="btn btn-primary">
                        <i class="fas fa-clipboard-list"></i> View All Applications
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-hover:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
}
</style>
@endpush