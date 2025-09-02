@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Admin Module Help Center</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <div class="mb-4">
                                <i class="fas fa-user-cog text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="fw-bold mb-3">Welcome to the Admin Module Help Center</h2>
                            <p class="text-muted mx-auto" style="max-width: 700px;">
                                Here you'll find comprehensive tutorials and guides to help you navigate and use the Admin Module effectively.
                                As an Administrator, you have access to powerful tools for managing the entire grading system.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-success text-white">
                                            <i class="fas fa-school"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">Schools Management</h5>
                                    </div>
                                    <p class="card-text">Learn how to create, manage, and monitor schools in the system.</p>
                                    <div class="mt-3">
                                        <span class="badge bg-light text-dark">10 min read</span>
                                    </div>
                                    <a href="{{ route('admin.help.tutorial', 'schools') }}" class="btn btn-success w-100 mt-3">
                                        <i class="fas fa-book-open me-2"></i> View Tutorial
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-primary text-white">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">Teacher Admins</h5>
                                    </div>
                                    <p class="card-text">Learn how to create and manage teacher admin accounts for schools.</p>
                                    <div class="mt-3">
                                        <span class="badge bg-light text-dark">8 min read</span>
                                    </div>
                                    <a href="{{ route('admin.help.tutorial', 'teacher-admins') }}" class="btn btn-primary w-100 mt-3">
                                        <i class="fas fa-book-open me-2"></i> View Tutorial
                                    </a>
                                </div>
                            </div>
                        </div>


                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-secondary text-white">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">Accounts</h5>
                                    </div>
                                    <p class="card-text">Learn how to manage user accounts and permissions across the system.</p>
                                    <div class="mt-3">
                                        <span class="badge bg-light text-dark">8 min read</span>
                                    </div>
                                    <a href="{{ route('admin.help.tutorial', 'accounts') }}" class="btn btn-secondary w-100 mt-3">
                                        <i class="fas fa-book-open me-2"></i> View Tutorial
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-danger text-white">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">Announcements</h5>
                                    </div>
                                    <p class="card-text">Learn how to create and manage system-wide announcements.</p>
                                    <div class="mt-3">
                                        <span class="badge bg-light text-dark">6 min read</span>
                                    </div>
                                    <a href="{{ route('admin.help.tutorial', 'announcements') }}" class="btn btn-danger w-100 mt-3">
                                        <i class="fas fa-book-open me-2"></i> View Tutorial
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-success text-white">
                                            <i class="fas fa-folder-open"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">Resources</h5>
                                    </div>
                                    <p class="card-text">Learn how to manage system resources and resource categories.</p>
                                    <div class="mt-3">
                                        <span class="badge bg-light text-dark">7 min read</span>
                                    </div>
                                    <a href="{{ route('admin.help.tutorial', 'resources') }}" class="btn btn-success w-100 mt-3">
                                        <i class="fas fa-book-open me-2"></i> View Tutorial
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-info text-white">
                                            <i class="fas fa-headset"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">Support</h5>
                                    </div>
                                    <p class="card-text">Learn how to manage support tickets and help users with their issues.</p>
                                    <div class="mt-3">
                                        <span class="badge bg-light text-dark">5 min read</span>
                                    </div>
                                    <a href="{{ route('admin.help.tutorial', 'support') }}" class="btn btn-info w-100 mt-3 text-white">
                                        <i class="fas fa-book-open me-2"></i> View Tutorial
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-dark text-white">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">FAQ</h5>
                                    </div>
                                    <p class="card-text">Find answers to frequently asked questions about the Admin Module features.</p>
                                    <div class="mt-3">
                                        <span class="badge bg-light text-dark">8 min read</span>
                                    </div>
                                    <a href="{{ route('admin.help.tutorial', 'faq') }}" class="btn btn-dark w-100 mt-3">
                                        <i class="fas fa-question me-2"></i> View FAQs
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
                                    <strong>Need more help?</strong> If you can't find what you're looking for in these tutorials, please use the Support feature to manage support tickets or contact other administrators.
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
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .icon-circle i {
        font-size: 1.5rem;
    }

    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .card-body {
        padding: 1.5rem;
    }

    .badge {
        padding: 0.5rem 0.8rem;
        font-weight: 500;
        border-radius: 30px;
    }
</style>
@endsection