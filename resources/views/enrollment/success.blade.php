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

    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .info-card .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .info-card .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
    }

    .success-icon {
        width: 4rem;
        height: 4rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
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

    .dark .info-card {
        background: var(--bg-card-header);
        border-color: var(--border-color);
    }

    .dark .info-card .info-label {
        color: var(--text-muted);
    }

    .dark .info-card .info-value {
        color: var(--text-color);
    }

    .dark .text-muted {
        color: var(--text-muted) !important;
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

    .dark .card-header .text-success {
        color: #28a745 !important;
    }

    .dark .card-body {
        color: var(--text-color);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Application Submitted Successfully
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('enrollment.status') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search me-1"></i> Check Status
                            </a>
                            <div class="text-muted">
                                <i class="fas fa-clipboard-check me-1"></i> Confirmation
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Success Message -->
                    <div class="text-center mb-4">
                        <div class="success-icon">
                            <i class="fas fa-check text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <h4 class="text-success mb-2">Application Submitted Successfully!</h4>
                        <p class="text-muted">Your enrollment application for <strong>{{ $enrollment->first_name }} {{ $enrollment->last_name }}</strong> has been submitted and is now under review.</p>
                    </div>

                    <!-- Application Details -->
                    <div class="form-section">
                        <h6 class="section-title">Application Details</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-hashtag me-1"></i> Application ID
                                    </div>
                                    <div class="info-value text-primary fw-bold">
                                        #{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-user me-1"></i> Student Name
                                    </div>
                                    <div class="info-value">
                                        {{ $enrollment->first_name }} {{ $enrollment->middle_name }} {{ $enrollment->last_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-graduation-cap me-1"></i> Preferred Grade Level
                                    </div>
                                    <div class="info-value">
                                        {{ $enrollment->preferred_grade_level }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-calendar me-1"></i> Application Date
                                    </div>
                                    <div class="info-value">
                                        {{ $enrollment->application_date->format('F j, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-school me-1"></i> School
                                    </div>
                                    <div class="info-value">
                                        {{ $enrollment->school->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-info-circle me-1"></i> Status
                                    </div>
                                    <div class="info-value">
                                        <span class="badge bg-warning text-dark">{{ ucfirst($enrollment->status) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="form-section">
                        <h6 class="section-title">Important Information</h6>
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i> Please Save This Information
                            </h6>
                            <ul class="mb-0">
                                <li><strong>Application ID:</strong> #{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }} - You'll need this to check your application status</li>
                                <li><strong>Processing Time:</strong> Applications are typically reviewed within 5-7 business days</li>
                                <li><strong>Status Updates:</strong> You'll receive email notifications when your application status changes</li>
                                <li><strong>Required Documents:</strong> Please ensure all required documents are submitted before the deadline</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="form-section">
                        <h6 class="section-title">Next Steps</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary rounded-circle" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;">1</span>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Wait for Review</h6>
                                        <p class="text-muted small mb-0">Our admissions team will review your application within 5-7 business days.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary rounded-circle" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;">2</span>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Check Status</h6>
                                        <p class="text-muted small mb-0">Use your Application ID to check the status of your application anytime.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary rounded-circle" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;">3</span>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Get Notified</h6>
                                        <p class="text-muted small mb-0">You'll receive email updates when your application status changes.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <a href="{{ route('enrollment.status') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Check Application Status
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('enrollment.create') }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-plus me-1"></i> Submit Another Application
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-home me-1"></i> Back to Home
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="form-section">
                        <h6 class="section-title">Need Help?</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-phone text-primary me-2 mt-1"></i>
                                    <div>
                                        <strong>Phone Support</strong><br>
                                        <span class="text-muted">Call us at: (123) 456-7890</span><br>
                                        <small class="text-muted">Monday - Friday, 8:00 AM - 5:00 PM</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-envelope text-primary me-2 mt-1"></i>
                                    <div>
                                        <strong>Email Support</strong><br>
                                        <a href="mailto:support@school.edu" class="text-decoration-none">support@school.edu</a><br>
                                        <small class="text-muted">We'll respond within 24 hours</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection