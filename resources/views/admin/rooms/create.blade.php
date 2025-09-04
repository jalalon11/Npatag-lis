@extends('layouts.app')

@section('title', 'Add New Room')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i> Create New Room
                    </h4>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Back to Rooms
                    </a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('batch_errors'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5><i class="fas fa-exclamation-triangle me-1"></i> Batch Entry Errors:</h5>
                            <ul class="mb-0">
                                @foreach(session('batch_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Entry Type Toggle -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Select Entry Method</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100 entry-method-card" id="single_card">
                                        <div class="card-body p-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="entry_type" id="single_entry" value="single" checked>
                                                <label class="form-check-label w-100" for="single_entry">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="bg-primary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                            <i class="fas fa-file-alt fa-lg"></i>
                                                        </div>
                                                        <h5 class="mb-0">Single Entry</h5>
                                                    </div>
                                                    <p class="text-muted mb-0">Create one room with detailed information including building and adviser assignment.</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 entry-method-card" id="batch_card">
                                        <div class="card-body p-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="entry_type" id="batch_entry" value="batch">
                                                <label class="form-check-label w-100" for="batch_entry">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="bg-success text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                            <i class="fas fa-layer-group fa-lg"></i>
                                                        </div>
                                                        <h5 class="mb-0">Batch Entry</h5>
                                                    </div>
                                                    <p class="text-muted mb-0">Create multiple rooms at once using CSV-like format.</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Single Entry Form -->
                    <div id="single_entry_form">
                        <form action="{{ route('admin.rooms.store') }}" method="POST" id="createRoomForm">
                            @csrf
                            <input type="hidden" name="is_batch" value="0" id="is_batch_input">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Basic Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Room Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter room name" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                                <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                                    <option value="" selected disabled>Select Grade Level</option>
                                                    @for($i = 1; $i <= 6; $i++)
                                                        <option value="Grade {{ $i }}" {{ old('grade_level') == "Grade $i" ? 'selected' : '' }}>Grade {{ $i }}</option>
                                                    @endfor
                                                </select>
                                                @error('grade_level')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="school_year" class="form-label">School Year <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('school_year') is-invalid @enderror" id="school_year" name="school_year" value="{{ old('school_year', \App\Models\SystemSetting::getSetting('school_year', date('Y') . '-' . (date('Y') + 1))) }}" placeholder="e.g., 2024-2025" required>
                                                @error('school_year')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Hidden school field since there's only one school -->
                            <input type="hidden" name="school_id" id="school_id" value="{{ $defaultSchool->id }}">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">School</label>
                                                <div class="form-control-plaintext bg-light p-2 rounded border">
                                                    <i class="fas fa-school me-2 text-primary"></i>
                                                    {{ $defaultSchool->name }}
                                                </div>
                                                <small class="form-text text-muted">Default school automatically selected</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Additional Details</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="adviser_id" class="form-label">Room Adviser <small class="text-muted">(Optional)</small></label>
                                                <select class="form-select @error('adviser_id') is-invalid @enderror" id="adviser_id" name="adviser_id">
                                                    <option value="" selected>No adviser assigned</option>
                                                    @foreach($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}" {{ old('adviser_id') == $teacher->id ? 'selected' : '' }}>
                                                            {{ $teacher->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('adviser_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="building_id" class="form-label">Building</label>
                                                <select class="form-select @error('building_id') is-invalid @enderror" id="building_id" name="building_id">
                                                    <option value="" selected>No Building Assigned</option>
                                                    @foreach($buildings as $building)
                                                        <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                                            {{ $building->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('building_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="student_limit" class="form-label">Student Limit</label>
                                                <input type="number" class="form-control @error('student_limit') is-invalid @enderror" id="student_limit" name="student_limit" value="{{ old('student_limit') }}" min="1" max="100" placeholder="Maximum students (optional)">
                                                @error('student_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_active">Active Room</label>
                                                    <small class="form-text text-muted d-block">Only active rooms can have students assigned to them.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitRoomsBtn">
                                    <i class="fas fa-save me-1"></i> Create Room
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Batch Entry Form -->
                    <div id="batch_entry_form" style="display: none;">
                        <div class="card mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Batch Room Entry</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="show_batch_example">
                                    <i class="fas fa-question-circle me-1"></i> Show Example
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-info-circle me-2 fa-lg"></i>
                                        <strong>Instructions:</strong>
                                    </div>
                                    <ol class="mb-2">
                                        <li>Enter one room per line using the <strong>exact</strong> format below</li>
                                        <li>Required fields: Room Name, Grade Level, School Year, Adviser ID</li>
                                        <li>Optional fields: Building ID, Student Limit (leave empty or use 0 for no limit)</li>
                                        <li>The Adviser ID must be a number that matches a teacher ID in your system</li>
                                        <li>Each line should have 4-6 parts separated by commas</li>
                                    </ol>
                                    <div class="bg-light p-2 rounded border">
                                        <code>Room Name, Grade Level, School Year, Student Limit, Adviser ID, Building ID</code>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Troubleshooting:</strong>
                                        <ul class="small mb-0">
                                            <li>Use the example data provided for correct formatting</li>
                                            <li>Check for extra commas or missing commas</li>
                                            <li>Ensure Adviser IDs match the available teacher IDs below</li>
                                            <li>Building ID is optional; leave empty if not assigning a building</li>
                                        </ul>
                                    </div>
                                </div>

                                <div id="batch_example" class="alert alert-secondary" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Example Data:</strong>
                                        <button type="button" class="btn btn-sm btn-primary" id="use_example_data">
                                            <i class="fas fa-copy me-1"></i> Use This Example
                                        </button>
                                    </div>
                                    <pre class="mb-0 bg-light p-2 rounded code-sample">Room A, 7, 2025-2026, 35, 1, 1
Room B, 7, 2025-2026, 40, 2, 
Science Lab, 8, 2025-2026, , 3, 2</pre>
                                    <div class="mt-2 small">
                                        <strong>Note:</strong> Make sure to use the exact format above with commas separating each field.
                                        <span class="text-danger">Do not use special characters in room names.</span>
                                    </div>
                                </div>

                                <form action="{{ route('admin.rooms.store') }}" method="POST" id="batchRoomForm">
                                    @csrf
                                    <input type="hidden" name="is_batch" value="1" id="is_batch_input_batch">
                                    
                                    <div class="mb-3">
                                        <label for="batch_data" class="form-label fw-bold">Room Data <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('batch_data') is-invalid @enderror" id="batch_data" name="batch_data" rows="10" placeholder="Room A, 7, 2025-2026, 35, 1, 1&#10;Room B, 7, 2025-2026, 40, 2, &#10;Science Lab, 8, 2025-2026, , 3, 2">{{ old('batch_data') }}</textarea>
                                        @error('batch_data')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="alert alert-success">
                                                <h6><i class="fas fa-check-circle me-1"></i> Benefits of Batch Entry:</h6>
                                                <ul class="mb-0">
                                                    <li>Create multiple rooms at once</li>
                                                    <li>Faster than creating rooms individually</li>
                                                    <li>Perfect for initial setup or new grade levels</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-secondary mb-3">
                                                <h6 class="mb-3 fw-bold text-primary"><i class="fas fa-users me-2"></i>Available Teachers and Buildings (Use these IDs):</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Teachers:</h6>
                                                        <ul class="list-unstyled small">
                                                            @foreach($teachers as $teacher)
                                                                <li class="mb-1"><span class="badge bg-dark">ID {{ $teacher->id }}</span> {{ $teacher->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Buildings:</h6>
                                                        <ul class="list-unstyled small">
                                                            @foreach($buildings as $building)
                                                                <li class="mb-1"><span class="badge bg-dark">ID {{ $building->id }}</span> {{ $building->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary me-2">
                                            <i class="fas fa-times me-1"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-success" id="submitBatchRoomsBtn">
                                            <i class="fas fa-upload me-1"></i> Create Rooms
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
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Style the entry method cards
        $('.entry-method-card').hover(function() {
            $(this).addClass('shadow-sm border-primary');
        }, function() {
            $(this).removeClass('shadow-sm border-primary');
        });

        // Handle card clicks to check the associated radio button
        $('.entry-method-card').click(function() {
            const radioId = $(this).find('input[type="radio"]').attr('id');
            $('#' + radioId).prop('checked', true).trigger('change');
        });

        // Toggle entry type
        $('input[name="entry_type"]').change(function() {
            if($(this).val() === 'single') {
                $('#single_entry_form').show();
                $('#batch_entry_form').hide();
                $('#is_batch_input').val('0');
                $('#single_card').addClass('border-primary');
                $('#batch_card').removeClass('border-primary');
            } else {
                $('#single_entry_form').hide();
                $('#batch_entry_form').show();
                $('#is_batch_input_batch').val('1');
                $('#batch_card').addClass('border-primary');
                $('#single_card').removeClass('border-primary');
            }
            console.log('Entry type changed:', $(this).val(), 'is_batch value:', $('#is_batch_input').val());
        });

        // Initialize the card highlighting based on selected option
        if($('#batch_entry').is(':checked')) {
            $('#batch_card').addClass('border-primary');
        } else {
            $('#single_card').addClass('border-primary');
        }

        // Show/hide example
        $('#show_batch_example').click(function() {
            $('#batch_example').toggle();
        });

        // Use example data
        $('#use_example_data').click(function() {
            const exampleData = $('.code-sample').text();
            $('#batch_data').val(exampleData);
            console.log('Example data inserted:', exampleData);
            validateBatchFormat();
        });

        // Batch format validation function
        function validateBatchFormat() {
            if ($('#batch_entry').is(':checked')) {
                const batchData = $('#batch_data').val().trim();
                if (batchData) {
                    let isValid = true;
                    let errorMessages = [];

                    const lines = batchData.split('\n');
                    lines.forEach((line, index) => {
                        line = line.trim();
                        if (!line) return;

                        const parts = line.split(',').map(part => part.trim()).filter(part => part.length > 0);

                        if (parts.length < 4) {
                            errorMessages.push(`Line ${index + 1}: Missing required fields. Found ${parts.length} of at least 4 required fields.`);
                            isValid = false;
                        } else {
                            // Validate Grade Level (1-12)
                            const gradeLevel = parts[1];
                            if (!/^\d+$/.test(gradeLevel) || parseInt(gradeLevel) < 1 || parseInt(gradeLevel) > 12) {
                                errorMessages.push(`Line ${index + 1}: Grade Level must be a number between 1 and 12, got '${gradeLevel}'`);
                                isValid = false;
                            }

                            // Validate School Year format (YYYY-YYYY)
                            const schoolYear = parts[2];
                            if (!/^\d{4}-\d{4}$/.test(schoolYear)) {
                                errorMessages.push(`Line ${index + 1}: School Year must be in format YYYY-YYYY, got '${schoolYear}'`);
                                isValid = false;
                            }

                            // Validate Adviser ID (numeric)
                            const adviserId = parts[4];
                            if (!$.isNumeric(adviserId)) {
                                errorMessages.push(`Line ${index + 1}: Adviser ID must be a number, got '${adviserId}'`);
                                isValid = false;
                            }

                            // Validate Student Limit (if provided)
                            if (parts[3] && !$.isNumeric(parts[3])) {
                                errorMessages.push(`Line ${index + 1}: Student Limit must be a number or empty, got '${parts[3]}'`);
                                isValid = false;
                            }

                            // Validate Building ID (if provided)
                            if (parts.length > 5 && parts[5] && !$.isNumeric(parts[5])) {
                                errorMessages.push(`Line ${index + 1}: Building ID must be a number or empty, got '${parts[5]}'`);
                                isValid = false;
                            }
                        }
                    });

                    // Show or clear validation message
                    if (!isValid) {
                        if (!$('#batch_format_error').length) {
                            $('#batch_data').after(`
                                <div id="batch_format_error" class="alert alert-danger mt-2">
                                    <h6><i class="fas fa-exclamation-triangle me-1"></i> Format Errors:</h6>
                                    <ul class="mb-0 ps-3">
                                        ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                                    </ul>
                                    <div class="mt-2">
                                        <small class="text-muted">Make sure each line follows the exact format: <code>Room Name, Grade Level, School Year, Student Limit, Adviser ID, Building ID</code></small>
                                    </div>
                                </div>
                            `);
                        } else {
                            $('#batch_format_error ul').html(errorMessages.map(msg => `<li>${msg}</li>`).join(''));
                        }
                        $('#batch_data').addClass('is-invalid');
                    } else {
                        $('#batch_format_error').remove();
                        $('#batch_data').removeClass('is-invalid');
                    }

                    return isValid;
                }
            }
            return true;
        }

        // Add live validation to batch data textarea
        $('#batch_data').on('input', validateBatchFormat);

        // Form validation for single entry form
        $("#createRoomForm").on("submit", function(e) {
            e.preventDefault();
            let isFormValid = true;

            const requiredFields = ['name', 'grade_level', 'school_year', 'school_id'];
            requiredFields.forEach(field => {
                const value = $(`#${field}`).val();
                if (!value || value.trim() === '') {
                    $(`#${field}`).addClass('is-invalid');
                    isFormValid = false;
                    console.log(`Field ${field} validation failed - empty value`);
                } else {
                    $(`#${field}`).removeClass('is-invalid');
                }
            });

            if (isFormValid) {
                console.log('Manually submitting single entry form');
                this.submit();
            } else {
                if (!$('.alert-danger').length) {
                    $('.card-body:first').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> Please fill in all required fields.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
            }
        });

        // Form validation for batch entry form
        $("#batchRoomForm").on("submit", function(e) {
            e.preventDefault();
            $('#is_batch_input_batch').val('1');

            if ($('#batch_data').val().trim() === '') {
                alert('Please enter room data for batch creation');
                $('#batch_data').addClass('is-invalid');
                return false;
            }

            if (!validateBatchFormat()) {
                console.log('Batch format validation failed');
                return false;
            }

            const form = $(this);
            form.find('input[name="batch_data"]').remove();
            form.find('input[name="batch_data_hidden"]').remove();
            const batchData = JSON.stringify($('#batch_data').val());
            form.append(`<input type="hidden" name="batch_data_json" value='${batchData}'>`);
            console.log('Submitting with JSON encoded batch data', batchData);

            console.log('Manually submitting batch entry form');
            this.submit();
        });

        // Add direct click handler for submit buttons
        $("#submitRoomsBtn").click(function(e) {
            e.preventDefault();
            $('#createRoomForm').submit();
        });

        $("#submitBatchRoomsBtn").click(function(e) {
            e.preventDefault();
            $('#batchRoomForm').submit();
        });
    });
</script>
@endpush
@endsection