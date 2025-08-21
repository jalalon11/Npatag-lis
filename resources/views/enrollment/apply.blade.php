@extends('layouts.app')

@push('styles')
<style>
    .enrollment-container {
        min-height: 100vh;
        background: #f8f9fa;
        padding: 2rem 0;
    }

    .enrollment-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        border: 1px solid #e9ecef;
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }

    .enrollment-header {
        background: white;
        color: #495057;
        padding: 2rem;
        text-align: center;
        border-bottom: 2px solid #f8f9fa;
    }

    .enrollment-header h1 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .enrollment-header p {
        opacity: 0.9;
        margin: 0;
    }

    .enrollment-body {
        padding: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #4361ee;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .required::after {
        content: " *";
        color: #dc3545;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }

    .btn-submit {
        background: #4361ee;
        border: none;
        color: white;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-submit:hover {
        background: #3f37c9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        color: white;
    }

    .alert {
        border-radius: 8px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .school-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .school-info h5 {
        color: #4361ee;
        margin-bottom: 0.5rem;
    }

    .school-info p {
        color: #6c757d;
        margin: 0;
        font-size: 0.9rem;
    }

    /* Clean navigation bar for enrollment form */
    .navbar {
        background: white !important;
        border-bottom: 1px solid #e9ecef;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }

    .navbar .container-fluid {
        padding: 0.5rem 1rem;
    }

    #sidebarCollapseFixed {
        display: none !important;
    }

    .navbar-brand {
        font-weight: 600;
        color: #495057 !important;
    }

    .nav-link {
        color: #6c757d !important;
        font-weight: 500;
    }

    .nav-link:hover {
        color: #4361ee !important;
    }

    @media (max-width: 768px) {
        .enrollment-container {
            padding: 1rem;
        }
        
        .enrollment-body {
            padding: 1.5rem;
        }
        
        .enrollment-header {
            padding: 1.5rem;
        }
        
        .enrollment-header h1 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="enrollment-container">
    <div class="container">
        <div class="enrollment-card">
            <div class="enrollment-header">
                <h1><i class="fas fa-graduation-cap"></i> Student Enrollment Application</h1>
                <p>Submit your application for review by our admissions team</p>
            </div>
            
            <div class="enrollment-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle"></i> Please correct the following errors:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="school-info">
                    <h5><i class="fas fa-school"></i> {{ $school->name }}</h5>
                    <p>Your application will be reviewed by our admissions team</p>
                </div>

                <form action="{{ route('enrollment.submit') }}" method="POST" id="enrollmentForm">
                    @csrf

                    <!-- Student Information -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i> Student Information
                        </h3>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name" class="form-label required">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                        id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name" class="form-label required">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id" class="form-label required">Student ID</label>
                                    <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                                        id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lrn" class="form-label required">Learner Reference Number (LRN)</label>
                                    <input type="text" class="form-control @error('lrn') is-invalid @enderror"
                                        id="lrn" name="lrn" value="{{ old('lrn') }}" required
                                        maxlength="12" placeholder="12-digit number">
                                    @error('lrn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="birth_date" class="form-label required">Birth Date</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                        id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender" class="form-label required">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="preferred_grade_level" class="form-label">Preferred Grade Level</label>
                                    <input type="text" class="form-control @error('preferred_grade_level') is-invalid @enderror"
                                        id="preferred_grade_level" name="preferred_grade_level" 
                                        value="{{ old('preferred_grade_level') }}"
                                        placeholder="e.g., Grade 7, K-1">
                                    @error('preferred_grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" rows="2" placeholder="Complete address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Guardian Information -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-users"></i> Guardian Information
                        </h3>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guardian_name" class="form-label required">Guardian Name</label>
                                    <input type="text" class="form-control @error('guardian_name') is-invalid @enderror"
                                        id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}" required>
                                    @error('guardian_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guardian_contact" class="form-label required">Guardian Contact Number</label>
                                    <input type="text" class="form-control @error('guardian_contact') is-invalid @enderror"
                                        id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact') }}" required>
                                    @error('guardian_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="guardian_email" class="form-label">Guardian Email</label>
                            <input type="email" class="form-control @error('guardian_email') is-invalid @enderror"
                                id="guardian_email" name="guardian_email" value="{{ old('guardian_email') }}"
                                placeholder="guardian@example.com">
                            @error('guardian_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Academic Background -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-book"></i> Academic Background
                        </h3>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="previous_school" class="form-label">Previous School</label>
                                    <input type="text" class="form-control @error('previous_school') is-invalid @enderror"
                                        id="previous_school" name="previous_school" value="{{ old('previous_school') }}"
                                        placeholder="Name of previous school">
                                    @error('previous_school')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="previous_grade_level" class="form-label">Previous Grade Level</label>
                                    <input type="text" class="form-control @error('previous_grade_level') is-invalid @enderror"
                                        id="previous_grade_level" name="previous_grade_level" value="{{ old('previous_grade_level') }}"
                                        placeholder="Last completed grade">
                                    @error('previous_grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle"></i> Additional Information
                        </h3>
                        
                        <div class="form-group">
                            <label for="medical_conditions" class="form-label">Medical Conditions or Special Needs</label>
                            <textarea class="form-control @error('medical_conditions') is-invalid @enderror"
                                id="medical_conditions" name="medical_conditions" rows="3" 
                                placeholder="Please list any medical conditions, allergies, or special needs (optional)">{{ old('medical_conditions') }}</textarea>
                            @error('medical_conditions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="preferred_section" class="form-label">Preferred Section</label>
                            <input type="text" class="form-control @error('preferred_section') is-invalid @enderror"
                                id="preferred_section" name="preferred_section" value="{{ old('preferred_section') }}"
                                placeholder="e.g., Section A, Morning Class (optional)">
                            @error('preferred_section')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Note: Final section assignment will be made by the school administration</small>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>
                            Submit Application for Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enrollmentForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const lrnInput = document.getElementById('lrn');
    const birthDateInput = document.getElementById('birth_date');
    
    // Set max date for birth date
    if (birthDateInput) {
        birthDateInput.max = new Date().toISOString().split('T')[0];
    }
    
    // Format LRN input to numbers only
    if (lrnInput) {
        lrnInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // Prevent multiple submissions
    form.addEventListener('submit', function(e) {
        if (form.classList.contains('submitting')) {
            e.preventDefault();
            return false;
        }
        
        form.classList.add('submitting');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting Application...';
        
        return true;
    });
});
</script>
@endpush
@endsection