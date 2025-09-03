@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
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
                            <h3 class="mb-0 fw-bold text-primary">{{ $accounts->count() }}</h3>
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
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-shield text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Admins</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $accounts->where('role', 'admin')->count() }}</h3>
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
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-chalkboard-teacher text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Teachers</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $accounts->where('role', 'teacher')->count() }}</h3>
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
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-friends text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Guardians</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $accounts->where('role', 'guardian')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card bg-white border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.accounts.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search accounts..." value="{{ request('search') }}">
                    </div>
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
                <div class="col-md-4 d-flex justify-content-end">
                    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary fw-bold">
                        <i class="fas fa-plus-circle me-1"></i> Add New Account
                    </a>
                </div>
                <!-- Hidden field to maintain active tab when filtering -->
                <input type="hidden" name="tab" id="activeTab" value="{{ request('tab', 'admin') }}">
            </form>
        </div>
    </div>

    <!-- Accounts Tabs -->
    <div class="card border-0 bg-white shadow-sm pb-2">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <ul class="nav nav-tabs card-header-tabs" id="accountTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab', 'admin') == 'admin' ? 'active' : '' }}" 
                            id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" 
                            type="button" role="tab" aria-controls="admin" 
                            aria-selected="{{ request('tab', 'admin') == 'admin' ? 'true' : 'false' }}">
                        <i class="fas fa-user-shield me-2"></i>Admins ({{ $accounts->where('role', 'admin')->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') == 'teacher' ? 'active' : '' }}" 
                            id="teacher-tab" data-bs-toggle="tab" data-bs-target="#teacher" 
                            type="button" role="tab" aria-controls="teacher" 
                            aria-selected="{{ request('tab') == 'teacher' ? 'true' : 'false' }}">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Teachers ({{ $accounts->where('role', 'teacher')->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') == 'guardian' ? 'active' : '' }}" 
                            id="guardian-tab" data-bs-toggle="tab" data-bs-target="#guardian" 
                            type="button" role="tab" aria-controls="guardian" 
                            aria-selected="{{ request('tab') == 'guardian' ? 'true' : 'false' }}">
                        <i class="fas fa-user-friends me-2"></i>Guardians ({{ $accounts->where('role', 'guardian')->count() }})
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="accountTabContent">
                <!-- Admin Tab -->
                <div class="tab-pane fade {{ request('tab', 'admin') == 'admin' ? 'show active' : '' }}" 
                     id="admin" role="tabpanel" aria-labelledby="admin-tab">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="background-color: white;">
                            <thead class="table-light" style="background-color: #f8f9fa;">
                                <tr>
                                    <th scope="col">Account</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">School</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white;">
                                @php
                                    $adminAccounts = $accounts->where('role', 'admin');
                                    
                                    // Apply search filter if provided
                                    if (request('search')) {
                                        $searchTerm = strtolower(request('search'));
                                        $adminAccounts = $adminAccounts->filter(function($account) use ($searchTerm) {
                                            return str_contains(strtolower($account->name), $searchTerm) || 
                                                   str_contains(strtolower($account->email), $searchTerm) ||
                                                   ($account->phone_number && str_contains(strtolower($account->phone_number), $searchTerm)) ||
                                                   ($account->address && str_contains(strtolower($account->address), $searchTerm)) ||
                                                   ($account->school && str_contains(strtolower($account->school->name), $searchTerm));
                                        });
                                    }
                                    
                                    // Apply sorting if provided
                                    if (request('sort')) {
                                        $sortField = request('sort');
                                        $sortOrder = request('order', 'asc');
                                        
                                        $adminAccounts = $adminAccounts->sortBy(function($account) use ($sortField) {
                                            switch ($sortField) {
                                                case 'name':
                                                    return strtolower($account->name);
                                                case 'role':
                                                    return $account->role;
                                                case 'created_at':
                                                    return $account->created_at;
                                                default:
                                                    return strtolower($account->name);
                                            }
                                        }, SORT_REGULAR, $sortOrder === 'desc');
                                    }
                                @endphp
                                
                                @forelse($adminAccounts as $account)
                                    <tr style="background-color: white;">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-user-shield text-primary"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $account->name }}</h6>
                                                    <small class="text-muted">{{ $account->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-user-shield me-1"></i> Admin
                                            </span>
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
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if($account->id !== auth()->id())
                                                    <!-- Demote to Teacher -->
                                                    <form class="btn btn-outline-primary btn-sm p-0" action="{{ route('admin.accounts.demote-to-teacher', $account->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to demote {{ $account->name }} to Teacher?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-primary btn-sm border-0" title="Demote to Teacher">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- View Details -->
                                                <a href="{{ route('admin.accounts.show', $account->id) }}" class="btn btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Edit Account -->
                                                <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn btn-outline-primary" title="Edit Account">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if($account->id !== auth()->id())
                                                    <!-- Delete Account -->
                                                    <button type="button" class="btn btn-outline-primary" title="Delete Account"
                                                            data-bs-toggle="modal" data-bs-target="#deleteAccountModal{{ $account->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4" style="background-color: white;">
                                            <div class="text-muted">
                                                <i class="fas fa-user-shield fa-2x mb-3"></i>
                                                <h5>No Admins Found</h5>
                                                @if(request('search'))
                                                    <p>No admins match your search criteria.</p>
                                                    <a href="{{ route('admin.accounts.index', ['tab' => 'admin']) }}" class="btn btn-secondary me-2">
                                                        <i class="fas fa-times me-1"></i> Clear Search
                                                    </a>
                                                @else
                                                    <p>Start by adding a new admin account.</p>
                                                @endif
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
                
                <!-- Teacher Tab -->
                <div class="tab-pane fade {{ request('tab') == 'teacher' ? 'show active' : '' }}" 
                     id="teacher" role="tabpanel" aria-labelledby="teacher-tab">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="background-color: white;">
                            <thead class="table-light" style="background-color: #f8f9fa;">
                                <tr>
                                    <th scope="col">Account</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">School</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white;">
                                @php
                                    $teacherAccounts = $accounts->where('role', 'teacher');
                                    
                                    // Apply search filter if provided
                                    if (request('search')) {
                                        $searchTerm = strtolower(request('search'));
                                        $teacherAccounts = $teacherAccounts->filter(function($account) use ($searchTerm) {
                                            return str_contains(strtolower($account->name), $searchTerm) || 
                                                   str_contains(strtolower($account->email), $searchTerm) ||
                                                   ($account->phone_number && str_contains(strtolower($account->phone_number), $searchTerm)) ||
                                                   ($account->address && str_contains(strtolower($account->address), $searchTerm)) ||
                                                   ($account->school && str_contains(strtolower($account->school->name), $searchTerm));
                                        });
                                    }
                                    
                                    // Apply sorting if provided
                                    if (request('sort')) {
                                        $sortField = request('sort');
                                        $sortOrder = request('order', 'asc');
                                        
                                        $teacherAccounts = $teacherAccounts->sortBy(function($account) use ($sortField) {
                                            switch ($sortField) {
                                                case 'name':
                                                    return strtolower($account->name);
                                                case 'role':
                                                    return $account->role;
                                                case 'created_at':
                                                    return $account->created_at;
                                                default:
                                                    return strtolower($account->name);
                                            }
                                        }, SORT_REGULAR, $sortOrder === 'desc');
                                    }
                                @endphp
                                
                                @forelse($teacherAccounts as $account)
                                    <tr style="background-color: white;">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-chalkboard-teacher text-success"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $account->name }}</h6>
                                                    <small class="text-muted">{{ $account->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="fas fa-chalkboard-teacher me-1"></i> Teacher
                                            </span>
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
                                                <form action="{{ route('admin.accounts.promote-to-admin', $account->id) }}" method="POST" class="btn btn-outline-primary p-0 btn-sm">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-primary border-0 btn-sm" title="Promote to Admin" onclick="return confirm('Are you sure you want to promote {{ $account->name }} to Admin?')">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.accounts.show', $account->id) }}" class="btn btn-outline-primary btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn btn-outline-primary btn-sm" title="Edit Account">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-primary btn-sm" title="Delete Account" data-bs-toggle="modal" data-bs-target="#deleteAccountModal{{ $account->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4" style="background-color: white;">
                                            <div class="text-muted">
                                                <i class="fas fa-chalkboard-teacher fa-2x mb-3"></i>
                                                <h5>No Teachers Found</h5>
                                                @if(request('search'))
                                                    <p>No teachers match your search criteria.</p>
                                                    <a href="{{ route('admin.accounts.index', ['tab' => 'teacher']) }}" class="btn btn-secondary me-2">
                                                        <i class="fas fa-times me-1"></i> Clear Search
                                                    </a>
                                                @else
                                                    <p>Start by adding a new teacher account.</p>
                                                @endif
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
                
                <!-- Guardian Tab -->
                <div class="tab-pane fade {{ request('tab') == 'guardian' ? 'show active' : '' }}" 
                     id="guardian" role="tabpanel" aria-labelledby="guardian-tab">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="background-color: white;">
                            <thead class="table-light" style="background-color: #f8f9fa;">
                                <tr>
                                    <th scope="col">Account</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">School</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white;">
                                @php
                                    $guardianAccounts = $accounts->where('role', 'guardian');
                                    
                                    // Apply search filter if provided
                                    if (request('search')) {
                                        $searchTerm = strtolower(request('search'));
                                        $guardianAccounts = $guardianAccounts->filter(function($account) use ($searchTerm) {
                                            return str_contains(strtolower($account->name), $searchTerm) || 
                                                   str_contains(strtolower($account->email), $searchTerm) ||
                                                   ($account->phone_number && str_contains(strtolower($account->phone_number), $searchTerm)) ||
                                                   ($account->address && str_contains(strtolower($account->address), $searchTerm)) ||
                                                   ($account->school && str_contains(strtolower($account->school->name), $searchTerm));
                                        });
                                    }
                                    
                                    // Apply sorting if provided
                                    if (request('sort')) {
                                        $sortField = request('sort');
                                        $sortOrder = request('order', 'asc');
                                        
                                        $guardianAccounts = $guardianAccounts->sortBy(function($account) use ($sortField) {
                                            switch ($sortField) {
                                                case 'name':
                                                    return strtolower($account->name);
                                                case 'role':
                                                    return $account->role;
                                                case 'created_at':
                                                    return $account->created_at;
                                                default:
                                                    return strtolower($account->name);
                                            }
                                        }, SORT_REGULAR, $sortOrder === 'desc');
                                    }
                                @endphp
                                
                                @forelse($guardianAccounts as $account)
                                    <tr style="background-color: white;">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="bg-info bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-user-friends text-info"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $account->name }}</h6>
                                                    <small class="text-muted">{{ $account->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="fas fa-user-friends me-1"></i> Guardian
                                            </span>
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
                                                <a href="{{ route('admin.accounts.show', $account->id) }}" class="btn btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn btn-outline-primary" title="Edit Account">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-primary" title="Delete Account" data-bs-toggle="modal" data-bs-target="#deleteAccountModal{{ $account->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4" style="background-color: white;">
                                            <div class="text-muted">
                                                <i class="fas fa-user-friends fa-2x mb-3"></i>
                                                <h5>No Guardians Found</h5>
                                                @if(request('search'))
                                                    <p>No guardians match your search criteria.</p>
                                                    <a href="{{ route('admin.accounts.index', ['tab' => 'guardian']) }}" class="btn btn-secondary me-2">
                                                        <i class="fas fa-times me-1"></i> Clear Search
                                                    </a>
                                                @else
                                                    <p>Start by adding a new guardian account.</p>
                                                @endif
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle tab clicks to update the hidden input
    const tabs = document.querySelectorAll('#accountTabs button[data-bs-toggle="tab"]');
    const activeTabInput = document.getElementById('activeTab');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const tabName = target.replace('#', '');
            activeTabInput.value = tabName;
        });
    });
    
    // Handle form submission to maintain active tab
    const filterForm = document.getElementById('filterForm');
    filterForm.addEventListener('submit', function() {
        const activeTab = document.querySelector('#accountTabs .nav-link.active');
        if (activeTab) {
            const target = activeTab.getAttribute('data-bs-target');
            const tabName = target.replace('#', '');
            activeTabInput.value = tabName;
        }
    });
});
</script>
@endsection