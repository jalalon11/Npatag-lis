@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 px-4">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="m-0">Create New Subject</h3>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Subjects
                    </a>
                </div>
                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-body">
                <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <!-- Entry Type Toggle -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">Select Entry Method</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 hover-card" id="single_card">
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
                                                            <p class="text-muted mb-0">Create one subject with detailed information including MAPEH options.</p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 hover-card" id="batch_card">
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
                                                            <p class="text-muted mb-0">Create multiple subjects at once using CSV-like format.</p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Single Entry Form -->
                        <form id="createSubjectForm" action="{{ route('admin.subjects.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" name="is_batch" value="0" id="is_batch_input">
                            
                            <!-- Hidden school field since there's only one school -->
                            <input type="hidden" name="school_id" value="{{ $defaultSchool->id }}">
                            
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
                        
                            <div id="single_entry_form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-4 border-0">
                                            <div class="card-header bg-white py-3">
                                                <h5 class="mb-0 fw-bold">Basic Information</h5>
                                            </div>
                                            <div class="card-body bg-white">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="code" class="form-label">Subject Code</label>
                                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}">
                                                    @error('code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text text-muted">A short code or identifier for this subject (e.g., MATH7, SCIB)</div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="grade_level" class="form-label">Grade Level</label>
                                                    <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level">
                                                        <option value="">Select Grade Level</option>
                                                        @foreach($gradeLevels as $grade)
                                                            <option value="{{ $grade }}" {{ old('grade_level') == $grade ? 'selected' : '' }}>Grade {{ $grade }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('grade_level')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text text-muted">Specify a grade level for this subject</div>
                                                </div>
                                                
                                                <div class="mb-0">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="is_mapeh" name="is_mapeh" value="1" {{ old('is_mapeh') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_mapeh">This is a MAPEH subject</label>
                                                    </div>
                                                    <div class="form-text text-muted">MAPEH includes Music, Arts, Physical Education, and Health components</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="card mb-4 border-0">
                                            <div class="card-header bg-white py-3">
                                                <h5 class="mb-0 fw-bold">Additional Details</h5>
                                            </div>
                                            <div class="card-body bg-white">
                                                <div class="mb-4">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="alert alert-info bg-light border-0">
                                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                                    <strong>Note:</strong> After creating the subject, you can assign it to sections and teachers.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            <!-- MAPEH Component Weights (initially hidden) -->
                            <div id="mapeh_components" class="mb-4" style="display: none;">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h5 class="mb-0 fw-bold">
                                            <i class="fas fa-balance-scale me-2 text-primary"></i>MAPEH Component Weights
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-4">Set the weight of each MAPEH component. The total should equal 100%.</p>
                                        
                                        <div class="row g-4">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="music_weight" class="form-label">Music Weight (%)</label>
                                                    <input type="number" class="form-control component-weight" id="music_weight" name="music_weight" value="{{ old('music_weight', 25) }}" min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="arts_weight" class="form-label">Arts Weight (%)</label>
                                                    <input type="number" class="form-control component-weight" id="arts_weight" name="arts_weight" value="{{ old('arts_weight', 25) }}" min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pe_weight" class="form-label">P.E. Weight (%)</label>
                                                    <input type="number" class="form-control component-weight" id="pe_weight" name="pe_weight" value="{{ old('pe_weight', 25) }}" min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="health_weight" class="form-label">Health Weight (%)</label>
                                                    <input type="number" class="form-control component-weight" id="health_weight" name="health_weight" value="{{ old('health_weight', 25) }}" min="0" max="100">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-warning bg-light border-0 mt-3" id="weight_warning" style="display: none;">
                                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                                            The total weight must equal 100%. Current total: <span id="total_weight" class="fw-bold">100</span>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                            <!-- Batch Entry Form -->
                            <div id="batch_entry_form" style="display: none;">
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-0">
                                        <h5 class="mb-0 fw-bold">Batch Subject Entry</h5>
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
                                                        <li>Enter one subject per line using the format below</li>
                                                        <li>Required fields: Subject Name, Subject Code, Grade Level</li>
                                                        <li>Optional: Add Description as 4th value</li>
                                                    </ol>
                                                    <div class="bg-white p-2 rounded border">
                                                        <code class="text-muted">Subject Name, Subject Code, Grade Level (number only), Description(optional)</code>
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
                                            <pre class="mb-0 bg-white p-3 rounded border code-sample">Mathematics 7, MATH-007, 7, Basic mathematics for 7th grade
Science 7, SCI-007, 7, Introduction to science concepts
Filipino 7, FIL-007, 7, Wika at Gramatika</pre>
                                        </div>
                                        
                                        <div class="form-group mb-4">
                                            <label for="batch_subjects" class="form-label fw-bold">Subjects (one per line) <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('batch_subjects') is-invalid @enderror" id="batch_subjects" name="batch_subjects" rows="8" placeholder="Mathematics 7, MATH-007, 7, Basic mathematics for 7th grade&#10;Science 7, SCI-007, 7, Introduction to science concepts">{{ old('batch_subjects') }}</textarea>
                                            @error('batch_subjects')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="row g-4">
                                            <div class="col-md-7">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title fw-bold"><i class="fas fa-check-circle text-success me-2"></i>Benefits of Batch Entry</h6>
                                                        <ul class="mb-0 text-muted">
                                                            <li>Create multiple subjects at once</li>
                                                            <li>Faster than creating subjects individually</li>
                                                            <li>Perfect for initial setup or new grade levels</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body text-center">
                                                        <h6 class="card-title fw-bold mb-1">
                                                            <i class="fas fa-list-ol text-primary me-2"></i>Subject Count
                                                        </h6>
                                                        <div class="display-4 fw-bold text-primary" id="line_count">0</div>
                                                        <small class="text-muted">Lines detected (empty lines ignored)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="mt-4 text-end">
                                <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitSubjectsBtn">
                                    <i class="fas fa-save me-1"></i> Create Subject(s)
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
            // Update active card styling
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
        
        // Toggle MAPEH component weights display
        $("#is_mapeh").change(function() {
            if($(this).is(":checked")) {
                $("#mapeh_components").slideDown();
            } else {
                $("#mapeh_components").slideUp();
            }
        });
        
        // Initialize MAPEH section visibility based on initial checkbox state
        if($("#is_mapeh").is(":checked")) {
            $("#mapeh_components").show();
        }
        
        // Calculate total weight and show warning if not 100%
        $(".component-weight").on("input", function() {
            let totalWeight = 0;
            $(".component-weight").each(function() {
                const weight = parseFloat($(this).val()) || 0;
                totalWeight += weight;
            });
            
            $("#total_weight").text(totalWeight);
            
            if(Math.abs(totalWeight - 100) > 0.01) {
                $("#weight_warning").show();
                // Add a red border to indicate the error
                $(".component-weight").addClass("border-danger");
            } else {
                $("#weight_warning").hide();
                // Remove the red border
                $(".component-weight").removeClass("border-danger");
            }
        });
        
        // Toggle batch example
        $("#show_batch_example").click(function() {
            $("#batch_example").slideToggle();
        });
        
        // Use example data
        $("#use_example_data").click(function() {
            const exampleData = $(".code-sample").text();
            $("#batch_subjects").val(exampleData);
            countLines();
        });
        
        // Count lines for batch entry
        function countLines() {
            const text = $("#batch_subjects").val();
            const lines = text ? text.split("\n").filter(line => line.trim() !== "").length : 0;
            $("#line_count").text(lines);
        }
        
        // Update line count when textarea changes
        $("#batch_subjects").on("input", function() {
            countLines();
        });
        
        // Initialize line count
        countLines();
        
        // Add direct click handler for the submit button
        $("#submitSubjectsBtn").click(function(e) {
            // Prevent the default button behavior to handle it manually
            e.preventDefault();
            
            // Ensure the batch mode is correctly set
            if($('#batch_entry').is(':checked')) {
                $('#is_batch_input').val('1');
            } else {
                $('#is_batch_input').val('0');
            }
            
            // Additional check to ensure form is valid
            let isFormValid = true;
            
            // Validate the current visible form
            if($('#is_batch_input').val() === '1') {
                // Batch form validation
                if(!$('#batch_subjects').val().trim()) {
                    isFormValid = false;
                    $('#batch_subjects').addClass('is-invalid');
                }
            } else {
                // Single form validation
                if(!$('#name').val().trim()) {
                    isFormValid = false;
                    $('#name').addClass('is-invalid');
                }
            }
            
            console.log('Submit button clicked, form valid:', isFormValid);
            
            if(isFormValid) {
                // If valid, manually submit the form
                console.log('Manually submitting form');
                $('#createSubjectForm').submit();
            } else {
                console.log('Form validation failed on submit button click');
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