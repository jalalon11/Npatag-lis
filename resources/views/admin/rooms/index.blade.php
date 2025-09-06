@extends('layouts.app')

@section('title', 'Rooms Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Rooms Management</h2>
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary fw-bold">
                    <i class="fas fa-plus-circle me-1"></i> Add New Room
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
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
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-door-open text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Rooms</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $totalRooms }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Active Rooms</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $activeRooms }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Students</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $totalStudents }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-times text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Unassigned Advisers</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $unassignedAdvisers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card bg-white border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.rooms.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search rooms..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="school_id" class="form-select">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="grade_level" class="form-select">
                        <option value="">All Grades</option>
                        @foreach($gradeLevels as $grade)
                            <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <select name="sort" class="form-select">
                        <option value="">Sort by...</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="grade_level" {{ request('sort') == 'grade_level' ? 'selected' : '' }}>Grade Level</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                    </select>
                </div>
                <div class="col-md-1">
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
            </form>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="card border-0 bg-white shadow-sm pb-2">
        <div class="card-header bg-light py-3 rounded-top">
            <h5 class="mb-0 fw-bold text-primary">Rooms ({{ $rooms->total() }} total)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="background-color: white;">
                    <thead class="table-light" style="background-color: #f8f9fa;">
                        <tr>
                            <th scope="col">Room Info</th>
                            <th scope="col">Grade Level</th>
                            <th scope="col">School</th>
                            <th scope="col">Adviser</th>
                            <th scope="col">Students</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: white;">
                        @php
                            $filteredRooms = $rooms;

                            if (request('search')) {
                                $searchTerm = strtolower(request('search'));
                                $filteredRooms = $rooms->filter(function($room) use ($searchTerm) {
                                    return str_contains(strtolower($room->name), $searchTerm) ||
                                           str_contains(strtolower($room->grade_level), $searchTerm) ||
                                           ($room->school && str_contains(strtolower($room->school->name), $searchTerm)) ||
                                           ($room->adviser && str_contains(strtolower($room->adviser->name), $searchTerm));
                                });
                            }

                            if (request('school_id')) {
                                $filteredRooms = $filteredRooms->where('school_id', request('school_id'));
                            }

                            if (request('grade_level')) {
                                $filteredRooms = $filteredRooms->where('grade_level', request('grade_level'));
                            }

                            if (request('status') && request('status') != 'all') {
                                $filteredRooms = $filteredRooms->where('is_active', request('status') == 'active');
                            }

                            if (request('sort')) {
                                $sortField = request('sort');
                                $sortOrder = request('order', 'asc');
                                $filteredRooms = $filteredRooms->sortBy(function($room) use ($sortField) {
                                    switch ($sortField) {
                                        case 'name':
                                            return strtolower($room->name);
                                        case 'grade_level':
                                            return $room->grade_level;
                                        case 'created_at':
                                            return $room->created_at;
                                        default:
                                            return strtolower($room->name);
                                    }
                                }, SORT_REGULAR, $sortOrder === 'desc');
                            }
                        @endphp

                        @forelse($filteredRooms as $room)
                            <tr style="background-color: white;">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-door-open text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $room->name }}</h6>
                                            <small class="text-muted">ID: {{ $room->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $room->grade_level }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $room->school->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($room->adviser)
                                        <span class="badge bg-primary">{{ $room->adviser->name }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Not Assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <span class="fw-bold">{{ $room->students_count }}/{{ $room->student_limit ?? 30 }}</span>
                                        </div>
                                        @php
                                            $percentage = $room->student_limit ? ($room->students_count / $room->student_limit) * 100 : 0;
                                            $progressClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 75 ? 'bg-warning' : 'bg-success');
                                        @endphp
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar {{ $progressClass }}" 
                                                 style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $room->is_active ? 'success' : 'secondary' }}">
                                        {{ $room->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.rooms.show', $room) }}"
                                           class="btn btn-outline-primary" title="View Room">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.rooms.edit', $room) }}"
                                           class="btn btn-outline-primary" title="Edit Room">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($room->students_count == 0)
                                            <button type="button" class="btn btn-outline-primary"
                                                    onclick="deleteRoom({{ $room->id }}, '{{ addslashes($room->name) }}')"
                                                    title="Delete Room">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-outline-primary" disabled
                                                    title="Cannot delete room with students">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4" style="background-color: white;">
                                    <div class="text-muted">
                                        <i class="fas fa-door-open fa-2x mb-3"></i>
                                        <h5>No Rooms Found</h5>
                                        @if(request('search') || request('school_id') || request('grade_level') || request('status') != 'all')
                                            <p>No rooms match your search or filter criteria.</p>
                                            <a href="{{ route('admin.rooms.index') }}"
                                               class="btn btn-secondary me-2">
                                                <i class="fas fa-times me-1"></i> Clear Filters
                                            </a>
                                        @else
                                            <p>Start by creating a new room.</p>
                                        @endif
                                        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-1"></i> Add New Room
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($filteredRooms->count() > 0)
                <div class="d-flex justify-content-center mt-3">
                    {{ $rooms->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modals -->
    @foreach($rooms as $room)
        @if($room->students_count == 0)
            <div class="modal fade" id="deleteRoomModal{{ $room->id }}" tabindex="-1"
                 aria-labelledby="deleteRoomModalLabel{{ $room->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteRoomModalLabel{{ $room->id }}">Delete Room</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete the room "{{ $room->name }}"?</p>
                            <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-trash me-1"></i> Delete Room
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

@endsection

@push('styles')
<style>
.card-hover:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}
.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #6c757d;
}
.table td {
    vertical-align: middle;
}
.progress {
    height: 8px;
    border-radius: 4px;
    background-color: #e9ecef;
}
.badge {
    font-weight: 500;
    padding: 0.4em 0.6em;
}
.btn-group-sm > .btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
function deleteRoom(roomId, roomName) {
    const modal = new bootstrap.Modal(document.getElementById(`deleteRoomModal${roomId}`));
    modal.show();
}

// Auto-submit form when filters change
$(document).ready(function() {
    $('select[name="school_id"], select[name="grade_level"], select[name="status"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush

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