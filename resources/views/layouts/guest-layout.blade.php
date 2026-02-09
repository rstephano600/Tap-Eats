<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'TapEats - Food Delivery in Dar es Salaam')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #FF6B35;
            --primary-dark: #E85A2A;
            --secondary-color: #FFB800;
            --dark-color: #1A1A1A;
            --light-bg: #F8F9FA;
            --border-color: #E8E8E8;
            --text-muted: #6C757D;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* ============================================
           NAVBAR STYLES
           ============================================ */
        .main-navbar {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: all 0.3s ease;
        }

        .main-navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-color);
            letter-spacing: -0.5px;
        }

        .navbar-brand img {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: rotate(360deg);
        }

        .nav-link {
            color: var(--dark-color);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background: rgba(255, 107, 53, 0.08);
        }

        .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .btn-login {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .btn-signup {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }

        /* Mobile Menu */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 107, 53, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ============================================
           FOOTER STYLES
           ============================================ */
        footer {
            background: linear-gradient(135deg, #1A1A1A 0%, #2D2D2D 100%);
            color: white;
            padding: 4rem 0 2rem;
            margin-top: 5rem;
        }

        footer h4, footer h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        footer a:hover {
            color: var(--primary-color);
            transform: translateX(4px);
            display: inline-block;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-5px) rotate(10deg);
        }

        /* ============================================
           UTILITY CLASSES
           ============================================ */
        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .bg-primary-custom {
            background: var(--primary-color) !important;
        }

        /* ============================================
           SCROLLBAR CUSTOMIZATION
           ============================================ */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* ============================================
           ANIMATIONS
           ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo/ejossolution.png') }}" alt="Logo" width="40" class="me-2">
                TapEats
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('restaurantspublic') ? 'active' : '' }}" href="{{ route('restaurantspublic') }}">Restaurants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dailymenuitems') ? 'active' : '' }}" href="{{ route('dailymenuitems') }}">Daily Meals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('catering') ? 'active' : '' }}" href="{{ route('catering') }}">Catering</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2">
                    @guest
                        <a href="{{ route('showLoginForm') }}" class="btn btn-login">Login</a>
                        <a href="{{ route('showRegisterForm') }}" class="btn btn-signup">Sign Up</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-signup">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="mb-3"><i class="bi bi-shop"></i> TapEats</h4>
                    <p>Your trusted partner for food delivery, daily meals, and catering services in Tanzania.</p>
                    <div class="social-links mt-4">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Company</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('aboutus') }}">About Us</a></li>
                        <li class="mb-2"><a href="#">Careers</a></li>
                        <li class="mb-2"><a href="#">Press</a></li>
                        <li class="mb-2"><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Services</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('restaurants.public') }}">Food Delivery</a></li>
                        <li class="mb-2"><a href="{{ route('dailymenuitems') }}">Daily Meals</a></li>
                        <li class="mb-2"><a href="{{ route('catering') }}">Catering</a></li>
                        <li class="mb-2"><a href="#">Corporate</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Partners</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('showRegisterForm') }}">Become a Restaurant Partner</a></li>
                        <li class="mb-2"><a href="{{ route('showRegisterForm') }}">Become a Delivery Partner</a></li>
                        <li class="mb-2"><a href="{{ route('showRegisterForm') }}">Partner Portal</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Support</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('customersupport') }}">Help Center</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li class="mb-2"><a href="#">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2024 TapEats. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Made with <i class="bi bi-heart-fill text-danger"></i> in Tanzania</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.main-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
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
    </script>

    @stack('scripts')
</body>
</html>
