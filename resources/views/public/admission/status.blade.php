<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Application Status - Student Admission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-search"></i> Check Application Status
                        </h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (isset($admission))
                            <!-- Application Found -->
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> Application found!
                            </div>
                            
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Application Details</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Application ID:</strong> {{ $admission->id }}</p>
                                            <p><strong>Student Name:</strong> {{ $admission->first_name }} {{ $admission->last_name }}</p>
                                            <p><strong>Grade Level:</strong> {{ $admission->preferred_grade_level }}</p>
                                            <p><strong>School:</strong> {{ $admission->school->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Status:</strong> 
                                                @if($admission->status == 'pending')
                                                    <span class="badge bg-warning">Pending Review</span>
                                                @elseif($admission->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($admission->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($admission->status) }}</span>
                                                @endif
                                            </p>
                                            <p><strong>Submitted:</strong> {{ $admission->created_at->format('M d, Y h:i A') }}</p>
                                            @if($admission->updated_at != $admission->created_at)
                                                <p><strong>Last Updated:</strong> {{ $admission->updated_at->format('M d, Y h:i A') }}</p>
                                            @endif
                                            @if($admission->section)
                                                <p><strong>Assigned Section:</strong> {{ $admission->section->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($admission->status == 'approved')
                                        <div class="alert alert-success mt-3">
                                            <i class="fas fa-graduation-cap"></i>
                                            <strong>Congratulations!</strong> Your application has been approved. 
                                            Please contact the school for enrollment procedures.
                                        </div>
                                    @elseif($admission->status == 'rejected')
                                        <div class="alert alert-danger mt-3">
                                            <i class="fas fa-times-circle"></i>
                                            <strong>Application Status:</strong> Unfortunately, your application was not approved at this time.
                                            @if($admission->rejection_reason)
                                                <br><strong>Reason:</strong> {{ $admission->rejection_reason }}
                                            @endif
                                        </div>
                                    @else
                                        <div class="alert alert-info mt-3">
                                            <i class="fas fa-clock"></i>
                                            <strong>Under Review:</strong> Your application is currently being reviewed by our admissions team. 
                                            We will contact you within 3-5 business days.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Search Form -->
                        <form action="{{ route('admission.status') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="application_id" class="form-label">Application ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="application_id" name="application_id" 
                                               value="{{ old('application_id', request('application_id')) }}" 
                                               placeholder="Enter your application ID" required>
                                        <small class="text-muted">The ID provided when you submitted your application</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Student Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="{{ old('last_name', request('last_name')) }}" 
                                               placeholder="Enter student's last name" required>
                                        <small class="text-muted">For verification purposes</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search"></i> Check Status
                                </button>
                                <a href="{{ route('admission.apply') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-plus"></i> New Application
                                </a>
                            </div>
                        </form>
                        
                        <div class="mt-4">
                            <div class="alert alert-light">
                                <h6><i class="fas fa-info-circle"></i> Need Help?</h6>
                                <p class="mb-0">
                                    If you can't find your application or need assistance, please contact the school office.
                                    Make sure to have your Application ID and student information ready.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>