@extends('layouts.app')

@section('title', 'Buildings Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h3 mb-0 text-dark">Buildings Management</h1>
        <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0" aria-label="Add new building">
            <i class="fas fa-plus me-1"></i> Add New Building
        </a>
    </div>

    <!-- Statistics Cards Row -->
    <div class="row mb-4 g-4">
        <!-- Total Buildings Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Buildings</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $totalBuildings }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-building fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Buildings Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Active Buildings</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $activeBuildings }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-check-circle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Rooms Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Rooms</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $totalRooms }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-door-open fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty Buildings Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Empty Buildings</div>
                            <div class="h4 mb-0 fw-bold text-dark">{{ $emptyBuildings }}</div>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buildings Grid -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-light py-3 rounded-top">
            <h5 class="mb-0 fw-bold text-primary">Buildings ({{ $buildings->total() }} total)</h5>
        </div>
        <div class="card-body p-4">
            @if($buildings->count() > 0)
                <div class="row g-4">
                    @foreach($buildings as $building)
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 shadow-sm rounded-3 h-100 building-card">
                                <div class="card-header bg-primary text-white py-3 rounded-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold">{{ $building->name }}</h6>
                                        <span class="badge bg-{{ $building->is_active ? 'success' : 'secondary' }} text-{{ $building->is_active ? 'white' : 'dark' }} px-2 py-1">
                                            {{ $building->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    @if($building->description)
                                        <div class="mb-3">
                                            <small class="text-muted">Description:</small>
                                            <div class="fw-bold">{{ $building->description }}</div>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <small class="text-muted">School:</small>
                                        <div class="fw-bold">{{ $building->school->name }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Rooms Count:</small>
                                        <div class="fw-bold">
                                            <span class="badge bg-secondary text-white px-2 py-1">
                                                {{ $building->rooms->count() }} rooms
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <small class="text-muted">Building ID:</small>
                                        <div class="fw-bold">#{{ $building->id }}</div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent pt-0">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.buildings.show', $building) }}" 
                                           class="btn btn-outline-info btn-sm" title="View Building" aria-label="View Building">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <a href="{{ route('admin.buildings.edit', $building) }}" 
                                           class="btn btn-outline-primary btn-sm" title="Edit Building" aria-label="Edit Building">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        @if($building->rooms->count() == 0)
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteBuilding({{ $building->id }}, '{{ $building->name }}')"
                                                    title="Delete Building" aria-label="Delete Building">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        @else
                                            <button class="btn btn-outline-danger btn-sm" disabled 
                                                    title="Cannot delete building with assigned rooms" aria-label="Cannot delete building">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($buildings->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $buildings->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-dark">No Buildings Found</h5>
                    <p class="text-muted">Start by creating your first building.</p>
                    <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary" aria-label="Create first building">
                        <i class="fas fa-plus me-1"></i> Add New Building
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
.btn {
    padding: 0.5rem 1.25rem;
}
.badge {
    font-size: 0.9rem;
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
function deleteBuilding(buildingId, buildingName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete the building "${buildingName}". This action cannot be undone.`,
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
            form.action = `/admin/buildings/${buildingId}`;
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
</script>
@endpush