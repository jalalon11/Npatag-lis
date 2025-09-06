@extends('layouts.app')

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease-in-out;
    }
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }
    .table-hover > tbody > tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="m-0">{{ $room->name }}</h2>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Rooms
                </a>
            </div>
            <p class="text-muted mb-0">
                Grade {{ $room->grade_level }} • {{ $room->school_year }}
                <span class="badge bg-{{ $room->status == 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $room->status == 'active' ? 'success' : 'secondary' }} ms-2">
                    {{ ucfirst($room->status) }}
                </span>
            </p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-door-open text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Room</h6>
                            <h4 class="mb-0 fw-bold">{{ $room->name }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-graduation-cap text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Grade Level</h6>
                            <h4 class="mb-0 fw-bold">Grade {{ $room->grade_level }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-calendar-alt text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">School Year</h6>
                            <h4 class="mb-0 fw-bold">{{ $room->school_year }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Student Limit</h6>
                            <h4 class="mb-0 fw-bold">{{ $room->student_limit ?? 'No Limit' }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Adviser -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Room Adviser
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changeAdviserModal">
                        <i class="fas fa-edit me-1"></i> Change Adviser
                    </button>
                </div>
                <div class="card-body">
                    @if($room->adviser)
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-user-tie text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $room->adviser->name }}</h5>
                                <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>{{ $room->adviser->email }}</p>
                                <p class="text-muted mb-0"><i class="fas fa-school me-2"></i>{{ $room->adviser->school->name ?? 'No School' }}</p>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-primary bg-opacity-10 text-primary">Adviser</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="bg-light rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-user-slash fa-2x text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Adviser Assigned</h5>
                            <p class="text-muted mb-4">This room doesn't have an assigned adviser yet.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changeAdviserModal">
                                <i class="fas fa-plus me-1"></i> Assign Adviser
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Student and Subjects Summary -->
    <div class="row mb-4">
        <!-- Student Summary -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-users text-success me-2"></i>Students
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-users text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1 fw-bold">{{ $room->students_count ?? 0 }}</h3>
                            @if($room->student_limit)
                                <p class="text-muted mb-1">
                                    <span class="fw-bold">{{ $room->students_count ?? 0 }}</span> of 
                                    <span class="fw-bold">{{ $room->student_limit }}</span> students enrolled
                                </p>
                                @php
                                    $percentage = $room->student_limit > 0 ? (($room->students_count ?? 0) / $room->student_limit) * 100 : 0;
                                    $percentage = min($percentage, 100);
                                @endphp
                                <div class="progress mb-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" 
                                         aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="small text-muted mb-0">{{ number_format($percentage, 0) }}% of capacity</p>
                            @else
                                <p class="text-muted mb-0">No student limit set</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Summary -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-book text-info me-2"></i>Subjects
                        </h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                            <i class="fas fa-plus me-1"></i> Assign Subject
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-book text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1 fw-bold">{{ $room->subjects->count() }}</h3>
                            <p class="text-muted mb-2">Subjects assigned to this room</p>
                            @if($room->subjects->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($room->subjects->take(3) as $subject)
                                        <span class="badge bg-light text-dark border">{{ $subject->name }}</span>
                                    @endforeach
                                    @if($room->subjects->count() > 3)
                                        <span class="badge bg-light text-muted border">+{{ $room->subjects->count() - 3 }} more</span>
                                    @endif
                                </div>
                            @else
                                <p class="text-muted mb-0">No subjects assigned yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Statistics -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-chart-pie text-primary me-2"></i>Enrollment Statistics
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Male Students -->
                <div class="col-md-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-male text-primary fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $room->male_students_count ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Male Students</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Female Students -->
                <div class="col-md-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-female text-danger fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $room->female_students_count ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Female Students</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Active Students -->
                <div class="col-md-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-check-circle text-success fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $room->active_students_count ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Active Students</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Inactive Students -->
                <div class="col-md-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-exclamation-circle text-warning fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $room->inactive_students_count ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Inactive Students</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gender Distribution -->
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">Gender Distribution</h6>
                    <div class="d-flex">
                        <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                            <i class="fas fa-male me-1"></i> Male: {{ $room->male_students_count ?? 0 }}
                        </span>
                        <span class="badge bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-female me-1"></i> Female: {{ $room->female_students_count ?? 0 }}
                        </span>
                    </div>
                </div>
                <div class="progress" style="height: 8px;">
                    @php
                        $total = ($room->male_students_count ?? 0) + ($room->female_students_count ?? 0);
                        $malePercent = $total > 0 ? (($room->male_students_count ?? 0) / $total) * 100 : 0;
                        $femalePercent = $total > 0 ? (($room->female_students_count ?? 0) / $total) * 100 : 0;
                    @endphp
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $malePercent }}%" 
                         aria-valuenow="{{ $malePercent }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $total > 0 ? number_format($malePercent, 0) : 0 }}%
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $femalePercent }}%" 
                         aria-valuenow="{{ $femalePercent }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $total > 0 ? number_format($femalePercent, 0) : 0 }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-primary w-100 h-100 text-start p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Manage Students</h6>
                                <p class="small text-muted mb-0">View and manage enrolled students</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100 h-100 text-start p-3" 
                            data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-book text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Manage Subjects</h6>
                                <p class="small text-muted mb-0">Assign or update subjects</p>
                            </div>
                        </div>
                    </button>
                </div>
                
                <div class="col-md-3">
                    <a href="{{ route('admin.rooms.edit', $room->id) }}" 
                       class="btn btn-outline-primary w-100 h-100 text-start p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="fas fa-edit text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Edit Room</h6>
                                <p class="small text-muted mb-0">Update room details</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-3">
                    <form action="{{ route('admin.rooms.toggle-status', $room->id) }}" method="POST" class="h-100">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-{{ $room->status == 'active' ? 'secondary' : 'success' }} w-100 h-100 text-start p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $room->status == 'active' ? 'secondary' : 'success' }}-bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-{{ $room->status == 'active' ? 'pause' : 'play' }}-circle text-{{ $room->status == 'active' ? 'secondary' : 'success' }}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $room->status == 'active' ? 'Deactivate' : 'Activate' }} Room</h6>
                                    <p class="small text-muted mb-0">{{ $room->status == 'active' ? 'Temporarily disable this room' : 'Activate this room' }}</p>
                                </div>
                            </div>
                        </button>
                    </form>
                </div>
                
                <div class="col-12 mt-3">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-outline-danger" 
                                data-bs-toggle="modal" data-bs-target="#deleteRoomModal">
                            <i class="fas fa-trash me-2"></i> Delete Room
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Subjects -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-book text-primary me-2"></i>Assigned Subjects
            </h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                <i class="fas fa-plus me-1"></i> Assign Subject
            </button>
        </div>
        <div class="card-body p-0">
            @if($room->subjects && $room->subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Subject</th>
                                <th class="py-3">Teacher</th>
                                <th class="py-3">Schedule</th>
                                <th class="text-center py-3">Status</th>
                                <th class="text-end pe-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($room->subjects as $subject)
                                <tr class="border-bottom">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $subject->name }}</h6>
                                                <small class="text-muted">{{ $subject->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subject->pivot->teacher)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="fas fa-user-tie text-info"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $subject->pivot->teacher->name }}</div>
                                                    <small class="text-muted">{{ $subject->pivot->teacher->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No teacher assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subject->pivot->schedule)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="fas fa-clock text-warning"></i>
                                                </div>
                                                <span>{{ $subject->pivot->schedule }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">No schedule set</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $status = $subject->pivot->status ?? 'active';
                                            $statusColors = [
                                                'active' => ['bg' => 'success', 'text' => 'success', 'icon' => 'check-circle'],
                                                'inactive' => ['bg' => 'secondary', 'text' => 'secondary', 'icon' => 'pause-circle'],
                                                'pending' => ['bg' => 'warning', 'text' => 'warning', 'icon' => 'clock']
                                            ];
                                            $statusConfig = $statusColors[$status] ?? $statusColors['inactive'];
                                        @endphp
                                        <span class="badge bg-{{ $statusConfig['bg'] }}-bg-opacity-10 text-{{ $statusConfig['text'] }} px-3 py-2">
                                            <i class="fas fa-{{ $statusConfig['icon'] }} me-1"></i>
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end">
                                            <a href="#" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="tooltip" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="#" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to remove this subject from the room?')"
                                                        data-bs-toggle="tooltip" 
                                                        title="Remove Subject">
                                                    <i class="fas fa-times"></i>
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
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-book-open fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">No Subjects Assigned</h5>
                    <p class="text-muted mb-4">Start by assigning subjects to this room to manage the curriculum.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                        <i class="fas fa-plus me-1"></i> Assign First Subject
                    </button>
                </div>
            @endif
        </div>
    </div>
        </div>
    </div>
</div>

<!-- Assign Subjects Modal -->
<div class="modal fade" id="assignSubjectsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-book-medical text-primary me-2"></i>Assign Subjects to {{ $room->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.rooms.assign-subjects', $room->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>Instructions:</strong> Add one or more subjects to this room. For each subject, select the subject, assign a teacher, and optionally set a schedule.
                            </div>
                        </div>
                    </div>
                    
                    <div id="subject-assignments">
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Subject <span class="text-danger">*</span></label>
                                        <select name="subjects[0][subject_id]" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select Subject</option>
                                            @foreach($availableSubjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Teacher <span class="text-danger">*</span></label>
                                        <select name="subjects[0][teacher_id]" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-medium">Schedule (Optional)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                                            <input type="text" name="subjects[0][schedule]" class="form-control" 
                                                   placeholder="e.g., Mon-Wed-Fri 8:00-9:00 AM">
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-subject" style="display: none;">
                                            <i class="fas fa-trash me-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" id="add-subject">
                        <i class="fas fa-plus me-1"></i> Add Another Subject
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Room Modal -->
<div class="modal fade" id="deleteRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Room
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-trash-alt fa-2x text-danger"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Are you sure you want to delete this room?</h5>
                    <p class="text-muted">This action cannot be undone. All associated data will be permanently removed.</p>
                    
                    <div class="card border-warning mb-3">
                        <div class="card-body">
                            <h6 class="fw-bold text-warning mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Room Details</h6>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-2"><strong>Name:</strong> {{ $room->name }}</p>
                                    <p class="mb-2"><strong>Grade:</strong> {{ $room->grade_level }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2"><strong>Students:</strong> {{ $room->students_count ?? 0 }}</p>
                                    <p class="mb-0"><strong>Subjects:</strong> {{ $room->subjects_count ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt me-1"></i> Delete Room
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Adviser Modal -->
<div class="modal fade" id="changeAdviserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-edit text-primary me-2"></i>{{ $room->adviser ? 'Change' : 'Assign' }} Room Adviser
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.rooms.change-adviser', $room->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    @if($room->adviser)
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Current Adviser:</strong> {{ $room->adviser->name }}
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="new_adviser_id" class="form-label fw-medium">Select New Adviser <span class="text-danger">*</span></label>
                        <select name="adviser_id" id="new_adviser_id" class="form-select form-select-sm" required>
                            <option value="" selected disabled>Select an adviser...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $room->adviser_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->school->name ?? 'No School' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Search and select the new room adviser</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> {{ $room->adviser ? 'Update' : 'Assign' }} Adviser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-card {
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }
    .subject-row {
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }
    .subject-row:hover {
        background-color: #f1f3f5;
    }
    .progress {
        height: 10px;
        border-radius: 5px;
        background-color: #e9ecef;
    }
    .progress-bar {
        border-radius: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Subject assignment functionality
        let subjectIndex = 1;
        const assignedSubjects = [];

        // Add subject row
        $('#add-subject').click(function() {
            const newRow = $('.subject-row').first().clone();
            
            // Update names and clear values
            newRow.find('select, input').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace('[0]', '[' + subjectIndex + ']'));
                    $(this).val('');
                    $(this).removeClass('is-invalid');
                }
            });
            
            // Show remove button and update UI
            newRow.find('.remove-subject').removeClass('d-none').show();
            newRow.find('.form-select, .form-control').removeClass('is-valid is-invalid');
            
            $('#subject-assignments').append(newRow);
            subjectIndex++;
            
            updateRemoveButtons();
            
            // Scroll to the new row
            $('html, body').animate({
                scrollTop: newRow.offset().top - 100
            }, 500);
        });

        // Remove subject row
        $(document).on('click', '.remove-subject', function() {
            const row = $(this).closest('.subject-row');
            row.addClass('animate__animated animate__fadeOut');
            
            setTimeout(() => {
                row.remove();
                updateRemoveButtons();
            }, 300);
        });

        // Update remove button visibility
        function updateRemoveButtons() {
            const rows = $('.subject-row');
            if (rows.length > 1) {
                rows.find('.remove-subject').show();
            } else {
                rows.find('.remove-subject').hide();
            }
            
            // Update the count in the UI if needed
            $('.subject-count').text(rows.length);
        }

        // Prevent duplicate subject selection
        $(document).on('change', '.subject-select', function() {
            const selectedSubjects = [];
            const currentSelect = $(this);
            
            $('.subject-select').each(function() {
                const val = $(this).val();
                if (val) {
                    if (selectedSubjects.includes(val)) {
                        // This is a duplicate
                        if ($(this).is(currentSelect)) {
                            // Show error for the current select
                            $(this).addClass('is-invalid');
                            // Show error message
                            let errorDiv = $(this).siblings('.invalid-feedback');
                            if (errorDiv.length === 0) {
                                errorDiv = $('<div class="invalid-feedback">This subject has already been selected.</div>');
                                $(this).after(errorDiv);
                            }
                            errorDiv.show();
                        }
                    } else {
                        selectedSubjects.push(val);
                        $(this).removeClass('is-invalid');
                        $(this).siblings('.invalid-feedback').hide();
                    }
                }
            });
        });
        
        // Form validation
        $('form').on('submit', function(e) {
            let isValid = true;
            
            // Check for duplicate subjects
            const selectedSubjects = [];
            $('.subject-select').each(function() {
                const val = $(this).val();
                if (val) {
                    if (selectedSubjects.includes(val)) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        let errorDiv = $(this).siblings('.invalid-feedback');
                        if (errorDiv.length === 0) {
                            errorDiv = $('<div class="invalid-feedback">This subject has already been selected.</div>');
                            $(this).after(errorDiv);
                        }
                        errorDiv.show();
                    } else {
                        selectedSubjects.push(val);
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
                
                // Show toast notification
                const toast = new bootstrap.Toast(document.getElementById('errorToast'));
                toast.show();
            }
        });
        
        // Initialize select2 for better select controls
        if ($.fn.select2) {
            $('.teacher-select, .subject-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: $(this).data('placeholder') || 'Select an option',
                allowClear: true
            });
        }
        
        // Toggle form based on entry type
        $('input[name="entry_type"]').change(function() {
            const isBatch = $(this).val() === 'batch';
            $('#single_entry_form').toggle(!isBatch);
            $('#batch_entry_form').toggle(isBatch);
            $('#is_batch_input').val(isBatch ? '1' : '0');
            
            // Toggle MAPEH components visibility
            if (!isBatch) {
                toggleMapehComponents();
            } else {
                $('#mapeh_components').hide();
            }
        });
        
        // Toggle MAPEH components
        $('#is_mapeh').change(function() {
            toggleMapehComponents();
        });
        
        function toggleMapehComponents() {
            if ($('#is_mapeh').is(':checked')) {
                $('#mapeh_components').slideDown();
            } else {
                $('#mapeh_components').slideUp();
            }
        }
        
        // Calculate total weight for MAPEH components
        $('.component-weight').on('input', function() {
            let total = 0;
            $('.component-weight').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            
            $('#total_weight').text(total.toFixed(0));
            
            if (Math.abs(total - 100) > 0.1) {
                $('#weight_warning').slideDown();
                $('#submitBtn').prop('disabled', true);
            } else {
                $('#weight_warning').slideUp();
                $('#submitBtn').prop('disabled', false);
            }
        });
    });
</script>
@endpush