@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Room Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-door-open me-2"></i> {{ $room->name }}
                        <span class="badge bg-{{ $room->status == 'active' ? 'success' : 'secondary' }} ms-2">{{ ucfirst($room->status) }}</span>
                    </h4>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Back to Rooms
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Grade Level</h6>
                            <p class="mb-0"><strong>Grade {{ $room->grade_level }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">School Year</h6>
                            <p class="mb-0"><strong>{{ $room->school_year }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Student Limit</h6>
                            <p class="mb-0"><strong>{{ $room->student_limit ?? 'No Limit' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">School</h6>
                            <p class="mb-0"><strong>{{ $room->school->name ?? 'No School Assigned' }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Room Adviser -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Room Adviser</h5>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changeAdviserModal">
                                <i class="fas fa-edit"></i> Change
                            </button>
                        </div>
                        <div class="card-body text-center">
                            @if($room->adviser)
                                <div class="mb-3">
                                    <div class="bg-primary text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                    <h6 class="mb-1">{{ $room->adviser->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $room->adviser->email }}</p>
                                    <p class="text-muted small">{{ $room->adviser->school->name ?? 'No School' }}</p>
                                </div>
                            @else
                                <div class="text-muted">
                                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                                    <p>No adviser assigned</p>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#changeAdviserModal">
                                        Assign Adviser
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Student Summary -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Students</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="bg-success text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <h3 class="mb-1">{{ $room->students_count ?? 0 }}</h3>
                                <p class="text-muted mb-0">
                                    @if($room->student_limit)
                                        of {{ $room->student_limit }} students
                                    @else
                                        students enrolled
                                    @endif
                                </p>
                                @if($room->student_limit)
                                    <div class="progress mt-2" style="height: 8px;">
                                        @php
                                            $percentage = $room->student_limit > 0 ? (($room->students_count ?? 0) / $room->student_limit) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-success" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subject Summary -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Subjects</h5>
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                                <i class="fas fa-plus"></i> Assign
                            </button>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="bg-info text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                                <h3 class="mb-1">{{ $room->subjects->count() }}</h3>
                                <p class="text-muted mb-0">subjects assigned</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment Statistics -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Enrollment Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1">{{ $room->male_students_count ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Male Students</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-danger mb-1">{{ $room->female_students_count ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Female Students</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-success mb-1">{{ $room->active_students_count ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Active Students</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-warning mb-1">{{ $room->inactive_students_count ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Inactive Students</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="#" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-users me-1"></i> Manage Students
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                                        <i class="fas fa-book me-1"></i> Manage Subjects
                                    </button>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-outline-warning w-100">
                                        <i class="fas fa-edit me-1"></i> Edit Room
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <form action="{{ route('admin.rooms.toggle-status', $room->id) }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-{{ $room->status == 'active' ? 'secondary' : 'success' }} w-100">
                                            <i class="fas fa-{{ $room->status == 'active' ? 'pause' : 'play' }} me-1"></i>
                                            {{ $room->status == 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteRoomModal">
                                        <i class="fas fa-trash me-1"></i> Delete Room
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Subjects -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Assigned Subjects</h5>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                                <i class="fas fa-plus me-1"></i> Assign Subjects
                            </button>
                        </div>
                        <div class="card-body">
                            @if($room->subjects && $room->subjects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>Schedule</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($room->subjects as $subject)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $subject->name }}</strong><br>
                                                        <small class="text-muted">{{ $subject->code }}</small>
                                                    </td>
                                                    <td>
                                                        @if($subject->pivot->teacher)
                                                            {{ $subject->pivot->teacher->name }}<br>
                                                            <small class="text-muted">{{ $subject->pivot->teacher->email }}</small>
                                                        @else
                                                            <span class="text-muted">No teacher assigned</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($subject->pivot->schedule)
                                                            {{ $subject->pivot->schedule }}
                                                        @else
                                                            <span class="text-muted">No schedule set</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $subject->pivot->status == 'active' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($subject->pivot->status ?? 'active') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-outline-primary me-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <form action="#" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this subject from the room?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No subjects assigned</h5>
                                    <p class="text-muted">Start by assigning subjects to this room.</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                                        <i class="fas fa-plus me-1"></i> Assign First Subject
                                    </button>
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
<div class="modal fade" id="assignSubjectsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Subjects to {{ $room->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.rooms.assign-subjects', $room->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="subject-assignments">
                        <div class="subject-row mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                                    <select name="subjects[0][subject_id]" class="form-select subject-select" required>
                                        <option value="">Select Subject</option>
                                        @foreach($availableSubjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teacher <span class="text-danger">*</span></label>
                                    <select name="subjects[0][teacher_id]" class="form-select teacher-select" required>
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label class="form-label">Schedule (Optional)</label>
                                    <input type="text" name="subjects[0][schedule]" class="form-control" placeholder="e.g., Mon-Wed-Fri 8:00-9:00 AM">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger remove-subject" style="display: none;">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary" id="add-subject">
                        <i class="fas fa-plus me-1"></i> Add Another Subject
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Assign Subjects</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Room Modal -->
<div class="modal fade" id="deleteRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5>Are you sure you want to delete this room?</h5>
                    <p class="text-muted">This action cannot be undone. All associated data will be permanently removed.</p>
                    <div class="alert alert-warning">
                        <strong>Room:</strong> {{ $room->name }}<br>
                        <strong>Grade:</strong> {{ $room->grade_level }}<br>
                        <strong>Students:</strong> {{ $room->students_count ?? 0 }}<br>
                        <strong>Subjects:</strong> {{ $room->subjects_count ?? 0 }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete Room</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Adviser Modal -->
<div class="modal fade" id="changeAdviserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Room Adviser</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.rooms.change-adviser', $room->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_adviser_id" class="form-label">Select New Adviser <span class="text-danger">*</span></label>
                        <select name="adviser_id" id="new_adviser_id" class="form-select" required>
                            <option value="">Choose an adviser...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $room->adviser_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->school->name ?? 'No School' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    @if($room->adviser)
                        <div class="alert alert-info">
                            <strong>Current Adviser:</strong> {{ $room->adviser->name }}
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Adviser</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let subjectIndex = 1;
        const assignedSubjects = [];

        // Add subject row
        $('#add-subject').click(function() {
            const newRow = $('.subject-row').first().clone();
            
            // Update names and clear values
            newRow.find('select, input').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace('[0]', '[' + subjectIndex + ']'));
                }
                $(this).val('');
            });
            
            // Show remove button
            newRow.find('.remove-subject').show();
            
            $('#subject-assignments').append(newRow);
            subjectIndex++;
            
            updateRemoveButtons();
        });

        // Remove subject row
        $(document).on('click', '.remove-subject', function() {
            $(this).closest('.subject-row').remove();
            updateRemoveButtons();
        });

        // Update remove button visibility
        function updateRemoveButtons() {
            const rows = $('.subject-row');
            if (rows.length > 1) {
                rows.find('.remove-subject').show();
            } else {
                rows.find('.remove-subject').hide();
            }
        }

        // Prevent duplicate subject selection
        $(document).on('change', '.subject-select', function() {
            const selectedSubjects = [];
            $('.subject-select').each(function() {
                const val = $(this).val();
                if (val) {
                    selectedSubjects.push(val);
                }
            });

            // Check for duplicates
            const duplicates = selectedSubjects.filter((item, index) => selectedSubjects.indexOf(item) !== index);
            if (duplicates.length > 0) {
                alert('You cannot assign the same subject multiple times.');
                $(this).val('');
            }
        });
    });
</script>
@endpush
@endsection});
</script>
@endpush
@endsection