<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted - Student Admission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        
                        <h2 class="text-success mb-3">Application Submitted Successfully!</h2>
                        
                        <p class="lead mb-4">
                            Thank you for submitting your admission application. Your application has been received and is now under review.
                        </p>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Application Details</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Application ID:</strong> {{ $admission->id }}</p>
                                        <p><strong>Student Name:</strong> {{ $admission->first_name }} {{ $admission->last_name }}</p>
                                        <p><strong>Grade Level:</strong> {{ $admission->preferred_grade_level }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>School:</strong> {{ $admission->school->name }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-warning">{{ ucfirst($admission->status) }}</span>
                                        </p>
                                        <p><strong>Submitted:</strong> {{ $admission->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>What's Next?</strong><br>
                            Our admissions team will review your application and contact you within 3-5 business days. 
                            You can check your application status anytime using the link below.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="{{ route('admission.status.form') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-search"></i> Check Application Status
                            </a>
                            <a href="{{ route('admission.apply') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-plus"></i> Submit Another Application
                            </a>
                        </div>
                        
                        <div class="mt-4">
                            <small class="text-muted">
                                Please save your Application ID ({{ $admission->id }}) for future reference.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>