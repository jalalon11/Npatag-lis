@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Manual Student Admission</h3>
                    <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Admissions
                    </a>
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

                    <form action="{{ route('admin.admissions.store') }}" method="POST" enctype="multipart/form-data" id="admissionForm">
                        @csrf
                        
                        <!-- Student Information -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-user"></i> Student Information</h5>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ old('first_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                           value="{{ old('middle_name') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ old('last_name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="birth_date">Birth Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" 
                                           value="{{ old('birth_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student_id">Student ID</label>
                                    <input type="text" class="form-control" id="student_id" name="student_id" 
                                           value="{{ old('student_id') }}" placeholder="Auto-generated if empty">
                                    <small class="text-muted">Leave empty to auto-generate</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lrn">LRN (Learner Reference Number)</label>
                                    <input type="text" class="form-control" id="lrn" name="lrn" 
                                           value="{{ old('lrn') }}" maxlength="12">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-users"></i> Guardian Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" 
                                           value="{{ old('guardian_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_contact">Guardian Contact <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" 
                                           value="{{ old('guardian_contact') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_email">Guardian Email</label>
                                    <input type="email" class="form-control" id="guardian_email" name="guardian_email" 
                                           value="{{ old('guardian_email') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">School <span class="text-danger">*</span></label>
                                    <select class="form-control" id="school_id" name="school_id" required>
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="preferred_grade_level">Grade Level <span class="text-danger">*</span></label>
                                    <select class="form-control" id="preferred_grade_level" name="preferred_grade_level" required>
                                        <option value="">Select Grade Level</option>
                                        <option value="Kindergarten" {{ old('preferred_grade_level') == 'Kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                                        <option value="Grade 1" {{ old('preferred_grade_level') == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
                                        <option value="Grade 2" {{ old('preferred_grade_level') == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
                                        <option value="Grade 3" {{ old('preferred_grade_level') == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
                                        <option value="Grade 4" {{ old('preferred_grade_level') == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
                                        <option value="Grade 5" {{ old('preferred_grade_level') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
                                        <option value="Grade 6" {{ old('preferred_grade_level') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_year">School Year <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="school_year" name="school_year" 
                                           value="{{ old('school_year', date('Y') . '-' . (date('Y') + 1)) }}" 
                                           placeholder="2024-2025" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preferred_section_id">Preferred Section</label>
                                    <select class="form-control" id="preferred_section_id" name="preferred_section_id">
                                        <option value="">Select Grade Level First</option>
                                    </select>
                                    <small class="text-muted">Please select school and grade level first</small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Additional Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="previous_school">Previous School</label>
                                    <input type="text" class="form-control" id="previous_school" name="previous_school" 
                                           value="{{ old('previous_school') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="previous_grade_level">Previous Grade Level</label>
                                    <input type="text" class="form-control" id="previous_grade_level" name="previous_grade_level" 
                                           value="{{ old('previous_grade_level') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_name">Emergency Contact Name</label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                           value="{{ old('emergency_contact_name') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_number">Emergency Contact Number</label>
                                    <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" 
                                           value="{{ old('emergency_contact_number') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_relationship">Relationship</label>
                                    <input type="text" class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship" 
                                           value="{{ old('emergency_contact_relationship') }}" placeholder="e.g., Aunt, Uncle, Grandparent">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birth_certificate">Birth Certificate <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file" id="birth_certificate" name="birth_certificate" 
                                           accept=".jpg,.jpeg,.png,.pdf" required>
                                    <small class="text-muted">Upload birth certificate (JPG, PNG, or PDF, max 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="medical_conditions">Medical Conditions</label>
                                    <textarea class="form-control" id="medical_conditions" name="medical_conditions" 
                                              rows="3" placeholder="Any medical conditions or allergies">{{ old('medical_conditions') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Admin Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" 
                                              rows="3" placeholder="Internal notes for this admission">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Admission
                                    </button>
                                    <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-select school if there's only one available
    const schoolSelect = $('#school_id');
    const schoolOptions = schoolSelect.find('option[value!=""]');
    if (schoolOptions.length === 1) {
        schoolSelect.val(schoolOptions.first().val());
        // Trigger change event to load sections if grade level is also selected
        if ($('#preferred_grade_level').val()) {
            loadSections();
        }
    }
    
    // Auto-generate Student ID
    function generateStudentId() {
        const year = new Date().getFullYear();
        const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        return year + random;
    }

    // Generate student ID if field is empty
    $('#student_id').on('focus', function() {
        if ($(this).val() === '') {
            $(this).val(generateStudentId());
        }
    });

    // Load sections based on school and grade level via AJAX
    function loadSections() {
        const schoolId = $('#school_id').val();
        const gradeLevel = $('#preferred_grade_level').val();
        const sectionSelect = $('#preferred_section_id');
        
        // Clear current options except the first one
        sectionSelect.find('option:not(:first)').remove();
        
        if (!schoolId || !gradeLevel) {
            sectionSelect.find('option:first').text('Select School and Grade Level First');
            return;
        }
        
        // Show loading state
        sectionSelect.find('option:first').text('Loading sections...');
        sectionSelect.prop('disabled', true);
        
        // Make AJAX request
        $.ajax({
            url: '{{ route("admin.api.sections") }}',
            method: 'GET',
            data: {
                school_id: schoolId,
                grade_level: gradeLevel
            },
            success: function(sections) {
                sectionSelect.find('option:first').text('Select Section (Optional)');
                
                if (sections.length === 0) {
                    sectionSelect.append('<option value="" disabled>No sections available</option>');
                } else {
                    sections.forEach(function(section) {
                        const selected = '{{ old("preferred_section_id") }}' == section.id ? 'selected' : '';
                        sectionSelect.append(`<option value="${section.id}" ${selected}>${section.name}</option>`);
                    });
                }
                
                sectionSelect.prop('disabled', false);
            },
            error: function() {
                sectionSelect.find('option:first').text('Error loading sections');
                sectionSelect.prop('disabled', false);
            }
        });
    }

    $('#school_id, #preferred_grade_level').on('change', loadSections);
    
    // Auto-set previous grade level based on selected grade level
    function setPreviousGradeLevel() {
        const gradeLevel = $('#preferred_grade_level').val();
        const previousGradeLevelInput = $('#previous_grade_level');
        
        if (gradeLevel) {
            // Extract numeric value from grade level (e.g., "Grade 2" -> 2)
            const gradeNumber = parseInt(gradeLevel.replace(/\D/g, ''));
            
            if (gradeNumber && gradeNumber > 1) {
                // Set previous grade level to one less than current grade level
                previousGradeLevelInput.val(gradeNumber - 1);
            } else if (gradeNumber === 1) {
                // For Grade 1, previous grade level should be empty or "Kindergarten"
                previousGradeLevelInput.val('Kindergarten');
            }
        }
    }
    
    // Trigger previous grade level update when grade level changes
    $('#preferred_grade_level').on('change', setPreviousGradeLevel);
    
    // Initial load if values are present
    if ($('#school_id').val() && $('#preferred_grade_level').val()) {
        loadSections();
        setPreviousGradeLevel();
    }

    // Form validation
    $('#admissionForm').on('submit', function(e) {
        let isValid = true;
        const requiredFields = ['first_name', 'last_name', 'birth_date', 'gender', 'address', 'guardian_name', 'guardian_contact', 'school_id', 'preferred_grade_level', 'school_year'];
        
        requiredFields.forEach(function(field) {
            const input = $('#' + field);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Remove validation error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endsection