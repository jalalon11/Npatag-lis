@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="mb-0">
                    <h2 class="m-0">{{ $account->name }}</h2>
                    <p class="m-0 text-muted">{{ ucfirst($account->role) }}</p>
                </div>
                <div class="d-flex">
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Accounts
                    </a>
                    <div class="btn-group">
                        <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-primary me-2"></i> Account Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mx-auto mb-3">
                            <div class="bg-{{ $account->role == 'teacher' ? 'success' : ($account->role == 'guardian' ? 'info' : 'secondary') }} bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="fas fa-user fa-3x text-{{ $account->role == 'teacher' ? 'success' : ($account->role == 'guardian' ? 'info' : 'secondary') }}"></i>
                            </div>
                        </div>
                        <h4 class="mb-2">{{ $account->name }}</h4>
                        <div class="d-flex flex-column align-items-center mb-3">
                            <div class="text-muted mb-2">
                                <i class="fas fa-envelope me-2"></i>{{ $account->email }}
                            </div>
                            @if($account->phone_number)
                                <div class="text-muted">
                                    <i class="fas fa-phone me-2"></i>{{ $account->phone_number }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted small mb-1 d-block">Role</label>
                        <div>
                            <span class="badge bg-{{ $account->role == 'teacher' ? 'success' : ($account->role == 'guardian' ? 'info' : 'secondary') }} px-3 py-2">
                                <i class="fas {{ $account->role == 'teacher' ? 'fa-chalkboard-teacher' : ($account->role == 'guardian' ? 'fa-user-friends' : 'fa-user') }} me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $account->role)) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($account->address)
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted small mb-1 d-block">Address</label>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>{{ $account->address }}
                            </p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="text-muted small mb-1 d-block">Member Since</label>
                        <p class="mb-0">
                            <i class="far fa-calendar-alt text-muted me-2"></i>{{ $account->created_at->format('F d, Y') }}
                        </p>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 border-0">
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                        <i class="fas fa-key me-1"></i> Reset Password
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold">
                        @if($account->role == 'teacher')
                            <i class="fas fa-chalkboard-teacher text-success me-2"></i> Teaching Information
                        @elseif($account->role == 'guardian')
                            <i class="fas fa-user-friends text-info me-2"></i> Guardian Information
                        @else
                            <i class="fas fa-info-circle text-primary me-2"></i> Additional Information
                        @endif
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($account->role == 'teacher')
                        @if(isset($sections) && $sections->count() > 0 || isset($teachingAssignments) && $teachingAssignments->count() > 0)
                                @if(isset($sections) && $sections->count() > 0)
                                    <h6 class="fw-bold mb-3 text-muted">Sections as Adviser</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Section Name</th>
                                                    <th>Grade Level</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sections as $section)
                                                    <tr>
                                                        <td>
                                                            <i class="fas fa-users me-2 text-muted"></i>
                                                            {{ $section->name }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                                <i class="fas fa-layer-group me-1"></i> Grade {{ $section->grade_level }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if(isset($teachingAssignments) && $teachingAssignments->count() > 0)
                                    <h6 class="fw-bold mb-3 text-muted">Subject Assignments</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Section</th>
                                                    <th>Grade Level</th>
                                                    <th>Subject</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($teachingAssignments as $assignment)
                                                    <tr>
                                                        <td>
                                                            <i class="fas fa-users me-2 text-muted"></i>
                                                            {{ $assignment->section_name }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                                                <i class="fas fa-layer-group me-1"></i> Grade {{ $assignment->grade_level }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <i class="fas fa-book me-2 text-muted"></i>
                                                            {{ $assignment->subject_name }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-clipboard-list fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <h5 class="text-muted">No Teaching Assignments</h5>
                                    <p class="text-muted">This teacher doesn't have any teaching assignments yet.</p>
                                </div>
                            @endif
                        

                        @elseif($account->role == 'guardian')
                            <div class="text-center py-5">
                                <div class="bg-info bg-opacity-10 rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-user-friends fa-3x text-info"></i>
                                </div>
                                <h5 class="mb-3">Guardian Account</h5>
                                <p class="text-muted mb-4">This account has guardian-level access to view their child's academic information and communicate with teachers.</p>
                                <div class="alert alert-info bg-light border-0">
                                    <i class="fas fa-info-circle me-2"></i> Student-guardian relationships will be displayed here once implemented.
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-user fa-3x text-secondary"></i>
                                </div>
                                <h5 class="mb-3">Account Information</h5>
                                <p class="text-muted">No additional information available for this account type.</p>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="resetPasswordModalLabel">
                    <i class="fas fa-key text-warning me-2"></i>Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.accounts.reset-password', $account->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-4">Reset password for: <strong>{{ $account->name }}</strong></p>
                    <div class="mb-3">
                        <label for="new_password" class="form-label small text-muted mb-1">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="new_password" name="password" required minlength="6" placeholder="Enter new password">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label small text-muted mb-1">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="6" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                    <h5>Delete Account</h5>
                    <p class="mb-0">Are you sure you want to delete the account for</p>
                    <p class="fw-bold">{{ $account->name }}?</p>
                    <div class="alert alert-danger bg-light border-0">
                        <i class="fas fa-exclamation-circle me-2"></i> This action cannot be undone.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --border-radius: 12px;
        --border-radius-pill: 50px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --primary-color: #0d6efd;
        --text-muted: #6c757d;
        --bg-light: #f8f9fa;
    }

    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .table > :not(caption) > * > * {
        padding: 0.75rem 1rem;
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
        border-bottom-width: 1px;
    }

    .table-hover > tbody > tr:hover {
        --bs-table-accent-bg: rgba(13, 110, 253, 0.03);
    }

    .alert {
        border: none;
        border-radius: var(--border-radius);
    }

    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
        border-radius: 4px;
    }
</style>
@endsection