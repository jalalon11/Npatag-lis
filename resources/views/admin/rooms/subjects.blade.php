@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Subjects in {{ $room->name }}</h4>
                    <div>
                        <span class="badge badge-info">{{ $room->grade_level }}</span>
                        <span class="badge badge-secondary">{{ $room->school->name }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Room Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Subjects</span>
                                    <span class="info-box-number">{{ $subjects->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-chalkboard-teacher"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Assigned Teachers</span>
                                    <span class="info-box-number">{{ $subjects->whereNotNull('pivot.teacher_id')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-user-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Unassigned</span>
                                    <span class="info-box-number">{{ $subjects->whereNull('pivot.teacher_id')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Students</span>
                                    <span class="info-box-number">{{ $room->students->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Subjects Table -->
                    <h5>Assigned Subjects</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $index => $subject)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $subject->code }}</td>
                                    <td>{{ $subject->name }}</td>
                                    <td>
                                        @if($subject->pivot && $subject->pivot->teacher_id)
                                            {{ $subject->pivot->teacher->name ?? 'Unknown Teacher' }}
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subject->pivot && $subject->pivot->teacher_id)
                                            <span class="badge badge-success">Assigned</span>
                                        @else
                                            <span class="badge badge-warning">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            @if($subject->pivot && $subject->pivot->teacher_id)
                                                <form action="{{ route('admin.rooms.subjects.unassign', [$room, $subject]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to unassign this subject?')">
                                                        <i class="fas fa-times"></i> Unassign
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No subjects assigned to this room.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Subject Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Assign New Subject</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.rooms.subjects.assign', $room) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="subject_id">Subject</label>
                                            <select name="subject_id" id="subject_id" class="form-control" required>
                                                <option value="">Select Subject</option>
                                                @foreach($allSubjects as $availableSubject)
                                                    @if(!$subjects->contains('id', $availableSubject->id))
                                                        <option value="{{ $availableSubject->id }}">{{ $availableSubject->code }} - {{ $availableSubject->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="teacher_id">Teacher (Optional)</label>
                                            <select name="teacher_id" id="teacher_id" class="form-control">
                                                <option value="">Select Teacher</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-plus"></i> Assign Subject
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-3">
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Rooms
                        </a>
                        <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View Room Details
                        </a>
                        <a href="{{ route('admin.rooms.students', $room) }}" class="btn btn-info">
                            <i class="fas fa-users"></i> View Students
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable if needed
        $('.table').DataTable({
            "responsive": true,
            "autoWidth": false,
            "pageLength": 25,
            "order": [[1, "asc"]] // Sort by subject code
        });
    });
</script>
@endsection