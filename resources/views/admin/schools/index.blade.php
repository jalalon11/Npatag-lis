@extends('layouts.app')

@section('styles')
<style>
    .school-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        border-radius: 12px;
    }

    .school-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .school-logo {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: 8px;
    }

    .school-logo-placeholder {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .stats-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        color: white;
    }

    .stats-card.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stats-card.success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stats-card.warning {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .search-box {
        border-radius: 25px;
        border: 2px solid #e9ecef;
        transition: border-color 0.3s ease;
    }

    .search-box:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .filter-dropdown {
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Schools Management</h2>
            <p class="text-muted mb-0">Manage all schools in the system</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                <i class="fas fa-plus me-2"></i>Add New School
            </button>
            <button class="btn btn-primary" onclick="exportSchools()">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $totalSchools ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Total Schools</p>
                        </div>
                        <i class="fas fa-school fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $activeSchools ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Active Schools</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $totalStudents ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Total Students</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $totalTeachers ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Total Teachers</p>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control search-box" id="schoolSearch" placeholder="Search schools...">
                </div>
                <div class="col-md-2">
                    <select class="form-select filter-dropdown" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select filter-dropdown" id="subscriptionFilter">
                        <option value="">All Subscriptions</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="trial">Trial</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Schools Grid -->
    <div class="row" id="schoolsGrid">
        @forelse($schools ?? [] as $school)
            <div class="col-lg-6 col-xl-4 mb-4 school-item" data-school-name="{{ strtolower($school->name) }}" data-status="{{ $school->status }}">
                <div class="card school-card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <!-- School Header -->
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                @if($school->logo_path)
                                    <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="school-logo">
                                @else
                                    <div class="school-logo-placeholder">
                                        <i class="fas fa-school text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fw-bold">{{ $school->name }}</h5>
                                <p class="text-muted small mb-2">Code: {{ $school->code }}</p>
                                <div class="d-flex gap-1">
                                    <span class="badge status-badge 
                                        @if($school->status === 'active') bg-success
                                        @elseif($school->status === 'inactive') bg-secondary
                                        @else bg-warning @endif">
                                        {{ ucfirst($school->status) }}
                                    </span>
                                    @if($school->subscription_status)
                                        <span class="badge status-badge 
                                            @if($school->subscription_status === 'active') bg-primary
                                            @elseif($school->subscription_status === 'expired') bg-danger
                                            @else bg-info @endif">
                                            {{ ucfirst($school->subscription_status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- School Details -->
                        <div class="mb-3">
                            @if($school->address)
                                <p class="small mb-1">
                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                    {{ Str::limit($school->address, 50) }}
                                </p>
                            @endif
                            @if($school->principal)
                                <p class="small mb-1">
                                    <i class="fas fa-user-tie text-muted me-2"></i>
                                    Principal: {{ $school->principal }}
                                </p>
                            @endif
                        </div>

                        <!-- Statistics -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="mb-0 fw-bold text-primary">{{ $school->students_count ?? 0 }}</h6>
                                    <small class="text-muted">Students</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="mb-0 fw-bold text-success">{{ $school->teachers_count ?? 0 }}</h6>
                                    <small class="text-muted">Teachers</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-0 fw-bold text-info">{{ $school->sections_count ?? 0 }}</h6>
                                <small class="text-muted">Sections</small>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.schools.show', $school->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.schools.reports', $school->id) }}">
                                        <i class="fas fa-chart-bar me-2"></i>Reports
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.schools.settings', $school->id) }}">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmAction('suspend', {{ $school->id }})">
                                        <i class="fas fa-ban me-2"></i>Suspend
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-school fa-4x text-muted"></i>
                    </div>
                    <h4>No Schools Found</h4>
                    <p class="text-muted">There are no schools in the system yet.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                        <i class="fas fa-plus me-2"></i>Add First School
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
// Search and filter functionality
document.getElementById('schoolSearch').addEventListener('input', filterSchools);
document.getElementById('statusFilter').addEventListener('change', filterSchools);
document.getElementById('subscriptionFilter').addEventListener('change', filterSchools);

function filterSchools() {
    const searchTerm = document.getElementById('schoolSearch').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const subscriptionFilter = document.getElementById('subscriptionFilter').value;
    
    const schoolItems = document.querySelectorAll('.school-item');
    
    schoolItems.forEach(item => {
        const schoolName = item.dataset.schoolName;
        const status = item.dataset.status;
        
        const matchesSearch = schoolName.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('schoolSearch').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('subscriptionFilter').value = '';
    filterSchools();
}

function exportSchools() {
    window.location.href = '{{ route("admin.schools.export") }}';
}

function confirmAction(action, schoolId) {
    if (confirm(`Are you sure you want to ${action} this school?`)) {
        // Handle action
        console.log(`${action} school ${schoolId}`);
    }
}
</script>
@endsection