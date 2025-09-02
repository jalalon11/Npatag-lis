@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Frequently Asked Questions</h5>
                    <a href="{{ route('admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Admin Module FAQs</h4>
                            <p class="text-muted">
                                Find answers to common questions about using the Admin Module.
                            </p>
                        </div>
                    </div>

                    <div class="accordion" id="faqAccordion">
                        <!-- General Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">General Questions</h5>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        What is the difference between Admin and Teacher Admin roles?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>The Admin role has system-wide access and can manage multiple schools, while Teacher Admin roles are school-specific:</p>
                                        <ul>
                                            <li><strong>Admin:</strong> Can create and manage schools, create Teacher Admin accounts, view all system data, manage announcements, and handle support tickets</li>
                                            <li><strong>Teacher Admin:</strong> Can only manage their assigned school's data including teachers, students, sections, and subjects</li>
                                        </ul>
                                        <p>As an Admin, you have the highest level of access in the system and are responsible for overall system management.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        How do I create a new school in the system?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To create a new school:</p>
                                        <ol>
                                            <li>Go to the "Schools" page in the Admin panel</li>
                                            <li>Click the "Add New School" button</li>
                                            <li>Fill in all required information (school name, address, contact details, etc.)</li>
                                            <li>Upload a school logo if available</li>
                                            <li>Set the school's subscription details</li>
                                            <li>Click "Create School" to save</li>
                                        </ol>
                                        <p>After creating a school, you'll need to create Teacher Admin accounts for that school to manage its operations.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Management Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">Account Management</h5>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        How do I create Teacher Admin accounts?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To create a Teacher Admin account:</p>
                                        <ol>
                                            <li>Go to the "Accounts" page in the Admin panel</li>
                                            <li>Click "Create New Account"</li>
                                            <li>Select "Teacher Admin" as the role</li>
                                            <li>Choose the school they will manage</li>
                                            <li>Fill in their personal information (name, email, etc.)</li>
                                            <li>Set a temporary password or let the system generate one</li>
                                            <li>Click "Create Account"</li>
                                        </ol>
                                        <p>The new Teacher Admin will receive login credentials and can immediately start managing their assigned school.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Can I disable or delete user accounts?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Yes, you can manage user accounts through the Accounts page:</p>
                                        <ul>
                                            <li><strong>Disable:</strong> Temporarily prevents login while preserving all data and relationships</li>
                                            <li><strong>Delete:</strong> Permanently removes the account (use with caution)</li>
                                        </ul>
                                        <p>When disabling accounts:</p>
                                        <ul>
                                            <li>The user cannot log in but their data remains intact</li>
                                            <li>You can reactivate the account at any time</li>
                                            <li>Historical records and relationships are preserved</li>
                                        </ul>
                                        <p>Deletion should only be used when you're certain the account is no longer needed, as this action cannot be undone.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- School Management Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">School Management</h5>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        How do I monitor school activities and data?
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>You can monitor school activities through several features:</p>
                                        <ul>
                                            <li><strong>Dashboard:</strong> View system-wide statistics and recent activities</li>
                                            <li><strong>Schools Page:</strong> See all schools, their status, and key metrics</li>
                                            <li><strong>Students Page:</strong> View student data across all schools</li>
                                            <li><strong>Admissions Page:</strong> Monitor admission applications and approvals</li>
                                            <li><strong>Support Page:</strong> Review support tickets from schools</li>
                                        </ul>
                                        <p>Each page provides filtering and search capabilities to help you find specific information quickly.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        What should I do if a school's subscription expires?
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>When a school's subscription expires:</p>
                                        <ol>
                                            <li>The school's status will automatically change to "Expired"</li>
                                            <li>Teachers and students from that school cannot log in</li>
                                            <li>Teacher Admins have limited access (mainly payment-related features)</li>
                                            <li>All data is preserved and will be accessible once renewed</li>
                                        </ol>
                                        <p>To help schools with renewals:</p>
                                        <ul>
                                            <li>Monitor subscription expiration dates on the Schools page</li>
                                            <li>Send reminders to schools approaching expiration</li>
                                            <li>Assist with payment processing if needed</li>
                                            <li>Manually extend subscriptions when payments are confirmed</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Management Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">System Management</h5>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        How do I manage system-wide announcements?
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To manage system announcements:</p>
                                        <ol>
                                            <li>Go to the "Announcements" page in the Admin panel</li>
                                            <li>Click "Create New Announcement"</li>
                                            <li>Write your announcement title and content</li>
                                            <li>Set the target audience (all users, specific schools, etc.)</li>
                                            <li>Choose the announcement priority level</li>
                                            <li>Set publication and expiration dates</li>
                                            <li>Click "Publish Announcement"</li>
                                        </ol>
                                        <p>Published announcements will appear on users' dashboards based on your targeting settings.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingEight">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                        How do I handle support tickets from schools?
                                    </button>
                                </h2>
                                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To manage support tickets:</p>
                                        <ol>
                                            <li>Go to the "Support" page to view all tickets</li>
                                            <li>Use filters to find specific tickets (by status, school, category, etc.)</li>
                                            <li>Click on a ticket to view details and conversation history</li>
                                            <li>Respond to tickets with helpful information or solutions</li>
                                            <li>Update ticket status as you work on them (In Progress, Resolved, etc.)</li>
                                            <li>Close tickets once issues are fully resolved</li>
                                        </ol>
                                        <p>Prompt and helpful responses to support tickets help maintain good relationships with schools using the system.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('admin.help.tutorial', 'support') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Support
                        </a>
                        <a href="{{ route('admin.help.index') }}" class="tutorial-nav-btn next">
                            Back to Help Center <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tutorial-section {
        border-left: 3px solid #e9ecef;
        padding-left: 20px;
    }

    .tutorial-heading {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .tutorial-content {
        color: #555;
    }

    .tutorial-tip {
        background-color: #fff8e1;
        border-left: 3px solid #ffc107;
        padding: 10px 15px;
        margin-top: 15px;
        border-radius: 4px;
        display: flex;
        align-items: center;
    }

    .tutorial-tip i {
        margin-right: 10px;
        font-size: 18px;
    }

    .accordion-button:not(.collapsed) {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
</style>

<!-- Include the tutorial navigation CSS -->
<link rel="stylesheet" href="{{ asset('css/tutorial-nav.css') }}">
@endsection