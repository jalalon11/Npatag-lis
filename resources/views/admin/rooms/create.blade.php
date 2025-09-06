@extends('layouts.app')

@section('title', 'Add New Room')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 px-4">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="m-0">Create New Room</h3>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Rooms
                    </a>
                </div>
                <div class="card border-0 shadow-sm bg-white">
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
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">Select Entry Method</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 hover-card entry-method-card" id="single_card">
                                        <div class="card-body p-4">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="radio" name="entry_type" id="single_entry" value="single" checked>
                                                <label class="form-check-label w-100" for="single_entry">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-file-alt fa-2x text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-1">Single Entry</h5>
                                                            <p class="text-muted mb-0">Create one room with detailed information including building and adviser assignment.</p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 hover-card entry-method-card" id="batch_card">
                                        <div class="card-body p-4">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="radio" name="entry_type" id="batch_entry" value="batch">
                                                <label class="form-check-label w-100" for="batch_entry">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-layer-group fa-2x text-success"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-1">Batch Entry</h5>
                                                            <p class="text-muted mb-0">Create multiple rooms at once using CSV-like format.</p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Container -->
                        <form id="createRoomForm" action="{{ route('admin.rooms.store') }}" method="POST" novalidate>
                            @csrf
                            <input type="hidden" name="is_batch" value="0" id="is_batch_input">

                            <!-- School Information Display -->
                            <div class="mb-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-school text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">School</h6>
                                                <p class="mb-0 text-muted">{{ $defaultSchool->name }}</p>
                                            </div>
                                            <div class="ms-auto">
                                                <span class="badge bg-primary bg-opacity-10 text-primary">Default School</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Entry Form -->
                            <div id="single_entry_form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-4 border-0">
                                            <div class="card-header bg-white py-3">
                                                <h5 class="mb-0 fw-bold">Basic Information</h5>
                                            </div>
                                            <div class="card-body bg-white">
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
                                                        @foreach($gradeLevels as $grade)
                                                            <option value="{{ $grade }}" {{ old('grade_level') == $grade ? 'selected' : '' }}>Grade {{ $grade }}</option>
                                                        @endforeach
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

                                                <input type="hidden" name="school_id" id="school_id" value="{{ $defaultSchool->id }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card mb-4 border-0">
                                            <div class="card-header bg-white py-3">
                                                <h5 class="mb-0 fw-bold">Additional Details</h5>
                                            </div>
                                            <div class="card-body bg-white">
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
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_active">Active Room</label>
                                                        <div class="form-text text-muted">Only active rooms can have students assigned to them.</div>
                                                    </div>
                                                </div>

                                                <div class="alert alert-info bg-light border-0">
                                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                                    <strong>Note:</strong> After creating the room, you can assign students and schedules.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Batch Entry Form -->
                            <div id="batch_entry_form" style="display: none;">
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-0">
                                        <h5 class="mb-0 fw-bold">Batch Room Entry</h5>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="show_batch_example">
                                            <i class="fas fa-question-circle me-1"></i> Show Example
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info bg-light border-0">
                                            <div class="d-flex">
                                                <div class="me-3">
                                                    <i class="fas fa-info-circle text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold">Instructions:</h6>
                                                    <ol class="mb-2 ps-3">
                                                        <li>Enter one room per line using the format below</li>
                                                        <li>Required fields: Room Name, Grade Level, School Year, Adviser ID</li>
                                                        <li>Optional: Student Limit, Building ID</li>
                                                    </ol>
                                                    <div class="bg-white p-2 rounded border">
                                                        <code class="text-muted">Room Name, Grade Level, School Year, Student Limit, Adviser ID, Building ID</code>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="batch_example" class="alert alert-secondary bg-light border-0" style="display: none;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold mb-0">Example Data:</h6>
                                                <button type="button" class="btn btn-sm btn-primary" id="use_example_data">
                                                    <i class="fas fa-copy me-1"></i> Use This Example
                                                </button>
                                            </div>
                                            <pre class="mb-0 bg-white p-3 rounded border code-sample">Room A, 7, 2025-2026, 35, 1, 1
Room B, 7, 2025-2026, 40, 2, 
Science Lab, 8, 2025-2026, , 3, 2</pre>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="batch_data" class="form-label fw-bold">Rooms (one per line) <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('batch_data') is-invalid @enderror" id="batch_data" name="batch_data" rows="8" placeholder="Room A, 7, 2025-2026, 35, 1, 1&#10;Room B, 7, 2025-2026, 40, 2, &#10;Science Lab, 8, 2025-2026, , 3, 2">{{ old('batch_data') }}</textarea>
                                            @error('batch_data')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row g-4">
                                            <div class="col-md-7">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title fw-bold"><i class="fas fa-check-circle text-success me-2"></i>Benefits of Batch Entry</h6>
                                                        <ul class="mb-0 text-muted">
                                                            <li>Create multiple rooms at once</li>
                                                            <li>Faster than creating rooms individually</li>
                                                            <li>Perfect for initial setup or new grade levels</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body text-center">
                                                        <h6 class="card-title fw-bold mb-1">
                                                            <i class="fas fa-list-ol text-primary me-2"></i>Room Count
                                                        </h6>
                                                        <div class="display-4 fw-bold text-primary" id="line_count">0</div>
                                                        <small class="text-muted">Lines detected (empty lines ignored)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-secondary bg-light border-0 mt-4">
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
                            </div>

                            <div class="mt-4 text-end">
                                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitRoomsBtn">
                                    <i class="fas fa-save me-1"></i> Create Room(s)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Add CSS for the entry method cards
        $("<style>")
            .prop("type", "text/css")
            .html(`
                .entry-method-card {
                    cursor: pointer;
                    transition: all 0.2s ease;
                    border: 2px solid transparent;
                }
                .entry-method-card.selected {
                    border-color: #4e73df;
                    box-shadow: 0 0 15px rgba(78, 115, 223, 0.25);
                }
                .entry-method-card:hover:not(.selected) {
                    border-color: #e9ecef;
                }
            `)
            .appendTo("head");

        // Make entire card clickable for entry method selection
        $('.entry-method-card').click(function() {
            const radioBtn = $(this).find('input[type="radio"]');
            radioBtn.prop('checked', true).trigger('change');
        });

        // Toggle entry type with visual feedback
        $('input[name="entry_type"]').change(function() {
            $('.entry-method-card').removeClass('selected');
            if($(this).val() === 'single') {
                $('#single_card').addClass('selected');
                $('#single_entry_form').show();
                $('#batch_entry_form').hide();
                $('#is_batch_input').val('0');
            } else {
                $('#batch_card').addClass('selected');
                $('#single_entry_form').hide();
                $('#batch_entry_form').show();
                $('#is_batch_input').val('1');
            }
        });

        // Set initial selected state
        if($('#batch_entry').is(':checked')) {
            $('#batch_card').addClass('selected');
            $('#is_batch_input').val('1');
        } else {
            $('#single_card').addClass('selected');
            $('#is_batch_input').val('0');
        }

        // Toggle batch example
        $("#show_batch_example").click(function() {
            $("#batch_example").slideToggle();
        });

        // Use example data
        $("#use_example_data").click(function() {
            const exampleData = $(".code-sample").text();
            $("#batch_data").val(exampleData);
            countLines();
        });

        // Count lines for batch entry
        function countLines() {
            const text = $("#batch_data").val();
            const lines = text ? text.split("\n").filter(line => line.trim() !== "").length : 0;
            $("#line_count").text(lines);
        }

        // Update line count when textarea changes
        $("#batch_data").on("input", function() {
            countLines();
        });

        // Initialize line count
        countLines();

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

        // Add direct click handler for the submit button
        $("#submitRoomsBtn").click(function(e) {
            e.preventDefault();

            // Ensure the batch mode is correctly set
            if($('#batch_entry').is(':checked')) {
                $('#is_batch_input').val('1');
            } else {
                $('#is_batch_input').val('0');
            }

            // Validate the current visible form
            let isFormValid = true;

            if($('#is_batch_input').val() === '1') {
                // Batch form validation
                if(!$('#batch_data').val().trim()) {
                    isFormValid = false;
                    $('#batch_data').addClass('is-invalid');
                } else if (!validateBatchFormat()) {
                    isFormValid = false;
                }
            } else {
                // Single form validation
                const requiredFields = ['name', 'grade_level', 'school_year', 'school_id'];
                requiredFields.forEach(field => {
                    const value = $(`#${field}`).val();
                    if (!value || value.trim() === '') {
                        $(`#${field}`).addClass('is-invalid');
                        isFormValid = false;
                    } else {
                        $(`#${field}`).removeClass('is-invalid');
                    }
                });
            }

            if(isFormValid) {
                // If valid, manually submit the form
                $('#createRoomForm').submit();
            } else {
                // Show error notification
                if (!$('.alert-danger').length) {
                    $('.card-body:first').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> Please fill in all required fields.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }

                // Scroll to first error
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
            }
        });
    });
</script>
@endpush
@endsection