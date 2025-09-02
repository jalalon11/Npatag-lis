@extends('layouts.app')

@section('title', 'Enrollment Applications')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-graduate me-2"></i>Admission Applications
            </h1>
            <p class="text-muted mb-0">Manage student admission applications and approvals</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkApproveModal">
                <i class="fas fa-check-double me-2"></i>Bulk Approve
            </button>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Add Student Manually
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Admissions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total'] ?? 0 }}</div>
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
                                Pending Admissions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Approved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['approved'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['rejected'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('admin.admissions.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Name, email, phone...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
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
                <div class="col-md-2">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Admission Applications
            </h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                    <i class="fas fa-check-square me-1"></i>Select All
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                    <i class="fas fa-square me-1"></i>Clear All
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="enrollmentsTable">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll()">
                                </th>
                                <th>Application ID</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>School</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="enrollment-checkbox" 
                                               value="{{ $enrollment->id }}" name="selected_enrollments[]">
                                    </td>
                                    <td>
                                        <strong class="text-primary">#{{ $enrollment->application_id }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-primary text-white rounded-circle">
                                                    {{ strtoupper(substr($enrollment->first_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $enrollment->first_name }} {{ $enrollment->last_name }}</strong>
                                                @if($enrollment->middle_name)
                                                    <br><small class="text-muted">{{ $enrollment->middle_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $enrollment->email }}" class="text-decoration-none">
                                            {{ $enrollment->email }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($enrollment->phone)
                                            <a href="tel:{{ $enrollment->phone }}" class="text-decoration-none">
                                                {{ $enrollment->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $enrollment->school->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $enrollment->status === 'approved' ? 'success' : 
                                            ($enrollment->status === 'rejected' ? 'danger' : 
                                            ($enrollment->status === 'verified' ? 'info' : 'warning'))
                                        }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $enrollment->created_at->format('M d, Y') }}</span>
                                        <br><small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.admissions.show', $enrollment) }}" 
                                               class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($enrollment->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="verifyAdmission({{ $enrollment->id }})" title="Verify">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            @if(in_array($enrollment->status, ['pending', 'verified']))
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="approveAdmission({{ $enrollment->id }})" title="Approve">
                                                    <i class="fas fa-thumbs-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="rejectAdmission({{ $enrollment->id }})" title="Reject">
                                                    <i class="fas fa-thumbs-down"></i>
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="text-muted mb-0">
                            Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }} 
                            of {{ $enrollments->total() }} results
                        </p>
                    </div>
                    <div>
                        {{ $enrollments->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No admission applications found</h5>
                    <p class="text-muted">There are no admission applications matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-double me-2"></i>Bulk Approve Admissions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkApproveForm">
                    @csrf
                    <div class="mb-3">
                        <label for="bulk_section_id" class="form-label">Assign to Section <span class="text-danger">*</span></label>
                        <select class="form-select" id="bulk_section_id" name="section_id" required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">
                                    {{ $section->name }} - {{ $section->school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Selected Applications:</strong> <span id="selectedCount">0</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="processBulkApprove()">
                    <i class="fas fa-check me-2"></i>Approve Selected
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
}

.badge-success {
    background-color: #1cc88a;
    color: white;
}

.badge-warning {
    background-color: #f6c23e;
    color: #5a5c69;
}

.badge-danger {
    background-color: #e74a3b;
    color: white;
}

.badge-info {
    background-color: #36b9cc;
    color: white;
}

.gap-2 {
    gap: 0.5rem;
}

.card {
    border: none;
    border-radius: 0.35rem;
}

.btn {
    border-radius: 0.35rem;
}
</style>
@endpush

@push('scripts')
<script>
function toggleAll() {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.enrollment-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelectedCount();
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.enrollment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('selectAllCheckbox').checked = true;
    updateSelectedCount();
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.enrollment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAllCheckbox').checked = false;
    updateSelectedCount();
}

function updateSelectedCount() {
    const selected = document.querySelectorAll('.enrollment-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = selected;
}

function verifyAdmission(id) {
    if (confirm('Are you sure you want to verify this admission application?')) {
        fetch(`/admin/admissions/${id}/verify`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred. Please try again.');
        });
    }
}

function approveAdmission(id) {
    const sections = @json($sections);
    let sectionOptions = '<option value="">Select Section</option>';
    sections.forEach(section => {
        sectionOptions += `<option value="${section.id}">${section.name} - ${section.school.name}</option>`;
    });
    
    const sectionId = prompt('Select section ID for this student:\n' + sections.map(s => `${s.id}: ${s.name}`).join('\n'));
    
    if (sectionId && confirm('Are you sure you want to approve this admission and create a student record?')) {
        fetch(`/admin/admissions/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ section_id: sectionId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Admission approved and student record created successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred. Please try again.');
        });
    }
}

function rejectAdmission(id) {
    const reason = prompt('Please provide a reason for rejection (optional):');
    
    if (confirm('Are you sure you want to reject this admission application?')) {
        fetch(`/admin/admissions/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred. Please try again.');
        });
    }
}

function processBulkApprove() {
    const selected = Array.from(document.querySelectorAll('.enrollment-checkbox:checked')).map(cb => cb.value);
    const sectionId = document.getElementById('bulk_section_id').value;
    
    if (selected.length === 0) {
        alert('Please select at least one admission application.');
        return;
    }
    
    if (!sectionId) {
        alert('Please select a section.');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selected.length} admission applications?`)) {
        fetch('/admin/admissions/bulk-approve', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                admission_ids: selected,
                section_id: sectionId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Successfully approved ${data.approved_count} admissions!`);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred. Please try again.');
        });
    }
}

// Update selected count when checkboxes change
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.enrollment-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    updateSelectedCount();
});
</script>
@endpush
@endsection