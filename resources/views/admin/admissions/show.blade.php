@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Admission Details - {{ $admission->first_name }} {{ $admission->last_name }}</h3>
                    <div>
                        @if($admission->status === 'pending')
                            <a href="{{ route('admin.admissions.edit', $admission) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Student Information -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-user"></i> Student Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Full Name:</strong></div>
                            <div class="col-sm-8" data-student-name>{{ $admission->first_name }} {{ $admission->middle_name }} {{ $admission->last_name }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Birth Date:</strong></div>
                                        <div class="col-sm-8">{{ $admission->birth_date->format('F d, Y') }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Gender:</strong></div>
                                        <div class="col-sm-8">{{ $admission->gender }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Student ID:</strong></div>
                                        <div class="col-sm-8">{{ $admission->student_id }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>LRN:</strong></div>
                                        <div class="col-sm-8">{{ $admission->lrn ?? 'Not provided' }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Address:</strong></div>
                                        <div class="col-sm-8">{{ $admission->address }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-users"></i> Guardian Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Name:</strong></div>
                                        <div class="col-sm-8" data-guardian-name>{{ $admission->guardian_name }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Contact:</strong></div>
                                        <div class="col-sm-8">{{ $admission->guardian_contact }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Email:</strong></div>
                                        <div class="col-sm-8" data-guardian-email>{{ $admission->guardian_email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Academic Information -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4"><strong>School:</strong></div>
                                        <div class="col-sm-8">{{ $admission->school->name ?? 'Not specified' }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Grade Level:</strong></div>
                                        <div class="col-sm-8" data-grade-level>{{ $admission->preferred_grade_level }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>School Year:</strong></div>
                                        <div class="col-sm-8">{{ $admission->school_year }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Preferred Section:</strong></div>
                                        <div class="col-sm-8">{{ $admission->preferredSection->name ?? 'Not specified' }}</div>
                                    </div>
                                    @if($admission->assignedSection)
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-4"><strong>Assigned Section:</strong></div>
                                            <div class="col-sm-8">{{ $admission->assignedSection->name }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Additional Information</h5>
                                </div>
                                <div class="card-body">
                                    @if($admission->previous_school)
                                        <div class="row">
                                            <div class="col-sm-4"><strong>Previous School:</strong></div>
                                            <div class="col-sm-8">{{ $admission->previous_school }}</div>
                                        </div>
                                        <hr>
                                    @endif
                                    @if($admission->previous_grade_level)
                                        <div class="row">
                                            <div class="col-sm-4"><strong>Previous Grade:</strong></div>
                                            <div class="col-sm-8">{{ $admission->previous_grade_level }}</div>
                                        </div>
                                        <hr>
                                    @endif
                                    @if($admission->emergency_contact_name)
                                        <div class="row">
                                            <div class="col-sm-4"><strong>Emergency Contact:</strong></div>
                                            <div class="col-sm-8">
                                                {{ $admission->emergency_contact_name }}<br>
                                                <small class="text-muted">{{ $admission->emergency_contact_number }}</small><br>
                                                <small class="text-muted">{{ $admission->emergency_contact_relationship }}</small>
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                    @if($admission->medical_conditions)
                                        <div class="row">
                                            <div class="col-sm-4"><strong>Medical Conditions:</strong></div>
                                            <div class="col-sm-8">{{ $admission->medical_conditions }}</div>
                                        </div>
                                        <hr>
                                    @endif
                                    @if($admission->birth_certificate)
                                        <div class="row">
                                            <div class="col-sm-4"><strong>Birth Certificate:</strong></div>
                                            <div class="col-sm-8">
                                                <a href="{{ route('admin.admissions.birth-certificate', $admission) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View Birth Certificate
                                                </a>
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Status -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> Application Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Status:</strong>
                                            @if($admission->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($admission->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($admission->status === 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Application Date:</strong><br>
                                            {{ $admission->application_date->format('F d, Y') }}
                                        </div>
                                        @if($admission->processed_at)
                                            <div class="col-md-3">
                                                <strong>Processed Date:</strong><br>
                                                {{ $admission->processed_at->format('F d, Y') }}
                                            </div>
                                        @endif
                                        @if($admission->processedBy)
                                            <div class="col-md-3">
                                                <strong>Processed By:</strong><br>
                                                {{ $admission->processedBy->name }}
                                            </div>
                                        @endif
                                    </div>
                                    @if($admission->notes)
                                        <hr>
                                        <div class="row">
                                            <div class="col-12">
                                                <strong>Admin Notes:</strong><br>
                                                <div class="bg-light p-3 rounded">{{ $admission->notes }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($admission->status === 'pending')
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-cogs"></i> Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('admin.admissions.approve', $admission) }}" class="d-inline">
                                            @csrf
                                            <div class="form-group">
                                                <label for="assigned_section_id">Assign to Section:</label>
                                                <select name="assigned_section_id" id="assigned_section_id" class="form-control" required>
                                                    <option value="">Select Section</option>
                                                    @foreach($sections as $section)
                                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this admission?')">
                                                <i class="fas fa-check"></i> Approve Admission
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.admissions.reject', $admission) }}" class="d-inline ml-2">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this admission?')">
                                                <i class="fas fa-times"></i> Reject Admission
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection