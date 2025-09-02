@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-edit mr-2"></i>
                        Assign Room Adviser
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.homeroom.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Back to Homeroom Advising
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Room Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-door-open mr-2"></i>
                                Room Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Room Name:</dt>
                                        <dd class="col-sm-8"><strong>{{ $room->name }}</strong></dd>
                                        
                                        <dt class="col-sm-4">School:</dt>
                                        <dd class="col-sm-8">{{ $room->school->name ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-4">Grade Level:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge badge-info">{{ $room->grade_level }}</span>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Building:</dt>
                                        <dd class="col-sm-8">{{ $room->building->name ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-4">Students:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge badge-secondary">{{ $room->students_count ?? 0 }}</span>
                                        </dd>
                                        
                                        <dt class="col-sm-4">Current Adviser:</dt>
                                        <dd class="col-sm-8">
                                            @if($room->adviser)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-success mr-2"></i>
                                                    <div>
                                                        <strong>{{ $room->adviser->name }}</strong>
                                                        <br><small class="text-muted">{{ ucfirst($room->adviser->role) }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-user-slash mr-1"></i>
                                                    No adviser assigned
                                                </span>
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            
                            @if($room->description)
                                <div class="mt-3">
                                    <dt>Description:</dt>
                                    <dd class="text-muted">{{ $room->description }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Adviser Assignment Form -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user-plus mr-2"></i>
                                Assign Adviser
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.homeroom.update-adviser', $room->id) }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="form-group">
                                    <label for="adviser_id" class="form-label">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                                        Select Adviser (Optional)
                                    </label>
                                    <select name="adviser_id" id="adviser_id" class="form-control @error('adviser_id') is-invalid @enderror">
                                        <option value="">-- No Adviser (Remove current adviser) --</option>
                                        @foreach($availableAdvisers as $adviser)
                                            <option value="{{ $adviser->id }}" 
                                                    {{ old('adviser_id', $room->adviser_id) == $adviser->id ? 'selected' : '' }}
                                                    data-role="{{ $adviser->role }}">
                                                {{ $adviser->name }} ({{ ucfirst($adviser->role) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('adviser_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Only teachers and administrators from the same school are available for selection.
                                    </small>
                                </div>
                                
                                <!-- Selected Adviser Preview -->
                                <div id="adviser-preview" class="alert alert-info" style="display: none;">
                                    <h6><i class="fas fa-info-circle mr-1"></i> Selected Adviser Preview:</h6>
                                    <div id="adviser-details"></div>
                                </div>
                                
                                <div class="form-group mt-4">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.homeroom.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times mr-1"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i>
                                            Update Adviser Assignment
                                        </button>
                                    </div>
                                </div>
                            </form>
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
    const adviserSelect = $('#adviser_id');
    const adviserPreview = $('#adviser-preview');
    const adviserDetails = $('#adviser-details');
    
    // Handle adviser selection preview
    adviserSelect.change(function() {
        const selectedOption = $(this).find('option:selected');
        const adviserId = selectedOption.val();
        
        if (adviserId) {
            const adviserName = selectedOption.text();
            const adviserRole = selectedOption.data('role');
            
            let roleIcon = 'fas fa-user';
            let roleClass = 'info';
            
            if (adviserRole === 'admin') {
                roleIcon = 'fas fa-user-shield';
                roleClass = 'warning';
            } else if (adviserRole === 'teacher') {
                roleIcon = 'fas fa-chalkboard-teacher';
                roleClass = 'success';
            }
            
            adviserDetails.html(`
                <div class="d-flex align-items-center">
                    <i class="${roleIcon} text-${roleClass} mr-2 fa-2x"></i>
                    <div>
                        <strong>${adviserName}</strong>
                        <br><span class="badge badge-${roleClass}">${adviserRole.charAt(0).toUpperCase() + adviserRole.slice(1)}</span>
                    </div>
                </div>
            `);
            
            adviserPreview.slideDown();
        } else {
            adviserPreview.slideUp();
        }
    });
    
    // Trigger change event on page load to show current selection
    adviserSelect.trigger('change');
    
    // Form validation
    $('form').submit(function(e) {
        const selectedAdviser = adviserSelect.val();
        const currentAdviser = '{{ $room->adviser_id }}';
        
        if (selectedAdviser === currentAdviser) {
            e.preventDefault();
            alert('Please select a different adviser or choose "No Adviser" to remove the current assignment.');
            return false;
        }
        
        // Confirm removal of adviser
        if (!selectedAdviser && currentAdviser) {
            if (!confirm('Are you sure you want to remove the current adviser from this room?')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Confirm assignment change
        if (selectedAdviser && currentAdviser && selectedAdviser !== currentAdviser) {
            if (!confirm('Are you sure you want to change the adviser for this room?')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endpush