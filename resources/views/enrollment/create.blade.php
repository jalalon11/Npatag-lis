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

    .dark .form-select {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .dark .input-group-text {
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .btn-back {
        color: var(--text-color);
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
    }

    .dark .btn-back:hover {
        background-color: var(--border-color);
        border-color: var(--border-color);
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

    .dark .card-header .text-primary {
        color: #4361ee !important;
    }

    .dark .card-body {
        color: var(--text-color);
    }

    .dark .breadcrumb {
        background-color: var(--bg-card-header);
    }

    .dark .breadcrumb-item {
        color: var(--text-muted);
    }

    .dark .breadcrumb-item.active {
        color: var(--text-color);
    }

    .dark .breadcrumb-item + .breadcrumb-item::before {
        color: var(--text-muted);
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
                            <i class="fas fa-user-graduate text-primary me-2"></i> Student Enrollment Application
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('enrollment.status') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search me-1"></i> Check Status
                            </a>
                            <div class="text-muted">
                                <i class="fas fa-school me-1"></i> {{ $school->name }}
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

                    <form action="{{ route('enrollment.store') }}" method="POST" id="enrollmentForm">
                        @csrf

                        <!-- Personal Information -->
                        <div class="form-section">
                            <h6 class="section-title">Personal Information</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
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
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                                            id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                                        @error('student_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lrn" class="form-label">Learner Reference Number (LRN) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lrn') is-invalid @enderror"
                                            id="lrn" name="lrn" value="{{ old('lrn') }}" required
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            maxlength="12" placeholder="12-digit number">
                                        @error('lrn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Numbers only</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
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
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                            id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
                                            max="{{ date('Y-m-d') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="form-section">
                            <h6 class="section-title">Guardian Information</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="guardian_name" class="form-label">Guardian Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('guardian_name') is-invalid @enderror"
                                            id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}" required>
                                        @error('guardian_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="guardian_contact" class="form-label">Guardian Contact <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('guardian_contact') is-invalid @enderror"
                                            id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact') }}" required>
                                        @error('guardian_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="guardian_email" class="form-label">Guardian Email</label>
                                        <input type="email" class="form-control @error('guardian_email') is-invalid @enderror"
                                            id="guardian_email" name="guardian_email" value="{{ old('guardian_email') }}">
                                        @error('guardian_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Contact Information -->
                        <div class="form-section">
                            <h6 class="section-title">Student Contact Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="student_email" class="form-label">Student Email</label>
                                        <input type="email" class="form-control @error('student_email') is-invalid @enderror"
                                            id="student_email" name="student_email" value="{{ old('student_email') }}">
                                        @error('student_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="form-section">
                            <h6 class="section-title">Emergency Contact</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                                        <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                            id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                                        @error('emergency_contact_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                                        <input type="text" class="form-control @error('emergency_contact_number') is-invalid @enderror"
                                            id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}">
                                        @error('emergency_contact_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                                        <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror"
                                            id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}">
                                        @error('emergency_contact_relationship')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Background -->
                        <div class="form-section">
                            <h6 class="section-title">Academic Background</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="previous_school" class="form-label">Previous School</label>
                                        <input type="text" class="form-control @error('previous_school') is-invalid @enderror"
                                            id="previous_school" name="previous_school" value="{{ old('previous_school') }}">
                                        @error('previous_school')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="previous_grade_level" class="form-label">Previous Grade Level</label>
                                        <input type="text" class="form-control @error('previous_grade_level') is-invalid @enderror"
                                            id="previous_grade_level" name="previous_grade_level" value="{{ old('previous_grade_level') }}">
                                        @error('previous_grade_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="form-section">
                            <h6 class="section-title">Medical Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="medical_conditions" class="form-label">Medical Conditions</label>
                                        <textarea class="form-control @error('medical_conditions') is-invalid @enderror"
                                            id="medical_conditions" name="medical_conditions" rows="3" placeholder="List any medical conditions, allergies, or special needs">{{ old('medical_conditions') }}</textarea>
                                        @error('medical_conditions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="medications" class="form-label">Current Medications</label>
                                        <textarea class="form-control @error('medications') is-invalid @enderror"
                                            id="medications" name="medications" rows="3" placeholder="List any current medications">{{ old('medications') }}</textarea>
                                        @error('medications')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- School Preferences -->
                        <div class="form-section">
                            <h6 class="section-title">School Preferences</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="preferred_grade_level" class="form-label">Preferred Grade Level</label>
                                        <input type="text" class="form-control @error('preferred_grade_level') is-invalid @enderror"
                                            id="preferred_grade_level" name="preferred_grade_level" 
                                            value="{{ old('preferred_grade_level') }}"
                                            placeholder="e.g., K-1, Grade 3, etc. (Optional)">
                                        @error('preferred_grade_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="preferred_section" class="form-label">Preferred Section</label>
                                        <input type="text" class="form-control @error('preferred_section') is-invalid @enderror"
                                            id="preferred_section" name="preferred_section" value="{{ old('preferred_section') }}"
                                            placeholder="e.g., Section A, Section 1 (Optional)">
                                        @error('preferred_section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-1"></i> Submit Enrollment Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gradeSelect = document.getElementById('preferred_grade_level');
        const sectionSelect = document.getElementById('section_id');
        const birthDateInput = document.getElementById('birth_date');
        const enrollmentForm = document.getElementById('enrollmentForm');
        const submitBtn = document.querySelector('#enrollmentForm button[type="submit"]');

        // Set max date to today for birth date
        if (birthDateInput) {
            birthDateInput.max = new Date().toISOString().split('T')[0];
        }

        // No section filtering needed for application-based enrollment

        // Prevent multiple form submissions
        if (enrollmentForm && submitBtn) {
            enrollmentForm.addEventListener('submit', function(e) {
                // Check if the form is already being submitted
                if (enrollmentForm.classList.contains('submitting')) {
                    e.preventDefault();
                    return false;
                }

                // Add submitting class to form
                enrollmentForm.classList.add('submitting');

                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting Application...';

                // Allow form submission
                return true;
            });
        }
    });
</script>
@endpush
@endsection