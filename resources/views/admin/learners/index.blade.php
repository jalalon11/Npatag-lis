@extends('layouts.app')

@section('title', 'Learners Record')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Learners Record</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-clipboard-list"></i> View Applications
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('admin.learners.enrolled') }}" class="text-decoration-none">
                <div class="card border-left-info shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Enrolled Students
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['enrolled_students'] }}</div>
                                <small class="text-muted">Click to view all</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Enrollment
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_enrollment'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Students</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.learners.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="school_id" class="form-label">School</label>
                    <select class="form-select" id="school_id" name="school_id">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="grade_level" class="form-label">Grade Level</label>
                    <select class="form-select" id="grade_level" name="grade_level">
                        <option value="">All Grades</option>
                        @foreach($gradeLevels as $grade)
                            <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Name, Student ID, LRN...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Approved Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Approved Students Ready for Enrollment</h6>
        </div>
        <div class="card-body">
            @if($approvedStudents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student Info</th>
                                <th>Guardian</th>
                                <th>School & Grade</th>
                                <th>Approval Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedStudents as $student)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</div>
                                        <small class="text-muted">
                                            ID: {{ $student->student_id }} | LRN: {{ $student->lrn }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            {{ $student->gender }} | {{ $student->birth_date->format('M d, Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $student->guardian_name }}</div>
                                        <small class="text-muted">{{ $student->guardian_contact }}</small>
                                        <br>
                                        <small class="text-muted">{{ $student->guardian_email }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $student->school->name ?? 'N/A' }}</div>
                                        <small class="text-muted">Grade {{ $student->preferred_grade_level }}</small>
                                        @if($student->assignedSection)
                                            <br><small class="text-success">Assigned: {{ $student->assignedSection->name }}</small>
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
                                    <td>
                                        @php
                                            $isEnrolled = \App\Models\Student::where('admission_id', $student->id)->exists();
                                        @endphp
                                        @if(!$isEnrolled)
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="enrollStudent({{ $student->id }})" title="Enroll Student">
                                                <i class="fas fa-user-plus"></i> Enroll
                                            </button>
                                        @else
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> Enrolled
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $approvedStudents->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No Approved Students Found</h5>
                    <p class="text-muted">No approved students match your current filters.</p>
                    <a href="{{ route('admin.admissions.index') }}" class="btn btn-primary">
                        <i class="fas fa-clipboard-list"></i> View Applications
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Enrollment Modal -->
<div class="modal fade" id="enrollmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enroll Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

@endsection

@push('styles')
<style>
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

a.text-decoration-none:hover .hover-card {
    color: inherit;
}

a.text-decoration-none:hover .text-gray-800 {
    color: #5a5c69 !important;
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
                        <strong>${data.student_name}</strong> - Grade ${data.grade_level}
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