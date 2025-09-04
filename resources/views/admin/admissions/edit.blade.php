@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Edit Student Admission</h3>
                    <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Admissions
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.admissions.update', $admission) }}" method="POST" enctype="multipart/form-data" id="admissionForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Student Information -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-user"></i> Student Information</h5>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ old('first_name', $admission->first_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                           value="{{ old('middle_name', $admission->middle_name) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ old('last_name', $admission->last_name) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="birth_date">Birth Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" 
                                           value="{{ old('birth_date', $admission->birth_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $admission->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $admission->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student_id">Student ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="student_id" name="student_id" 
                                           value="{{ old('student_id', $admission->student_id) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lrn">LRN (Learner Reference Number)</label>
                                    <input type="text" class="form-control" id="lrn" name="lrn" 
                                           value="{{ old('lrn', $admission->lrn) }}" maxlength="12">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address', $admission->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-users"></i> Guardian Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" 
                                           value="{{ old('guardian_name', $admission->guardian_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_contact">Guardian Contact <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" 
                                           value="{{ old('guardian_contact', $admission->guardian_contact) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_email">Guardian Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="guardian_email" name="guardian_email" 
                                           value="{{ old('guardian_email', $admission->guardian_email) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">School <span class="text-danger">*</span></label>
                                    <select class="form-control" id="school_id" name="school_id" required>
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id', $admission->school_id) == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="preferred_grade_level">Grade Level <span class="text-danger">*</span></label>
                                    <select class="form-control" id="preferred_grade_level" name="preferred_grade_level" required>
                                        <option value="">Select Grade Level</option>
                                        <option value="Kindergarten" {{ old('preferred_grade_level', $admission->preferred_grade_level) == 'Kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                                        <option value="Grade 1" {{ old('preferred_grade_level', $admission->preferred_grade_level) == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
                                        <option value="Grade 2" {{ old('preferred_grade_level', $admission->preferred_grade_level) == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
                                        <option value="Grade 3" {{ old('preferred_grade_level', $admission->preferred_grade_level) == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
                                        <option value="Grade 4" {{ old('preferred_grade_level', $admission->preferred_grade_level) == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
                                        <option value="Grade 5" {{ old('preferred_grade_level', $admission->preferred_grade_level) == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
                                        <option value="Grade 6" {{ old('preferred_grade_level', $admission->preferred_grade_level) == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_year">School Year <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="school_year" name="school_year" 
                                           value="{{ old('school_year', $admission->school_year) }}" 
                                           placeholder="2024-2025" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preferred_section_id">Preferred Section</label>
                                    <select class="form-control" id="preferred_section_id" name="preferred_section_id">
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('preferred_section_id', $admission->preferred_section_id) == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Additional Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="previous_school">Previous School</label>
                                    <input type="text" class="form-control" id="previous_school" name="previous_school" 
                                           value="{{ old('previous_school', $admission->previous_school) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="previous_grade_level">Previous Grade Level</label>
                                    <input type="text" class="form-control" id="previous_grade_level" name="previous_grade_level" 
                                           value="{{ old('previous_grade_level', $admission->previous_grade_level) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_name">Emergency Contact Name</label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                           value="{{ old('emergency_contact_name', $admission->emergency_contact_name) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_number">Emergency Contact Number</label>
                                    <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" 
                                           value="{{ old('emergency_contact_number', $admission->emergency_contact_number) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_relationship">Relationship</label>
                                    <input type="text" class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship" 
                                           value="{{ old('emergency_contact_relationship', $admission->emergency_contact_relationship) }}" placeholder="e.g., Aunt, Uncle, Grandparent">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birth_certificate">Birth Certificate</label>
                                    <input type="file" class="form-control-file" id="birth_certificate" name="birth_certificate" 
                                           accept=".jpg,.jpeg,.png,.pdf">
                                    <small class="text-muted">Upload new birth certificate (JPG, PNG, or PDF, max 5MB) - Leave empty to keep current file</small>
                                    @if($admission->birth_certificate)
                                        <div class="mt-2">
                                            <strong>Current file:</strong> 
                                            <a href="{{ route('admin.admissions.birth-certificate', $admission) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-file-alt"></i> View Current Birth Certificate
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="medical_conditions">Medical Conditions</label>
                                    <textarea class="form-control" id="medical_conditions" name="medical_conditions" 
                                              rows="3" placeholder="Any medical conditions or allergies">{{ old('medical_conditions', $admission->medical_conditions) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Admin Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" 
                                              rows="3" placeholder="Internal notes for this admission">{{ old('notes', $admission->notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Admission
                                    </button>
                                    <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection