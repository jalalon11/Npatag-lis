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
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Admissions</h1>
        <a href="{{ route('admin.admissions.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create Manual Admission
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
                                Total Applications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                Pending Review
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.admissions.approved') }}" class="text-decoration-none">
                <div class="card border-left-success shadow h-100 py-2 card-hover">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Approved
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                                    <small class="text-muted">Click to view all</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.admissions.rejected') }}" class="text-decoration-none">
                <div class="card border-left-danger shadow h-100 py-2 card-hover">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Rejected
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>
                                    <small class="text-muted">Click to view all</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Applications</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.admissions.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                    </select>
                </div>
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
                <div class="col-md-4">
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
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Student Applications</h6>
        </div>
        <div class="card-body">
            @if($admissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student Info</th>
                                <th>Guardian</th>
                                <th>School & Grade</th>
                                <th>Application Date</th>
                                <th>Status</th>
                                 <th>Birth Certificate</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admissions as $admission)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $admission->first_name }} {{ $admission->middle_name }} {{ $admission->last_name }}</div>
                                        <small class="text-muted">
                                            ID: {{ $admission->student_id }} | LRN: {{ $admission->lrn }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            {{ $admission->gender }} | {{ $admission->birth_date->format('M d, Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $admission->guardian_name }}</div>
                                        <small class="text-muted">{{ $admission->guardian_contact }}</small>
                                        <br>
                                        <small class="text-muted">{{ $admission->guardian_email }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $admission->school->name ?? 'N/A' }}</div>
                                        <small class="text-muted">Grade {{ $admission->preferred_grade_level }}</small>
                                        @if($admission->preferredSection)
                                            <br><small class="text-muted">Preferred: {{ $admission->preferredSection->name }}</small>
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
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="openBirthCertificateModal('{{ route('admin.admissions.birth-certificate', $admission) }}')"
                                                    title="View Birth Certificate">
                                                <i class="fas fa-file-alt"></i> View
                                            </button>
                                        @else
                                            <span class="text-muted">Not uploaded</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.admissions.show', $admission) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($admission->status === 'pending')
                                                <a href="{{ route('admin.admissions.edit', $admission) }}" 
                                                   class="btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="approveAdmission({{ $admission->id }})" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="rejectAdmission({{ $admission->id }})" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            
                                            @if($admission->status !== 'enrolled')
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteAdmission({{ $admission->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $admissions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No Applications Found</h5>
                    <p class="text-muted">No Pending Student Applications Found.</p>
                    <a href="{{ route('admin.admissions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Manual Admission
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Admission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                            <!-- <li>Student can be assigned to a section (optional)</li> -->
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
                    
                    <!-- <div class="mb-3">
                        <label for="assigned_section_id" class="form-label">Assign to Section (Optional)</label>
                        <select class="form-select" id="assigned_section_id" name="assigned_section_id">
                            <option value="">Select Section Later</option>
                        </select>
                        <div class="form-text">You can assign the student to a section now or later in Learners Record.</div>
                    </div> -->
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
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Admission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

@endsection

@push('scripts')
<script>
function approveAdmission(admissionId) {
    const form = document.getElementById('approvalForm');
    form.action = `/admin/admissions/${admissionId}/approve`;
    
    // Reset modal content
    document.getElementById('studentName').textContent = 'Loading...';
    document.getElementById('gradeLevel').textContent = 'Loading...';
    document.getElementById('guardianName').textContent = 'Loading...';
    document.getElementById('guardianEmail').textContent = 'Loading...';
    document.getElementById('guardianAccountInfo').textContent = 'Loading guardian account information...';
    
    // Load admission data
    fetch(`/admin/admissions/${admissionId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // Parse the HTML to extract admission data
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Extract student information from the show page
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
                document.getElementById('guardianEmail').innerHTML = '<span class="text-muted">Not provided</span>';
                guardianAccountInfo.innerHTML = '<span class="text-warning">No guardian account will be created (no email provided)</span>';
            }
            
            const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading admission data:', error);
            // Show modal anyway with error message
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
    if (confirm('Are you sure you want to delete this admission? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/admissions/${admissionId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@include('components.birth-certificate-modal')
@endpush