@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-edit me-2 text-primary"></i>
                            Assign Room Adviser
                        </h3>
                        <a href="{{ route('admin.homeroom.index') }}" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Homeroom Advising
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Room Information -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light py-3 rounded-top">
                            <h5 class="mb-0">
                                <i class="fas fa-door-open me-2 text-info"></i>
                                Room Information
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 fw-bold">Room Name:</dt>
                                        <dd class="col-sm-8">{{ $room->name }}</dd>
                                        
                                        <dt class="col-sm-4 fw-bold">School:</dt>
                                        <dd class="col-sm-8">{{ $room->school->name ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-4 fw-bold">Grade Level:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-info text-white px-2 py-1">{{ $room->grade_level }}</span>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 fw-bold">Building:</dt>
                                        <dd class="col-sm-8">{{ $room->building->name ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-4 fw-bold">Students:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-secondary px-2 py-1">{{ $room->students_count ?? 0 }}</span>
                                        </dd>
                                        
                                        <dt class="col-sm-4 fw-bold">Current Adviser:</dt>
                                        <dd class="col-sm-8">
                                            @if($room->adviser)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-success me-2"></i>
                                                    <div>
                                                        <strong>{{ $room->adviser->name }}</strong>
                                                        <br><small class="text-muted">{{ ucfirst($room->adviser->role) }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-user-slash me-1"></i>
                                                    No adviser assigned
                                                </span>
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            
                            @if($room->description)
                                <div class="mt-4 pt-2 border-top">
                                    <dt class="fw-bold">Description:</dt>
                                    <dd class="text-muted">{{ $room->description }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Adviser Assignment Form -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white py-3 rounded-top">
                            <h5 class="mb-0">
                                <i class="fas fa-user-plus me-2"></i>
                                Assign Adviser
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="adviser-form" method="POST" action="{{ route('admin.homeroom.update-adviser', $room->id) }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-4">
                                    <label for="adviser_id" class="form-label fw-bold">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>
                                        Select Adviser
                                    </label>
                                    <select name="adviser_id" id="adviser_id" class="form-select rounded @error('adviser_id') is-invalid @enderror">
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
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        Only teachers and administrators from the same school are available.
                                    </small>
                                </div>
                                
                                <!-- Selected Adviser Preview -->
                                <div id="adviser-preview" class="alert alert-info py-2 px-3 mb-4" style="display: none;">
                                    <h6 class="mb-2"><i class="fas fa-info-circle me-1"></i> Selected Adviser:</h6>
                                    <div id="adviser-details"></div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.homeroom.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
                                        <i class="fas fa-save me-1"></i>
                                        Update Adviser
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.form-select {
    transition: border-color 0.2s ease-in-out;
}
.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}
.alert-info {
    background-color: #e7f1ff;
    border-color: #b8daff;
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
    const adviserSelect = $('#adviser_id');
    const adviserPreview = $('#adviser-preview');
    const adviserDetails = $('#adviser-details');
    const submitBtn = $('#submit-btn');
    
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
                roleClass = 'success';
            } else if (adviserRole === 'teacher') {
                roleIcon = 'fas fa-chalkboard-teacher';
                roleClass = 'success';
            }
            
            adviserDetails.html(`
                <div class="d-flex align-items-center">
                    <i class="${roleIcon} text-${roleClass} me-2 fa-lg"></i>
                    <div>
                        <strong>${adviserName}</strong>
                        <br><span class="badge bg-${roleClass} text-white px-2 py-1">${adviserRole.charAt(0).toUpperCase() + adviserRole.slice(1)}</span>
                    </div>
                </div>
            `);
            
            adviserPreview.fadeIn(200);
        } else {
            adviserPreview.fadeOut(200);
        }
    });
    
    // Trigger change event on page load
    adviserSelect.trigger('change');
    
    // Form submission handling
    $('#adviser-form').submit(function(e) {
        e.preventDefault();
        const selectedAdviser = adviserSelect.val();
        const currentAdviser = '{{ $room->adviser_id }}';
        
        // Validate same adviser selection
        if (selectedAdviser === currentAdviser) {
            alert('Please select a different adviser or choose "No Adviser" to remove the current assignment.');
            return false;
        }
        
        // Confirm removal of adviser
        if (!selectedAdviser && currentAdviser) {
            if (!confirm('Are you sure you want to remove the current adviser from this room?')) {
                return false;
            }
        }
        
        // Confirm assignment change
        if (selectedAdviser && currentAdviser && selectedAdviser !== currentAdviser) {
            if (!confirm('Are you sure you want to change the adviser for this room?')) {
                return false;
            }
        }
        
        // Show loading state
        submitBtn.find('.spinner-border').removeClass('d-none');
        submitBtn.prop('disabled', true);
        
        // Submit form
        this.submit();
    });
});
</script>
@endpush