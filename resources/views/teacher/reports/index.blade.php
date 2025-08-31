@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 py-3">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>Reports Dashboard
                    </h4>
                </div>
                <div class="card-body bg-white pt-0">
                    <p class="text-muted mb-4 mt-4">Select a report type from the options below to generate detailed student performance records.</p>

                    <div class="row">
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container">
                                        <i class="fas fa-table fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Class Record</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate a comprehensive class record showing detailed student grades by component.</p>
                                    <a href="{{ route('teacher.reports.class-record') }}" class="btn btn-primary mt-3">
                                        Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container">
                                        <i class="fas fa-file-alt fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Grade Slips</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate grade slips for students in your advisory sections.</p>
                                    <a href="{{ route('teacher.reports.grade-slips') }}" class="btn btn-primary text-white mt-3">
                                        Generate Grade Slips
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container">
                                        <i class="fas fa-award fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Academic Excellence Certificates</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate certificates for students who have achieved academic excellence.</p>
                                    <a href="{{ route('teacher.reports.certificates.index') }}" class="btn btn-primary mt-3">
                                        Generate Certificates
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container">
                                        <i class="fas fa-user-graduate fa-3x text-info"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Form 138 (Grade Card)</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate individual student progress reports with detailed analysis.</p>
                                    <a href="#" class="btn btn-outline-secondary mt-3 disabled">
                                        <i class="fas fa-clock me-2"></i>Coming Soon
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --border-radius: 12px;
        --border-radius-pill: 50px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --primary-color: #0d6efd;
        --text-muted: #6c757d;
        --bg-light: #f8f9fa;
    }
    .btn {
    border-radius: var(--border-radius-pill);
    padding: 0.5rem 1.25rem;
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .report-icon-container {
        background-color: rgba(13, 110, 253, 0.1);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .card:nth-child(2) .report-icon-container {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .card:nth-child(3) .report-icon-container {
        background-color: rgba(13, 202, 240, 0.1);
    }
</style>
@endsection