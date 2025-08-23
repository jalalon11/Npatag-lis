@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --info-color: #4895ef;
        --warning-color: #f72585;
        --danger-color: #e63946;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --gray-800: #343a40;
        --gray-900: #212529;
        --font-family: 'Inter', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        --border-radius: 0.5rem;
        --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --transition: all 0.2s ease-in-out;
    }

    .form-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }

    .form-section-title {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.25rem 1.5rem;
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section-content {
        padding: 2rem 1.5rem;
    }

    .form-control {
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: var(--transition);
        background-color: var(--light-color);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        background-color: white;
    }

    .form-label {
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        padding: 0.75rem 2rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
    }

    .btn-secondary {
        background: var(--gray-500);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-secondary:hover {
        background: var(--gray-600);
        transform: translateY(-1px);
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 1.5rem;
    }

    .card-body {
        padding: 2rem;
    }

    /* Dark mode styles */
    [data-bs-theme="dark"] {
        --light-color: #2d3748;
        --gray-200: #4a5568;
        --gray-700: #e2e8f0;
    }

    [data-bs-theme="dark"] .form-section {
        background: #2d3748;
        border-color: #4a5568;
    }

    [data-bs-theme="dark"] .form-control {
        background-color: #4a5568;
        border-color: #718096;
        color: #e2e8f0;
    }

    [data-bs-theme="dark"] .form-control:focus {
        background-color: #2d3748;
        border-color: var(--primary-color);
        color: #e2e8f0;
    }

    [data-bs-theme="dark"] .card {
        background: #2d3748;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Student Enrollment Application
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('teacher_admin.students.store') }}" method="POST" id="enrollmentForm">
                        @csrf

                        <!-- Personal Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-user"></i>
                                Personal Information
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="{{ old('first_name') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                               value="{{ old('middle_name') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="{{ old('last_name') }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="student_id" class="form-label">Student ID *</label>
                                        <input type="text" class="form-control" id="student_id" name="student_id" 
                                               value="{{ old('student_id') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="lrn" class="form-label">LRN (12 digits) *</label>
                                        <input type="text" class="form-control" id="lrn" name="lrn" 
                                               value="{{ old('lrn') }}" maxlength="12" pattern="[0-9]{12}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="gender" class="form-label">Gender *</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_date" class="form-label">Birth Date *</label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date" 
                                               value="{{ old('birth_date') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" 
                                               value="{{ old('address') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-users"></i>
                                Guardian Information
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="guardian_name" class="form-label">Guardian Name *</label>
                                        <input type="text" class="form-control" id="guardian_name" name="guardian_name" 
                                               value="{{ old('guardian_name') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="guardian_contact" class="form-label">Guardian Contact *</label>
                                        <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" 
                                               value="{{ old('guardian_contact') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="guardian_email" class="form-label">Guardian Email</label>
                                        <input type="email" class="form-control" id="guardian_email" name="guardian_email" 
                                               value="{{ old('guardian_email') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Contact Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-envelope"></i>
                                Student Contact Information
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="student_email" class="form-label">Student Email</label>
                                        <input type="email" class="form-control" id="student_email" name="student_email" 
                                               value="{{ old('student_email') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-phone-alt"></i>
                                Emergency Contact
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                                        <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" 
                                               value="{{ old('emergency_contact_number') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                                        <input type="text" class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship" 
                                               value="{{ old('emergency_contact_relationship') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Background -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-graduation-cap"></i>
                                Academic Background
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="previous_school" class="form-label">Previous School</label>
                                        <input type="text" class="form-control" id="previous_school" name="previous_school" 
                                               value="{{ old('previous_school') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-heartbeat"></i>
                                Medical Information
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="medical_conditions" class="form-label">Medical Conditions</label>
                                        <textarea class="form-control" id="medical_conditions" name="medical_conditions" rows="3">{{ old('medical_conditions') }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="medications" class="form-label">Current Medications</label>
                                        <textarea class="form-control" id="medications" name="medications" rows="3">{{ old('medications') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Assignment -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-chalkboard-teacher"></i>
                                Section Assignment
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="section_id" class="form-label">Assign to Section *</label>
                                        <select class="form-control" id="section_id" name="section_id" required>
                                            <option value="">Select Section</option>
                                            @foreach($sections as $gradeLevel => $gradeSections)
                                                <optgroup label="{{ $gradeLevel }}">
                                                    @foreach($gradeSections as $section)
                                                        <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                            {{ $section->name }} ({{ $section->students_count ?? 0 }}/{{ $section->student_limit }} students)
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('teacher_admin.students.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Students
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-user-plus me-2"></i>
                                <span id="submitText">Enroll Student</span>
                                <span id="loadingText" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set max date for birth date (must be at least 5 years old)
        const birthDateInput = document.getElementById('birth_date');
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 5, today.getMonth(), today.getDate());
        birthDateInput.max = maxDate.toISOString().split('T')[0];

        // Prevent multiple form submissions
        const form = document.getElementById('enrollmentForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const loadingText = document.getElementById('loadingText');

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitText.style.display = 'none';
            loadingText.style.display = 'inline';
        });
    });
</script>
@endpush