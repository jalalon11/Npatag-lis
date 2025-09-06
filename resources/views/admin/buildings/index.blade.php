@extends('layouts.app')

@section('title', 'Buildings Management')

@push('styles')
<style>
.card-hover:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Buildings Management</h2>
                <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary fw-bold">
                    <i class="fas fa-plus-circle me-1"></i> Add New Building
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
                                <i class="fas fa-building text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Buildings</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $totalBuildings }}</h3>
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
                            <h6 class="text-muted mb-1">Active Buildings</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $activeBuildings }}</h3>
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
                                <i class="fas fa-exclamation-triangle text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Empty Buildings</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $emptyBuildings }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card bg-white border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.buildings.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search buildings..." value="{{ request('search') }}">
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

    <!-- Buildings Table -->
    <div class="card border-0 bg-white shadow-sm pb-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="background-color: white;">
                    <thead class="table-light" style="background-color: #f8f9fa;">
                        <tr>
                            <th scope="col">Building Info</th>
                            <th scope="col">School</th>
                            <th scope="col">Rooms Count</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created Date</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: white;">
                        @php
                            $filteredBuildings = $buildings;

                            if (request('search')) {
                                $searchTerm = strtolower(request('search'));
                                $filteredBuildings = $buildings->filter(function($building) use ($searchTerm) {
                                    return str_contains(strtolower($building->name), $searchTerm) ||
                                           str_contains(strtolower($building->description ?? ''), $searchTerm) ||
                                           str_contains(strtolower($building->id), $searchTerm) ||
                                           ($building->school && str_contains(strtolower($building->school->name), $searchTerm));
                                });
                            }

                            if (request('school_id')) {
                                $filteredBuildings = $filteredBuildings->where('school_id', request('school_id'));
                            }

                            if (request('status') && request('status') != 'all') {
                                $filteredBuildings = $filteredBuildings->where('is_active', request('status') == 'active');
                            }

                            if (request('sort')) {
                                $sortField = request('sort');
                                $sortOrder = request('order', 'asc');
                                $filteredBuildings = $filteredBuildings->sortBy(function($building) use ($sortField) {
                                    switch ($sortField) {
                                        case 'name':
                                            return strtolower($building->name);
                                        case 'created_at':
                                            return $building->created_at;
                                        default:
                                            return strtolower($building->name);
                                    }
                                }, SORT_REGULAR, $sortOrder === 'desc');
                            }
                        @endphp

                        @forelse($filteredBuildings as $building)
                            <tr style="background-color: white;">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-building text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $building->name }}</h6>
                                            @if($building->description)
                                                <small class="text-muted">{{ Str::limit($building->description, 50) }}</small>
                                                <br>
                                            @endif
                                            <small class="text-muted">ID: {{ $building->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $building->school->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $building->rooms->count() }} rooms</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $building->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $building->created_at->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">{{ $building->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.buildings.show', $building) }}"
                                           class="btn btn-outline-primary" title="View Building">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.buildings.edit', $building) }}"
                                           class="btn btn-outline-primary" title="Edit Building">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($building->rooms->count() == 0)
                                            <button type="button" class="btn btn-outline-primary"
                                                    onclick="deleteBuilding({{ $building->id }}, '{{ addslashes($building->name) }}')"
                                                    title="Delete Building">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-outline-primary" disabled
                                                    title="Cannot delete building with assigned rooms">
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
                                        <i class="fas fa-building fa-2x mb-3"></i>
                                        <h5>No Buildings Found</h5>
                                        @if(request('search') || request('school_id') || request('status') != 'all')
                                            <p>No buildings match your search or filter criteria.</p>
                                            <a href="{{ route('admin.buildings.index') }}"
                                               class="btn btn-secondary me-2">
                                                <i class="fas fa-times me-1"></i> Clear Filters
                                            </a>
                                        @else
                                            <p>Start by creating a new building.</p>
                                        @endif
                                        <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-1"></i> Add New Building
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($filteredBuildings->count() > 0)
                <div class="d-flex justify-content-center mt-3">
                    {{ $buildings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modals -->
    @foreach($buildings as $building)
        @if($building->rooms->count() == 0)
            <div class="modal fade" id="deleteBuildingModal{{ $building->id }}" tabindex="-1"
                 aria-labelledby="deleteBuildingModalLabel{{ $building->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteBuildingModalLabel{{ $building->id }}">Delete Building</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete the building "{{ $building->name }}"?</p>
                            <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('admin.buildings.destroy', $building->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-trash me-1"></i> Delete Building
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

@push('scripts')
<script>
function deleteBuilding(buildingId, buildingName) {
    const modal = new bootstrap.Modal(document.getElementById(`deleteBuildingModal${buildingId}`));
    modal.show();
}
</script>
@endpush
@endsection