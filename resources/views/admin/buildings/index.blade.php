@extends('layouts.app')

@section('title', 'Buildings Management')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buildings Management</h1>
        <a href="{{ route('admin.buildings.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Building
        </a>
    </div>

    <!-- Statistics Cards Row -->
    <div class="row">
        <!-- Total Buildings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Buildings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBuildings }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Buildings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Buildings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeBuildings }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Rooms Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
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

        <!-- Empty Buildings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Empty Buildings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $emptyBuildings }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Buildings Grid -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Buildings ({{ $buildings->total() }} total)</h6>
        </div>
        <div class="card-body">
            @if($buildings->count() > 0)
                <div class="row">
                    @foreach($buildings as $building)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100 building-card">
                                <div class="card-header bg-gradient-primary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 font-weight-bold">{{ $building->name }}</h6>
                                        @if($building->is_active)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Active
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-times"></i> Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($building->description)
                                        <div class="mb-2">
                                            <small class="text-muted">Description:</small>
                                            <div class="font-weight-bold">{{ $building->description }}</div>
                                        </div>
                                    @endif
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">School:</small>
                                        <div class="font-weight-bold">{{ $building->school->name }}</div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Rooms Count:</small>
                                        <div class="font-weight-bold">
                                            <span class="badge badge-info">
                                                {{ $building->rooms->count() }} rooms
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Building ID:</small>
                                        <div class="font-weight-bold">#{{ $building->id }}</div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.buildings.show', $building) }}" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.buildings.edit', $building) }}" 
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($building->rooms->count() == 0)
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteBuilding({{ $building->id }}, '{{ $building->name }}')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        @else
                                            <button class="btn btn-outline-danger btn-sm" disabled 
                                                    title="Cannot delete building with assigned rooms">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($buildings->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $buildings->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-building fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No Buildings Found</h5>
                    <p class="text-gray-500">Start by creating your first building.</p>
                    <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Building
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function deleteBuilding(buildingId, buildingName) {
        if (confirm(`Are you sure you want to delete the building "${buildingName}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/buildings/${buildingId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection