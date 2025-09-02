@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Account Management</h2>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Accounts</h6>
                            <h3 class="mb-0">{{ $accounts->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-chalkboard-teacher text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Admins</h6>
                            <h3 class="mb-0">{{ $accounts->where('role', 'admin')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-friends text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Guardians</h6>
                            <h3 class="mb-0">{{ $accounts->where('role', 'guardian')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card bg-white border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.accounts.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search accounts..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                        <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teachers</option>
                        <option value="guardian" {{ request('role') == 'guardian' ? 'selected' : '' }}>Guardians</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort by...</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="role" {{ request('sort') == 'role' ? 'selected' : '' }}>Role</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="order" class="form-select">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                </div>
                <div class="col-md-2 d-flex justify-content-end">
                    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Add New Account
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="card border-0 bg-white shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-white table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Account</th>
                            <th scope="col">Role</th>
                            <th scope="col">School</th>
                            <th scope="col">Contact</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="
                                            @if($account->role == 'admin') bg-danger bg-opacity-10 rounded-circle p-2
                                            @elseif($account->role == 'teacher') bg-success bg-opacity-10 rounded-circle p-2
                                            @elseif($account->role == 'guardian') bg-info bg-opacity-10 rounded-circle p-2
                                            @endif">
                                            @if($account->role == 'admin')
                                                <i class="fas fa-user-shield text-danger"></i>
                                            @elseif($account->role == 'teacher')
                                                <i class="fas fa-chalkboard-teacher text-success"></i>
                                            @elseif($account->role == 'guardian')
                                                <i class="fas fa-user-friends text-info"></i>
                                            @endif
                                        </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $account->name }}</h6>
                                            <small class="text-muted">{{ $account->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($account->role == 'admin')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-user-shield me-1"></i> Admin
                                        </span>
                                    @elseif($account->role == 'teacher')
                                        <span class="badge bg-success">
                                            <i class="fas fa-chalkboard-teacher me-1"></i> Teacher
                                        </span>
                                    @elseif($account->role == 'guardian')
                                        <span class="badge bg-info">
                                            <i class="fas fa-user-friends me-1"></i> Guardian
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($account->school)
                                        <span class="badge bg-primary">
                                            {{ $account->school->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            No School Assigned
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($account->phone_number)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-phone me-1"></i> {{ $account->phone_number }}
                                        </small>
                                    @endif
                                    @if($account->address)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ Str::limit($account->address, 30) }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($account->role == 'teacher')
                                            <form action="{{ route('admin.accounts.promote-to-admin', $account->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Promote to Admin" onclick="return confirm('Are you sure you want to promote {{ $account->name }} to Admin?')">
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>
                                            </form>
                                        @elseif($account->role == 'admin' && $account->id !== auth()->id())
                                            <form action="{{ route('admin.accounts.demote-to-teacher', $account->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary" title="Demote to Teacher" onclick="return confirm('Are you sure you want to demote {{ $account->name }} to Teacher?')">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.accounts.show', $account->id) }}" class="btn btn-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn btn-warning" title="Edit Account">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($account->id !== auth()->id())
                                            <button type="button" class="btn btn-danger" title="Delete Account" data-bs-toggle="modal" data-bs-target="#deleteAccountModal{{ $account->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <h5>No Accounts Found</h5>
                                        <p>Start by adding a new account.</p>
                                        <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-1"></i> Add New Account
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modals -->
@foreach($accounts as $account)
<div class="modal fade" id="deleteAccountModal{{ $account->id }}" tabindex="-1" aria-labelledby="deleteAccountModalLabel{{ $account->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel{{ $account->id }}">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the account "{{ $account->name }}" ({{ ucfirst(str_replace('_', ' ', $account->role)) }})?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="POST" class="d-inline">
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
@endforeach
@endsection