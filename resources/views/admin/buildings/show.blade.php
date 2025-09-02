@extends('layouts.app')

@section('title', 'Building Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Building Information Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Building Details: {{ $building->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Buildings
                        </a>
                        <a href="{{ route('admin.buildings.edit', $building) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit Building
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Building ID:</th>
                                    <td>{{ $building->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $building->name }}</td>
                                </tr>
                                <tr>
                                    <th>School:</th>
                                    <td>{{ $building->school->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($building->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Total Rooms:</th>
                                    <td>
                                        <span class="badge badge-info">{{ $building->rooms->count() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Students:</th>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $building->rooms->sum(function($room) { return $room->students->count(); }) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $building->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $building->updated_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($building->description)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Description:</h6>
                                <p class="text-muted">{{ $building->description }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Assigned Rooms -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assigned Rooms ({{ $building->rooms->count() }})</h3>
                </div>
                <div class="card-body">
                    @if($building->rooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Room Name</th>
                                        <th>Grade Level</th>
                                        <th>Adviser</th>
                                        <th>Students</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($building->rooms as $room)
                                        <tr>
                                            <td>{{ $room->name }}</td>
                                            <td>{{ $room->grade_level }}</td>
                                            <td>
                                                @if($room->adviser)
                                                    {{ $room->adviser->first_name }} {{ $room->adviser->last_name }}
                                                @else
                                                    <span class="text-muted">No adviser</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $room->students->count() }}</span>
                                            </td>
                                            <td>
                                                @if($room->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.rooms.show', $room) }}" 
                                                       class="btn btn-sm btn-info" title="View Room">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('admin.buildings.unassign-room', $building) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to unassign this room from the building?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                        <button type="submit" class="btn btn-sm btn-warning" title="Unassign Room">
                                                            <i class="fas fa-unlink"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No rooms assigned</h5>
                            <p class="text-muted">This building doesn't have any rooms assigned yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Available Rooms for Assignment -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assign Room</h3>
                </div>
                <div class="card-body">
                    @if($availableRooms->count() > 0)
                        <form action="{{ route('admin.buildings.assign-room', $building) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="room_id">Select Room to Assign:</label>
                                <select class="form-control @error('room_id') is-invalid @enderror" 
                                        id="room_id" 
                                        name="room_id" 
                                        required>
                                    <option value="">Choose a room...</option>
                                    @foreach($availableRooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }} ({{ $room->grade_level }})
                                            @if($room->adviser)
                                                - {{ $room->adviser->first_name }} {{ $room->adviser->last_name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-link"></i> Assign Room
                            </button>
                        </form>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Only unassigned rooms from the same school are shown.
                            </small>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-exclamation-circle fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No available rooms to assign.</p>
                            <small class="text-muted">
                                All rooms in this school are either already assigned to buildings or inactive.
                            </small>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Quick Stats</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-door-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rooms</span>
                                    <span class="info-box-number">{{ $building->rooms->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Students</span>
                                    <span class="info-box-number">
                                        {{ $building->rooms->sum(function($room) { return $room->students->count(); }) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-focus on room selection
    $('#room_id').focus();
    
    // Confirmation for room assignment
    $('form[action*="assign-room"]').on('submit', function(e) {
        const roomName = $('#room_id option:selected').text();
        if (roomName && !confirm(`Are you sure you want to assign "${roomName}" to this building?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection