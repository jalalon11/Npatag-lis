@extends('layouts.teacher_admin')

@section('title', 'Student Details - ' . $student->full_name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Student Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher-admin.students.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">{{ $student->full_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('teacher-admin.students.edit', $student) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Student
            </a>
            <a href="{{ route('teacher-admin.students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Student Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Student ID:</td>
                                    <td>{{ $student->student_id }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">LRN:</td>
                                    <td>{{ $student->lrn }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Full Name:</td>
                                    <td>{{ $student->full_name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Gender:</td>
                                    <td>{{ $student->gender }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Birth Date:</td>
                                    <td>{{ $student->birth_date ? $student->birth_date->format('F d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Age:</td>
                                    <td>{{ $student->birth_date ? $student->birth_date->age : 'N/A' }} years old</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Section:</td>
                                    <td>
                                        <span class="badge badge-info badge-lg">
                                            {{ $student->section->name }} (Grade {{ $student->section->grade_level }})
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">School Year:</td>
                                    <td>{{ $student->school_year ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Status:</td>
                                    <td>
                                        @if($student->is_active)
                                            <span class="badge badge-success badge-lg">Active</span>
                                        @else
                                            <span class="badge badge-warning badge-lg">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Address:</td>
                                    <td>{{ $student->address ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Guardian Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Guardian Name:</td>
                                    <td>{{ $student->guardian_name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Contact Number:</td>
                                    <td>{{ $student->guardian_contact }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Email Address:</td>
                                    <td>{{ $student->guardian_email ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if($student->enrollment)
            <!-- Enrollment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Enrollment Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Enrollment ID:</td>
                                    <td>{{ $student->enrollment->id }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Application Date:</td>
                                    <td>{{ $student->enrollment->created_at->format('F d, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Approval Date:</td>
                                    <td>{{ $student->enrollment->approved_at ? $student->enrollment->approved_at->format('F d, Y g:i A') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Preferred Grade:</td>
                                    <td>Grade {{ $student->enrollment->preferred_grade_level }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Preferred Section:</td>
                                    <td>{{ $student->enrollment->preferred_section ?? 'Any' }}</td>
                                </tr>
                                @if($student->enrollment->notes)
                                <tr>
                                    <td class="font-weight-bold">Notes:</td>
                                    <td>{{ $student->enrollment->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Actions & Statistics -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher-admin.students.edit', $student) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Edit Student
                        </a>
                        
                        <form method="POST" action="{{ route('teacher-admin.students.toggle-status', $student) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-{{ $student->is_active ? 'secondary' : 'success' }} btn-block"
                                    onclick="return confirm('Are you sure you want to {{ $student->is_active ? 'deactivate' : 'activate' }} this student?')">
                                <i class="fas fa-{{ $student->is_active ? 'user-times' : 'user-check' }}"></i> 
                                {{ $student->is_active ? 'Deactivate' : 'Activate' }} Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Academic Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Academic Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">{{ $student->grades->count() }}</h4>
                            <small class="text-muted">Total Grades</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-success">{{ $student->attendances->where('status', 'present')->count() }}</h4>
                            <small class="text-muted">Days Present</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-warning">{{ $student->attendances->where('status', 'absent')->count() }}</h4>
                            <small class="text-muted">Days Absent</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Section Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="font-weight-bold">Section:</td>
                            <td>{{ $student->section->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Grade Level:</td>
                            <td>{{ $student->section->grade_level }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Capacity:</td>
                            <td>{{ $student->section->capacity ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Status:</td>
                            <td>
                                @if($student->section->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection