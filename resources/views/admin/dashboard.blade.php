@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@php
    $maintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();
    $maintenanceMessage = \App\Models\SystemSetting::getMaintenanceMessage();
    $maintenanceDuration = \App\Models\SystemSetting::getMaintenanceDuration();
@endphp

@section('content')
<div class="container-fluid px-4">
    <!-- Top Row: Welcome Card and System Status -->
    <div class="row g-4 mb-4">
        <!-- Welcome Card - Left Side -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm welcome-header rounded text-white position-relative overflow-hidden h-100">
                <div class="position-absolute top-0 end-0 w-60 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.10; clip-path: polygon(20% 0, 100% 0%, 100% 100%, 0% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-50 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.45; clip-path: polygon(25% 0, 100% 0%, 100% 100%, 5% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-40 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.60; clip-path: polygon(30% 0, 100% 0%, 100% 100%, 10% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-30 h-100 z-0 d-none d-lg-block bg-primary" style="clip-path: polygon(35% 0, 100% 0%, 100% 100%, 15% 100%);"></div>
                
                <div class="card-body p-4 position-relative z-1 d-flex flex-column h-100">
                    <div class="d-flex flex-column align-items-start">
                        @if(Auth::user()->school && Auth::user()->school->logo_path)
                            <div class="mb-3">
                                <img src="{{ Auth::user()->school->logo_url }}" alt="{{ Auth::user()->school->name }} Logo" class="rounded" style="max-height: 50px;">
                            </div>
                        @else
                            <div class="avatar bg-white bg-opacity-25 rounded p-2 mb-3">
                                <i class="fas fa-chalkboard-teacher fa-lg"></i>
                            </div>
                        @endif
                        <h3 class="fw-bold mb-2 text-white display-6">Admin Dashboard</h3>
                        <p class="text-white mb-0 lead opacity-90">Welcome back, {{ Auth::user()->name }}!</p>
                        <div class="d-flex align-items-center mt-3">
                            <span class="badge bg-primary bg-opacity-10 text-white border border-primary border-opacity-20 px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> System Online
                            </span>
                            <div class="ms-3 d-flex align-items-center text-white text-opacity-70">
                                <i class="far fa-calendar-alt text-info me-2"></i>
                                <span>{{ now()->format('F d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status - Right Side -->
        <div class="col-lg-4">
            <div class="bg-white rounded shadow-sm h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0">System Status</h5>
                            <p class="text-muted small mb-0">Current system state</p>
                        </div>
                    </div>
                    <span class="badge {{ $maintenanceMode ? 'bg-danger bg-opacity-10 text-danger' : 'bg-primary bg-opacity-10 text-primary' }} px-3 py-2 fw-semibold">
                        {{ $maintenanceMode ? 'Maintenance' : 'Online' }}
                    </span>
                </div>

                <div class="status-details">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="status-indicator bg-{{ $maintenanceMode ? 'danger' : 'primary' }} bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-{{ $maintenanceMode ? 'exclamation-triangle' : 'check-circle' }} text-{{ $maintenanceMode ? 'danger' : 'primary' }} fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">{{ $maintenanceMode ? 'Under Maintenance' : 'All Systems Running' }}</h6>
                            <p class="text-muted small mb-0">
                                {{ $maintenanceMode 
                                    ? 'System maintenance in progress' 
                                    : 'Everything is working normally' }}
                            </p>
                        </div>
                    </div>

                    @if($maintenanceMode && $maintenanceDuration)
                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 rounded-4 p-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <span class="small fw-medium">Duration: {{ $maintenanceDuration }} minutes</span>
                        </div>
                    </div>
                    @endif

                    <button type="button" class="btn btn-{{ $maintenanceMode ? 'success' : 'primary' }} w-100 py-2"
                            data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} me-2"></i>
                        {{ $maintenanceMode ? 'Exit Maintenance' : 'Enter Maintenance' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Teachers Card -->
        <div class="col-lg col-md-6">
            <a href="{{ route('admin.teachers.index') }}" class="text-decoration-none">
                <div class="bg-white rounded shadow-sm p-4 h-100 hover-lift">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-chalkboard-teacher text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase fw-medium small mb-1">Teachers</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="fw-bold mb-0">{{ $stats['teachersCount'] ?? 0 }}</h3>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Students Card -->
        <div class="col-lg col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="bg-white rounded shadow-sm p-4 h-100 hover-lift">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-user-graduate text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase fw-medium small mb-1">Students</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="fw-bold mb-0">{{ $stats['studentsCount'] ?? 0 }}</h3>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Classes Card -->
        <div class="col-lg col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="bg-white rounded shadow-sm p-4 h-100 hover-lift">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-chalkboard text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase fw-medium small mb-1">Classes</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="fw-bold mb-0">{{ $stats['classesCount'] ?? 0 }}</h3>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Subjects Card -->
        <div class="col-lg col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="bg-white rounded shadow-sm p-4 h-100 hover-lift">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-book text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase fw-medium small mb-1">Subjects</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="fw-bold mb-0">{{ $stats['subjectsCount'] ?? 0 }}</h3>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Rooms Card -->
        <div class="col-lg col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="bg-white rounded shadow-sm p-4 h-100 hover-lift">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-door-open text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase fw-medium small mb-1">Rooms</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="fw-bold mb-0">{{ $stats['roomsCount'] ?? 0 }}</h3>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>


    <!-- Essential Controls - Full Width -->
    <div class="row">
        <div class="col-12">
            <div class="controls-card bg-white rounded-4 shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="card-header text-white p-4 border-0">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex ">
                            <div class="text-primary rounded-circle me-3">
                                <i class="fas fa-bolt fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Essential Controls</h5>
                                <p class="mb-0 text-dark text-opacity-90 small">Quick access to core administrative functions</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary text-primary bg-opacity-10 px-3 py-2 rounded-pill">
                                {{ ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'][\App\Models\SystemSetting::getSetting('global_quarter', 'Q1')] }}
                            </span>
                            <span class="badge bg-primary text-primary bg-opacity-10 px-3 py-2 rounded-pill">
                                SY {{ \App\Models\SystemSetting::getSetting('school_year', date('Y') . '-' . (date('Y') + 1)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Controls Grid -->
                <div class="card-body p-0">
                    <div class="row g-4 p-4">
                        <!-- Add New User -->
                        <div class="col-lg-6">
                            <div class="p-4 bg-light rounded">
                                <div class="d-flex">
                                    <div class="rounded-4 me-4">
                                        <i class="fas fa-user-plus text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-2">Add New User</h6>
                                        <p class="text-muted mb-3 small">Create new accounts for teachers, students, or administrators</p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary btn-sm px-3">
                                                <i class="fas fa-chalkboard-teacher me-1"></i> Teacher
                                            </a>
                                            <a href="#" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-user-graduate me-1"></i> Student
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Settings -->
                        <div class="col-lg-6">
                            <div class="p-4 bg-light rounded">
                                <div class="d-flex">
                                    <div class="rounded-4 me-4">
                                        <i class="fas fa-graduation-cap text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-2">Academic Settings</h6>
                                        <p class="text-muted mb-3 small">Manage academic year, quarters, terms and school calendar</p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.academics.index') }}" class="btn btn-primary btn-sm px-3">
                                                <i class="fas fa-cog me-1"></i> Settings
                                            </a>
                                            <a href="#" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-calendar me-1"></i> Calendar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Class Management -->
                        <div class="col-lg-6">
                            <div class="p-4 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-4 me-4">
                                        <i class="fas fa-chalkboard-teacher text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-2">Class Management</h6>
                                        <p class="text-muted mb-3 small">Organize classes, sections, schedules and room assignments</p>
                                        <div class="d-flex gap-2">
                                            <a href="#" class="btn btn-primary btn-sm px-3">
                                                <i class="fas fa-chalkboard me-1"></i> Classes
                                            </a>
                                            <a href="#" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-clock me-1"></i> Schedule
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reports & Analytics -->
                        <div class="col-lg-6">
                            <div class="p-4 bg-light rounded">
                                <div class="d-flex">
                                    <div class="rounded-4 me-4">
                                        <i class="fas fa-chart-line text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-2">Reports & Analytics</h6>
                                        <p class="text-muted mb-3 small">Generate comprehensive reports and view system analytics</p>
                                        <div class="d-flex gap-2">
                                            <a href="#" class="btn btn-primary btn-sm px-3">
                                                <i class="fas fa-chart-bar me-1"></i> Reports
                                            </a>
                                            <a href="#" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-download me-1"></i> Export
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #4e73df;
    --success: #1cc88a;
    --info: #36b9cc;
    --warning: #f6c23e;
    --danger: #e74a3b;
    --purple: #6f42c1;
}

.w-60 { width: 60%; }
.w-40 { width: 40%; }
.w-30 { width: 30%; }
.welcome-header {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #1e2c38 0%, #2d3e4f 100%);
    border-radius: var(--border-radius);
}
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}

.hover-bg {
    transition: all 0.3s ease;
    cursor: pointer;
}
.hover-bg:hover {
    background-color: rgba(0,0,0,0.02);
}

.welcome-card {
    background-attachment: fixed;
}

.stat-card {
    border: 1px solid rgba(0,0,0,0.06);
    transition: all 0.3s ease;
}
.stat-card:hover {
    border-color: rgba(0,0,0,0.12);
}

.status-card {
    border: 1px solid rgba(0,0,0,0.06);
}

.controls-card {
    border: 1px solid rgba(0,0,0,0.06);
}

.control-item {
    min-height: 140px;
    display: flex;
    align-items: center;
}

.stat-icon, .control-icon, .status-icon {
    transition: all 0.3s ease;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.text-purple {
    color: var(--purple) !important;
}

.bg-purple {
    background-color: var(--purple) !important;
}

.btn-outline-secondary {
    --bs-btn-color: #6c757d;
    --bs-btn-border-color: #6c757d;
}

@media (max-width: 991.98px) {
    .control-item {
        min-height: auto;
        padding: 1.5rem 1rem !important;
    }
    
    .control-icon {
        padding: 1rem !important;
    }
    
    .control-icon i {
        font-size: 1.5rem !important;
    }
}
</style>

<!-- Maintenance Mode Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-{{ $maintenanceMode ? 'success' : 'warning' }} bg-opacity-10 py-4">
                <div class="d-flex align-items-center">
                    <div class="bg-{{ $maintenanceMode ? 'success' : 'warning' }} bg-opacity-25 p-3 rounded-circle me-3">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} text-{{ $maintenanceMode ? 'success' : 'warning' }} fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-1">
                            {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance Mode
                        </h5>
                        <p class="text-muted mb-0 small">
                            {{ $maintenanceMode ? 'Return system to normal operation' : 'Put system in maintenance mode' }}
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('maintenance.toggle') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if($maintenanceMode)
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 rounded-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-info me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">System Currently in Maintenance</h6>
                                    <p class="mb-0 small">Disabling will restore user access immediately.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-warning me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Important Notice</h6>
                                    <p class="mb-0 small">This will prevent all non-admin users from accessing the system.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="maintenance_message" class="form-label fw-semibold">Maintenance Message</label>
                            <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3" placeholder="Enter message for users...">{{ $maintenanceMessage }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="maintenance_duration" class="form-label fw-semibold">Duration (minutes)</label>
                            <input type="number" class="form-control" id="maintenance_duration" name="maintenance_duration" min="1" value="30">
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-{{ $maintenanceMode ? 'success' : 'warning' }} px-4">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} me-2"></i>
                        {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Counter animation for stats
    $('.counter').each(function() {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 2000,
            easing: 'swing',
            step: function(now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
});
</script>
@endpush

@endsection