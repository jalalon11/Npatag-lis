@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 py-3">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>Admin Reports Dashboard
                    </h4>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body pt-0">
                    <p class="text-muted mb-4">Generate comprehensive reports across all schools in the system. Select a report type from the options below.</p>

                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container">
                                        <i class="fas fa-school fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Schools Overview</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate comprehensive reports showing all schools, their status, admission numbers, and key metrics.</p>
                                    <a href="{{ route('admin.reports.schools-overview') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-arrow-right me-2"></i>Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container" style="background-color: rgba(40, 167, 69, 0.1);">
                                        <i class="fas fa-users fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Student Analytics</h5>
                                    <p class="card-text text-muted flex-grow-1">Analyze student data across all schools including admission trends, demographics, and performance metrics.</p>
                                    <a href="{{ route('admin.reports.student-analytics') }}" class="btn btn-success mt-3">
                                        <i class="fas fa-arrow-right me-2"></i>Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container" style="background-color: rgba(255, 193, 7, 0.1);">
                                        <i class="fas fa-user-plus fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Admission Reports</h5>
                        <p class="card-text text-muted flex-grow-1">Track admission applications, approval rates, and admission trends across all schools in the system.</p>
                        <a href="{{ route('admin.reports.enrollment-analytics') }}" class="btn btn-warning mt-3 text-white">
                                        <i class="fas fa-arrow-right me-2"></i>Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container" style="background-color: rgba(220, 53, 69, 0.1);">
                                        <i class="fas fa-user-tie fa-3x text-danger"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">User Accounts</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate reports on user accounts, roles, activity levels, and account status across the system.</p>
                                    <a href="{{ route('admin.reports.user-accounts') }}" class="btn btn-danger mt-3">
                                        <i class="fas fa-arrow-right me-2"></i>Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container" style="background-color: rgba(108, 117, 125, 0.1);">
                                        <i class="fas fa-headset fa-3x text-secondary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Support Analytics</h5>
                                    <p class="card-text text-muted flex-grow-1">Analyze support ticket trends, response times, and common issues across all schools.</p>
                                    <a href="{{ route('admin.reports.support-analytics') }}" class="btn btn-secondary mt-3">
                                        <i class="fas fa-arrow-right me-2"></i>Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container" style="background-color: rgba(23, 162, 184, 0.1);">
                                        <i class="fas fa-chart-line fa-3x text-info"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">System Usage</h5>
                                    <p class="card-text text-muted flex-grow-1">Monitor system usage patterns, login statistics, and feature adoption across all schools.</p>
                                    <a href="{{ route('admin.reports.system-usage') }}" class="btn btn-info mt-3 text-white">
                                        <i class="fas fa-arrow-right me-2"></i>Generate Report
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
</style>
@endsection