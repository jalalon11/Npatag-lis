@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Homeroom Advising</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Rooms
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRooms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Assigned Advisers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assignedRooms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unassigned Advisers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unassignedRooms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    
    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Rooms</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.homeroom.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by room name...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="school_id" class="form-label">School</label>
                        <select class="form-control" id="school_id" name="school_id">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select class="form-control" id="grade_level" name="grade_level">
                            <option value="">All Grades</option>
                            @foreach($gradeLevels as $grade)
                                <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="adviser_status" class="form-label">Adviser Status</label>
                        <select class="form-control" id="adviser_status" name="adviser_status">
                            <option value="">All Status</option>
                            <option value="assigned" {{ request('adviser_status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="unassigned" {{ request('adviser_status') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.homeroom.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
                    
    <!-- Rooms Grid -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rooms ({{ $rooms->total() }} total)</h6>
        </div>
        <div class="card-body">
            @if($rooms->count() > 0)
                <div class="row">
                    @foreach($rooms as $room)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100 room-card">
                                <div class="card-header bg-gradient-primary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 font-weight-bold">{{ $room->name }}</h6>
                                        @if($room->adviser)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Assigned
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Unassigned
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Grade Level:</small>
                                        <div class="font-weight-bold">{{ $room->grade_level }}</div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">School:</small>
                                        <div class="font-weight-bold">{{ $room->school->name ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Building:</small>
                                        <div class="font-weight-bold">{{ $room->building->name ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Students:</small>
                                        <div class="font-weight-bold">{{ $room->students_count }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Current Adviser:</small>
                                        <div class="font-weight-bold">
                                            @if($room->adviser)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-success mr-2"></i>
                                                    <div>
                                                        {{ $room->adviser->name }}
                                                        <br><small class="text-muted">{{ ucfirst($room->adviser->role) }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-warning">
                                                    <i class="fas fa-user-slash mr-1"></i>
                                                    Not assigned
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.homeroom.assign', $room->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-user-edit"></i> 
                                            {{ $room->adviser ? 'Change' : 'Assign' }}
                                        </a>
                                        @if($room->adviser)
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="removeAdviser({{ $room->id }}, '{{ $room->name }}')">
                                                <i class="fas fa-user-minus"></i> Remove
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $rooms->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No Rooms Found</h5>
                    <p class="text-gray-500">No rooms match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form when filters change
    $('#school_id, #grade_level, #adviser_status').change(function() {
        $(this).closest('form').submit();
    });
    
    // Handle search input with debounce
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        const form = $(this).closest('form');
        searchTimeout = setTimeout(function() {
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