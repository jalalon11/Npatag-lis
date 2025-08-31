@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    :root {
        --border-radius: 12px;
        --border-radius-pill: 50px;
        --padding-sm: 1rem;
        --padding-md: 1.5rem;
        --margin-sm: 1rem;
        --margin-md: 1.5rem;
        --shadow-hover: 0 12px 24px rgba(0, 0, 0, 0.12);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

    .stats-card {
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Main Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <h2 class="mb-0 fw-bold">
            Enrollment Management
        </h2>
        <button type="button" class="btn btn-primary" onclick="loadStatistics()" data-bs-toggle="tooltip" title="View Statistics">
            <i class="fas fa-chart-bar me-2"></i> Statistics
        </button>
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

    <!-- Statistics Cards -->
    <div class="row mb-4 animate__animated animate__fadeIn" id="statistics-cards" style="display: none;">
        <div class="col-md-2">
            <div class="card stats-card bg-warning text-white">
                <div class="card-body text-center p-3">
                    <h5 class="mb-1" id="pending-count">0</h5>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card bg-info text-white">
                <div class="card-body text-center p-3">
                    <h5 class="mb-1" id="verified-count">0</h5>
                    <small>Verified</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card bg-success text-white">
                <div class="card-body text-center p-3">
                    <h5 class="mb-1" id="approved-count">0</h5>
                    <small>Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body text-center p-3">
                    <h5 class="mb-1" id="enrolled-count">0</h5>
                    <small>Enrolled</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card bg-danger text-white">
                <div class="card-body text-center p-3">
                    <h5 class="mb-1" id="rejected-count">0</h5>
                    <small>Rejected</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stats-card bg-info text-white">
                <div class="card-body text-center p-3">
                    <h5 class="mb-1" id="total-count">0</h5>
                    <small>Total</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-white d-flex align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-filter text-primary me-2"></i> Filter Enrollments</h5>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group" style="max-width: 400px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" id="searchInput" class="form-control border-start-0" placeholder="Search by name, student ID, or LRN" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="grade_level" class="form-select" onchange="this.form.submit()">
                            <option value="">All Grade Levels</option>
                            @foreach($gradeLevels as $level)
                                <option value="{{ $level }}" {{ request('grade_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card border-0 mb-4 animate__animated animate__fadeIn" id="bulk-actions" style="display: none;">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span id="selected-count">0</span> enrollment(s) selected
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-success action-btn" onclick="showBulkApproveModal()" data-bs-toggle="tooltip" title="Bulk Approve">
                        <i class="fas fa-check me-2"></i> Bulk Approve
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="card border-0 animate__animated animate__fadeIn">
        <div class="card-header bg-white d-flex align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list text-primary me-2"></i> Enrollment List</h5>
        </div>
        <div class="card-body p-0">
            <div class="scrollable-table">
                <table class="table table-hover mb-0" id="enrollmentsTable">
                    <thead>
                        <tr>
                            <th class="ps-4">
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                            </th>
                            <th>Student Info</th>
                            <th>Contact</th>
                            <th>Preferred Grade/Section</th>
                            <th class="text-center">Status</th>
                            <th>Submitted</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $enrollment)
                            <tr>
                                <td class="ps-4">
                                    @if($enrollment->isPending())
                                        <input type="checkbox" class="enrollment-checkbox" value="{{ $enrollment->id }}" onchange="updateBulkActions()">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('teacher-admin.enrollments.show', $enrollment) }}" class="text-decoration-none">
                                        <strong>{{ $enrollment->full_name }}</strong>
                                    </a>
                                    <div class="small text-muted">
                                        ID: {{ $enrollment->student_id }}<br>
                                        LRN: {{ $enrollment->lrn }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $enrollment->guardian_name }}</strong>
                                    <div class="small text-muted">
                                        {{ $enrollment->guardian_contact }}
                                        @if($enrollment->guardian_email)
                                            <br>{{ $enrollment->guardian_email }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $enrollment->preferred_grade_level }}</strong>
                                    @if($enrollment->preferredSection)
                                        <div class="small text-muted">Preferred: {{ $enrollment->preferredSection->name }}</div>
                                    @endif
                                    @if($enrollment->assignedSection && $enrollment->isApproved())
                                        <span class="badge bg-info">Assigned: {{ $enrollment->assignedSection->name }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($enrollment->isPending())
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($enrollment->isVerified())
                                        <span class="badge bg-info">Verified</span>
                                    @elseif($enrollment->isApproved())
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($enrollment->isEnrolled())
                                        <span class="badge bg-primary">Enrolled</span>
                                    @elseif($enrollment->isRejected())
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        {{ $enrollment->created_at->format('M d, Y') }}<br>
                                        {{ $enrollment->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teacher-admin.enrollments.show', $enrollment) }}"
                                           class="btn btn-sm btn-outline-primary action-btn"
                                           data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($enrollment->isPending())
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary action-btn"
                                                    onclick="showVerifyModal({{ $enrollment->id }})"
                                                    data-bs-toggle="tooltip" title="Verify Enrollment">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger action-btn"
                                                    onclick="showRejectModal({{ $enrollment->id }})"
                                                    data-bs-toggle="tooltip" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @elseif($enrollment->isVerified())
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success action-btn"
                                                    onclick="showQuickAssignModal({{ $enrollment->id }}, '{{ $enrollment->full_name }}', '{{ $enrollment->preferred_grade_level }}')"
                                                    data-bs-toggle="tooltip" title="Quick Assign & Approve">
                                                <i class="fas fa-users"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="avatar bg-light p-3 mb-3">
                                        <i class="fas fa-inbox text-secondary fa-2x"></i>
                                    </div>
                                    <h5 class="text-muted">No Enrollment Requests Found</h5>
                                    <p class="text-muted">No enrollments match the current filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($enrollments->count() > 10)
                <div class="text-center py-2 border-top">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Scroll to see all {{ $enrollments->count() }} enrollments</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4 animate__animated animate__fadeIn">
        {{ $enrollments->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

    <!-- No Results Message -->
    <div id="noResultsMessage" class="text-center py-5 d-none animate__animated animate__fadeIn">
        <div class="avatar bg-light p-3 mb-3">
            <i class="fas fa-search text-secondary fa-2x"></i>
        </div>
        <h5 class="text-muted">No Matching Enrollments</h5>
        <p class="text-muted">Try adjusting your search or filters.</p>
    </div>
</div>

<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="verifyForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-header">
                    <h5 class="modal-title">Verify Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please review the enrollment details and verify if the information is correct and complete.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Verify Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Section Modal -->
<div class="modal fade" id="assignSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="assignSectionForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Student to Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Student:</strong> <span id="assignSectionStudentName"></span><br>
                        <strong>Grade Level:</strong> <span id="assignSectionGradeLevel"></span>
                    </div>
                    <div class="mb-3">
                        <label for="assign_section_id" class="form-label">Assign to Section *</label>
                        <select name="assigned_section_id" id="assign_section_id" class="form-select" required>
                            <option value="">Loading sections...</option>
                        </select>
                        <small class="form-text text-muted">Numbers show current students / capacity limit</small>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-users me-2"></i> Assign to Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_section_id" class="form-label">Assign to Section *</label>
                        <select name="assigned_section_id" id="assigned_section_id" class="form-select" required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                @php
                                    $currentCount = $section->getCurrentStudentCount();
                                    $limit = $section->student_limit;
                                    $capacityText = $limit ? "($currentCount/$limit)" : "($currentCount)";
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
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection *</label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" required placeholder="Please provide a reason for rejecting this enrollment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Assign Section Modal -->
<div class="modal fade" id="quickAssignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="quickAssignForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-header">
                    <h5 class="modal-title">Quick Assign Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Student:</strong> <span id="quickAssignStudentName"></span><br>
                        <strong>Grade Level:</strong> <span id="quickAssignGradeLevel"></span>
                    </div>
                    <div class="mb-3">
                        <label for="quickAssignSection" class="form-label">Assign to Section *</label>
                        <select name="section_id" id="quickAssignSection" class="form-select" required>
                            <option value="">Select Section</option>
                        </select>
                        <small class="form-text text-muted">Numbers show current students / capacity limit</small>
                    </div>
                    <div class="mb-3">
                        <label for="quickAssignNotes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="quickAssignNotes" class="form-control" rows="2" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i> Assign & Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="bulkApproveForm" method="POST" action="{{ route('teacher-admin.enrollments.bulk-approve') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Approve Enrollments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Assign sections for the selected enrollments:</p>
                    <div id="bulk-assignments"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Selected</button>
                </div>
            </form>
        </div>
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
        const statusValue = document.querySelector('select[name="status"]').value;
        const gradeValue = document.querySelector('select[name="grade_level"]').value;
        let hasResults = false;

        document.querySelectorAll('#enrollmentsTable tbody tr').forEach(row => {
            let shouldShow = true;

            // Search filter
            if (searchValue) {
                const textContent = row.textContent.toLowerCase();
                shouldShow = textContent.includes(searchValue);
            }

            // Status filter
            if (shouldShow && statusValue) {
                const statusText = row.querySelector('td:nth-child(5)').textContent.trim();
                shouldShow = statusText.toLowerCase().includes(statusValue.toLowerCase());
            }

            // Grade filter
            if (shouldShow && gradeValue) {
                const gradeText = row.querySelector('td:nth-child(4)').textContent.trim();
                shouldShow = gradeText.includes(gradeValue);
            }

            row.style.display = shouldShow ? '' : 'none';
            if (shouldShow) hasResults = true;
        });

        // Show/hide no results message
        const noResultsMessage = document.getElementById('noResultsMessage');
        if (!hasResults && (searchValue || statusValue || gradeValue)) {
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
    document.querySelector('select[name="status"]').addEventListener('change', filterTable);
    document.querySelector('select[name="grade_level"]').addEventListener('change', filterTable);

    // Load statistics on page load
    loadStatistics();
});

function loadStatistics() {
    fetch('{{ route("teacher-admin.enrollments.statistics") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('pending-count').textContent = data.pending || 0;
            document.getElementById('verified-count').textContent = data.verified || 0;
            document.getElementById('approved-count').textContent = data.approved || 0;
            document.getElementById('enrolled-count').textContent = data.enrolled || 0;
            document.getElementById('rejected-count').textContent = data.rejected || 0;
            document.getElementById('total-count').textContent = data.total || 0;
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

function showVerifyModal(enrollmentId) {
    const form = document.getElementById('verifyForm');
    form.action = `/teacher-admin/enrollments/${enrollmentId}/verify`;
    const verifyModal = new bootstrap.Modal(document.getElementById('verifyModal'));
    verifyModal.show();
}

function showRejectModal(enrollmentId) {
    const form = document.getElementById('rejectForm');
    form.action = `/teacher-admin/enrollments/${enrollmentId}/reject`;
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    rejectModal.show();
}

function showAssignSectionModal(enrollmentId, studentName, gradeLevel) {
    document.getElementById('assignSectionStudentName').textContent = studentName;
    document.getElementById('assignSectionGradeLevel').textContent = gradeLevel;
    
    fetch(`/teacher-admin/enrollments/${enrollmentId}/sections`)
        .then(response => response.json())
        .then(data => {
            const sectionSelect = document.getElementById('assign_section_id');
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            
            data.sections.forEach(section => {
                const currentCount = section.current_student_count || 0;
                const capacity = section.student_limit || 'No limit';
                const isFull = capacity !== 'No limit' && currentCount >= capacity;
                const capacityText = capacity === 'No limit' ? `(${currentCount})` : `(${currentCount}/${capacity})`;
                
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = `${section.name} (${section.grade_level}) ${capacityText}${isFull ? ' - FULL' : ''}`;
                option.disabled = isFull;
                if (isFull) option.style.color = '#dc3545';
                
                sectionSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading sections:', error);
            alert('Error loading sections. Please try again.');
        });
    
    const form = document.getElementById('assignSectionForm');
    form.action = `/teacher-admin/enrollments/${enrollmentId}/assign-section`;
    
    const assignSectionModal = new bootstrap.Modal(document.getElementById('assignSectionModal'));
    assignSectionModal.show();
}

function showQuickAssignModal(enrollmentId, studentName, gradeLevel) {
    document.getElementById('quickAssignStudentName').textContent = studentName;
    document.getElementById('quickAssignGradeLevel').textContent = gradeLevel;
    
    fetch(`/teacher-admin/enrollments/${enrollmentId}/sections`)
        .then(response => response.json())
        .then(data => {
            const sectionSelect = document.getElementById('quickAssignSection');
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            
            data.sections.forEach(section => {
                const currentCount = section.current_student_count || 0;
                const capacity = section.student_limit || 'No limit';
                const isFull = capacity !== 'No limit' && currentCount >= capacity;
                const capacityText = capacity === 'No limit' ? `(${currentCount})` : `(${currentCount}/${capacity})`;
                
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = `${section.name} (${section.grade_level}) ${capacityText}${isFull ? ' - FULL' : ''}`;
                option.disabled = isFull;
                if (isFull) option.style.color = '#dc3545';
                
                sectionSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading sections:', error);
            alert('Error loading sections. Please try again.');
        });
    
    const form = document.getElementById('quickAssignForm');
    form.action = `/teacher-admin/enrollments/${enrollmentId}/quick-assign`;
    
    const quickAssignModal = new bootstrap.Modal(document.getElementById('quickAssignModal'));
    quickAssignModal.show();
}

function showBulkApproveModal() {
    const checkboxes = document.querySelectorAll('.enrollment-checkbox:checked');
    const assignmentsDiv = document.getElementById('bulk-assignments');
    assignmentsDiv.innerHTML = '';

    checkboxes.forEach(checkbox => {
        const enrollmentId = checkbox.value;
        const row = checkbox.closest('tr');
        const studentName = row.querySelector('strong').textContent;
        const gradeLevel = row.querySelector('td:nth-child(4) strong').textContent;

        fetch(`/teacher-admin/enrollments/${enrollmentId}/sections`)
            .then(response => response.json())
            .then(data => {
                let sectionOptions = '<option value="">Select Section</option>';
                data.sections.forEach(section => {
                    const currentCount = section.current_student_count || 0;
                    const capacity = section.student_limit || 'No limit';
                    const isFull = capacity !== 'No limit' && currentCount >= capacity;
                    const capacityText = capacity === 'No limit' ? `(${currentCount})` : `(${currentCount}/${capacity})`;
                    sectionOptions += `<option value="${section.id}" ${isFull ? 'disabled' : ''} class="${isFull ? 'text-danger' : ''}">
                        ${section.name} (${section.grade_level}) ${capacityText}${isFull ? ' - FULL' : ''}
                    </option>`;
                });

                const assignmentHtml = `
                    <div class="mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <label><strong>${studentName}</strong> (${gradeLevel})</label>
                                <input type="hidden" name="enrollment_ids[]" value="${enrollmentId}">
                            </div>
                            <div class="col-md-6">
                                <select name="section_assignments[${enrollmentId}]" class="form-select" required>
                                    ${sectionOptions}
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                assignmentsDiv.innerHTML += assignmentHtml;
            })
            .catch(error => {
                console.error('Error loading sections for bulk assign:', error);
                assignmentsDiv.innerHTML += `
                    <div class="mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <label><strong>${studentName}</strong> (${gradeLevel})</label>
                                <input type="hidden" name="enrollment_ids[]" value="${enrollmentId}">
                            </div>
                            <div class="col-md-6">
                                <select name="section_assignments[${enrollmentId}]" class="form-select" required>
                                    <option value="">Error loading sections</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
            });
    });

    const bulkApproveModal = new bootstrap.Modal(document.getElementById('bulkApproveModal'));
    bulkApproveModal.show();
}
</script>
@endpush
@endsection