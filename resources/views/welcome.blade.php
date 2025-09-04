<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Patag Elementary School - Grading System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                line-height: 1.6;
                color: #1f2937;
                overflow-x: hidden;
            }

            /* Navigation Styles */
            .nav-container {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
            }

            /* Hero Section */
            .hero-section {
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx=".5" cy=".5" r=".5"><stop offset="0%" stop-color="%23ffffff" stop-opacity=".1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="300" cy="700" r="120" fill="url(%23a)"/><circle cx="700" cy="800" r="80" fill="url(%23a)"/></svg>') no-repeat center center;
                background-size: cover;
                opacity: 0.3;
            }

            .hero-content {
                text-align: center;
                color: white;
                max-width: 800px;
                padding: 0 20px;
                position: relative;
                z-index: 2;
            }

            .school-logo {
                width: 120px;
                height: 120px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 2rem;
                backdrop-filter: blur(10px);
                border: 3px solid rgba(255, 255, 255, 0.3);
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }

            .hero-title {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            }

            .hero-subtitle {
                font-size: 1.5rem;
                font-weight: 500;
                margin-bottom: 1rem;
                opacity: 0.9;
            }

            .hero-description {
                font-size: 1.1rem;
                margin-bottom: 2.5rem;
                opacity: 0.8;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            /* Button Styles */
            .btn {
                display: inline-block;
                padding: 12px 30px;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                font-size: 1rem;
            }

            .btn-primary {
                background: rgba(255, 255, 255, 0.2);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(10px);
            }

            .btn-primary:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            }

            .btn-secondary {
                background: white;
                color: #667eea;
                border: 2px solid white;
            }

            .btn-secondary:hover {
                background: transparent;
                color: white;
                border-color: white;
                transform: translateY(-2px);
            }

            /* About Section */
            .about-section {
                padding: 100px 0;
                background: #f8fafc;
            }

            .section-title {
                font-size: 2.5rem;
                font-weight: 700;
                text-align: center;
                margin-bottom: 1rem;
                color: #1f2937;
            }

            .section-subtitle {
                font-size: 1.2rem;
                text-align: center;
                color: #6b7280;
                margin-bottom: 3rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            /* Features Grid */
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                margin-top: 3rem;
            }

            .feature-card {
                background: white;
                padding: 2rem;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: all 0.3s ease;
                border: 1px solid #e5e7eb;
            }

            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }

            .feature-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                color: white;
                font-size: 2rem;
            }

            .feature-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 1rem;
                color: #1f2937;
            }

            .feature-description {
                color: #6b7280;
                line-height: 1.6;
            }

            /* Stats Section */
            .stats-section {
                padding: 80px 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 2rem;
                text-align: center;
            }

            .stat-number {
                font-size: 3rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .stat-label {
                font-size: 1.1rem;
                opacity: 0.9;
            }

            /* CTA Section */
            .cta-section {
                padding: 100px 0;
                background: #1f2937;
                color: white;
                text-align: center;
            }

            /* Footer */
            .footer {
                background: #111827;
                color: #9ca3af;
                padding: 40px 0 20px;
                text-align: center;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem;
                }
                
                .hero-subtitle {
                    font-size: 1.2rem;
                }
                
                .section-title {
                    font-size: 2rem;
                }
                
                .features-grid {
                    grid-template-columns: 1fr;
                }
            }

            /* Utility Classes */
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .flex {
                display: flex;
            }

            .items-center {
                align-items: center;
            }

            .justify-center {
                justify-content: center;
            }

            .justify-between {
                justify-content: space-between;
            }

            .gap-4 {
                gap: 1rem;
            }

            .mb-4 {
                margin-bottom: 1rem;
            }

            .text-center {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="nav-container">
            <div class="container">
                <div class="flex justify-between items-center" style="height: 70px;">
                    <div class="flex items-center gap-4">
                        <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            @if($school && $school->logo_path)
                                <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" style="max-width: 30px; max-height: 30px; object-fit: contain; border-radius: 50%;">
                            @else
                                <i class="fas fa-graduation-cap"></i>
                            @endif
                        </div>
                        <span style="font-size: 1.2rem; font-weight: 600; color: #1f2937;">Patag Elementary School</span>
                    </div>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="#about" style="color: #6b7280; text-decoration: none; font-weight: 500; transition: color 0.3s ease;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#6b7280'">About</a>
                        <a href="#features" style="color: #6b7280; text-decoration: none; font-weight: 500; transition: color 0.3s ease;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#6b7280'">Features</a>
                        <a href="#contact" style="color: #6b7280; text-decoration: none; font-weight: 500; transition: color 0.3s ease;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#6b7280'">Contact</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                        @else
                            <!-- <a href="{{ route('login') }}" class="btn btn-primary">Login</a> -->
                        @endauth
                    </div>
                    <div class="md:hidden">
                        <button id="mobile-menu-button" style="color: #6b7280; font-size: 1.5rem;">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden" style="background: white; padding: 20px; border-top: 1px solid #e5e7eb;">
                <a href="#about" style="display: block; padding: 10px 0; color: #6b7280; text-decoration: none;">About</a>
                <a href="#features" style="display: block; padding: 10px 0; color: #6b7280; text-decoration: none;">Features</a>
                <a href="#contact" style="display: block; padding: 10px 0; color: #6b7280; text-decoration: none;">Contact</a>
                <a href="{{ route('enrollment.create') }}" style="display: block; padding: 10px 0; color: #10b981; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-user-plus" style="margin-right: 5px;"></i>Enroll Now
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}" style="display: block; padding: 10px 0; color: #667eea; text-decoration: none; font-weight: 600;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" style="display: block; padding: 10px 0; color: #667eea; text-decoration: none; font-weight: 600;">Login</a>
                @endauth
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <div class="school-logo">
                    @if($school && $school->logo_path)
                        <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" style="max-width: 80px; max-height: 80px; object-fit: contain;">
                    @else
                        <i class="fas fa-school" style="font-size: 3rem;"></i>
                    @endif
                </div>
                <h1 class="hero-title">{{ $school ? $school->name : 'Patag Elementary School' }}</h1>
                <p class="hero-subtitle">Excellence in Education, Innovation in Learning</p>
                <p class="hero-description">
                    Welcome to our comprehensive grading system designed specifically for Patag Elementary School. 
                    Empowering teachers, engaging students, and connecting families through modern educational technology.
                </p>
                <div class="flex justify-center gap-4" style="flex-wrap: wrap;">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>Access Portal
                    </a>
                    <a href="{{ route('admission.apply') }}" class="btn btn-secondary">
                        <i class="fas fa-user-plus" style="margin-right: 8px;"></i>Apply for Admission
                    </a>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <h2 class="section-title">About Patag Elementary School</h2>
                <p class="section-subtitle">
                    Committed to providing quality education and fostering academic excellence in a nurturing environment 
                    where every student can thrive and reach their full potential.
                </p>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="feature-title">Qualified Teachers</h3>
                        <p class="feature-description">
                            Our dedicated and experienced teachers are committed to providing personalized attention 
                            and quality education to every student.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h3 class="feature-title">Modern Technology</h3>
                        <p class="feature-description">
                            Integrated digital learning tools and modern grading systems to enhance the educational 
                            experience and track student progress effectively.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Community Focus</h3>
                        <p class="feature-description">
                            Building strong partnerships with families and the community to create a supportive 
                            learning environment for all students.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="about-section" style="background: white;">
            <div class="container">
                <h2 class="section-title">Grading System Features</h2>
                <p class="section-subtitle">
                    Our comprehensive grading system provides powerful tools for teachers, students, and parents 
                    to track academic progress and achievement.
                </p>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Real-time Tracking</h3>
                        <p class="feature-description">
                            Monitor student progress in real-time with instant grade updates and comprehensive 
                            performance analytics.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="feature-title">Detailed Reports</h3>
                        <p class="feature-description">
                            Generate comprehensive grade reports, progress summaries, and academic transcripts 
                            with just a few clicks.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Secure Access</h3>
                        <p class="feature-description">
                            Protected student data with role-based access control ensuring privacy and security 
                            for all academic information.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Mobile Friendly</h3>
                        <p class="feature-description">
                            Access grades and reports from any device, anywhere, anytime with our responsive 
                            and user-friendly interface.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="feature-title">Notifications</h3>
                        <p class="feature-description">
                            Stay informed with automatic notifications for grade updates, assignments, and 
                            important school announcements.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3 class="feature-title">Easy Management</h3>
                        <p class="feature-description">
                            Streamlined grade entry, bulk operations, and automated calculations make 
                            academic management efficient and accurate.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <h2 class="section-title" style="color: white; margin-bottom: 1rem;">Ready to Get Started?</h2>
                <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Join the Patag Elementary School community and experience excellence in education. Apply for admission today!
                </p>
                <div class="flex justify-center gap-4" style="flex-wrap: wrap;">
                    <a href="{{ route('admission.apply') }}" class="btn" style="background: #10b981; color: white; border: 2px solid #10b981;" onmouseover="this.style.background='transparent'; this.style.color='#10b981';" onmouseout="this.style.background='#10b981'; this.style.color='white';">
                        <i class="fas fa-user-plus" style="margin-right: 8px;"></i>Apply for Admission
                    </a>
                    <a href="{{ route('login') }}" class="btn" style="background: transparent; color: white; border: 2px solid white;" onmouseover="this.style.background='white'; this.style.color='#1f2937';" onmouseout="this.style.background='transparent'; this.style.color='white';">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>Access Portal
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="footer">
            <div class="container">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem; text-align: left;">
                    <div>
                        <h4 style="color: white; font-size: 1.2rem; margin-bottom: 1rem;">Patag Elementary School</h4>
                        <p style="margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>Patag, Digos City, Davao del Sur</p>
                        <p style="margin-bottom: 0.5rem;"><i class="fas fa-phone" style="margin-right: 8px;"></i>(082) 123-4567</p>
                        <p><i class="fas fa-envelope" style="margin-right: 8px;"></i>info@patagelementary.edu.ph</p>
                    </div>
                    <div>
                        <h4 style="color: white; font-size: 1.2rem; margin-bottom: 1rem;">Quick Links</h4>
                        <p style="margin-bottom: 0.5rem;"><a href="#about" style="color: #9ca3af; text-decoration: none;">About Us</a></p>
                        <p style="margin-bottom: 0.5rem;"><a href="#features" style="color: #9ca3af; text-decoration: none;">Features</a></p>
                        <p style="margin-bottom: 0.5rem;"><a href="{{ route('enrollment.create') }}" style="color: #9ca3af; text-decoration: none;">Enrollment</a></p>
                        <p><a href="{{ route('login') }}" style="color: #9ca3af; text-decoration: none;">Login</a></p>
                    </div>
                    <div>
                        <h4 style="color: white; font-size: 1.2rem; margin-bottom: 1rem;">School Hours</h4>
                        <p style="margin-bottom: 0.5rem;">Monday - Friday: 7:00 AM - 5:00 PM</p>
                        <p style="margin-bottom: 0.5rem;">Saturday: 8:00 AM - 12:00 PM</p>
                        <p>Sunday: Closed</p>
                    </div>
                </div>
                <div style="border-top: 1px solid #374151; padding-top: 2rem; text-align: center;">
                    <p>&copy; {{ date('Y') }} Patag Elementary School. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- JavaScript -->
        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.toggle('hidden');
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add scroll effect to navigation
            window.addEventListener('scroll', function() {
                const nav = document.querySelector('.nav-container');
                if (window.scrollY > 50) {
                    nav.style.background = 'rgba(255, 255, 255, 0.98)';
                } else {
                    nav.style.background = 'rgba(255, 255, 255, 0.95)';
                }
            });
        </script>
    </body>
</html>