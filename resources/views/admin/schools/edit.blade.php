@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-school text-primary me-2"></i> Edit School Information</h2>
                <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Schools
                </a>
            </div>
            <p class="text-muted">Update school information and settings. As an administrator, you have full access to modify all school details.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> School Information</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.schools.update', $school->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Basic Information -->
                            <div class="col-md-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">School Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $school->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="code" class="form-label fw-bold">School Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $school->code) }}" required>
                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">Unique identifier for the school. Use only letters, numbers, and hyphens.</div>
                            </div>

                            <div class="col-md-12">
                                <label for="address" class="form-label fw-bold">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $school->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="principal" class="form-label fw-bold">Principal</label>
                                <input type="text" class="form-control @error('principal') is-invalid @enderror" id="principal" name="principal" value="{{ old('principal', $school->principal) }}">
                                @error('principal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="contact_email" class="form-label fw-bold">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $school->contact_email) }}">
                                @error('contact_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label fw-bold">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $school->contact_phone) }}">
                                @error('contact_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $school->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $school->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status', $school->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Logo Section -->
                            <div class="col-md-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-image me-2"></i>School Logo</h6>
                                <div class="row align-items-center">
                                    <div class="col-auto mb-3">
                                        @if($school->logo_path)
                                            <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="img-thumbnail" style="max-width: 120px; max-height: 120px; object-fit: contain;">
                                        @else
                                            <div class="bg-primary bg-opacity-10 p-4 rounded text-center" style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-school text-primary fa-3x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                        @error('logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="form-text">Upload a new school logo (JPEG, PNG, GIF - max 2MB). The logo appears on reports, grade slips, and certificates.</div>
                                        @if($school->logo_path)
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                                <label class="form-check-label text-danger" for="remove_logo">
                                                    Remove current logo
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Grade Levels Section -->
                            <div class="col-md-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-graduation-cap me-2"></i>Grade Levels</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-3 text-muted">Select the grade levels offered by this school:</p>
                                        <div class="row">
                                            @php
                                                $currentGradeLevels = is_array($school->grade_levels) ? $school->grade_levels : 
                                                                    (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);
                                                $availableGrades = ['K', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
                                            @endphp
                                            @foreach($availableGrades as $grade)
                                                <div class="col-md-2 col-sm-3 col-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_{{ $grade }}" name="grade_levels[]" value="{{ $grade }}" 
                                                               {{ in_array($grade, old('grade_levels', $currentGradeLevels)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_{{ $grade }}">
                                                            Grade {{ $grade }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('grade_levels')
                                            <div class="text-danger mt-2">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Subscription Information -->
                            <div class="col-md-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-credit-card me-2"></i>Subscription Information</h6>
                            </div>

                            <div class="col-md-6">
                                <label for="subscription_status" class="form-label fw-bold">Subscription Status</label>
                                <select class="form-select @error('subscription_status') is-invalid @enderror" id="subscription_status" name="subscription_status">
                                    <option value="trial" {{ old('subscription_status', $school->subscription_status) === 'trial' ? 'selected' : '' }}>Trial</option>
                                    <option value="active" {{ old('subscription_status', $school->subscription_status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expired" {{ old('subscription_status', $school->subscription_status) === 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="suspended" {{ old('subscription_status', $school->subscription_status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('subscription_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="subscription_expires_at" class="form-label fw-bold">Subscription Expires At</label>
                                <input type="date" class="form-control @error('subscription_expires_at') is-invalid @enderror" id="subscription_expires_at" name="subscription_expires_at" 
                                       value="{{ old('subscription_expires_at', $school->subscription_expires_at ? $school->subscription_expires_at->format('Y-m-d') : '') }}">
                                @error('subscription_expires_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-md-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.schools.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
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