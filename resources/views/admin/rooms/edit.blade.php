@extends('layouts.app')

@section('title', 'Edit Room')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Room: {{ $room->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Rooms
                        </a>
                        <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" id="editRoomForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Room Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $room->name) }}" placeholder="Enter room name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grade_level">Grade Level <span class="text-danger">*</span></label>
                                    <select class="form-control @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                        <option value="" disabled>Select Grade Level</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('grade_level', $room->grade_level) == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_year">School Year <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('school_year') is-invalid @enderror" id="school_year" name="school_year" value="{{ old('school_year', $room->school_year ?: \App\Models\SystemSetting::getSetting('school_year', date('Y') . '-' . (date('Y') + 1))) }}" placeholder="e.g., 2024-2025" required>
                                    @error('school_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_limit">Student Limit</label>
                                    <input type="number" class="form-control @error('student_limit') is-invalid @enderror" id="student_limit" name="student_limit" value="{{ old('student_limit', $room->student_limit) }}" min="1" max="100" placeholder="Maximum students">
                                    @error('student_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty for no limit</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_id">School <span class="text-danger">*</span></label>
                                    <select class="form-control @error('school_id') is-invalid @enderror" id="school_id" name="school_id" required>
                                        <option value="" disabled>Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id', $room->school_id) == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="building_id">Building</label>
                                    <select class="form-control @error('building_id') is-invalid @enderror" id="building_id" name="building_id">
                                        <option value="" {{ old('building_id', $room->building_id) == '' ? 'selected' : '' }}>No Building Assigned</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" {{ old('building_id', $room->building_id) == $building->id ? 'selected' : '' }}>
                                                {{ $building->name }} ({{ $building->school->name ?? 'No School' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Optional: Assign this room to a building</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adviser_id">Room Adviser <small class="text-muted">(Optional)</small></label>
                                    <select class="form-control @error('adviser_id') is-invalid @enderror" id="adviser_id" name="adviser_id">
                                        <option value="">No adviser assigned</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                data-school="{{ $teacher->school_id }}" 
                                                {{ old('adviser_id', $room->adviser_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} ({{ $teacher->school->name ?? 'No School' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('adviser_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_active" 
                                               name="status" 
                                               value="active" 
                                               {{ old('status', $room->status) == 'active' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Active Room
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Only active rooms can have students enrolled.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Note:</strong> Subject and teacher assignments can be managed from the room details page.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Room
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Filter teachers based on selected school
    $('#school_id').change(function() {
        const selectedSchoolId = $(this).val();
        const adviserSelect = $('#adviser_id');
        
        // Reset adviser selection
        adviserSelect.val('');
        
        // Show/hide teachers based on school
        adviserSelect.find('option').each(function() {
            const teacherSchoolId = $(this).data('school');
            
            if ($(this).val() === '' || teacherSchoolId == selectedSchoolId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Form validation
    $('#editRoomForm').on('submit', function(e) {
        const name = $('#name').val().trim();
        const gradeLevel = $('#grade_level').val();
        const schoolYear = $('#school_year').val().trim();
        const schoolId = $('#school_id').val();
        const adviserId = $('#adviser_id').val();
        
        if (!name || !gradeLevel || !schoolYear || !schoolId || !adviserId) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });

    // Initialize teacher filtering on page load
    $('#school_id').trigger('change');
});
</script>
@endpush
@endsection