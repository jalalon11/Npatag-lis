@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    :root {
        --border-radius: 12px;
        --border-radius-pill: 50px;
        --padding-sm: 1rem;
        --padding-md: 1.5rem;
        --margin-sm: 1rem;
        --margin-md: 1.5rem;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card {
        border: none !important;
        border-radius: var(--border-radius) !important;
        transition: var(--transition);
    }

    .card-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        border-bottom: none !important;
        padding: var(--padding-md) !important;
    }

    .badge {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .btn {
        border-radius: var(--border-radius-pill);
        padding: 0.5rem 1.25rem;
        transition: var(--transition);
    }

    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #007bff;
    }

    .stat-card {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border: 1px solid #e9ecef;
    }

    .section-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid #007bff;
    }

    .teacher-item {
        background: #e8f4fd;
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid #17a2b8;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 animate__animated animate__fadeInLeft">
                <i class="fas fa-book text-primary me-2"></i>Subject Details
            </h1>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}">Subjects</a></li>
                    <li class="breadcrumb-item active">{{ $subject->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="animate__animated animate__fadeInRight">
            <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i>Edit Subject
            </a>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Subject Information -->
        <div class="col-lg-8">
            <div class="card info-card animate__animated animate__fadeIn">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-primary me-2"></i>Subject Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Subject Name</label>
                            <p class="fs-5 mb-0">{{ $subject->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Subject Code</label>
                            <p class="fs-5 mb-0">{{ $subject->code ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Grade Level</label>
                            <p class="mb-0">
                                <span class="badge bg-light text-dark border fs-6">Grade {{ $subject->grade_level }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $subject->is_active ? 'success' : 'danger' }} fs-6">
                                    {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                        @if($subject->description)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Description</label>
                            <p class="mb-0">{{ $subject->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sections Teaching This Subject -->
            @if($subject->sections && $subject->sections->count() > 0)
            <div class="card mt-4 animate__animated animate__fadeIn animate__delay-1s">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-users text-info me-2"></i>Sections ({{ $subject->sections->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($subject->sections as $section)
                        <div class="col-md-6 mb-2">
                            <div class="section-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $section->name }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-user-tie me-1"></i>
                                            Adviser: {{ $section->adviser->name ?? 'Not assigned' }}
                                        </small>
                                    </div>
                                    <a href="{{ route('admin.rooms.show', $section) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Statistics and Teachers -->
        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="card stat-card animate__animated animate__fadeIn animate__delay-2s">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar text-success me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-primary mb-1">{{ $subject->sections->count() }}</h3>
                                <small class="text-muted">Sections</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-info mb-1">{{ $teachers->count() }}</h3>
                                <small class="text-muted">Available Teachers</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Teachers -->
            @if($teachers && $teachers->count() > 0)
            <div class="card mt-4 animate__animated animate__fadeIn animate__delay-3s">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chalkboard-teacher text-warning me-2"></i>Available Teachers
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($teachers->take(5) as $teacher)
                    <div class="teacher-item">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $teacher->name }}</h6>
                                <small class="text-muted">{{ $teacher->email }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($teachers->count() > 5)
                    <div class="text-center mt-2">
                        <small class="text-muted">And {{ $teachers->count() - 5 }} more teachers...</small>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush