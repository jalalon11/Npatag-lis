@extends('layouts.teacher_admin')

@section('title', 'Edit Student - ' . $student->full_name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Student</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher-admin.students.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher-admin.students.show', $student) }}">{{ $student->full_name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('teacher-admin.students.show', $student) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Details
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher-admin.students.update', $student) }}">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name', $student->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                           id="middle_name" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name', $student->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Student ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                           id="student_id" name="student_id" value="{{ old('student_id', $student->student_id) }}" required>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lrn">LRN (Learner Reference Number) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('lrn') is-invalid @enderror" 
                                           id="lrn" name="lrn" value="{{ old('lrn', $student->lrn) }}" required
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                           maxlength="12" placeholder="12-digit number">
                                    @error('lrn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birth_date">Birth Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" 
                                           value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}" required>
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section_id">Section <span class="text-danger">*</span></label>
                                    <select class="form-control @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" 
                                                    {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }} (Grade {{ $section->grade_level }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Guardian Information -->
                        <hr>
                        <h5 class="mb-3">Guardian Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('guardian_name') is-invalid @enderror" 
                                           id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" required>
                                    @error('guardian_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guardian_contact">Guardian Contact <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('guardian_contact') is-invalid @enderror" 
                                           id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}" required>
                                    @error('guardian_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="guardian_email">Guardian Email</label>
                            <input type="email" class="form-control @error('guardian_email') is-invalid @enderror" 
                                   id="guardian_email" name="guardian_email" value="{{ old('guardian_email', $student->guardian_email) }}">
                            @error('guardian_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Student
                            </button>
                            <a href="{{ route('teacher-admin.students.show', $student) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Student Summary -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="font-weight-bold">Student ID:</td>
                            <td>{{ $student->student_id }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">LRN:</td>
                            <td>{{ $student->lrn }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Current Section:</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $student->section->name }} (Grade {{ $student->section->grade_level }})
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Status:</td>
                            <td>
                                @if($student->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-warning">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">School Year:</td>
                            <td>{{ $student->school_year ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($student->enrollment)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Enrollment Info</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="font-weight-bold">Enrollment ID:</td>
                            <td>{{ $student->enrollment->id }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Applied:</td>
                            <td>{{ $student->enrollment->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Approved:</td>
                            <td>{{ $student->enrollment->approved_at ? $student->enrollment->approved_at->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Note:</strong> Changes to student information will be saved immediately. Make sure all information is accurate before updating.
            </div>
        </div>
    </div>
</div>
@endsection