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
    <!-- Fixed Dashboard Layout -->
    <div class="row g-4 mb-4">
        <!-- Welcome Card - Left Side -->
        <div class="col-lg-8">
            <div class="dashboard-header rounded-4 shadow-sm h-100 p-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e2c38 0%, #2d3e4f 100%)">
                <!-- Layered Blue Polygons -->
                <div class="position-absolute top-0 end-0 w-60 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.10; clip-path: polygon(20% 0, 100% 0%, 100% 100%, 0% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-50 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.45; clip-path: polygon(25% 0, 100% 0%, 100% 100%, 5% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-40 h-100 z-0 d-none d-lg-block bg-primary" style="opacity: 0.60; clip-path: polygon(30% 0, 100% 0%, 100% 100%, 10% 100%);"></div>
                <div class="position-absolute top-0 end-0 w-30 h-100 z-0 d-none d-lg-block bg-primary" style=" clip-path: polygon(35% 0, 100% 0%, 100% 100%, 15% 100%);"></div>
                
                
                <div class="d-flex position-relative z-1 h-100">
                    <div>
                        <h1 class="fw-bold mb-2 text-white display-6">Admin Dashboard</h1>
                        <p class="text-white mb-0 lead opacity-90">Welcome back, {{ Auth::user()->name }}!</p>
                        <div class="d-flex align-items-center mt-3">
                            <span class="badge bg-success bg-opacity-10 text-white border border-success border-opacity-20 px-3 py-2 rounded-pill">
                                <i class="fas fa-check-circle me-1"></i> System Online
                            </span>
                            <div class="ms-3 d-flex align-items-center text-white text-opacity-70">
                                <i class="far fa-calendar-alt text-info me-2"></i>
                                <span>{{ date('F d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards - Right Side -->
        <div class="col-lg-4">
            <div class="row g-3 h-100">
                <!-- Teachers Card -->
                <div class="col-12">
                    <div class="stat-card bg-white rounded-4 shadow-sm h-100 position-relative overflow-hidden transition-all hover-lift p-4">
                        <div class="position-absolute top-0 start-0 h-100 w-1 bg-primary"></div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="w-100">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="stat-icon-sm bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="fas fa-chalkboard-teacher text-primary"></i>
                                        </div>
                                        <h6 class="text-uppercase fw-semibold mb-0 small">Teachers</h6>
                                    </div>
                                    <h3 class="fw-bold mb-0 display-6">{{ $stats['teachersCount'] }}</h3>
                                    <p class="small text-muted mb-0 mt-2">
                                        Active Faculty Members
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 mt-3">
                            <a href="{{ route('admin.teachers.index') }}" class="btn btn-md btn-primary w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-eye me-2"></i> View All Teachers
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Students Card -->
                <div class="col-12">
                    <div class="stat-card bg-white rounded-4 shadow-sm h-100 position-relative overflow-hidden transition-all hover-lift p-4">
                        <div class="position-absolute top-0 start-0 h-100 w-1 bg-success"></div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="w-100">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="stat-icon-sm bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="fas fa-user-graduate text-primary"></i>
                                        </div>
                                        <h6 class="text-uppercase fw-semibold mb-0 small">Students</h6>
                                    </div>
                                    <h3 class="fw-bold mb-0 display-6">{{ $stats['studentsCount'] }}</h3>
                                    <p class="small text-muted mb-0 mt-2">
                                        Enrolled Learners
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 mt-2">
                            <a href="#" class="btn btn-md btn-primary w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-eye me-2"></i> View All Students
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Actions - Simplified -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 position-relative overflow-hidden animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                <div class="p-4 bg-white d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <div class=" rounded-circle me-3">
                            <i class="fas fa-bolt text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">Essential Controls</h5>
                            <p class="text-muted mb-0 small">Core administrative functions</p>
                        </div>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2 fw-semibold">
                        <i class="fas fa-shield-alt me-1"></i> Admin Panel
                    </span>
                </div>
                <div class="card-body bg-white p-0">
                    <div class="row g-0">
                        <!-- Registration Keys -->
                        <div class="col-md-12 border-bottom">
                            <a href="{{ route('admin.registration-keys') }}" class="quick-action-item d-flex align-items-center p-5 text-decoration-none transition-all hover-bg position-relative">
                                <div class="position-absolute start-0 top-0 bottom-0 w-1 bg-warning opacity-0 transition-all hover-opacity-100"></div>
                                <div class="d-flex align-items-center justify-content-center me-4 bg-warning bg-opacity-10 rounded-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-key text-warning fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-2 fw-bold text-dark">Registration Keys Management</h5>
                                    <p class="text-muted mb-2">Create, manage, and monitor access keys for user registration</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning me-2 px-3 py-1">
                                            <i class="fas fa-users me-1"></i> User Access Control
                                        </span>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-1">
                                            <i class="fas fa-shield-check me-1"></i> Secure Authentication
                                        </span>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 transition-all hover-bg-warning">
                                        <i class="fas fa-arrow-right text-warning fa-lg"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 position-relative overflow-hidden animate__animated animate__fadeIn" style="animation-delay: 0.3s;">
                <div class=" bg-white p-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-{{ $maintenanceMode ? 'danger' : 'primary' }} bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-{{ $maintenanceMode ? 'exclamation-triangle' : 'server' }} text-{{ $maintenanceMode ? 'danger' : 'primary' }} fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">System Status</h5>
                            <p class="text-muted mb-0 small">Monitor & control system</p>
                        </div>
                    </div>
                    <span class="badge {{ $maintenanceMode ? 'bg-danger' : 'bg-primary' }} rounded-pill px-3 py-2 fw-semibold">
                        <i class="fas fa-{{ $maintenanceMode ? 'exclamation-circle' : 'check-circle' }} me-1"></i>
                        {{ $maintenanceMode ? 'Maintenance' : 'Online' }}
                    </span>
                </div>
                <div class="card-body bg-white p-4">
                    <div class="text-center mb-4">
                        <div class="mx-auto mb-3">
                            <div class="p-4 rounded-circle {{ $maintenanceMode ? 'bg-danger' : 'bg-primary' }} bg-opacity-10 d-inline-block position-relative">
                                <i class="fas fa-{{ $maintenanceMode ? 'tools' : 'shield-alt' }} {{ $maintenanceMode ? 'text-danger' : 'text-primary' }} fa-3x"></i>
                                @if(!$maintenanceMode)
                                <div class="position-absolute top-0 end-0">
                                    <span class="badge bg-primary rounded-circle p-2">
                                        <i class="fas fa-check text-white"></i>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $maintenanceMode ? 'Maintenance Active' : 'All Systems Operational' }}</h5>
                        <p class="text-muted mb-0">
                            {{ $maintenanceMode
                                ? 'System is in maintenance mode. Only administrators have access.'
                                : 'All systems are running smoothly and accessible to users.'
                            }}
                        </p>
                    </div>

                    @if($maintenanceMode)
                    <div class="maintenance-details bg-danger bg-opacity-5 border border-danger border-opacity-20 rounded-3 p-3 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-danger me-2"></i>
                            <span class="text-danger fw-semibold">Duration: {{ $maintenanceDuration }} minutes</span>
                        </div>
                        @if($maintenanceMessage)
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-danger me-2 mt-1"></i>
                            <span class="text-danger">{{ $maintenanceMessage }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <button type="button" class="btn btn-{{ $maintenanceMode ? 'success' : 'primary' }} w-100 py-3 d-flex align-items-center justify-content-center shadow-sm fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} me-2"></i>
                        <span>{{ $maintenanceMode ? 'Exit Maintenance Mode' : 'Enter Maintenance Mode' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
    .w-60 { width: 60%; }
    .w-40 { width: 40%; }
    .w-30 { width: 30%; }
    .h-1 { height: 4px; }
    .border-opacity-0 { --bs-border-opacity: 0; }
    .hover-border-opacity-100:hover { --bs-border-opacity: 1; }
    .hover-opacity-100:hover { opacity: 1 !important; }
    .hover-bg-warning:hover { background-color: rgba(255, 193, 7, 0.1) !important; }
    
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    .hover-bg {
        transition: all 0.3s ease;
    }
    .hover-bg:hover {
        background-color: rgba(0,0,0,0.02);
        transform: translateX(5px);
    }
    
    .quick-action-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .quick-action-item:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    .dashboard-header {
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at top right, rgba(52, 152, 219, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    
    .dashboard-header .fas {
        animation: float 6s ease-in-out infinite;
    }
    
    .dashboard-header .fas:nth-child(2) {
        animation-delay: -2s;
    }
    </style>
</div>

<!-- Maintenance Mode Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-{{ $maintenanceMode ? 'success' : 'warning' }} bg-opacity-10 py-4">
                <div class="d-flex align-items-center">
                    <div class="bg-{{ $maintenanceMode ? 'success' : 'warning' }} bg-opacity-25 p-3 rounded-circle me-3">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} text-{{ $maintenanceMode ? 'success' : 'warning' }} fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="maintenanceModalLabel">
                            {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance Mode
                        </h5>
                        <p class="text-muted mb-0 small">
                            {{ $maintenanceMode ? 'Return the system to normal operation' : 'Put the system in maintenance mode' }}
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('maintenance.toggle') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if($maintenanceMode)
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 d-flex align-items-center rounded-3 mb-4">
                            <div class="p-2 rounded-circle bg-info bg-opacity-25 me-3">
                                <i class="fas fa-info-circle text-info"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">System Currently in Maintenance Mode</h6>
                                <p class="mb-0">Disabling maintenance mode will make the system accessible to all users again.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-3">
                            <div class="me-3">
                                <i class="fas fa-users text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">User Access Will Be Restored</h6>
                                <p class="text-muted mb-0 small">All users will regain access to the system</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 d-flex align-items-center rounded-3 mb-4">
                            <div class="p-2 rounded-circle bg-warning bg-opacity-25 me-3">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Important Notice</h6>
                                <p class="mb-0">Enabling maintenance mode will prevent all non-admin users from accessing the system.</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="maintenance_message" class="form-label fw-semibold">Maintenance Message</label>
                            <textarea class="form-control border bg-white" id="maintenance_message" name="maintenance_message" rows="3" placeholder="Enter a message to display to users during maintenance...">{{ \App\Models\SystemSetting::getMaintenanceMessage() }}</textarea>
                            <div class="form-text">This message will be displayed to users on the maintenance page.</div>
                        </div>

                        <div class="mb-3">
                            <label for="maintenance_duration" class="form-label fw-semibold">Maintenance Duration (minutes)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-clock text-muted"></i>
                                </span>
                                <input type="number" class="form-control border bg-white" id="maintenance_duration" name="maintenance_duration" min="1" value="30" placeholder="Enter duration in minutes">
                            </div>
                            <div class="form-text">Estimated time for maintenance to complete. This will be displayed to users.</div>
                        </div>

                        <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-0">
                            <div class="me-3">
                                <i class="fas fa-user-shield text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">Admin Access Maintained</h6>
                                <p class="text-muted mb-0 small">Administrators will still have full access to the system</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-{{ $maintenanceMode ? 'success' : 'warning' }} px-4 py-2 shadow-sm">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} me-2"></i>
                        {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance Mode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection