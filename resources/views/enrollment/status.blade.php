@extends('layouts.app')

@push('styles')
<style>
    /* Form styling */
    .form-section {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }

    /* Dark mode support */
    .dark .form-section {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .section-title {
        color: var(--text-color);
        border-bottom-color: var(--border-color);
    }

    .dark .card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .card-header {
        background-color: var(--bg-card-header) !important;
        border-bottom-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .card-header h5 {
        color: var(--text-color);
    }

    .dark .card-header .text-primary {
        color: #4361ee !important;
    }

    .dark .card-body {
        color: var(--text-color);
    }

    .dark .text-muted {
        color: var(--text-muted) !important;
    }
</style>
@endpush

@section('title', 'Enrollment Application Status')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-check text-primary me-2"></i> Enrollment Application Status
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('enrollment.create') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> New Application
                            </a>
                            <a href="{{ route('enrollment.status.form') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-search me-1"></i> Status Lookup
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- Status Card -->
                    <div class="form-section">
                        <h6 class="section-title">Application Status</h6>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div></div>
                            <div>
                                @if($enrollment->status === 'pending')
                                    <span class="badge bg-warning text-dark d-flex align-items-center">
                                        <i class="fas fa-clock me-1"></i>
                                        Pending Review
                                    </span>
                                @elseif($enrollment->status === 'approved')
                                    <span class="badge bg-success d-flex align-items-center">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Approved
                                    </span>
                                @elseif($enrollment->status === 'enrolled')
                                    <span class="badge bg-primary d-flex align-items-center">
                                        <i class="fas fa-user-check me-1"></i>
                                        Enrolled
                                    </span>
                                @elseif($enrollment->status === 'rejected')
                                    <span class="badge bg-danger d-flex align-items-center">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Rejected
                                    </span>
                                @endif
                </div>
            </div>

                        <!-- Application Details -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Application ID</label>
                                <p class="h6 font-monospace">{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Application Date</label>
                                <p class="h6">{{ $enrollment->application_date->format('F j, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Student Name</label>
                                <p class="h6">{{ $enrollment->first_name }} {{ $enrollment->middle_name }} {{ $enrollment->last_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">School</label>
                                <p class="h6">{{ $enrollment->school->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Preferred Grade Level</label>
                                <p class="h6"> {{ $enrollment->preferred_grade_level }}</p>
                            </div>
                            @if($enrollment->preferred_section)
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Preferred Section</label>
                                    <p class="h6">{{ $enrollment->preferred_section }}</p>
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Processing Information -->
                    @if($enrollment->processed_at || $enrollment->processed_by || $enrollment->assigned_section_id)
                        <div class="form-section">
                            <h6 class="section-title">Processing Information</h6>
                            <div class="row g-3">
                                @if($enrollment->processed_at)
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Processed Date</label>
                                        <p class="h6">{{ $enrollment->processed_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                @endif
                                @if($enrollment->assigned_section_id && $enrollment->assignedSection)
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Assigned Section</label>
                                        <p class="h6">{{ $enrollment->assignedSection->name }} (Grade {{ $enrollment->assignedSection->grade_level }})</p>
                                    </div>
                                @endif
                            </div>
                            @if($enrollment->notes)
                                <div class="mt-3">
                                    <label class="form-label text-muted small">Notes</label>
                                    <div class="bg-light p-3 rounded">{{ $enrollment->notes }}</div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Status-specific Information -->
                    @if($enrollment->status === 'pending')
                        <div class="alert alert-warning d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Application Under Review</h6>
                                <p class="mb-1">Your application is currently being reviewed by the school's admission team. This process typically takes 3-5 business days.</p>
                                <p class="mb-0">You may be contacted if additional information or documents are needed.</p>
                            </div>
                        </div>
                    @elseif($enrollment->status === 'approved')
                        <div class="alert alert-success d-flex align-items-start">
                            <i class="fas fa-check-circle me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Application Approved!</h6>
                                <p class="mb-1">Congratulations! Your enrollment application has been approved.</p>
                                @if($enrollment->assigned_section_id)
                                    <p class="mb-1">You have been assigned to <strong>{{ $enrollment->assignedSection->name }}</strong>.</p>
                                @endif
                                <p class="mb-0">The school will contact you soon with next steps for enrollment completion.</p>
                            </div>
                        </div>
                    @elseif($enrollment->status === 'enrolled')
                        <div class="alert alert-primary d-flex align-items-start">
                            <i class="fas fa-user-check me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Successfully Enrolled!</h6>
                                <p class="mb-1">You are now officially enrolled at {{ $enrollment->school->name }}.</p>
                                @if($enrollment->assigned_section_id)
                                    <p class="mb-1">Section: <strong>{{ $enrollment->assignedSection->name }}</strong> (Grade {{ $enrollment->assignedSection->grade_level }})</p>
                                @endif
                                <p class="mb-0">Welcome to the school! Please contact the school for class schedules and other important information.</p>
                            </div>
                        </div>
                    @elseif($enrollment->status === 'rejected')
                        <div class="alert alert-danger d-flex align-items-start">
                            <i class="fas fa-times-circle me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Application Not Approved</h6>
                                <p class="mb-1">Unfortunately, your enrollment application was not approved at this time.</p>
                                @if($enrollment->notes)
                                    <p class="mb-1">Reason: {{ $enrollment->notes }}</p>
                                @endif
                                <p class="mb-0">You may contact the school directly for more information or to discuss reapplication options.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="form-section">
                        <h6 class="section-title">School Contact Information</h6>
                        <div class="text-muted">
                            <p class="fw-bold text-dark">{{ $enrollment->school->name }}</p>
                            @if($enrollment->school->address)
                                <p class="mb-1">{{ $enrollment->school->address }}</p>
                            @endif
                            @if($enrollment->school->contact_number)
                                <p class="mb-1">Phone: {{ $enrollment->school->contact_number }}</p>
                            @endif
                            @if($enrollment->school->email)
                                <p class="mb-0">Email: {{ $enrollment->school->email }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="{{ route('enrollment.status.form') }}" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-2"></i>Check Another Application
                        </a>
                        <a href="{{ route('enrollment.create') }}" class="btn btn-outline-secondary flex-fill">
                            <i class="fas fa-plus me-2"></i>Submit New Application
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection