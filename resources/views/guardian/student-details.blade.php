@extends('layouts.app')

@section('title', 'Student Details - ' . $student->first_name . ' ' . $student->last_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Student Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guardian.dashboard') }}">Guardian Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $student->first_name }} {{ $student->last_name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Student Information Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="avatar-lg bg-primary rounded-circle mx-auto mb-3">
                            <i class="mdi mdi-account text-white font-24"></i>
                        </div>
                        <h4 class="mb-1">{{ $student->first_name }} {{ $student->last_name }}</h4>
                        <p class="text-muted mb-3">Student ID: {{ $student->student_id }}</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Grade Level:</td>
                                    <td class="fw-semibold">{{ $student->grade_level }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Section:</td>
                                    <td class="fw-semibold">{{ $student->section->name ?? 'Not Assigned' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">School:</td>
                                    <td class="fw-semibold">{{ $student->admission->school->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">LRN:</td>
                                    <td class="fw-semibold">{{ $student->lrn ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Gender:</td>
                                    <td class="fw-semibold">{{ $student->gender ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Birth Date:</td>
                                    <td class="fw-semibold">{{ $student->birth_date ? $student->birth_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grades and Attendance -->
        <div class="col-lg-8">
            <!-- Grades Section -->
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-chart-line"></i> Academic Performance
                    </h4>

                    @if($student->grades->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Quarter</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->grades->sortByDesc('created_at') as $grade)
                                        <tr>
                                            <td>{{ $grade->subject->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($grade->grade >= 90) bg-success
                                                    @elseif($grade->grade >= 80) bg-info
                                                    @elseif($grade->grade >= 75) bg-warning
                                                    @else bg-danger
                                                    @endif
                                                ">{{ $grade->grade }}</span>
                                            </td>
                                            <td>{{ $grade->quarter ?? 'N/A' }}</td>
                                            <td>{{ $grade->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-chart-line font-24 text-muted"></i>
                            <p class="text-muted mt-2">No grades available yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Attendance Section -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-calendar-check"></i> Recent Attendance
                    </h4>

                    @if($student->attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->attendances->sortByDesc('date') as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($attendance->status === 'present') bg-success
                                                    @elseif($attendance->status === 'late') bg-warning
                                                    @else bg-danger
                                                    @endif
                                                ">{{ ucfirst($attendance->status) }}</span>
                                            </td>
                                            <td>{{ $attendance->remarks ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-calendar-check font-24 text-muted"></i>
                            <p class="text-muted mt-2">No attendance records available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection