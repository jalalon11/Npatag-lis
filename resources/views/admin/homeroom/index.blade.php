@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h3 mb-0 text-dark">Homeroom Advising</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-4">
        <div class="col-xl-4 col-md-6">
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

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Assigned Advisers</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $assignedRooms }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-user-check fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Unassigned Advisers</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $unassignedRooms }}</div>
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
            <form method="GET" action="{{ route('admin.homeroom.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label fw-bold">Search</label>
                        <input type="text" class="form-control rounded" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by room name...">
                    </div>
                    <div class="col-md-3">
                        <label for="school_id" class="form-label fw-bold">School</label>
                        <select class="form-select rounded" id="school_id" name="school_id">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="grade_level" class="form-label fw-bold">Grade Level</label>
                        <select class="form-select rounded" id="grade_level" name="grade_level">
                            <option value="">All Grades</option>
                            @foreach($gradeLevels as $grade)
                                <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="adviser_status" class="form-label fw-bold">Adviser Status</label>
                        <select class="form-select rounded" id="adviser_status" name="adviser_status">
                            <option value="">All Status</option>
                            <option value="assigned" {{ request('adviser_status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="unassigned" {{ request('adviser_status') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm me-2" id="filter-btn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.homeroom.index') }}" class="btn btn-outline-secondary btn-sm">
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
                                        @if($room->adviser)
                                            <span class="badge bg-success text-white px-2 py-1">
                                                <i class="fas fa-check me-1"></i> Assigned
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark px-2 py-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Unassigned
                                            </span>
                                        @endif
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
                                        <small class="text-muted">Building:</small>
                                        <div class="fw-bold">{{ $room->building->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Students:</small>
                                        <div class="fw-bold">{{ $room->students_count }}</div>
                                    </div>
                                    <div class="mb-0">
                                        <small class="text-muted">Current Adviser:</small>
                                        <div class="fw-bold">
                                            @if($room->adviser)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-success me-2"></i>
                                                    <div>
                                                        {{ $room->adviser->name }}
                                                        <br><small class="text-muted">{{ ucfirst($room->adviser->role) }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-success">
                                                    <i class="fas fa-user-slash me-1"></i>
                                                    Not assigned
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent pt-0">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.homeroom.assign', $room->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-user-edit me-1"></i> 
                                            {{ $room->adviser ? 'Change' : 'Assign' }}
                                        </a>
                                        @if($room->adviser)
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="removeAdviser({{ $room->id }}, '{{ $room->name }}')">
                                                <i class="fas fa-user-minus me-1"></i> Remove
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
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <h5 class="text-dark">No Rooms Found</h5>
                    <p class="text-muted">No rooms match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
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
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const filterBtn = $('#filter-btn');
    
    // Auto-submit form when filters change
    $('#school_id, #grade_level, #adviser_status').change(function() {
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

// Remove adviser function
function removeAdviser(roomId, roomName) {
    if (confirm(`Are you sure you want to remove the adviser from ${roomName}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/homeroom/${roomId}/update-adviser`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        
        const adviserField = document.createElement('input');
        adviserField.type = 'hidden';
        adviserField.name = 'adviser_id';
        adviserField.value = '';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(adviserField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush