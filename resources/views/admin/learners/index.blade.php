@extends('layouts.app')

@section('title', 'Learners Record')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Learners Record</h2>
                <a href="{{ route('admin.admissions.index') }}" class="btn btn-primary fw-bold">
                    <i class="fas fa-clipboard-list me-1"></i> View Applications
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
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-check text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Approved Students</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $stats['total_approved'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.learners.index', ['status' => 'enrolled']) }}" class="text-decoration-none">
                <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-user-graduate text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Enrolled Students</h6>
                                <h3 class="mb-0 fw-bold text-primary">{{ $stats['enrolled_students'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending Enrollment</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $stats['pending_enrollment'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card bg-white border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.learners.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search students..." value="{{ request('search') }}">
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
                    <select name="grade_level" class="form-select">
                        <option value="">All Grades</option>
                        @foreach($gradeLevels as $grade)
                            <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Enrollment</option>
                        <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort by...</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="processed_at" {{ request('sort') == 'processed_at' ? 'selected' : '' }}>Approval Date</option>
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

    <!-- Approved Students Table -->
    <div class="card border-0 bg-white shadow-sm pb-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="background-color: white;">
                    <thead class="" style="background-color: #ffffff;">
                        <tr>
                            <th scope="col">Student Info</th>
                            <th scope="col">Guardian</th>
                            <th scope="col">School & Grade</th>
                            <th scope="col">Approval Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: white;">
                        @php
                            $filteredStudents = $approvedStudents;

                            if (request('search')) {
                                $searchTerm = strtolower(request('search'));
                                $filteredStudents = $approvedStudents->filter(function($student) use ($searchTerm) {
                                    return str_contains(strtolower($student->first_name . ' ' . $student->last_name), $searchTerm) ||
                                           str_contains(strtolower($student->student_id), $searchTerm) ||
                                           str_contains(strtolower($student->lrn), $searchTerm) ||
                                           str_contains(strtolower($student->guardian_name), $searchTerm) ||
                                           ($student->school && str_contains(strtolower($student->school->name), $searchTerm));
                                });
                            }

                            if (request('school_id')) {
                                $filteredStudents = $filteredStudents->where('school_id', request('school_id'));
                            }

                            if (request('grade_level')) {
                                $filteredStudents = $filteredStudents->where('preferred_grade_level', request('grade_level'));
                            }

                            if (request('status') && request('status') != 'all') {
                                $filteredStudents = $filteredStudents->filter(function($student) use ($request) {
                                    $isEnrolled = \App\Models\Student::where('admission_id', $student->id)->exists();
                                    return request('status') == 'enrolled' ? $isEnrolled : !$isEnrolled;
                                });
                            }

                            if (request('sort')) {
                                $sortField = request('sort');
                                $sortOrder = request('order', 'asc');
                                $filteredStudents = $filteredStudents->sortBy(function($student) use ($sortField) {
                                    switch ($sortField) {
                                        case 'name':
                                            return strtolower($student->first_name . ' ' . $student->last_name);
                                        case 'processed_at':
                                            return $student->processed_at;
                                        default:
                                            return strtolower($student->first_name . ' ' . $student->last_name);
                                    }
                                }, SORT_REGULAR, $sortOrder === 'desc');
                            }
                        @endphp

                        @forelse($filteredStudents as $student)
                            <tr style="background-color: white;">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-user-graduate text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</h6>
                                            <small class="text-muted">ID: {{ $student->student_id }} | LRN: {{ $student->lrn }}</small>
                                            <br>
                                            <small class="text-muted">{{ $student->gender }} | {{ $student->birth_date->format('M d, Y') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">{{ $student->guardian_name }}</h6>
                                        <small class="text-muted">{{ $student->guardian_contact }}</small>
                                        <br>
                                        <small class="text-muted">{{ $student->guardian_email }}</small>
                                    </td>
                                    <td>
                                        @if($student->school)
                                            <span class="badge bg-primary">{{ $student->school->name }}</span>
                                            <br>
                                            <small class="text-muted">Grade {{ $student->preferred_grade_level }}</small>
                                            @if($student->assignedSection)
                                                <br><small class="text-success">Assigned: {{ $student->assignedSection->name }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">No School Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $student->processed_at ? $student->processed_at->format('M d, Y') : 'N/A' }}
                                        <br>
                                        <small class="text-muted">{{ $student->processed_at ? $student->processed_at->format('h:i A') : '' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $isEnrolled = \App\Models\Student::where('admission_id', $student->id)->exists();
                                        @endphp
                                        @if($isEnrolled)
                                            <span class="badge bg-success">Enrolled</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Ready for Enrollment</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @php
                                            $isEnrolled = \App\Models\Student::where('admission_id', $student->id)->exists();
                                        @endphp
                                        @if(!$isEnrolled)
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="enrollStudent({{ $student->id }})" title="Enroll Student">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        @else
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> Enrolled
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4" style="background-color: white;">
                                        <div class="text-muted">
                                            <i class="fas fa-user-graduate fa-2x mb-3"></i>
                                            <h5>No Approved Students Found</h5>
                                            @if(request('search') || request('school_id') || request('grade_level') || request('status') != 'all')
                                                <p>No students match your search or filter criteria.</p>
                                                <a href="{{ route('admin.learners.index') }}"
                                                   class="btn btn-secondary me-2">
                                                    <i class="fas fa-times me-1"></i> Clear Filters
                                                </a>
                                            @else
                                                <p>No approved students are available for enrollment.</p>
                                            @endif
                                            <a href="{{ route('admin.admissions.index') }}" class="btn btn-primary">
                                                <i class="fas fa-clipboard-list me-1"></i> View Applications
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($filteredStudents->count() > 0)
                    <div class="d-flex justify-content-center mt-3">
                        {{ $approvedStudents->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Enrollment Modal -->
        <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="enrollmentModalLabel">Enroll Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="studentInfo" class="mb-3"></div>
                        <div class="mb-3">
                            <label for="section_id" class="form-label">Select Section *</label>
                            <select class="form-select" id="section_id" name="section_id" required>
                                <option value="">Loading sections...</option>
                            </select>
                            <div class="form-text">Only sections matching the student's grade level will be shown.</div>
                        </div>
                        <div id="sectionInfo" class="mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="confirmEnrollment">Enroll Student</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
    .card-hover:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    a.text-decoration-none:hover .card-hover {
        color: inherit;
    }
    </style>
    @endpush

    @push('scripts')
    <script>
    let currentStudentId = null;

    function enrollStudent(studentId) {
        currentStudentId = studentId;

        // Fetch sections for this student
        fetch(`/admin/learners/${studentId}/sections`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update student info
                    document.getElementById('studentInfo').innerHTML = `
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-user-graduate me-1"></i> Student Information</h6>
                            <p><strong>Name:</strong> ${data.student_name}</p>
                            <p><strong>Grade Level:</strong> ${data.grade_level}</p>
                        </div>
                    `;

                    // Update sections dropdown
                    const sectionSelect = document.getElementById('section_id');
                    sectionSelect.innerHTML = '<option value="">Select a section</option>';

                    data.sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = `${section.name} (${section.student_count}/${section.capacity || '∞'} students) - ${section.adviser_name}`;
                        option.disabled = section.is_full;
                        sectionSelect.appendChild(option);
                    });

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('enrollmentModal'));
                    modal.show();
                } else {
                    alert('Error loading sections: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading sections. Please try again.');
            });
    }

    document.getElementById('confirmEnrollment').addEventListener('click', function() {
        const sectionId = document.getElementById('section_id').value;

        if (!sectionId) {
            alert('Please select a section.');
            return;
        }

        // Disable button to prevent double submission
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enrolling...';

        // Submit enrollment
        fetch(`/admin/learners/${currentStudentId}/enroll`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                section_id: sectionId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Student enrolled successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
                this.disabled = false;
                this.innerHTML = 'Enroll Student';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error enrolling student. Please try again.');
            this.disabled = false;
            this.innerHTML = 'Enroll Student';
        });
    });
    </script>
    @endpush
@endsection