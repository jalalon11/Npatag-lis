@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Room Management</h1>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Room
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Rooms
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeRooms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unassigned Advisers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unassignedAdvisers }}</div>
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
            <form method="GET" action="{{ route('admin.rooms.index') }}" id="filterForm">
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
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-sm">
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
                                        <span class="badge badge-{{ $room->is_active ? 'success' : 'secondary' }}">
                                            {{ $room->is_active ? 'Active' : 'Inactive' }}
                                        </span>
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
                                        <small class="text-muted">Adviser:</small>
                                        <div class="font-weight-bold">
                                            @if($room->adviser)
                                                {{ $room->adviser->name }}
                                            @else
                                                <span class="text-warning">Not assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Students:</small>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="font-weight-bold">{{ $room->students_count }}/{{ $room->student_limit ?? 30 }}</span>
                                        </div>
                                        @php
                                            $percentage = $room->student_limit ? ($room->students_count / $room->student_limit) * 100 : 0;
                                            $progressClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 75 ? 'bg-warning' : 'bg-success');
                                        @endphp
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar {{ $progressClass }}" 
                                                 style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.rooms.show', $room) }}" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.rooms.edit', $room) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($room->students_count == 0)
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteRoom({{ $room->id }}, '{{ $room->name }}')">
                                                <i class="fas fa-trash"></i> Delete
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
                    <i class="fas fa-door-open fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No Rooms Found</h5>
                    <p class="text-gray-500">No rooms match your current filters.</p>
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Room
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.room-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.room-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.bg-gradient-primary {
    background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
}

.text-xs {
    font-size: 0.7rem;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-600 {
    color: #858796 !important;
}

.text-gray-500 {
    color: #b7b9cc !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>

<script>
function deleteRoom(roomId, roomName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete the room "${roomName}". This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
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

document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input[name="search"]');
    
    filterInputs.forEach(input => {
        if (input.type === 'text') {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 500);
            });
        } else {
            input.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    });
});
</script>
@endsection