@extends('layouts.app')

@push('styles')
<style>
    .success-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem 0;
        display: flex;
        align-items: center;
    }

    .success-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    .success-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 3rem 2rem;
    }

    .success-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }

    .success-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .success-subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .success-body {
        padding: 2rem;
    }

    .info-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border-left: 4px solid #4361ee;
    }

    .info-title {
        font-weight: 600;
        color: #4361ee;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .enrollment-details {
        background: #e3f2fd;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem 0;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #bbdefb;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 500;
        color: #1565c0;
    }

    .detail-value {
        color: #0d47a1;
        font-weight: 600;
    }

    .btn-status {
        background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        margin: 1rem 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-status:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-home {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        margin: 1rem 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(108, 117, 125, 0.3);
        color: white;
        text-decoration: none;
    }

    .timeline {
        margin: 2rem 0;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        margin: 1rem 0;
        padding: 0.5rem;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .timeline-icon.completed {
        background: #28a745;
        color: white;
    }

    .timeline-icon.pending {
        background: #ffc107;
        color: #212529;
    }

    .timeline-icon.future {
        background: #e9ecef;
        color: #6c757d;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .timeline-desc {
        color: #6c757d;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .success-container {
            padding: 1rem;
        }
        
        .success-body {
            padding: 1.5rem;
        }
        
        .success-header {
            padding: 2rem 1.5rem;
        }
        
        .success-title {
            font-size: 1.5rem;
        }
        
        .btn-status, .btn-home {
            display: block;
            width: 100%;
            margin: 0.5rem 0;
        }
    }
</style>
@endpush

@section('content')
<div class="success-container">
    <div class="container">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="success-title">Application Submitted!</h1>
                <p class="success-subtitle">Your enrollment application has been sent for review</p>
            </div>
            
            <div class="success-body">
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-info-circle"></i>
                        What Happens Next?
                    </div>
                    <p class="mb-0">Your application has been submitted to the school's admissions team for review. You will be contacted once a decision has been made.</p>
                </div>

                <div class="enrollment-details">
                    <h5 class="text-center mb-3"><i class="fas fa-file-alt"></i> Application Details</h5>
                    <div class="detail-row">
                        <span class="detail-label">Application ID:</span>
                        <span class="detail-value">#{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Student Name:</span>
                        <span class="detail-value">{{ $enrollment->first_name }} {{ $enrollment->last_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">School:</span>
                        <span class="detail-value">{{ $enrollment->school->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Submitted:</span>
                        <span class="detail-value">{{ $enrollment->application_date->format('M d, Y \\a\\t g:i A') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock"></i> Pending Review
                            </span>
                        </span>
                    </div>
                </div>

                <div class="timeline">
                    <h5 class="text-center mb-3"><i class="fas fa-tasks"></i> Application Process</h5>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon completed">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Application Submitted</div>
                            <div class="timeline-desc">Your application has been successfully submitted</div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon pending">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Under Review</div>
                            <div class="timeline-desc">Admissions team is reviewing your application</div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon future">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Decision & Notification</div>
                            <div class="timeline-desc">You will be contacted with the admission decision</div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon future">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Enrollment Complete</div>
                            <div class="timeline-desc">Welcome to {{ $enrollment->school->name }}!</div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-phone"></i>
                        Need Help?
                    </div>
                    <p class="mb-2">If you have any questions about your application, please contact the school directly.</p>
                    <p class="mb-0"><strong>Keep your Application ID (#{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}) for reference.</strong></p>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('enrollment.status-form') }}" class="btn-status">
                        <i class="fas fa-search me-2"></i>
                        Check Application Status
                    </a>
                    <a href="{{ route('home') }}" class="btn-home">
                        <i class="fas fa-home me-2"></i>
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection