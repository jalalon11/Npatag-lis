@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h3 mb-0 text-dark">Room Management</h1>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0" aria-label="Add new room">
            <i class="fas fa-plus me-1"></i> Add New Room
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Rooms</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $totalRooms }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-door-open fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Active Rooms</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $activeRooms }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-check-circle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Students</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $totalStudents }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-users fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Unassigned Advisers</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $unassignedAdvisers }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-user-times fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-light py-3 rounded-top">
            <h5 class="mb-0 fw-bold text-primary">Filter Rooms</h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.rooms.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <label for="search" class="form-label fw-bold">Search</label>
                        <input type="text" class="form-control rounded" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by room name..." aria-label="Search rooms">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="school_id" class="form-label fw-bold">School</label>
                        <select class="form-select rounded" id="school_id" name="school_id" aria-label="Select school">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="grade_level" class="form-label fw-bold">Grade Level</label>
                        <select class="form-select rounded" id="grade_level" name="grade_level" aria-label="Select grade level">
                            <option value="">All Grades</option>
                            @foreach($gradeLevels as $grade)
                                <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select rounded" id="status" name="status" aria-label="Select status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm me-2" id="filter-btn" aria-label="Apply filters">
                            <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm" aria-label="Clear filters">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Rooms Grid -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-light py-3 rounded-top">
            <h5 class="mb-0 fw-bold text-primary">Rooms ({{ $rooms->total() }} total)</h5>
        </div>
        <div class="card-body p-4">
            @if($rooms->count() > 0)
                <div class="row g-4">
                    @foreach($rooms as $room)
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 shadow-sm rounded-3 h-100 room-card">
                                <div class="card-header bg-primary text-white py-3 rounded-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold">{{ $room->name }}</h6>
                                        <span class="badge bg-{{ $room->is_active ? 'success' : 'secondary' }} text-{{ $room->is_active ? 'white' : 'dark' }} px-2 py-1">
                                            {{ $room->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        <small class="text-muted">Grade Level:</small>
                                        <div class="fw-bold">{{ $room->grade_level }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">School:</small>
                                        <div class="fw-bold">{{ $room->school->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Adviser:</small>
                                        <div class="fw-bold">
                                            @if($room->adviser)
                                                {{ $room->adviser->name }}
                                            @else
                                                <span class="text-warning">Not assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <small class="text-muted">Students:</small>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold">{{ $room->students_count }}/{{ $room->student_limit ?? 30 }}</span>
                                        </div>
                                        @php
                                            $percentage = $room->student_limit ? ($room->students_count / $room->student_limit) * 100 : 0;
                                            $progressClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 75 ? 'bg-warning' : 'bg-success');
                                        @endphp
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar {{ $progressClass }}" 
                                                 style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent pt-0">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.rooms.show', $room) }}" 
                                           class="btn btn-outline-info btn-sm" title="View Room" aria-label="View Room">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <a href="{{ route('admin.rooms.edit', $room) }}" 
                                           class="btn btn-outline-primary btn-sm" title="Edit Room" aria-label="Edit Room">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        @if($room->students_count == 0)
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteRoom({{ $room->id }}, '{{ $room->name }}')"
                                                    title="Delete Room" aria-label="Delete Room">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $rooms->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-dark">No Rooms Found</h5>
                    <p class="text-muted">No rooms match your current filters.</p>
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary" aria-label="Create first room">
                        <i class="fas fa-plus me-1"></i> Create First Room
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
.form-control, .form-select {
    transition: border-color 0.2s ease-in-out;
}
.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}
.btn {
    padding: 0.5rem 1.25rem;
}
.badge {
    font-size: 0.9rem;
}
.progress {
    border-radius: 4px;
}
@media (max-width: 576px) {
    .container-fluid {
        padding: 15px;
    }
    .card-body {
        padding: 15px;
    }
    .btn-group .btn {
        font-size: 0.85rem;
        padding: 6px 10px;
    }
    .fa-2x {
        font-size: 1.5rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
function deleteRoom(roomId, roomName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete the room "${roomName}". This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#007bff',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/rooms/${roomId}`;
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

$(document).ready(function() {
    const filterBtn = $('#filter-btn');
    
    // Auto-submit form when filters change
    $('#school_id, #grade_level, #status').change(function() {
        filterBtn.find('.spinner-border').removeClass('d-none');
        filterBtn.prop('disabled', true);
        $(this).closest('form').submit();
    });
    
    // Handle search input with debounce
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        const form = $(this).closest('form');
        searchTimeout = setTimeout(function() {
            filterBtn.find('.spinner-border').removeClass('d-none');
            filterBtn.prop('disabled', true);
            form.submit();
        }, 500);
    });
});
</script>
@endpush