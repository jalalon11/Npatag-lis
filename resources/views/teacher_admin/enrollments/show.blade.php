@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Enrollment Details</h4>
                    <a href="{{ route('teacher-admin.enrollments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Student Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Student Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Full Name:</strong></td>
                                            <td>{{ $enrollment->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Birth Date:</strong></td>
                                            <td>{{ $enrollment->birth_date->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender:</strong></td>
                                            <td>{{ $enrollment->gender }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Student ID:</strong></td>
                                            <td>{{ $enrollment->student_id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>LRN:</strong></td>
                                            <td>{{ $enrollment->lrn }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Address:</strong></td>
                                            <td>{{ $enrollment->address ?: 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Guardian Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Guardian Name:</strong></td>
                                            <td>{{ $enrollment->guardian_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contact Number:</strong></td>
                                            <td>{{ $enrollment->guardian_contact }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $enrollment->guardian_email ?: 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Enrollment Details -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Enrollment Details</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>School Year:</strong></td>
                                            <td>{{ $enrollment->school_year }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Preferred Grade Level:</strong></td>
                                            <td>{{ $enrollment->preferred_grade_level }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Preferred Section:</strong></td>
                                            <td>{{ $enrollment->preferredSection ? $enrollment->preferredSection->name : 'Any available section' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Application Date:</strong></td>
                                            <td>{{ $enrollment->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($enrollment->isPending())
                                                    <span class="badge badge-warning badge-lg">Pending</span>
                                                @elseif($enrollment->isApproved())
                                                    <span class="badge badge-success badge-lg">Approved</span>
                                                @elseif($enrollment->isEnrolled())
                                                    <span class="badge badge-primary badge-lg">Enrolled</span>
                                                @elseif($enrollment->isRejected())
                                                    <span class="badge badge-danger badge-lg">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Processing Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Processing Information</h5>
                                </div>
                                <div class="card-body">
                                    @if($enrollment->processed_at)
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Processed By:</strong></td>
                                                <td>{{ $enrollment->processedBy ? $enrollment->processedBy->name : 'Unknown' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Processed Date:</strong></td>
                                                <td>{{ $enrollment->processed_at->format('F d, Y h:i A') }}</td>
                                            </tr>
                                            @if($enrollment->assignedSection)
                                                <tr>
                                                    <td><strong>Assigned Section:</strong></td>
                                                    <td>{{ $enrollment->assignedSection->name }} ({{ $enrollment->assignedSection->grade_level }})</td>
                                                </tr>
                                            @endif
                                            @if($enrollment->notes)
                                                <tr>
                                                    <td><strong>Notes:</strong></td>
                                                    <td>{{ $enrollment->notes }}</td>
                                                </tr>
                                            @endif
                                            @if($enrollment->rejection_reason)
                                                <tr>
                                                    <td><strong>Rejection Reason:</strong></td>
                                                    <td class="text-danger">{{ $enrollment->rejection_reason }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    @else
                                        <p class="text-muted">This enrollment has not been processed yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.table-borderless td:first-child {
    width: 40%;
    font-weight: 500;
}
</style>
@endpush