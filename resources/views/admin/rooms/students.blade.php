@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Students in {{ $room->name }}</h4>
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
                                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Students</span>
                                    <span class="info-box-number">{{ $totalStudents }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Active Students</span>
                                    <span class="info-box-number">{{ $activeStudents }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-user-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Inactive Students</span>
                                    <span class="info-box-number">{{ $inactiveStudents }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-chalkboard-teacher"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Adviser</span>
                                    <span class="info-box-number">{{ $room->adviser ? $room->adviser->name : 'Not Assigned' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Enrolled Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->student_id ?? 'N/A' }}</td>
                                    <td>{{ $student->last_name }}, {{ $student->first_name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if($student->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No students found in this room.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-3">
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Rooms
                        </a>
                        <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View Room Details
                        </a>
                        <a href="{{ route('admin.rooms.subjects', $room) }}" class="btn btn-info">
                            <i class="fas fa-book"></i> View Subjects
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
            "order": [[2, "asc"]] // Sort by name
        });
    });
</script>
@endsection