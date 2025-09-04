@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
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

    .btn {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1rem;
        transition: var(--transition);
    }

    .form-control, .form-select, .input-group-text {
        border-radius: var(--border-radius-pill);
    }

    .logo-preview {
        max-width: 200px;
        max-height: 200px;
        border-radius: var(--border-radius);
        border: 2px dashed #dee2e6;
        padding: 1rem;
        text-align: center;
        background: #f8f9fa;
    }

    .logo-preview img {
        max-width: 100%;
        max-height: 150px;
        border-radius: var(--border-radius);
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: var(--transition);
    }

    .upload-area:hover {
        border-color: #007bff;
        background: #e3f2fd;
    }

    .upload-area.dragover {
        border-color: #007bff;
        background: #e3f2fd;
    }

    .readonly-field {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">School Details Management</h2>
                    <p class="text-muted mb-0">Manage comprehensive school information and logo</p>
                </div>
                <div>
                    <a href="{{ route('admin.academics.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Academic
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- School Logo Section -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0"><i class="fas fa-image me-2"></i>School Logo</h5>
                </div>
                <div class="card-body">
                    <div class="logo-preview mb-3">
                        @if($school && $school->logo_url)
                            <img src="{{ $school->logo_url }}" alt="School Logo" id="logoPreview">
                        @else
                            <div id="logoPlaceholder">
                                <i class="fas fa-school fa-3x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No logo uploaded</p>
                            </div>
                        @endif
                    </div>
                    
                    <form id="logoUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-area mb-3" onclick="document.getElementById('logoInput').click()">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="mb-1">Click to upload or drag and drop</p>
                            <small class="text-muted">PNG, JPG, GIF up to 2MB</small>
                        </div>
                        <input type="file" id="logoInput" name="logo" accept="image/*" style="display: none;">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="uploadBtn" style="display: none;">
                                <i class="fas fa-upload me-1"></i> Upload Logo
                            </button>
                            @if($school && $school->logo_url)
                                <button type="button" class="btn btn-outline-danger" id="deleteLogoBtn">
                                    <i class="fas fa-trash me-1"></i> Delete Logo
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- School Information Section -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0"><i class="fas fa-school me-2"></i>School Information</h5>
                </div>
                <div class="card-body">
                    <form id="schoolDetailsForm">
                        @csrf
                        
                        <!-- School Information -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-school text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="name" name="name" 
                                           value="{{ $school->name }}" placeholder="Enter school name" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">School Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-hashtag text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="code" name="code" 
                                           value="{{ $school->code }}" placeholder="Enter school code" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">School Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                    </span>
                                    <textarea class="form-control border-start-0" id="address" name="address" rows="2" 
                                              placeholder="Enter school address" required>{{ $school->address }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-globe text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="region" name="region" 
                                           value="{{ $school->region }}" placeholder="Enter region" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="grade_levels" class="form-label">Grade Levels <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-layer-group text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="grade_levels" name="grade_levels" 
                                           value="{{ is_array($school->grade_levels) ? implode(', ', $school->grade_levels) : $school->grade_levels }}" 
                                           placeholder="Enter grade levels (e.g., K-12, 1-6)" required>
                                </div>
                            </div>
                        </div>

                        <!-- Division Information -->
                        <hr class="my-4">
                        <h6 class="mb-3"><i class="fas fa-building me-2"></i>Division Information</h6>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="division_name" class="form-label">Division Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-building text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="division_name" name="division_name" 
                                           value="{{ $school->division_name }}" placeholder="Enter division name" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="division_code" class="form-label">Division Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-hashtag text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="division_code" name="division_code" 
                                           value="{{ $school->division_code }}" placeholder="Enter division code" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="division_address" class="form-label">Division Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                    </span>
                                    <textarea class="form-control border-start-0" id="division_address" name="division_address" rows="2" 
                                              placeholder="Enter division address" required>{{ $school->division_address }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Principal Information -->
                        <hr class="my-4">
                        <h6 class="mb-3"><i class="fas fa-user-tie me-2"></i>Principal Information</h6>
                        
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="principal" class="form-label">Principal Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="principal" name="principal" 
                                           value="{{ $school->principal }}" placeholder="Enter principal name" required>
                                </div>
                                <small class="form-text text-muted">This field can be updated</small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update School Details
                            </button>
                        </div>
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
            <i class="fas fa-bell me-2"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            <!-- Message will be inserted here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Logo upload functionality
    $('#logoInput').on('change', function() {
        const file = this.files[0];
        if (file) {
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logoPreview').attr('src', e.target.result).show();
                $('#logoPlaceholder').hide();
            };
            reader.readAsDataURL(file);
            
            // Show upload button
            $('#uploadBtn').show();
        }
    });

    // Drag and drop functionality
    $('.upload-area').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    $('.upload-area').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    $('.upload-area').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $('#logoInput')[0].files = files;
            $('#logoInput').trigger('change');
        }
    });

    // Logo upload form submission
    $('#logoUploadForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const logoFile = $('#logoInput')[0].files[0];
        const principal = $('#principal').val();
        
        if (!logoFile) {
            showToast('Please select a logo file.', 'error');
            return;
        }
        
        formData.append('logo', logoFile);
        formData.append('principal', principal);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: '{{ route("admin.academics.school-details.update") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    $('#uploadBtn').hide();
                    
                    // Update delete button visibility
                    if (response.logo_url && $('#deleteLogoBtn').length === 0) {
                        $('#uploadBtn').after('<button type="button" class="btn btn-outline-danger" id="deleteLogoBtn"><i class="fas fa-trash me-1"></i> Delete Logo</button>');
                    }
                    
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'An error occurred while uploading the logo.', 'error');
            }
        });
    });

    // School details form submission
    $('#schoolDetailsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('principal', $('#principal').val());
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: '{{ route("admin.academics.school-details.update") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response?.message || 'An error occurred while updating school details.', 'error');
            }
        });
    });

    // Delete logo functionality
    $(document).on('click', '#deleteLogoBtn', function() {
        if (confirm('Are you sure you want to delete the school logo?')) {
            $.ajax({
                url: '{{ route("admin.academics.school-details.delete-logo") }}',
                method: 'DELETE',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showToast(response?.message || 'An error occurred while deleting the logo.', 'error');
                }
            });
        }
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = $('#toast');
        const toastMessage = $('#toastMessage');
        
        // Set message and style based on type
        toastMessage.text(message);
        toast.removeClass('bg-success bg-danger bg-info');
        
        if (type === 'success') {
            toast.addClass('bg-success text-white');
        } else if (type === 'error') {
            toast.addClass('bg-danger text-white');
        } else {
            toast.addClass('bg-info text-white');
        }
        
        // Show toast
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
    }
});
</script>
@endpush