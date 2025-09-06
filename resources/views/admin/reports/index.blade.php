@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow bg-white p-3 border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 py-3">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>Admin Reports Dashboard
                    </h4>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body bg-white pt-0">
                    <p class="text-muted mb-4 mt-4">Generate comprehensive reports across all schools in the system. Select a report type from the options below.</p>

                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container">
                                        <i class="fas fa-school fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Schools Overview</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate comprehensive reports showing all schools, their status, admission numbers, and key metrics.</p>
                                    <a href="{{ route('admin.school.index') }}" class="btn btn-primary mt-3">
                                        Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container" style="background-color: rgba(25, 135, 84, 0.1);">
                                        <i class="fas fa-chart-bar fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Consolidated Grades</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate consolidated grading sheets by section and quarter across all schools.</p>
                                    <a href="{{ route('admin.reports.consolidated-grades') }}" class="btn btn-success mt-3">
                                        Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container" style="background-color: rgba(13, 202, 240, 0.1);">
                                        <i class="fas fa-calendar-check fa-3x text-info"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Attendance Summary</h5>
                                    <p class="card-text text-muted flex-grow-1">Comprehensive attendance analysis and reports across all schools.</p>
                                    <a href="{{ route('admin.reports.attendance-summary') }}" class="btn btn-info text-white mt-3">
                                        Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="fas fa-info-circle me-3 fs-4"></i>
                                <div>
                                    <strong>Report Generation:</strong> All reports can be filtered by date range, school, or other criteria. Reports are generated in real-time and can be exported to various formats including PDF and Excel.
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

    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: var(--border-radius);
        overflow: hidden;
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