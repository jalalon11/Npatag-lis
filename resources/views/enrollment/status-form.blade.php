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

    .required:after {
        content: " *";
        color: #dc3545;
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

    .dark .form-control {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .form-control:focus {
        background-color: var(--bg-card);
        border-color: #4361ee;
        color: var(--text-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .dark .form-control::placeholder {
        color: var(--text-muted);
    }

    .dark .form-label {
        color: var(--text-color);
    }

    .dark .form-text {
        color: var(--text-muted);
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

    .dark .card-header .text-primary {
        color: #4361ee !important;
    }

    .dark .card-body {
        color: var(--text-color);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-search text-primary me-2"></i> Check Enrollment Status
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('enrollment.create') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> New Application
                            </a>
                            <div class="text-muted">
                                <i class="fas fa-clipboard-check me-1"></i> Status Lookup
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('enrollment.status') }}" method="POST" id="statusForm">
                        @csrf

                        <!-- Application Information -->
                        <div class="form-section">
                            <h6 class="section-title">Application Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="enrollment_id" class="form-label">Application ID <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">#</span>
                                            <input type="text" class="form-control @error('enrollment_id') is-invalid @enderror"
                                                id="enrollment_id" name="enrollment_id" value="{{ old('enrollment_id') }}" 
                                                placeholder="000123" pattern="\d{6}" maxlength="6" required>
                                        </div>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i> Enter the 6-digit ID from your application confirmation.
                                        </div>
                                        @error('enrollment_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Student's Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                            placeholder="Enter student's last name" required>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i> Use the exact last name from your application.
                                        </div>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_date" class="form-label">Student's Birth Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                            id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i> Enter the birth date as provided in your application.
                                        </div>
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Help Information -->
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

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-1"></i> Check Status
                            </button>
                        </div>
                    </form>

                    <!-- Navigation Links -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <a href="{{ route('enrollment.create') }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-plus me-1"></i> Submit New Application
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-home me-1"></i> Back to Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const birthDateInput = document.getElementById('birth_date');
        const statusForm = document.getElementById('statusForm');
        const submitBtn = document.querySelector('#statusForm button[type="submit"]');
        const enrollmentIdInput = document.getElementById('enrollment_id');

        // Set max date to today for birth date
        if (birthDateInput) {
            birthDateInput.max = new Date().toISOString().split('T')[0];
        }

        // Format enrollment ID input
        if (enrollmentIdInput) {
            enrollmentIdInput.addEventListener('input', function(e) {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Limit to 6 digits
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6);
                }
            });
        }

        // Prevent multiple form submissions
        if (statusForm && submitBtn) {
            statusForm.addEventListener('submit', function(e) {
                // Check if the form is already being submitted
                if (statusForm.classList.contains('submitting')) {
                    e.preventDefault();
                    return false;
                }

                // Add submitting class to form
                statusForm.classList.add('submitting');

                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Checking Status...';

                // Allow form submission
                return true;
            });
        }
    });
</script>
@endpush
@endsection