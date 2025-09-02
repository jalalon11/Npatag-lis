@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit me-2"></i>Edit Student
            </h1>
            <p class="text-muted mb-0">Update student information for {{ $student->full_name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Student Information Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Student Information
                    </h6>
                    <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'warning' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.update', $student) }}" method="POST" id="studentForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-id-card me-2"></i>Personal Information
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name', $student->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                       id="middle_name" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name', $student->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-envelope me-2"></i>Contact Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $student->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $student->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $student->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-graduation-cap me-2"></i>Academic Information
                                </h6>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }} - {{ $section->school->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-users me-2"></i>Guardian Information
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="guardian_name" class="form-label">Guardian Name</label>
                                <input type="text" class="form-control @error('guardian_name') is-invalid @enderror" 
                                       id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}">
                                @error('guardian_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="guardian_phone" class="form-label">Guardian Phone</label>
                                <input type="tel" class="form-control @error('guardian_phone') is-invalid @enderror" 
                                       id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}">
                                @error('guardian_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="guardian_email" class="form-label">Guardian Email</label>
                                <input type="email" class="form-control @error('guardian_email') is-invalid @enderror" 
                                       id="guardian_email" name="guardian_email" value="{{ old('guardian_email', $student->guardian_email) }}">
                                @error('guardian_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Student
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Student Info Sidebar -->
        <div class="col-lg-4">
            <!-- Current Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Current Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <strong>Student ID:</strong><br>
                            <span class="text-muted">{{ $student->student_id }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Current Section:</strong><br>
                            <span class="text-muted">{{ $student->section->name ?? 'Not Assigned' }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Enrolled Date:</strong><br>
                            <span class="text-muted">{{ $student->enrolled_at?->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Status:</strong><br>
                            <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.students.toggle-status', $student) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $student->status === 'active' ? 'warning' : 'success' }} btn-sm w-100">
                                <i class="fas fa-{{ $student->status === 'active' ? 'pause' : 'play' }} me-2"></i>
                                {{ $student->status === 'active' ? 'Deactivate' : 'Activate' }} Student
                            </button>
                        </form>
                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye me-2"></i>View Full Details
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-list me-2"></i>All Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Information -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Important Notes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <ul class="mb-0 small">
                            <li>Changing the section will affect grade assignments</li>
                            <li>Email address must remain unique</li>
                            <li>Status changes affect student access to the system</li>
                            <li>All changes are logged for audit purposes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.gap-2 {
    gap: 0.5rem;
}

.border-bottom {
    border-bottom: 1px solid #dee2e6 !important;
}

.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.text-danger {
    color: #e74a3b !important;
}

.alert {
    border: none;
    border-radius: 0.35rem;
}

.card {
    border: none;
    border-radius: 0.35rem;
}

.btn {
    border-radius: 0.35rem;
}

.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
}

.badge-success {
    background-color: #1cc88a;
    color: white;
}

.badge-warning {
    background-color: #f6c23e;
    color: #5a5c69;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#studentForm').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Validate email format
        const email = $('#email').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            isValid = false;
            $('#email').addClass('is-invalid');
        }
        
        // Validate date of birth (must be in the past)
        const dob = new Date($('#date_of_birth').val());
        const today = new Date();
        if (dob >= today) {
            isValid = false;
            $('#date_of_birth').addClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields correctly.');
        }
    });
    
    // Remove validation errors on input
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Confirm status toggle
    $('form[action*="toggle-status"]').on('submit', function(e) {
        const action = $(this).find('button').text().trim();
        if (!confirm(`Are you sure you want to ${action.toLowerCase()} this student?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection