@extends('layouts.app')

@section('title', 'Guardian Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Guardian Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Guardian Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-account-child"></i> My Children
                    </h4>

                    @if($students->count() > 0)
                        <div class="row">
                            @foreach($students as $student)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="mdi mdi-account text-white font-18"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1">{{ $student->first_name }} {{ $student->last_name }}</h5>
                                                    <p class="text-muted mb-0">Student ID: {{ $student->student_id }}</p>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="text-muted mb-1">Grade Level</p>
                                                        <p class="mb-0 fw-semibold">{{ $student->grade_level }}</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="text-muted mb-1">Section</p>
                                                        <p class="mb-0 fw-semibold">{{ $student->section->name ?? 'Not Assigned' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <p class="text-muted mb-1">School</p>
                                                <p class="mb-0">{{ $student->admission->school->name ?? 'N/A' }}</p>
                                            </div>

                                            @if($student->grades->count() > 0)
                                                <div class="mb-3">
                                                    <p class="text-muted mb-2">Recent Grades</p>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($student->grades->take(3) as $grade)
                                                            <span class="badge bg-info">{{ $grade->subject->name ?? 'Subject' }}: {{ $grade->grade }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="d-grid">
                                                <a href="{{ route('guardian.student.details', $student->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="mdi mdi-eye"></i> View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg bg-light rounded-circle mx-auto mb-4">
                                <i class="mdi mdi-account-child font-24 text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Students Found</h5>
                            <p class="text-muted">You don't have any students associated with your account yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection