@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Enrollment Management</h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-info btn-sm" onclick="loadStatistics()">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4" id="statistics-cards" style="display: none;">
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 id="pending-count">0</h5>
                                    <small>Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 id="approved-count">0</h5>
                                    <small>Approved</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 id="enrolled-count">0</h5>
                                    <small>Enrolled</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5 id="rejected-count">0</h5>
                                    <small>Rejected</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 id="total-count">0</h5>
                                    <small>Total</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="grade_level" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Grade Levels</option>
                                    @foreach($gradeLevels as $level)
                                        <option value="{{ $level }}" {{ request('grade_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by name, student ID, or LRN" value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-secondary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('teacher-admin.enrollments.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Bulk Actions -->
                    <div class="mb-3" id="bulk-actions" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <span id="selected-count">0</span> enrollment(s) selected
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-success btn-sm" onclick="showBulkApproveModal()">
                                            <i class="fas fa-check"></i> Bulk Approve
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollments Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Student Info</th>
                                    <th>Contact</th>
                                    <th>Preferred Grade/Section</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrollments as $enrollment)
                                    <tr>
                                        <td>
                                            @if($enrollment->isPending())
                                                <input type="checkbox" class="enrollment-checkbox" value="{{ $enrollment->id }}" onchange="updateBulkActions()">
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $enrollment->full_name }}</strong><br>
                                                <small class="text-muted">
                                                    ID: {{ $enrollment->student_id }}<br>
                                                    LRN: {{ $enrollment->lrn }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $enrollment->guardian_name }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $enrollment->guardian_contact }}
                                                    @if($enrollment->guardian_email)
                                                        <br>{{ $enrollment->guardian_email }}
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $enrollment->preferred_grade_level }}</strong>
                                                @if($enrollment->preferredSection)
                                                    <br><small class="text-muted">{{ $enrollment->preferredSection->name }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($enrollment->isPending())
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($enrollment->isApproved())
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($enrollment->isEnrolled())
                                                <span class="badge badge-primary">Enrolled</span>
                                            @elseif($enrollment->isRejected())
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $enrollment->created_at->format('M d, Y') }}<br>
                                                {{ $enrollment->created_at->format('h:i A') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher-admin.enrollments.show', $enrollment) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($enrollment->isPending())
                                                    <button type="button" class="btn btn-success btn-sm" onclick="showApproveModal({{ $enrollment->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="showRejectModal({{ $enrollment->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>No enrollment requests found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $enrollments->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Enrollment</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="assigned_section_id">Assign to Section *</label>
                        <select name="assigned_section_id" id="assigned_section_id" class="form-control" required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                @php
                                    $currentCount = $section->getCurrentStudentCount();
                                    $limit = $section->student_limit;
                                    $capacityText = $limit ? "({$currentCount}/{$limit})" : "({$currentCount})";
                                    $isFull = $limit && $currentCount >= $limit;
                                @endphp
                                <option value="{{ $section->id }}" {{ $isFull ? 'disabled' : '' }} 
                                        class="{{ $isFull ? 'text-danger' : '' }}">
                                    {{ $section->name }} ({{ $section->grade_level }}) {{ $capacityText }}
                                    {{ $isFull ? ' - FULL' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Numbers in parentheses show current students / capacity limit</small>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Enrollment</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Reason for Rejection *</label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" required placeholder="Please provide a reason for rejecting this enrollment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="bulkApproveForm" method="POST" action="{{ route('teacher-admin.enrollments.bulk-approve') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Approve Enrollments</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Assign sections for the selected enrollments:</p>
                    <div id="bulk-assignments"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function loadStatistics() {
    fetch('{{ route("teacher-admin.enrollments.statistics") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('pending-count').textContent = data.pending;
            document.getElementById('approved-count').textContent = data.approved;
            document.getElementById('enrolled-count').textContent = data.enrolled;
            document.getElementById('rejected-count').textContent = data.rejected;
            document.getElementById('total-count').textContent = data.total;
            document.getElementById('statistics-cards').style.display = 'flex';
        })
        .catch(error => console.error('Error loading statistics:', error));
}

function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.enrollment-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.enrollment-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    if (checkboxes.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = checkboxes.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

function showApproveModal(enrollmentId) {
    const form = document.getElementById('approveForm');
    form.action = `/teacher-admin/enrollments/${enrollmentId}/approve`;
    $('#approveModal').modal('show');
}

function showRejectModal(enrollmentId) {
    const form = document.getElementById('rejectForm');
    form.action = `/teacher-admin/enrollments/${enrollmentId}/reject`;
    $('#rejectModal').modal('show');
}

function showBulkApproveModal() {
    const checkboxes = document.querySelectorAll('.enrollment-checkbox:checked');
    const assignmentsDiv = document.getElementById('bulk-assignments');
    
    assignmentsDiv.innerHTML = '';
    
    checkboxes.forEach(checkbox => {
        const enrollmentId = checkbox.value;
        const row = checkbox.closest('tr');
        const studentName = row.querySelector('strong').textContent;
        
        const assignmentHtml = `
            <div class="form-group row">
                <div class="col-md-6">
                    <label>${studentName}</label>
                    <input type="hidden" name="enrollment_ids[]" value="${enrollmentId}">
                </div>
                <div class="col-md-6">
                    <select name="section_assignments[${enrollmentId}]" class="form-control" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }} ({{ $section->grade_level }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        `;
        
        assignmentsDiv.innerHTML += assignmentHtml;
    });
    
    $('#bulkApproveModal').modal('show');
}
</script>
@endpush