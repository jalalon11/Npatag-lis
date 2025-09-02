@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-door-open me-2"></i> Section Details: {{ $section->name }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.sections.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to Sections
                        </a>
                        <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Section
                        </a>
                    </div>
                </div>
                <div class="card-body">
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
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Basic Information</h5>
                                    <span class="badge {{ $section->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($section->status) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">School</label>
                                        <h6 class="mb-0 fw-bold">{{ $section->school->name ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $section->school->code ?? 'N/A' }}</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Section Name</label>
                                        <h6 class="mb-0 fw-bold">{{ $section->name }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Grade Level</label>
                                        <h6 class="mb-0">{{ $section->grade_level }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">School Year</label>
                                        <h6 class="mb-0">{{ $section->school_year }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Student Limit</label>
                                        <h6 class="mb-0">
                                            @if($section->student_limit)
                                                <span class="badge bg-secondary">{{ $section->student_limit }} students</span>
                                            @else
                                                <span class="text-muted">No limit set</span>
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Classroom Adviser</h5>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changeAdviserModal">
                                        <i class="fas fa-user-edit me-1"></i> Change
                                    </button>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="text-center mb-3">
                                        <div class="mb-3">
                                            <i class="fas fa-user-tie fa-4x text-primary"></i>
                                        </div>
                                        <h5 class="mb-1">{{ $section->adviser->name ?? 'Not assigned' }}</h5>
                                        <p class="text-muted small mb-0">{{ $section->adviser->email ?? '' }}</p>
                                        @if($section->adviser && $section->adviser->school)
                                            <small class="text-muted">{{ $section->adviser->school->name }}</small>
                                        @endif
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <label class="text-muted small mb-1">Students</label>
                                                <h6 class="mb-0">
                                                    <span class="badge {{ $section->student_limit && $section->students_count >= $section->student_limit ? 'bg-warning text-dark' : 'bg-info' }}">
                                                        {{ $section->students_count ?? 0 }}
                                                        @if($section->student_limit)
                                                            / {{ $section->student_limit }}
                                                        @endif
                                                        {{ Str::plural('student', $section->students_count ?? 0) }}
                                                        @if($section->student_limit && ($section->students_count ?? 0) >= $section->student_limit)
                                                            <i class="fas fa-exclamation-triangle ms-1" title="At capacity"></i>
                                                        @endif
                                                    </span>
                                                </h6>
                                            </div>
                                            <div>
                                                <label class="text-muted small mb-1">Subjects</label>
                                                <h6 class="mb-0">
                                                    <span class="badge bg-secondary">
                                                        {{ $section->subjects->count() }} {{ Str::plural('subject', $section->subjects->count()) }}
                                                    </span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Enrollment Status</label>
                                        <div class="progress mb-2" style="height: 20px;">
                                            @php
                                                $enrollmentPercentage = $section->student_limit ? (($section->students_count ?? 0) / $section->student_limit) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar {{ $enrollmentPercentage >= 100 ? 'bg-danger' : ($enrollmentPercentage >= 80 ? 'bg-warning' : 'bg-success') }}" 
                                                 role="progressbar" style="width: {{ min($enrollmentPercentage, 100) }}%">
                                                {{ number_format($enrollmentPercentage, 1) }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            @if($section->student_limit)
                                                {{ $section->students_count ?? 0 }} of {{ $section->student_limit }} students
                                            @else
                                                {{ $section->students_count ?? 0 }} students (no limit)
                                            @endif
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Subject Coverage</label>
                                        <h6 class="mb-0">
                                            <span class="badge {{ $section->subjects->count() >= 8 ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $section->subjects->count() }} subjects assigned
                                            </span>
                                        </h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Created</label>
                                        <h6 class="mb-0">{{ $section->created_at->format('M d, Y') }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Last Updated</label>
                                        <h6 class="mb-0">{{ $section->updated_at->format('M d, Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Admin Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                                            <i class="fas fa-book me-1"></i> Manage Subjects
                                        </button>

                                        <a href="{{ route('admin.sections.students', $section) }}" class="btn btn-info">
                                            <i class="fas fa-users me-1"></i> Manage Students
                                        </a>

                                        <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-1"></i> Edit Details
                                        </a>

                                        <form action="{{ route('admin.sections.toggle-status', $section) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn {{ $section->status === 'active' ? 'btn-danger' : 'btn-success' }} w-100">
                                                <i class="fas {{ $section->status === 'active' ? 'fa-toggle-off' : 'fa-toggle-on' }} me-1"></i>
                                                {{ $section->status === 'active' ? 'Deactivate' : 'Activate' }} Section
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash-alt me-1"></i> Delete Section
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-2">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Assigned Subjects</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                                <i class="fas fa-plus-circle me-1"></i> Assign Subjects
                            </button>
                        </div>
                        <div class="card-body">
                            @if($section->subjects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>School</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($section->subjects as $subject)
                                                <tr>
                                                    <td>
                                                        {{ $subject->name }}
                                                        @if($subject->grade_level)
                                                            <small class="text-muted">({{ $subject->grade_level }})</small>
                                                        @endif
                                                        @if($subject->getIsMAPEHAttribute())
                                                            <span class="badge bg-info ms-1">MAPEH</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $teacher = App\Models\User::find($subject->pivot->teacher_id ?? 0);
                                                        @endphp
                                                        {{ $teacher ? $teacher->name : 'Not assigned' }}
                                                    </td>
                                                    <td>
                                                        {{ $teacher && $teacher->school ? $teacher->school->name : 'N/A' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeSubject({{ $subject->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-1"></i> No subjects assigned to this section yet.
                                    Click the "Assign Subjects" button to add subjects.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Subjects Modal -->
<div class="modal fade" id="assignSubjectsModal" tabindex="-1" aria-labelledby="assignSubjectsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="assignSubjectsModalLabel">
                    <i class="fas fa-book me-1"></i> Assign Subjects to {{ $section->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.sections.assign-subjects', $section) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> You can assign multiple subjects to this section. Existing subject assignments will be preserved.
                        If you assign a subject that's already in this section, only the teacher assignment will be updated.
                    </div>

                    <p class="mb-3">Select subjects and assign teachers to this section.</p>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="subjectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Subject</th>
                                    <th style="width: 50%">Teacher</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="subject-row">
                                    <td>
                                        <select class="form-select subject-select" name="subjects[0][subject_id]" required>
                                            <option value="" selected disabled>Select Subject ({{ $section->grade_level }} Only)</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">
                                                    {{ $subject->name }}
                                                    @if($subject->grade_level)
                                                        ({{ $subject->grade_level }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select" name="subjects[0][teacher_id]" required>
                                            <option value="" selected disabled>Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->school->name ?? 'No School' }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-subject" disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="addSubjectRow">
                        <i class="fas fa-plus-circle me-1"></i> Add Another Subject
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Assignments
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Section Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-1"></i> Delete Section
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the section <strong>{{ $section->name }}</strong>?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone and will affect:</p>
                <ul class="text-danger">
                    <li>All enrolled students in this section</li>
                    <li>Subject assignments and grades</li>
                    <li>Attendance records</li>
                    <li>Reports and analytics</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.sections.destroy', $section) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete Section
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Adviser Modal -->
<div class="modal fade" id="changeAdviserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.sections.update-adviser', $section) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit me-1"></i> Change Section Adviser
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Select a new adviser for this section. The current adviser is <strong>{{ $section->adviser->name ?? 'not assigned' }}</strong>.</p>

                    <div class="mb-3">
                        <label class="form-label">Select New Adviser</label>
                        <select name="adviser_id" class="form-select" required>
                            <option value="">Choose an adviser...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $section->adviser_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->school->name ?? 'No School' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Adviser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let rowCount = 1;

        // Add new subject row
        $('#addSubjectRow').click(function() {
            let newRow = `
                <tr class="subject-row">
                    <td>
                        <select class="form-select subject-select" name="subjects[${rowCount}][subject_id]" required>
                            <option value="" selected disabled>Select Subject ({{ $section->grade_level }} Only)</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }}
                                    @if($subject->grade_level)
                                        ({{ $subject->grade_level }})
                                    @endif
                                    @if($subject->getIsMAPEHAttribute())
                                        [MAPEH]
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-select" name="subjects[${rowCount}][teacher_id]" required>
                            <option value="" selected disabled>Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->school->name ?? 'No School' }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-subject">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#subjectsTable tbody').append(newRow);
            rowCount++;

            // Enable the first row's remove button if we have more than one row
            if ($('.subject-row').length > 1) {
                $('.remove-subject').prop('disabled', false);
            }

            // Update subject selections to prevent duplicates
            preventDuplicateSubjects();
        });

        // Remove subject row
        $(document).on('click', '.remove-subject', function() {
            $(this).closest('tr').remove();

            // If only one row left, disable its remove button
            if ($('.subject-row').length == 1) {
                $('.remove-subject').prop('disabled', true);
            }

            // Update subject selections after removal
            preventDuplicateSubjects();
        });

        // Prevent selecting the same subject twice
        function preventDuplicateSubjects() {
            $('.subject-select').on('change', function() {
                let selectedValues = [];

                // Get all currently selected values
                $('.subject-select').each(function() {
                    if ($(this).val()) {
                        selectedValues.push($(this).val());
                    }
                });

                // Disable selected options in all other dropdowns
                $('.subject-select').each(function() {
                    let currentSelect = $(this);
                    let currentVal = currentSelect.val();

                    // Reset options
                    currentSelect.find('option').not(':first').prop('disabled', false);

                    // Disable options selected in other dropdowns
                    $.each(selectedValues, function(index, value) {
                        if (value !== currentVal) {
                            currentSelect.find('option[value="' + value + '"]').prop('disabled', true);
                        }
                    });
                });
            });
        }

        // Initialize duplicate prevention
        preventDuplicateSubjects();

        // Trigger change on page load to set initial state
        $('.subject-select').first().trigger('change');
    });

    // Remove subject function
    function removeSubject(subjectId) {
        if (confirm('Are you sure you want to remove this subject from the section?')) {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('admin.sections.remove-subject', $section) }}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            const subjectField = document.createElement('input');
            subjectField.type = 'hidden';
            subjectField.name = 'subject_id';
            subjectField.value = subjectId;
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            form.appendChild(subjectField);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection