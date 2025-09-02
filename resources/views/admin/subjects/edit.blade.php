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
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card {
        border: none !important;
        border-radius: var(--border-radius) !important;
        transition: var(--transition);
    }

    .card-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        border-bottom: none !important;
        padding: var(--padding-md) !important;
    }

    .btn {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1.25rem;
        transition: var(--transition);
    }

    .form-control, .form-select {
        border-radius: var(--border-radius);
        border: 1px solid #e3e6f0;
        transition: var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
    }

    .edit-form-card {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border: 1px solid #e3e6f0;
    }

    .required {
        color: #e74a3b;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 animate__animated animate__fadeInLeft">
                <i class="fas fa-edit text-primary me-2"></i>Edit Subject
            </h1>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subjects.show', $subject) }}">{{ $subject->name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="animate__animated animate__fadeInRight">
            <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-eye me-1"></i>View Subject
            </a>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card edit-form-card animate__animated animate__fadeIn">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-book text-primary me-2"></i>Subject Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Hidden School ID -->
                        <input type="hidden" name="school_id" value="{{ $defaultSchool->id }}">

                        <div class="row">
                            <!-- Subject Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Subject Name <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $subject->name) }}" 
                                       required 
                                       maxlength="255"
                                       placeholder="Enter subject name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maximum 255 characters</div>
                            </div>

                            <!-- Subject Code -->
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Subject Code</label>
                                <input type="text" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       id="code" 
                                       name="code" 
                                       value="{{ old('code', $subject->code) }}" 
                                       maxlength="50"
                                       placeholder="Enter subject code (optional)">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional - Maximum 50 characters</div>
                            </div>

                            <!-- Grade Level -->
                            <div class="col-md-6 mb-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <select class="form-select @error('grade_level') is-invalid @enderror" 
                                        id="grade_level" 
                                        name="grade_level">
                                    <option value="">Select Grade Level</option>
                                    @foreach($gradeLevels as $grade)
                                        <option value="{{ $grade }}" 
                                                {{ old('grade_level', $subject->grade_level) == $grade ? 'selected' : '' }}>
                                            @if($grade === 'K')
                                                Kindergarten
                                            @else
                                                Grade {{ $grade }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('grade_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Select the appropriate grade level</div>
                            </div>

                            <!-- Status (if needed) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Status</label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-{{ $subject->is_active ? 'success' : 'danger' }} fs-6">
                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="form-text">Status can be changed from the subject list</div>
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Enter subject description (optional)">{{ old('description', $subject->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional - Provide a brief description of the subject</div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-lg-4">
            <div class="card animate__animated animate__fadeIn animate__delay-1s">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-info me-2"></i>Help & Tips
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary"><i class="fas fa-lightbulb me-1"></i>Subject Name</h6>
                        <p class="small text-muted mb-2">Use clear, descriptive names like "Mathematics", "English Language Arts", or "Science".</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary"><i class="fas fa-code me-1"></i>Subject Code</h6>
                        <p class="small text-muted mb-2">Optional short codes like "MATH", "ENG", "SCI" for easy identification.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary"><i class="fas fa-layer-group me-1"></i>Grade Level</h6>
                        <p class="small text-muted mb-2">Select the appropriate grade level. This helps organize subjects by academic level.</p>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Note:</strong> Changes will affect all sections currently using this subject.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation and enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const nameInput = document.getElementById('name');
        const codeInput = document.getElementById('code');
        
        // Auto-generate code from name if code is empty
        nameInput.addEventListener('input', function() {
            if (!codeInput.value) {
                const code = this.value
                    .toUpperCase()
                    .replace(/[^A-Z0-9\s]/g, '')
                    .split(' ')
                    .map(word => word.substring(0, 3))
                    .join('')
                    .substring(0, 10);
                codeInput.value = code;
            }
        });
        
        // Form submission confirmation
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
            submitBtn.disabled = true;
        });
    });
</script>
@endpush