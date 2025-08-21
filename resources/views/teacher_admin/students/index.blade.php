@extends('layouts.teacher_admin')

@section('title', 'Student Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Management</h1>
        <div>
            <button type="button" class="btn btn-info btn-sm" onclick="loadStatistics()">
                <i class="fas fa-chart-bar"></i> Statistics
            </button>
        </div>
    </div>

    <!-- Statistics Cards (Initially Hidden) -->
    <div id="statisticsCards" class="row mb-4" style="display: none;">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="inactiveStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Male</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="maleStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mars fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Female</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="femaleStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-venus fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('teacher-admin.students.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Students</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="grade_level">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-control">
                            <option value="">All Grades</option>
                            @foreach($gradeLevels as $grade)
                                <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                    Grade {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="section_id">Section</label>
                        <select name="section_id" id="section_id" class="form-control">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }} (Grade {{ $section->grade_level }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="school_year">School Year</label>
                        <select name="school_year" id="school_year" class="form-control">
                            <option value="">All Years</option>
                            @foreach($schoolYears as $year)
                                <option value="{{ $year }}" {{ request('school_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Search by name, student ID, or LRN" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('teacher-admin.students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Enrolled Students</h6>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>LRN</th>
                                <th>Section</th>
                                <th>Gender</th>
                                <th>Guardian</th>
                                <th>School Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->student_id }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ $student->lrn }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $student->section->name }} (Grade {{ $student->section->grade_level }})
                                        </span>
                                    </td>
                                    <td>{{ $student->gender }}</td>
                                    <td>
                                        <div class="small">
                                            <strong>{{ $student->guardian_name }}</strong><br>
                                            {{ $student->guardian_contact }}
                                            @if($student->guardian_email)
                                                <br>{{ $student->guardian_email }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $student->school_year ?? 'N/A' }}</td>
                                    <td>
                                        @if($student->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-warning">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('teacher-admin.students.show', $student) }}" 
                                               class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher-admin.students.edit', $student) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('teacher-admin.students.toggle-status', $student) }}" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-{{ $student->is_active ? 'secondary' : 'success' }} btn-sm"
                                                        title="{{ $student->is_active ? 'Deactivate' : 'Activate' }}"
                                                        onclick="return confirm('Are you sure you want to {{ $student->is_active ? 'deactivate' : 'activate' }} this student?')">
                                                    <i class="fas fa-{{ $student->is_active ? 'user-times' : 'user-check' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} results
                    </div>
                    <div>
                        {{ $students->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No students found</h5>
                    <p class="text-gray-500">No enrolled students match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function loadStatistics() {
    fetch('{{ route("teacher-admin.students.statistics") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalStudents').textContent = data.total;
            document.getElementById('activeStudents').textContent = data.active;
            document.getElementById('inactiveStudents').textContent = data.inactive;
            document.getElementById('maleStudents').textContent = data.male;
            document.getElementById('femaleStudents').textContent = data.female;
            
            // Show statistics cards
            document.getElementById('statisticsCards').style.display = 'flex';
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            alert('Error loading statistics. Please try again.');
        });
}
</script>
@endsection