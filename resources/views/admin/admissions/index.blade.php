@extends('layouts.app')

@section('title', 'Student Admissions')

@push('styles')
<style>
.card-hover:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Student Admissions</h2>
                <a href="{{ route('admin.admissions.create') }}" class="btn btn-primary fw-bold">
                    <i class="fas fa-plus-circle me-1"></i> Create Manual Admission
                </a>
            </div>
        </div>
    </div>
    
    <!-- Success/Error Messages -->
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

    <!-- Success/Error Messages -->
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
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clipboard-list text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Applications</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending Review</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.admissions.index', ['status' => 'approved']) }}" class="text-decoration-none">
                <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-check-circle text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Approved</h6>
                                <h3 class="mb-0 fw-bold text-primary">{{ $stats['approved'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.admissions.index', ['status' => 'rejected']) }}" class="text-decoration-none">
                <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-times-circle text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Rejected</h6>
                                <h3 class="mb-0 fw-bold text-primary">{{ $stats['rejected'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card bg-white border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.admissions.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search applications..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="school_id" class="form-select">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort by...</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Application Date</option>
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
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card border-0 bg-white shadow-sm pb-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="background-color: white;">
                    <thead class="table-light" style="background-color: #f8f9fa;">
                        <tr>
                            <th scope="col">Student Info</th>
                            <th scope="col">Guardian</th>
                            <th scope="col">School & Grade</th>
                            <th scope="col">Application Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Birth Certificate</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: white;">
                        @php
                            $filteredAdmissions = $admissions;

                            if (request('search')) {
                                $searchTerm = strtolower(request('search'));
                                $filteredAdmissions = $admissions->filter(function($admission) use ($searchTerm) {
                                    return str_contains(strtolower($admission->first_name . ' ' . $admission->last_name), $searchTerm) ||
                                           str_contains(strtolower($admission->student_id), $searchTerm) ||
                                           str_contains(strtolower($admission->lrn), $searchTerm) ||
                                           str_contains(strtolower($admission->guardian_name), $searchTerm) ||
                                           ($admission->school && str_contains(strtolower($admission->school->name), $searchTerm));
                                });
                            }

                            if (request('status') && request('status') != 'all') {
                                $filteredAdmissions = $filteredAdmissions->where('status', request('status'));
                            }

                            if (request('school_id')) {
                                $filteredAdmissions = $filteredAdmissions->where('school_id', request('school_id'));
                            }

                            if (request('sort')) {
                                $sortField = request('sort');
                                $sortOrder = request('order', 'asc');
                                $filteredAdmissions = $filteredAdmissions->sortBy(function($admission) use ($sortField) {
                                    switch ($sortField) {
                                        case 'name':
                                            return strtolower($admission->first_name . ' ' . $admission->last_name);
                                        case 'created_at':
                                            return $admission->created_at;
                                        default:
                                            return strtolower($admission->first_name . ' ' . $admission->last_name);
                                    }
                                }, SORT_REGULAR, $sortOrder === 'desc');
                            }
                        @endphp

                        @forelse($filteredAdmissions as $admission)
                            <tr style="background-color: white;">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-user-graduate text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $admission->first_name }} {{ $admission->middle_name }} {{ $admission->last_name }}</h6>
                                            <small class="text-muted">ID: {{ $admission->student_id }} | LRN: {{ $admission->lrn }}</small>
                                            <br>
                                            <small class="text-muted">{{ $admission->gender }} | {{ $admission->birth_date->format('M d, Y') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">{{ $admission->guardian_name }}</h6>
                                        <small class="text-muted">{{ $admission->guardian_contact }}</small>
                                        <br>
                                        <small class="text-muted">{{ $admission->guardian_email }}</small>
                                    </td>
                                    <td>
                                        @if($admission->school)
                                            <span class="badge bg-primary">{{ $admission->school->name }}</span>
                                            <br>
                                            <small class="text-muted">Grade {{ $admission->preferred_grade_level }}</small>
                                            @if($admission->preferredSection)
                                                <br><small class="text-muted">Preferred: {{ $admission->preferredSection->name }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">No School Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $admission->created_at->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $admission->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @switch($admission->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @case('enrolled')
                                                <span class="badge bg-info">Enrolled</span>
                                                @break
                                        @endswitch
                                        @if($admission->processed_at)
                                            <br><small class="text-muted">{{ $admission->processed_at->format('M d, Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($admission->birth_certificate)

                                            <a href="{{ route('admin.admissions.birth-certificate', $admission) }}"
                                               target="_blank" class="btn btn-sm btn-outline-info" title="View Birth Certificate">
                                                <i class="fas fa-file-alt"></i> View
                                            </button>
                                        @else
                                            <span class="text-muted">Not uploaded</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.admissions.show', $admission) }}"
                                               class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($admission->status === 'pending')
                                                <a href="{{ route('admin.admissions.edit', $admission) }}"
                                                   class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-success"
                                                        onclick="approveAdmission({{ $admission->id }})" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                        onclick="rejectAdmission({{ $admission->id }})" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            @if($admission->status !== 'enrolled')
                                                <button type="button" class="btn btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteAdmissionModal{{ $admission->id }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4" style="background-color: white;">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <h5>No Applications Found</h5>
                                            @if(request('search') || request('status') != 'all' || request('school_id'))
                                                <p>No applications match your search or filter criteria.</p>
                                                <a href="{{ route('admin.admissions.index') }}"
                                                   class="btn btn-secondary me-2">
                                                    <i class="fas fa-times me-1"></i> Clear Filters
                                                </a>
                                            @else
                                                <p>Start by creating a new admission.</p>
                                            @endif
                                            <a href="{{ route('admin.admissions.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Create Manual Admission
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($filteredAdmissions->count() > 0)
                    <div class="d-flex justify-content-center mt-3">
                        {{ $admissions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Approval Modal -->
        <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approvalModalLabel">Approve Admission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="approvalForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-info mb-4">
                                <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Approval Process</h6>
                                <p class="mb-2">When you approve this admission, the following will happen:</p>
                                <ul class="mb-0">
                                    <li>Student status will be changed to <strong>Approved</strong></li>
                                    <li id="guardianAccountInfo">Guardian account information will be displayed here</li>
                                </ul>
                            </div>
                            <div id="studentInfo" class="mb-3">
                                <h6>Student Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> <span id="studentName">Loading...</span></p>
                                        <p><strong>Grade Level:</strong> <span id="gradeLevel">Loading...</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Guardian:</strong> <span id="guardianName">Loading...</span></p>
                                        <p><strong>Guardian Email:</strong> <span id="guardianEmail">Loading...</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve Admission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectionModalLabel">Reject Admission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="rejectionForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label">Reason for Rejection *</label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason"
                                          rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modals -->
        @foreach($admissions as $admission)
            @if($admission->status !== 'enrolled')
                <div class="modal fade" id="deleteAdmissionModal{{ $admission->id }}" tabindex="-1"
                     aria-labelledby="deleteAdmissionModalLabel{{ $admission->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteAdmissionModalLabel{{ $admission->id }}">Delete Admission</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete the admission for "{{ $admission->first_name }} {{ $admission->last_name }}"?</p>
                                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('admin.admissions.destroy', $admission->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-1"></i> Delete Admission
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    @push('scripts')
    <script>
    function approveAdmission(admissionId) {
        const form = document.getElementById('approvalForm');
        form.action = `/admin/admissions/${admissionId}/approve`;

        document.getElementById('studentName').textContent = 'Loading...';
        document.getElementById('gradeLevel').textContent = 'Loading...';
        document.getElementById('guardianName').textContent = 'Loading...';
        document.getElementById('guardianEmail').textContent = 'Loading...';
        document.getElementById('guardianAccountInfo').textContent = 'Loading guardian account information...';

        fetch(`/admin/admissions/${admissionId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const studentNameElement = doc.querySelector('[data-student-name]');
                const gradeLevelElement = doc.querySelector('[data-grade-level]');
                const guardianNameElement = doc.querySelector('[data-guardian-name]');
                const guardianEmailElement = doc.querySelector('[data-guardian-email]');

                if (studentNameElement) {
                    document.getElementById('studentName').textContent = studentNameElement.textContent.trim();
                }
                if (gradeLevelElement) {
                    document.getElementById('gradeLevel').textContent = gradeLevelElement.textContent.trim();
                }
                if (guardianNameElement) {
                    document.getElementById('guardianName').textContent = guardianNameElement.textContent.trim();
                }

                const guardianEmail = guardianEmailElement ? guardianEmailElement.textContent.trim() : '';
                const guardianAccountInfo = document.getElementById('guardianAccountInfo');

                if (guardianEmail && guardianEmail !== 'Not provided') {
                    document.getElementById('guardianEmail').innerHTML = `<span class="text-success">${guardianEmail}</span>`;
                    guardianAccountInfo.innerHTML = `A guardian account will be created with email: <strong class="text-success">${guardianEmail}</strong> (default password: 'password')`;
                } else {
                    document.getElementById('guardianEmail').innerHTML = `<span class="text-muted">Not provided</span>`;
                    guardianAccountInfo.innerHTML = `<span class="text-warning">No guardian account will be created (no email provided)</span>`;
                }

                const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error loading admission data:', error);
                document.getElementById('studentName').textContent = 'Error loading data';
                document.getElementById('gradeLevel').textContent = 'Error loading data';
                document.getElementById('guardianName').textContent = 'Error loading data';
                document.getElementById('guardianEmail').textContent = 'Error loading data';
                document.getElementById('guardianAccountInfo').innerHTML = '<span class="text-danger">Error loading guardian information</span>';

                const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
                modal.show();
            });
    }

    function rejectAdmission(admissionId) {
        const form = document.getElementById('rejectionForm');
        form.action = `/admin/admissions/${admissionId}/reject`;

        const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        modal.show();
    }

    function deleteAdmission(admissionId) {
        const modal = new bootstrap.Modal(document.getElementById(`deleteAdmissionModal${admissionId}`));
        modal.show();
    }
    </script>
    @endpush
@endsection
