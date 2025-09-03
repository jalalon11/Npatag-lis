
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    /* Adapted styles from account-management */
    :root {
        --border-radius: 8px;
        --border-radius-pill: 50px;
        --padding-sm: 0.75rem;
        --padding-md: 1rem;
        --margin-sm: 1rem;
        --margin-md: 1.5rem;
        --transition: all 0.2s ease-in-out;
    }

    .card {
        border: none !important;
        border-radius: var(--border-radius) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        transition: var(--transition);
    }

    .card-header {
        background: white !important;
        border-bottom: none !important;
        padding: var(--padding-md) !important;
    }

    .badge {
        border-radius: var(--border-radius-pill);
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .btn {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1rem;
        transition: var(--transition);
    }

    .form-control, .form-select, .input-group-text {
        border-radius: var(--border-radius-pill);
    }

    .small, .form-text {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .toast {
        border-radius: var(--border-radius);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .toast-header {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Main Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Academic Management</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Academic Management</li>
            </ol>
        </nav>
    </div>

    <!-- Alerts -->
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

    <div class="row">
        <!-- Quarter Selection Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0">Quarter Selection</h5>
                </div>
                <div class="card-body">
                    <form id="quarterForm">
                        @csrf
                        <div class="mb-3">
                            <label for="quarter" class="form-label">Current Active Quarter</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt text-muted"></i>
                                </span>
                                <select class="form-select border-start-0" id="quarter" name="quarter">
                                    @foreach($quarters as $key => $label)
                                        <option value="{{ $key }}" {{ $currentQuarter == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Quarter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- School Year Management Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0">School Year Management</h5>
                </div>
                <div class="card-body">
                    <form id="schoolYearForm">
                        @csrf
                        <div class="mb-3">
                            <label for="school_year" class="form-label">Current School Year</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="school_year" name="school_year" 
                                       value="{{ $currentSchoolYear }}" placeholder="2024-2025" 
                                       pattern="\d{4}-\d{4}" title="Format: YYYY-YYYY">
                            </div>
                            <div class="form-text">Format: YYYY-YYYY (e.g., 2024-2025)</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update School Year
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Principal Management Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0">Principal Management</h5>
                </div>
                <div class="card-body">
                    <form id="principalForm">
                        @csrf
                        <div class="mb-3">
                            <label for="principal_name" class="form-label">Principal Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-user-tie text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="principal_name" name="principal_name" 
                                       value="{{ $principalName }}" placeholder="Enter principal's full name">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Principal Name
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- School Information Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0">School Information</h5>
                </div>
                <div class="card-body">
                    <form id="schoolDetailsForm">
                        @csrf
                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-school text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="school_name" name="school_name" 
                                       value="{{ $schoolName }}" placeholder="Enter school name">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="school_address" class="form-label">School Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                </span>
                                <textarea class="form-control border-start-0" id="school_address" name="school_address" 
                                          rows="3" placeholder="Enter school address">{{ $schoolAddress }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update School Details
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-bell me-2"></i>
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-message">
                <!-- Message will be inserted here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Function to show toast notification
    function showToast(message, type = 'success') {
        const toast = $('#toast');
        const toastMessage = $('#toast-message');
        
        // Set message and styling
        toastMessage.text(message);
        toast.removeClass('bg-success bg-danger text-white');
        
        if (type === 'success') {
            toast.addClass('bg-success text-white');
        } else {
            toast.addClass('bg-danger text-white');
        }
        
        // Show toast
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
    }
    
    // Quarter Form Submission
    $('#quarterForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');
        
        $.ajax({
            url: '{{ route("admin.academics.update-quarter") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'An error occurred', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update Quarter');
            }
        });
    });
    
    // School Year Form Submission
    $('#schoolYearForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');
        
        $.ajax({
            url: '{{ route("admin.academics.update-school-year") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'An error occurred', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update School Year');
            }
        });
    });
    
    // Principal Form Submission
    $('#principalForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');
        
        $.ajax({
            url: '{{ route("admin.academics.update-principal") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'An error occurred', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update Principal Name');
            }
        });
    });
    
    // School Details Form Submission
    $('#schoolDetailsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');
        
        $.ajax({
            url: '{{ route("admin.academics.update-school-details") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'An error occurred', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update School Details');
            }
        });
    });
});
</script>
@endpush