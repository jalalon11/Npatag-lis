@extends('layouts.app')

@section('title', 'Admission Application Details')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-graduate me-2"></i>{{ $enrollment->first_name }} {{ $enrollment->last_name }}
            </h1>
            <p class="text-muted mb-0">Application ID: {{ $enrollment->application_id }}</p>
        </div>
        <div class="d-flex gap-2">
            @if(in_array($enrollment->status, ['pending', 'verified']))
                <button type="button" class="btn btn-success" onclick="approveAdmission()">
                    <i class="fas fa-thumbs-up me-2"></i>Approve
                </button>
                <button type="button" class="btn btn-danger" onclick="rejectAdmission()">
                    <i class="fas fa-thumbs-down me-2"></i>Reject
                </button>
            @endif
            <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Applications
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Application Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Application Status
                    </h6>
                    <span class="badge badge-{{ 
                        $enrollment->status === 'approved' ? 'success' : 
                        ($enrollment->status === 'rejected' ? 'danger' : 
                        ($enrollment->status === 'verified' ? 'info' : 'warning'))
                    }} p-2">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Application ID:</strong><br>
                            <span class="h6">{{ $enrollment->application_id }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Applied Date:</strong><br>
                            <span>{{ $enrollment->created_at->format('F d, Y g:i A') }}</span>
                            <small class="text-muted d-block">{{ $enrollment->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Current Status:</strong><br>
                            <span class="badge badge-{{ 
                                $enrollment->status === 'approved' ? 'success' : 
                                ($enrollment->status === 'rejected' ? 'danger' : 
                                ($enrollment->status === 'verified' ? 'info' : 'warning'))
                            }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Last Updated:</strong><br>
                            <span>{{ $enrollment->updated_at->format('F d, Y g:i A') }}</span>
                        </div>
                        @if($enrollment->status === 'rejected' && $enrollment->rejection_reason)
                            <div class="col-12">
                                <strong class="text-danger">Rejection Reason:</strong><br>
                                <div class="alert alert-danger">
                                    {{ $enrollment->rejection_reason }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-id-card me-2"></i>Personal Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong class="text-success">First Name:</strong><br>
                            <span>{{ $enrollment->first_name }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong class="text-success">Middle Name:</strong><br>
                            <span>{{ $enrollment->middle_name ?: 'Not provided' }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong class="text-success">Last Name:</strong><br>
                            <span>{{ $enrollment->last_name }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-success">Date of Birth:</strong><br>
                            <span>{{ $enrollment->date_of_birth?->format('F d, Y') ?? 'Not provided' }}</span>
                            @if($enrollment->date_of_birth)
                                <small class="text-muted d-block">
                                    Age: {{ $enrollment->date_of_birth->age }} years old
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-success">Gender:</strong><br>
                            <span>{{ $enrollment->gender ? ucfirst($enrollment->gender) : 'Not specified' }}</span>
                        </div>
                        @if($enrollment->address)
                            <div class="col-12 mb-3">
                                <strong class="text-success">Address:</strong><br>
                                <span>{{ $enrollment->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-envelope me-2"></i>Contact Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="text-info">Email Address:</strong><br>
                            <a href="mailto:{{ $enrollment->email }}" class="text-decoration-none">
                                <i class="fas fa-envelope me-1"></i>{{ $enrollment->email }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-info">Phone Number:</strong><br>
                            @if($enrollment->phone)
                                <a href="tel:{{ $enrollment->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i>{{ $enrollment->phone }}
                                </a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            @if($enrollment->guardian_name || $enrollment->guardian_phone || $enrollment->guardian_email)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-users me-2"></i>Guardian Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($enrollment->guardian_name)
                            <div class="col-md-6 mb-3">
                                <strong class="text-warning">Guardian Name:</strong><br>
                                <span>{{ $enrollment->guardian_name }}</span>
                            </div>
                        @endif
                        @if($enrollment->guardian_phone)
                            <div class="col-md-6 mb-3">
                                <strong class="text-warning">Guardian Phone:</strong><br>
                                <a href="tel:{{ $enrollment->guardian_phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i>{{ $enrollment->guardian_phone }}
                                </a>
                            </div>
                        @endif
                        @if($enrollment->guardian_email)
                            <div class="col-md-6 mb-3">
                                <strong class="text-warning">Guardian Email:</strong><br>
                                <a href="mailto:{{ $enrollment->guardian_email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i>{{ $enrollment->guardian_email }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Academic Preferences -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-purple">
                        <i class="fas fa-graduation-cap me-2"></i>Academic Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="text-purple">Preferred School:</strong><br>
                            <span>{{ $enrollment->school->name ?? 'Not specified' }}</span>
                        </div>
                        @if($enrollment->section_id)
                            <div class="col-md-6 mb-3">
                                <strong class="text-purple">Assigned Section:</strong><br>
                                <span class="badge badge-info">{{ $enrollment->section->name ?? 'N/A' }}</span>
                            </div>
                        @endif
                        @if($enrollment->previous_school)
                            <div class="col-12 mb-3">
                                <strong class="text-purple">Previous School:</strong><br>
                                <span>{{ $enrollment->previous_school }}</span>
                            </div>
                        @endif
                        @if($enrollment->additional_notes)
                            <div class="col-12">
                                <strong class="text-purple">Additional Notes:</strong><br>
                                <div class="bg-light p-3 rounded">
                                    {{ $enrollment->additional_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($enrollment->status === 'pending')
                            <button type="button" class="btn btn-info btn-sm" onclick="verifyAdmission()">
                                <i class="fas fa-check me-2"></i>Verify Application
                            </button>
                        @endif
                        
                        @if(in_array($enrollment->status, ['pending', 'verified']))
                            <button type="button" class="btn btn-success btn-sm" onclick="approveEnrollment()">
                                <i class="fas fa-thumbs-up me-2"></i>Approve & Create Student
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="rejectEnrollment()">
                                <i class="fas fa-thumbs-down me-2"></i>Reject Application
                            </button>
                        @endif
                        
                        @if($enrollment->status === 'approved' && $enrollment->student_id)
                            <a href="{{ route('admin.students.show', $enrollment->student_id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-user me-2"></i>View Student Record
                            </a>
                        @endif
                        
                        <hr>
                        <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-list me-2"></i>All Applications
                        </a>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="printApplication()">
                            <i class="fas fa-print me-2"></i>Print Application
                        </button>
                    </div>
                </div>
            </div>

            <!-- Application Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-history me-2"></i>Application Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @if($enrollment->status === 'approved')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Application Approved</h6>
                                    <p class="text-muted mb-0 small">Student record created</p>
                                    <small class="text-muted">{{ $enrollment->updated_at->format('M d, Y g:i A') }}</small>
                                </div>
                            </div>
                        @elseif($enrollment->status === 'rejected')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Application Rejected</h6>
                                    <p class="text-muted mb-0 small">Application was rejected</p>
                                    <small class="text-muted">{{ $enrollment->updated_at->format('M d, Y g:i A') }}</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($enrollment->status === 'verified' || $enrollment->status === 'approved')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Application Verified</h6>
                                    <p class="text-muted mb-0 small">Documents verified by admin</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Application Submitted</h6>
                                <p class="text-muted mb-0 small">Initial application received</p>
                                <small class="text-muted">{{ $enrollment->created_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-address-book me-2"></i>Contact
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <a href="mailto:{{ $enrollment->email }}" class="text-decoration-none">
                            {{ $enrollment->email }}
                        </a>
                    </div>
                    @if($enrollment->phone)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-success me-2"></i>
                            <a href="tel:{{ $enrollment->phone }}" class="text-decoration-none">
                                {{ $enrollment->phone }}
                            </a>
                        </div>
                    @endif
                    @if($enrollment->guardian_phone)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-friends text-info me-2"></i>
                            <a href="tel:{{ $enrollment->guardian_phone }}" class="text-decoration-none">
                                {{ $enrollment->guardian_phone }} (Guardian)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.gap-2 {
    gap: 0.5rem;
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

.text-purple {
    color: #6f42c1 !important;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    height: calc(100% + 10px);
    width: 2px;
    background-color: #e3e6f0;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-content h6 {
    color: #5a5c69;
    font-weight: 600;
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
function verifyAdmission() {
    if (confirm('Are you sure you want to verify this admission application?')) {
        fetch(`/admin/admissions/{{ $enrollment->id }}/verify`, {
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

function approveAdmission() {
    const sections = @json($sections ?? []);
    let sectionOptions = '<option value="">Select Section</option>';
    sections.forEach(section => {
        sectionOptions += `<option value="${section.id}">${section.name} - ${section.school.name}</option>`;
    });
    
    // Create a simple section selection dialog
    const sectionId = prompt('Enter section ID for this student:\n' + sections.map(s => `${s.id}: ${s.name} - ${s.school.name}`).join('\n'));
    
    if (sectionId && confirm('Are you sure you want to approve this admission and create a student record?')) {
        fetch(`/admin/admissions/{{ $enrollment->id }}/approve`, {
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

function rejectAdmission() {
    const reason = prompt('Please provide a reason for rejection (optional):');
    
    if (confirm('Are you sure you want to reject this admission application?')) {
        fetch(`/admin/admissions/{{ $enrollment->id }}/reject`, {
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

function printApplication() {
    window.print();
}
</script>
@endpush
@endsection