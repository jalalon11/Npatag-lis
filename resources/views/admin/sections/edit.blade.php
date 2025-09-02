@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i> Edit Section: {{ $section->name }}
                    </h4>
                    <a href="{{ route('admin.sections.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Back to Sections
                    </a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.sections.update', $section) }}" method="POST" id="editSectionForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Basic Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="school_id" class="form-label">School <span class="text-danger">*</span></label>
                                            <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id" required>
                                                <option value="" disabled>Select School</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" {{ (old('school_id', $section->school_id) == $school->id) ? 'selected' : '' }}>
                                                        {{ $school->name }} ({{ $school->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('school_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Section Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $section->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                            <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                                <option value="" disabled>Select Grade Level</option>
                                                @foreach($gradeLevels ?? ['K', '1', '2', '3', '4', '5', '6'] as $grade)
                                                    <option value="Grade {{ $grade }}" {{ (old('grade_level', $section->grade_level) == "Grade {$grade}") ? 'selected' : '' }}>
                                                        Grade {{ $grade }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('grade_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="school_year" class="form-label">School Year <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('school_year') is-invalid @enderror" id="school_year" name="school_year" value="{{ old('school_year', $section->school_year) }}" placeholder="e.g. 2023-2024" required>
                                            @error('school_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="student_limit" class="form-label">Student Limit</label>
                                            <input type="number" class="form-control @error('student_limit') is-invalid @enderror" id="student_limit" name="student_limit" value="{{ old('student_limit', $section->student_limit) }}" min="1" max="100" placeholder="Leave empty for no limit">
                                            @error('student_limit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Maximum number of students allowed in this section. Leave empty for unlimited enrollment.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                                <option value="active" {{ (old('status', $section->status) == 'active') ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ (old('status', $section->status) == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Adviser Assignment</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="adviser_id" class="form-label">Adviser <span class="text-danger">*</span></label>
                                            <select class="form-select @error('adviser_id') is-invalid @enderror" id="adviser_id" name="adviser_id" required>
                                                <option value="" disabled>Select Adviser</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" {{ (old('adviser_id', $section->adviser_id) == $teacher->id) ? 'selected' : '' }}>
                                                        {{ $teacher->name }} ({{ $teacher->school->name ?? 'No School' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('adviser_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Note:</strong> You can assign subjects and teachers from the section details page.
                                        </div>

                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            <strong>Admin Note:</strong> Changing the school will affect all related data including students and subject assignments.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Section
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
    $(document).ready(function() {
        // Update teachers when school changes
        $('#school_id').on('change', function() {
            const schoolId = $(this).val();
            const adviserSelect = $('#adviser_id');
            
            if (schoolId) {
                // Clear current options
                adviserSelect.html('<option value="" disabled selected>Loading teachers...</option>');
                
                // Fetch teachers for selected school
                $.get(`/admin/schools/${schoolId}/teachers`)
                    .done(function(teachers) {
                        adviserSelect.html('<option value="" disabled>Select Adviser</option>');
                        teachers.forEach(function(teacher) {
                            adviserSelect.append(`<option value="${teacher.id}">${teacher.name}</option>`);
                        });
                    })
                    .fail(function() {
                        adviserSelect.html('<option value="" disabled>Error loading teachers</option>');
                    });
            } else {
                adviserSelect.html('<option value="" disabled>Select School First</option>');
            }
        });

        // Form validation
        $("#editSectionForm").on("submit", function(e) {
            let valid = true;
            const requiredFields = ['school_id', 'name', 'grade_level', 'school_year', 'adviser_id', 'status'];
            
            requiredFields.forEach(field => {
                const value = $(`#${field}`).val();
                if (!value || value.trim() === '') {
                    $(`#${field}`).addClass('is-invalid');
                    valid = false;
                } else {
                    $(`#${field}`).removeClass('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
                
                // Show error alert
                if (!$('.alert-danger').length) {
                    $('.card-body').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> Please fill in all required fields.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            }
        });
    });
</script>
@endpush
@endsection