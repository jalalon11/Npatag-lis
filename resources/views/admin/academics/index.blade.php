@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Academic Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Academic Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quarter Selection Card -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Quarter Selection</h4>
                </div>
                <div class="card-body">
                    <form id="quarterForm">
                        @csrf
                        <div class="mb-3">
                            <label for="quarter" class="form-label">Current Active Quarter</label>
                            <select class="form-select" id="quarter" name="quarter">
                                @foreach($quarters as $key => $label)
                                    <option value="{{ $key }}" {{ $currentQuarter == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Update Quarter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- School Year Management Card -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">School Year Management</h4>
                </div>
                <div class="card-body">
                    <form id="schoolYearForm">
                        @csrf
                        <div class="mb-3">
                            <label for="school_year" class="form-label">Current School Year</label>
                            <input type="text" class="form-control" id="school_year" name="school_year" 
                                   value="{{ $currentSchoolYear }}" placeholder="2024-2025" 
                                   pattern="\d{4}-\d{4}" title="Format: YYYY-YYYY">
                            <div class="form-text">Format: YYYY-YYYY (e.g., 2024-2025)</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Update School Year
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Principal Management Card -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Principal Management</h4>
                </div>
                <div class="card-body">
                    <form id="principalForm">
                        @csrf
                        <div class="mb-3">
                            <label for="principal_name" class="form-label">Principal Name</label>
                            <input type="text" class="form-control" id="principal_name" name="principal_name" 
                                   value="{{ $principalName }}" placeholder="Enter principal's full name">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Update Principal Name
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- School Information Card -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">School Information</h4>
                </div>
                <div class="card-body">
                    <form id="schoolDetailsForm">
                        @csrf
                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name</label>
                            <input type="text" class="form-control" id="school_name" name="school_name" 
                                   value="{{ $schoolName }}" 
                                   placeholder="Enter school name">
                        </div>
                        <div class="mb-3">
                            <label for="school_address" class="form-label">School Address</label>
                            <textarea class="form-control" id="school_address" name="school_address" 
                                      rows="3" placeholder="Enter school address">{{ $schoolAddress }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Update School Details
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message">
            <!-- Message will be inserted here -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
        
        submitBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Updating...');
        
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
                submitBtn.prop('disabled', false).html('<i class="bx bx-save"></i> Update Quarter');
            }
        });
    });
    
    // School Year Form Submission
    $('#schoolYearForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Updating...');
        
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
                submitBtn.prop('disabled', false).html('<i class="bx bx-save"></i> Update School Year');
            }
        });
    });
    
    // Principal Form Submission
    $('#principalForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Updating...');
        
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
                submitBtn.prop('disabled', false).html('<i class="bx bx-save"></i> Update Principal Name');
            }
        });
    });
    
    // School Details Form Submission
    $('#schoolDetailsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Updating...');
        
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
                submitBtn.prop('disabled', false).html('<i class="bx bx-save"></i> Update School Details');
            }
        });
    });
});
</script>
@endsection