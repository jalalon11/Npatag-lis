@extends('layouts.app')

@section('title', 'Edit Building')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Building: {{ $building->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Buildings
                        </a>
                        <a href="{{ route('admin.buildings.show', $building) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.buildings.update', $building) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Building Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $building->name) }}" 
                                           placeholder="Enter building name" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_name">School</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="school_name" 
                                           value="{{ $school->name }}" 
                                           readonly>
                                    <small class="form-text text-muted">
                                        Building is assigned to this school automatically.
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Enter building description (optional)">{{ old('description', $building->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', $building->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Active Building
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Only active buildings can have rooms assigned to them.
                                        @if($building->rooms->count() > 0)
                                            <br><strong>Note:</strong> This building has {{ $building->rooms->count() }} assigned room(s). 
                                            Deactivating will not remove room assignments.
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        @if($building->rooms->count() > 0)
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Current Room Assignments</h6>
                                        <p class="mb-2">This building currently has the following rooms assigned:</p>
                                        <ul class="mb-0">
                                            @foreach($building->rooms as $room)
                                                <li>{{ $room->name }} ({{ $room->grade_level }})</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Building
                                </button>
                                <a href="{{ route('admin.buildings.show', $building) }}" class="btn btn-info ml-2">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-focus on the name field
    $('#name').focus();
    
    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        if (!$('#name').val().trim()) {
            isValid = false;
            $('#name').addClass('is-invalid');
        } else {
            $('#name').removeClass('is-invalid');
        }
        
        if (!$('#school_id').val()) {
            isValid = false;
            $('#school_id').addClass('is-invalid');
        } else {
            $('#school_id').removeClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Warn about school change if building has rooms
    @if($building->rooms->count() > 0)
        $('#school_id').on('change', function() {
            if ($(this).val() != '{{ $building->school_id }}') {
                alert('Warning: Changing the school will not automatically move the assigned rooms. Please ensure room assignments are updated accordingly.');
            }
        });
    @endif
});
</script>
@endsection