@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Add New Account</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Accounts
                    </a>
                </div>
            </div>
            <p class="text-muted">Create a new user account in the system.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Account Information</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.accounts.store') }}">
                        @csrf
                        
                        <!-- Hidden input for default school assignment -->
                        <input type="hidden" name="school_id" value="1">
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Role & Access</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="role" class="form-label fw-bold">Account Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Select a role...</option>
                                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>
                                            <i class="fas fa-chalkboard-teacher"></i> Teacher
                                        </option>

                                        <option value="guardian" {{ old('role') == 'guardian' ? 'selected' : '' }}>
                                            <i class="fas fa-user-friends"></i> Guardian
                                        </option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">
                                            <strong>Teacher:</strong> Can manage grades, attendance, and view student information.<br>
                                            <strong>Guardian:</strong> Can view their child's academic information and communicate with teachers.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Contact Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label fw-bold">Phone Number</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g., +63 912 345 6789">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label fw-bold">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Complete address">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Security</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">Password must be at least 6 characters long.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection