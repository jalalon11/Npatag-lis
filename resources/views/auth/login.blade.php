@extends('layouts.app')

@push('styles')
<style>
    /* Announcement Modal Styling */
    .announcement-modal .modal-content {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .announcement-modal .modal-header {
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .announcement-modal .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
        color: white;
    }

    .announcement-modal .modal-body {
        padding: 1.5rem;
        font-size: 1rem;
        line-height: 1.6;
        max-height: 70vh;
        overflow-y: auto;
    }

    .announcement-modal .modal-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
        background-color: #f8f9fa;
        padding: 0.75rem 1.5rem;
    }

    .announcement-content {
        white-space: pre-line;
    }

    .announcement-content p {
        margin-bottom: 1rem;
    }

    .announcement-content h4 {
        margin-bottom: 1.25rem;
        color: #1C2833;
    }

    .announcement-content h5 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: #2E4053;
        font-weight: 600;
    }

    .announcement-content ul,
    .announcement-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .announcement-content li {
        margin-bottom: 0.5rem;
    }

    .announcement-content strong {
        font-weight: 600;
    }

    .announcement-content .d-flex {
        margin-bottom: 0.5rem;
    }

    .announcement-content .fas {
        font-size: 0.9rem;
    }

    .announcement-content ul {
        margin-top: 0.5rem;
    }

    /* Special styling for checkmark lists */
    .announcement-content .ps-3 {
        padding-left: 0.75rem !important;
    }

    .announcement-content .ps-3 i {
        width: 16px;
        text-align: center;
    }

    /* Link styling */
    .announcement-content a {
        color: #2E4053;
        text-decoration: none;
        word-break: break-word;
        font-weight: 500;
    }

    .announcement-content a:hover {
        text-decoration: underline;
        color: #1C2833;
    }

    .announcement-modal .btn-close {
        color: white;
        opacity: 0.8;
        filter: brightness(0) invert(1);
    }

    .announcement-modal .btn-close:hover {
        opacity: 1;
    }

    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #F4F6F6 0%, #D5DBDB 100%);
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .login-container::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(28,40,51,0.1) 0%, rgba(46,64,83,0.1) 100%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Override default main padding */
    main {
        padding: 0 !important;
    }

    #content {
        padding: 0 !important;
    }

    .auth-card {
        max-width: 450px;
        width: 100%;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
        background: rgba(244, 246, 246, 0.95);
        border: 1px solid rgba(170, 183, 184, 0.2);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .school-logo {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1C2833;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }

    .auth-subtitle {
        color: #2E4053;
        font-size: 1rem;
        line-height: 1.5;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.9);
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        background: #ffffff;
    }

    .input-group-text {
        border: 2px solid #e2e8f0;
        background-color: rgba(247, 250, 252, 0.9);
        color: #718096;
        border-radius: 0.75rem 0 0 0.75rem;
    }

    .input-group .form-control {
        border-left: 0;
        border-radius: 0;
    }

    .input-group .input-group-text {
        border-right: 0;
    }

    /* Registration key styles removed - account creation is now admin-only */

    .btn-auth {
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        border: none;
        font-size: 1rem;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        color: white;
    }

    .btn-auth::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }

    .btn-auth:hover::before {
        left: 100%;
    }

    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .remember-me {
        user-select: none;
    }

    .remember-me input[type="checkbox"] {
        border-radius: 0.375rem;
        border: 2px solid #e2e8f0;
        width: 1.25rem;
        height: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .remember-me input[type="checkbox"]:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .card-footer {
        background: transparent;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem;
    }

    /* Override any default body padding/margin */
    body {
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #f6f8fc 0%, #e9ecef 100%);
    }

    /* Remove default container padding */
    .container {
        padding: 0;
    }

    .row {
        margin: 0;
    }

    .col-12 {
        padding: 0;
    }

    /* Verify button styling */
    .verify-btn {
        min-width: 90px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        margin-left: -1px;
        border: 2px solid #e2e8f0;
        border-left: 0;
    }

    /* Override Bootstrap's outline button styles to match our design */
    .btn-outline-primary.verify-btn {
        color: #2E4053;
        border-color: #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .btn-outline-primary.verify-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(46, 64, 83, 0.1), transparent);
        transition: 0.5s;
        z-index: -1;
    }

    .btn-outline-primary.verify-btn:hover::before {
        left: 100%;
    }

    .btn-outline-primary.verify-btn:hover {
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        border-color: #2E4053;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(28, 40, 51, 0.2);
    }

    .btn-outline-primary.verify-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(28, 40, 51, 0.2);
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .auth-card {
            max-width: 100%;
            margin: 1rem;
            width: calc(100% - 2rem);
        }

        .card-body {
            padding: 1.5rem;
        }

        .auth-title {
            font-size: 1.75rem;
        }

        .school-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
        }

        /* Improved tab navigation for mobile */
        .nav-tabs {
            display: flex;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .nav-tabs .nav-item {
            flex: 1;
            display: flex;
            margin: 0 0.25rem;
        }

        .nav-tabs .nav-link {
            flex: 1;
            padding: 0.75rem 0.5rem;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-control, .input-group-text {
            padding: 0.75rem;
        }

        /* Improved auth buttons for mobile */
        .btn-auth {
            padding: 0.875rem 1rem;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 3.5rem;
            font-size: 1.1rem;
        }

        /* Mobile styles for key form */
        .key-form-container {
            padding: 1.25rem;
        }

        .key-icon {
            width: 56px;
            height: 56px;
        }

        .key-icon i {
            font-size: 1.5rem;
        }

        .key-title {
            font-size: 1.2rem;
        }

        .key-description {
            font-size: 0.85rem;
            padding: 0;
        }

        .btn-verify {
            height: 3rem;
            font-size: 0.95rem;
        }
    }

    @media (max-width: 480px) {
        .auth-card {
            margin: 0.5rem;
            width: calc(100% - 1rem);
        }

        .card-body {
            padding: 1.25rem;
        }

        .auth-title {
            font-size: 1.5rem;
        }

        /* Further improved tab navigation for very small screens */
        .nav-tabs .nav-item {
            margin: 0 0.125rem;
        }

        .nav-tabs .nav-link {
            padding: 0.625rem 0.25rem;
            font-size: 0.9rem;
        }

        /* Ensure icons are properly sized and aligned */
        .nav-tabs .nav-link i {
            margin-right: 0.25rem !important;
        }

        /* Optimize tab navigation for small screens */
        .nav-tabs .nav-link span.d-flex {
            flex-direction: column;
            gap: 0.25rem;
        }

        .nav-tabs .nav-link i {
            margin-right: 0 !important;
        }

        /* Improved auth buttons for very small screens */
        .btn-auth {
            height: 3.25rem;
            font-size: 1rem;
        }
    }

    /* Extra small devices */
    @media (max-width: 360px) {
        .nav-tabs .nav-link {
            font-size: 0.85rem;
            padding: 0.5rem 0.25rem;
        }

        /* Stack icon and text vertically on very small screens */
        .nav-tabs .nav-link span.d-flex {
            flex-direction: column;
            gap: 0.125rem;
        }

        .nav-tabs .nav-link i {
            font-size: 1.1rem;
            margin: 0 auto 0.125rem !important;
        }

        .btn-auth {
            font-size: 0.95rem;
        }

        /* Extra small screen styles for key form */
        .key-form-container {
            padding: 1rem;
        }

        .key-icon {
            width: 48px;
            height: 48px;
            margin-bottom: 0.75rem;
        }

        .key-icon i {
            font-size: 1.25rem;
        }

        .key-title {
            font-size: 1.1rem;
        }

        .key-description {
            font-size: 0.8rem;
            margin-bottom: 1.25rem;
        }

        .key-input-container .form-label {
            font-size: 0.85rem;
        }

        .key-input-container .form-control {
            font-size: 0.95rem;
            padding: 0.75rem 1rem 0.75rem 2.25rem;
        }

        .input-icon {
            font-size: 0.9rem;
            left: 0.85rem;
        }

        .btn-verify {
            height: 2.75rem;
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
        }

        .key-info {
            font-size: 0.75rem;
            padding: 0.5rem;
        }

        /* Success and warning styles for small screens */
        .key-success {
            padding: 1.25rem;
        }

        .key-success-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
        }

        .key-success-icon i {
            font-size: 1.5rem;
        }

        .key-success-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .key-success-message {
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
        }

        .key-warning {
            padding: 1rem;
            gap: 0.75rem;
        }

        .key-warning-icon {
            width: 28px;
            height: 28px;
        }

        .key-warning-title {
            font-size: 0.95rem;
            margin-bottom: 0.35rem;
        }

        .key-warning-message {
            font-size: 0.8rem;
        }

        /* Registration key input styles removed - account creation is now admin-only */
        /* #registration-key {
            min-width: 0;
            font-size: 16px; Prevent iOS zoom
        } */
    }

    /* Error message styling */
    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.5rem;
        color: #e53e3e;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .invalid-feedback i {
        color: #e53e3e;
    }

    /* Tab styling */
    .nav-tabs {
        border: none;
        margin-bottom: 2rem;
        justify-content: center;
    }

    .nav-tabs .nav-item {
        margin: 0 0.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        color: #718096;
        transition: all 0.2s ease;
        background-color: rgba(255, 255, 255, 0.5);
    }

    .nav-tabs .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.8);
    }

    .nav-tabs .nav-link.active {
        color: #1C2833;
        background-color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Registration key form styles removed - account creation is now admin-only */

    /* Input styling */
    .key-input-container {
        margin-bottom: 1.5rem;
    }

    .key-input-container .form-label {
        color: #2E4053;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
        font-size: 1rem;
        z-index: 2;
    }

    .key-input-container .form-control {
        border: 2px solid #e2e8f0;
        padding: 0.875rem 1rem 0.875rem 2.5rem;
        font-size: 1rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        height: auto;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .key-input-container .form-control:focus {
        border-color: #2E4053;
        box-shadow: 0 0 0 4px rgba(46, 64, 83, 0.1);
        background: #ffffff;
    }

    /* Verify button */
    .key-action {
        margin-bottom: 1.25rem;
    }

    .btn-verify {
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        border: none;
        font-size: 1rem;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        color: white;
        height: 3.25rem;
    }

    .btn-verify::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }

    .btn-verify:hover::before {
        left: 100%;
    }

    .btn-verify:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(28, 40, 51, 0.3);
    }

    .btn-verify.verifying {
        background: linear-gradient(135deg, #2E4053 0%, #1C2833 100%);
        cursor: wait;
        opacity: 0.9;
    }

    /* Key info text */
    .key-info {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        color: #718096;
        font-size: 0.85rem;
        line-height: 1.5;
        padding: 0.75rem;
        background-color: rgba(46, 64, 83, 0.05);
        border-radius: 0.5rem;
    }

    .key-info i {
        color: #2E4053;
        font-size: 0.9rem;
        margin-top: 0.125rem;
    }

    .tab-content > .tab-pane {
        display: none;
    }

    .tab-content > .active {
        display: block;
    }

    /* Clearfix for nav tabs */
    .nav-tabs::after {
        display: block;
        clear: both;
        content: "";
    }

    /* Announcement modal animation */
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(-10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .announcement-modal.show .modal-dialog {
        animation: modalFadeIn 0.3s ease-out forwards;
    }

    .announcement-modal .modal-dialog {
        transform: scale(0.95) translateY(-10px);
        opacity: 0;
    }

    /* Warning alert styling */
    .alert-warning {
        border-left: 4px solid #f59f00;
        background-color: #fff9db;
        color: #6b5900;
    }

    .alert-warning strong {
        color: #e67700;
    }

    /* Key verified styles removed - account creation is now admin-only */

    /* Form floating styles */
    .form-floating {
        position: relative;
    }

    .form-floating > .form-control {
        padding: 1.25rem 1rem 0.5rem;
        height: 3.5rem;
    }

    .form-floating > label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 1rem;
        pointer-events: none;
        border: 1px solid transparent;
        transform-origin: 0 0;
        transition: opacity .1s ease-in-out, transform .1s ease-in-out;
        color: #718096;
        font-size: 0.95rem;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        opacity: .85;
        transform: scale(.85) translateY(-0.5rem);
    }

    .form-floating > .form-control:focus {
        border-color: #2E4053;
        box-shadow: 0 0 0 0.25rem rgba(46, 64, 83, 0.1);
    }

    /* Password container styles */
    .password-container {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #718096;
        z-index: 5;
        padding: 0.25rem 0.5rem;
    }

    .password-toggle:hover {
        color: #2E4053;
    }

    .password-container .form-control {
        padding-right: 2.5rem;
    }

    /* Key warning styling */
    .key-warning {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        background-color: #fff9db;
        border-left: 4px solid #f59f00;
        padding: 1.25rem;
        border-radius: 0.5rem;
        text-align: left;
        margin-top: 1.5rem;
    }

    .key-warning-icon {
        width: 32px;
        height: 32px;
        background-color: #f59f00;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .key-warning-icon i {
        color: white;
        font-size: 0.9rem;
    }

    .key-warning-content {
        flex: 1;
    }

    .key-warning-title {
        color: #e67700;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .key-warning-message {
        color: #6b5900;
        font-size: 0.85rem;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* Key error styles removed - account creation is now admin-only */

</style>
@endpush

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="auth-card card border-0 shadow-lg mx-auto">
                    <div class="card-body p-5">
                        <div class="school-logo">
                            @if($school && $school->logo_path)
                                <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                            @else
                                <i class="fas fa-graduation-cap text-white" style="font-size: 3rem;"></i>
                            @endif
                        </div>

                        <!-- <ul class="nav nav-tabs" id="authTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane" aria-selected="true">
                                    <span class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        <span>Sign In</span>
                                    </span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-tab-pane" type="button" role="tab" aria-controls="register-tab-pane" aria-selected="false">
                                    <span class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-plus me-2"></i>
                                        <span>Register</span>
                                    </span>
                                </button>
                            </li>
                        </ul> -->

                        @if(session('error'))
                            <div class="alert alert-danger mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <span>{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="tab-content" id="authTabsContent">
                            <!-- Login Tab -->
                            <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                                <div class="auth-header">
                                    <h1 class="auth-title">Welcome Back!</h1>
                                    <p class="auth-subtitle">Sign in to continue to your account</p>
                                </div>

                                <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="login-email" class="form-label fw-medium mb-2">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input id="login-email" type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}"
                                                required autocomplete="email" autofocus
                                                placeholder="Enter your email">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="login-password" class="form-label fw-medium mb-0">Password</label>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input id="login-password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password"
                                                placeholder="Enter your password">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleLoginPassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <div class="form-check remember-me">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label ms-2" for="remember">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-auth btn-lg">
                                            <span class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-sign-in-alt me-2"></i>
                                                <span>Sign In</span>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Register Tab -->
                            <div class="tab-pane fade" id="register-tab-pane" role="tabpanel" aria-labelledby="register-tab" tabindex="0">
                                <div class="auth-header">
                                    <h1 class="auth-title">Account Registration</h1>
                                    <p class="auth-subtitle">Administrator Access Required</p>
                                </div>

                                <!-- Registration is now admin-only -->
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-user-shield text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                    <h4 class="text-muted mb-3">Account Registration Restricted</h4>
                                    <p class="text-muted mb-4">
                                        Account creation is now handled exclusively by system administrators through the admin panel.
                                        Please contact your system administrator to request a new account.
                                    </p>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        If you already have an account, please use the <strong>Login</strong> tab above.
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <span class="text-muted">&copy; {{ date('Y') }} Patag LIS All Rights Reserve</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if register tab should be active
    @if(session('register_tab'))
    document.getElementById('register-tab').click();
    @endif

    // Login form functionality
    const loginForm = document.getElementById('loginForm');
    const loginButton = loginForm.querySelector('button[type="submit"]');
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const loginPasswordInput = document.getElementById('login-password');

    // Toggle login password visibility
    toggleLoginPassword.addEventListener('click', function() {
        const type = loginPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        loginPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Login form submission
    loginForm.addEventListener('submit', function() {
        loginButton.classList.add('loading');
        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';
    });

    // Register form functionality
    const registerForm = document.getElementById('registerForm');
    const registerButton = registerForm?.querySelector('button[type="submit"]');
    const toggleRegisterPassword = document.getElementById('toggleRegisterPassword');
    const registerPasswordInput = document.getElementById('register-password');

    // Toggle register password visibility
    if (toggleRegisterPassword) {
        toggleRegisterPassword.addEventListener('click', function() {
            const type = registerPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            registerPasswordInput.setAttribute('type', type);

            // Also toggle the confirm password field if it exists
            const confirmPasswordInput = document.getElementById('password-confirm');
            if (confirmPasswordInput && type === 'text') {
                confirmPasswordInput.setAttribute('type', 'text');
            } else if (confirmPasswordInput) {
                confirmPasswordInput.setAttribute('type', 'password');
            }

            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    // Register form submission
    if (registerForm) {
        registerForm.addEventListener('submit', function() {
            registerButton.classList.add('loading');
            registerButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating account...';

            // Remove the page unload warning when form is submitted
            window.onbeforeunload = null;
        });
    }

    // Registration key verification removed - account creation is now admin-only

    // Fetch and display announcements
    fetchAnnouncements();

    function fetchAnnouncements() {
        fetch('/api/announcements')
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    // Create announcement modal for each active announcement
                    data.forEach((announcement, index) => {
                        createAnnouncementModal(announcement, index);
                    });

                    // Show the first announcement modal automatically
                    setTimeout(() => {
                        const firstModal = document.getElementById('announcementModal0');
                        if (firstModal) {
                            const modal = new bootstrap.Modal(firstModal);
                            modal.show();
                        }
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error fetching announcements:', error);
            });
    }

    function createAnnouncementModal(announcement, index) {
        // Format the content to preserve line breaks and formatting
        const formattedContent = formatAnnouncementContent(announcement.content);

        // Create modal element
        const modalHtml = `
            <div class="modal fade announcement-modal" id="announcementModal${index}" tabindex="-1" aria-labelledby="announcementModalLabel${index}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-header">
                            <h5 class="modal-title" id="announcementModalLabel${index}">
                                <i class="fas fa-bullhorn me-2"></i>${announcement.title}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="announcement-content">${formattedContent}</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%); color: white;" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Add event listener for when the modal is shown
        const modalElement = document.getElementById(`announcementModal${index}`);
        modalElement.addEventListener('shown.bs.modal', function() {
            // Fix any emoji rendering issues
            const emojiElements = modalElement.querySelectorAll('.announcement-content .fas');
            emojiElements.forEach(el => {
                el.style.display = 'inline-block';
                el.style.width = '1.25em';
                el.style.textAlign = 'center';
            });
        });
    }

    function formatAnnouncementContent(content) {
        if (!content) return '';

        // First, handle any HTML entities to prevent double-encoding
        let formatted = content.replace(/&/g, '&amp;')
                              .replace(/</g, '&lt;')
                              .replace(/>/g, '&gt;');

        // Handle hashtag announcements at the beginning of lines
        formatted = formatted.replace(/(^|<br>)(#+)\s*ANNOUNCEMENT:\s*([^\n<]+)/gi,
            '$1<h4 class="fw-bold"><i class="fas fa-bullhorn me-2"></i>$3</h4>');

        // Replace line breaks with <br> tags
        formatted = formatted.replace(/\n/g, '<br>');

        // Format bullet points and check marks
        formatted = formatted.replace(/(^|<br>)•\s+([^<]+)/g, '$1<li>$2</li>');
        formatted = formatted.replace(/(^|<br>)✓\s+([^<]+)/g,
            '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-check text-success me-2"></i><div>$2</div></div>');
        formatted = formatted.replace(/(^|<br>)✅\s+([^<]+)/g,
            '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-check-square text-success me-2"></i><div>$2</div></div>');

        // Format special icons
        formatted = formatted.replace(/(^|<br>)📅\s+([^<]+)/g,
            '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-calendar-alt me-2" style="color: #2E4053;"></i><div>$2</div></div>');
        formatted = formatted.replace(/(^|<br>)📣\s+([^<]+)/g,
            '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-bullhorn me-2" style="color: #1C2833;"></i><div>$2</div></div>');
        formatted = formatted.replace(/(^|<br>)💡\s+([^<]+)/g,
            '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-lightbulb me-2" style="color: #2E4053;"></i><div>$2</div></div>');

        // Wrap consecutive <li> elements in <ul> tags
        if (formatted.includes('<li>')) {
            // Find all sequences of <li> elements
            formatted = formatted.replace(/(<li>[^<]+<\/li>)+/g, function(match) {
                return '<ul class="mb-3">' + match + '</ul>';
            });
        }

        // Add emphasis to text between asterisks
        formatted = formatted.replace(/\*\*([^*<]+)\*\*/g, '<strong>$1</strong>');
        formatted = formatted.replace(/\*([^*<]+)\*/g, '<em>$1</em>');

        // Format section headers (###)
        formatted = formatted.replace(/(^|<br>)###\s+([^<\n]+)/g,
            '$1<h5 class="mt-3 mb-2 fw-bold">$2</h5>');

        // Handle emojis in text
        formatted = formatted.replace(/🎉/g, '<i class="fas fa-party-horn" style="color: #2E4053;"></i>');
        formatted = formatted.replace(/🚀/g, '<i class="fas fa-rocket" style="color: #2E4053;"></i>');

        // Convert URLs to clickable links
        // First, handle URLs with protocol (http://, https://)
        formatted = formatted.replace(
            /(https?:\/\/[^\s<]+)(?![^<>]*>|[^<>]*<\/a>)/gi,
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
        );

        // Then, handle URLs without protocol (www.example.com)
        formatted = formatted.replace(
            /(?<![\/"'>])\b(www\.[^\s<]+\.[^\s<]+)(?![^<>]*>|[^<>]*<\/a>)/gi,
            '<a href="http://$1" target="_blank" rel="noopener noreferrer">$1</a>'
        );

        return formatted;
    }
});
</script>
@endpush

<!-- Announcement Modals will be dynamically inserted here -->

@endsection
