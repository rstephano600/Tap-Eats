<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TapEats – Food Delivery')</title>
    <link rel="icon" href="{{ asset('images/logo/ejossolution.png') }}" type="image/png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        /* ══ TOKENS ══ */
        :root {
            --brand:       #FF6B35;
            --brand-dark:  #E5531A;
            --brand-light: #FF8C5A;
            --brand-muted: rgba(255,107,53,0.10);
            --gold:        #FFB800;
            --dark:        #1A1714;
            --surface:     #FFFBF7;
            --card:        #FFFFFF;
            --border:      #F0EBE3;
            --muted:       #8B7D72;
            --nav-h:       66px;
            --drawer-w:    300px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; background: var(--surface); color: var(--dark); overflow-x: hidden; }

        /* ══ SCROLLBAR ══ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: var(--brand); border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--brand-dark); }

        /* ══════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════ */
        .main-nav {
            height: var(--nav-h);
            background: var(--card);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 16px rgba(26,23,20,0.07);
            position: sticky; top: 0; z-index: 1030;
            transition: height 0.3s, box-shadow 0.3s;
        }
        .main-nav.scrolled {
            height: 56px;
            box-shadow: 0 4px 24px rgba(26,23,20,0.12);
        }
        .nav-inner {
            display: flex; align-items: center; height: 100%;
            padding: 0 1.5rem; max-width: 1400px; margin: 0 auto; gap: 1rem;
        }

        /* Brand */
        .nav-brand {
            display: flex; align-items: center; gap: 0.55rem;
            text-decoration: none; flex-shrink: 0;
        }
        .nav-brand-logo { width: 34px; height: 34px; border-radius: 9px; transition: transform 0.5s; }
        .nav-brand:hover .nav-brand-logo { transform: rotate(360deg); }
        .nav-brand-text {
            font-family: 'Sora', sans-serif; font-size: 1.35rem; font-weight: 800;
            color: var(--brand); letter-spacing: -0.3px;
        }

        /* Desktop links */
        .nav-links { display: flex; align-items: center; gap: 0.1rem; list-style: none; margin: 0 auto; }
        .nav-links a {
            display: block; padding: 0.42rem 0.82rem; border-radius: 8px;
            font-size: 0.88rem; font-weight: 500; color: var(--dark);
            text-decoration: none; transition: background 0.18s, color 0.18s; white-space: nowrap;
        }
        .nav-links a:hover { background: var(--brand-muted); color: var(--brand); }
        .nav-links a.active {
            background: var(--brand-muted); color: var(--brand); font-weight: 600;
        }

        /* Auth */
        .nav-auth { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; }
        .btn-nav-login {
            padding: 0.42rem 1.1rem; border-radius: 8px; font-weight: 600; font-size: 0.86rem;
            border: 2px solid var(--brand); color: var(--brand); background: transparent;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-nav-login:hover { background: var(--brand); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(255,107,53,0.28); }
        .btn-nav-signup {
            padding: 0.42rem 1.1rem; border-radius: 8px; font-weight: 700; font-size: 0.86rem;
            background: var(--brand); color: #fff; border: none; text-decoration: none; transition: all 0.2s;
        }
        .btn-nav-signup:hover { background: var(--brand-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 5px 16px rgba(255,107,53,0.38); }

        /* Hamburger */
        .nav-hamburger {
            display: none; flex-direction: column; justify-content: center; align-items: center;
            gap: 5px; width: 40px; height: 40px; border: none; background: none;
            cursor: pointer; padding: 0; border-radius: 8px; transition: background 0.2s; margin-left: auto;
        }
        .nav-hamburger:hover { background: var(--brand-muted); }
        .nav-hamburger span {
            display: block; width: 22px; height: 2px; background: var(--brand);
            border-radius: 2px; transition: transform 0.3s, opacity 0.3s, width 0.3s; transform-origin: center;
        }
        body.drawer-open .nav-hamburger span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        body.drawer-open .nav-hamburger span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        body.drawer-open .nav-hamburger span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* ══════════════════════════════════════
           RIGHT-SIDE MOBILE DRAWER
        ══════════════════════════════════════ */
        .drawer-overlay {
            position: fixed; inset: 0;
            background: rgba(26,23,20,0.52); backdrop-filter: blur(4px);
            z-index: 1040; opacity: 0; pointer-events: none; transition: opacity 0.35s;
        }
        body.drawer-open .drawer-overlay { opacity: 1; pointer-events: all; }

        .mobile-drawer {
            position: fixed;
            top: 0; right: 0;               /* RIGHT side */
            width: var(--drawer-w);
            height: 100dvh;
            background: var(--card);
            z-index: 1050;
            transform: translateX(100%);    /* starts off-screen RIGHT */
            transition: transform 0.38s cubic-bezier(0.4,0,0.2,1);
            display: flex; flex-direction: column;
            overflow-y: auto;
            box-shadow: -8px 0 40px rgba(26,23,20,0.16);
        }
        body.drawer-open .mobile-drawer { transform: translateX(0); }

        /* Drawer header */
        .drawer-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.1rem; border-bottom: 1px solid var(--border); flex-shrink: 0;
        }
        .drawer-close {
            width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid var(--border);
            background: none; display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 0.95rem; color: var(--muted); transition: all 0.2s;
        }
        .drawer-close:hover { border-color: var(--brand); color: var(--brand); }

        /* User card */
        .drawer-user {
            margin: 0.85rem 1rem 0;
            padding: 0.8rem 0.9rem;
            background: var(--surface); border: 1px solid var(--border); border-radius: 12px;
            display: flex; align-items: center; gap: 0.7rem;
        }
        .drawer-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: var(--dark); color: var(--gold);
            font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--gold); flex-shrink: 0; overflow: hidden;
        }
        .drawer-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .drawer-uname { font-family: 'Sora', sans-serif; font-size: 0.85rem; font-weight: 700; line-height: 1.2; }
        .drawer-urole { font-size: 0.68rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }

        /* Section label */
        .d-section-label {
            padding: 0.9rem 1.1rem 0.35rem;
            font-size: 0.66rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted);
        }

        /* Drawer nav */
        .d-nav { list-style: none; padding: 0 0.65rem; margin: 0; }
        .d-nav li a,
        .d-nav li .d-link {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.62rem 0.7rem; border-radius: 9px;
            font-size: 0.88rem; font-weight: 500; color: var(--dark);
            text-decoration: none; border: none; background: none;
            width: 100%; text-align: left; cursor: pointer;
            transition: background 0.18s, color 0.18s;
        }
        .d-nav li a:hover, .d-nav li .d-link:hover { background: var(--brand-muted); color: var(--brand); }
        .d-nav li a.active { background: var(--brand-muted); color: var(--brand); font-weight: 600; }
        .d-icon {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--border); color: var(--dark);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.88rem; flex-shrink: 0; transition: background 0.18s, color 0.18s;
        }
        .d-nav li a:hover .d-icon,
        .d-nav li a.active .d-icon,
        .d-nav li .d-link:hover .d-icon { background: var(--brand); color: #fff; }

        /* Auth buttons in drawer */
        .drawer-auth { padding: 0.85rem 1rem 0.5rem; display: flex; flex-direction: column; gap: 0.45rem; }
        .drawer-auth a {
            display: block; text-align: center; padding: 0.65rem; border-radius: 10px;
            font-weight: 700; font-size: 0.88rem; text-decoration: none; transition: all 0.2s;
        }
        .d-btn-login { border: 2px solid var(--brand); color: var(--brand); background: transparent; }
        .d-btn-login:hover { background: var(--brand); color: #fff; }
        .d-btn-signup { background: var(--brand); color: #fff !important; border: 2px solid var(--brand); }
        .d-btn-signup:hover { background: var(--brand-dark) !important; }

        /* Drawer footer */
        .drawer-foot {
            margin-top: auto; padding: 0.9rem 1rem;
            border-top: 1px solid var(--border);
            text-align: center; font-size: 0.73rem; color: var(--muted);
        }

        /* ══ MAIN CONTENT ══ */
        main { min-height: 60vh; }

        /* ══════════════════════════════════════
           FOOTER
        ══════════════════════════════════════ */
        .site-footer {
            background: #1A1714;
            color: rgba(255,255,255,0.82);
            padding: 4rem 0 0;
            margin-top: 5rem;
            font-size: 0.87rem;
        }
        .footer-in { max-width: 1400px; margin: 0 auto; padding: 0 1.5rem; }
        .footer-brand { font-family: 'Sora', sans-serif; font-size: 1.45rem; font-weight: 800; color: var(--brand); margin-bottom: 0.7rem; }
        .footer-tagline { color: rgba(255,255,255,0.48); line-height: 1.65; margin-bottom: 1.2rem; font-size: 0.83rem; }

        .footer-socials { display: flex; gap: 0.55rem; margin-bottom: 1.25rem; }
        .footer-socials a {
            width: 38px; height: 38px; border-radius: 50%;
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.65); display: flex; align-items: center; justify-content: center;
            text-decoration: none; font-size: 0.95rem; transition: all 0.28s;
        }
        .footer-socials a:hover { background: var(--brand); border-color: var(--brand); color: #fff; transform: translateY(-3px); }

        .app-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 0.85rem; background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.13); border-radius: 9px;
            color: rgba(255,255,255,0.82); text-decoration: none; font-size: 0.76rem;
            transition: all 0.2s; margin-right: 0.4rem; margin-bottom: 0.4rem;
        }
        .app-btn:hover { background: var(--brand); border-color: var(--brand); color: #fff; }
        .app-btn i { font-size: 1.1rem; }
        .app-btn-sub { font-size: 0.58rem; display: block; opacity: 0.65; }
        .app-btn-name { font-weight: 700; font-size: 0.8rem; }

        .footer-col-title {
            font-family: 'Sora', sans-serif; font-size: 0.76rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: rgba(255,255,255,0.38); margin-bottom: 0.9rem;
        }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a {
            color: rgba(255,255,255,0.65); text-decoration: none;
            transition: color 0.2s, padding-left 0.2s; display: inline-block;
        }
        .footer-links a:hover { color: var(--brand); padding-left: 4px; }

        .footer-contact-item {
            display: flex; align-items: center; gap: 0.55rem;
            margin-bottom: 0.55rem; color: rgba(255,255,255,0.58); font-size: 0.81rem;
        }
        .footer-c-icon {
            width: 28px; height: 28px; border-radius: 7px;
            background: rgba(255,107,53,0.14); color: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; flex-shrink: 0;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 1.1rem 0; margin-top: 3rem;
        }
        .footer-bottom-in {
            max-width: 1400px; margin: 0 auto; padding: 0 1.5rem;
            display: flex; flex-wrap: wrap; align-items: center;
            justify-content: space-between; gap: 0.5rem;
            font-size: 0.76rem; color: rgba(255,255,255,0.38);
        }
        .footer-bottom-in a { color: rgba(255,255,255,0.38); text-decoration: none; transition: color 0.2s; }
        .footer-bottom-in a:hover { color: var(--brand); }
        .footer-bottom-links { display: flex; gap: 1.1rem; flex-wrap: wrap; }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 991px) {
            .nav-links, .nav-auth { display: none !important; }
            .nav-hamburger { display: flex; }
        }

        /* ══ ANIMATIONS ══ */
        @keyframes fadeUp { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.55s ease forwards; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Logout form — MUST be outside nav/ul/li --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

{{-- ══════════════ NAVBAR ══════════════ --}}
<nav class="main-nav" id="mainNav">
    <div class="nav-inner">
        <a class="nav-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo/ejossolution.png') }}" alt="TapEats" class="nav-brand-logo">
            <span class="nav-brand-text">TapEats</span>
        </a>

        {{-- Desktop links --}}
        <ul class="nav-links">
            <li><a href="{{ route('home') }}"              class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('restaurantspublic') }}" class="{{ request()->routeIs('restaurantspublic') ? 'active' : '' }}">Restaurants</a></li>
            <li><a href="{{ route('dailymenuitems') }}"    class="{{ request()->routeIs('dailymenuitems') ? 'active' : '' }}">Daily Meals</a></li>
            <li><a href="{{ route('catering') }}"          class="{{ request()->routeIs('catering') ? 'active' : '' }}">Catering</a></li>
            <li><a href="{{ route('ordersindex') }}"       class="{{ request()->routeIs('ordersindex') ? 'active' : '' }}">My Orders</a></li>
            <li><a href="{{ route('aboutus') }}"           class="{{ request()->routeIs('aboutus') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('contactus') }}"         class="{{ request()->routeIs('contactus') ? 'active' : '' }}">Contact</a></li>
        </ul>

        {{-- Desktop auth --}}
        <div class="nav-auth">
            @guest
                <a href="{{ route('showLoginForm') }}"   class="btn-nav-login">Login</a>
                <a href="{{ route('showRegisterForm') }}" class="btn-nav-signup">Sign Up</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn-nav-signup">
                    <i class="bi bi-grid me-1"></i> Dashboard
                </a>
            @endguest
        </div>

        {{-- Hamburger —  mobile only --}}
        <button class="nav-hamburger" id="hamburgerBtn" aria-label="Open navigation menu" aria-expanded="false" aria-controls="mobileDrawer">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- ══════════════════════════════════════
     RIGHT-SIDE MOBILE DRAWER
══════════════════════════════════════ --}}
<div class="drawer-overlay" id="drawerOverlay" role="presentation"></div>

<aside class="mobile-drawer" id="mobileDrawer" aria-label="Navigation menu" aria-hidden="true">

    {{-- Header --}}
    <div class="drawer-header">
        <a class="nav-brand" href="{{ route('home') }}" style="gap:0.4rem;">
            <img src="{{ asset('images/logo/ejossolution.png') }}" alt="TapEats" style="width:26px;height:26px;border-radius:7px;">
            <span class="nav-brand-text" style="font-size:1.1rem;">TapEats</span>
        </a>
        <button class="drawer-close" id="drawerClose" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- User card --}}
    @auth
    <div class="drawer-user">
        <div class="drawer-avatar">
            @if(Auth::user()->profile_photo_path)
                <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}" alt="Avatar">
            @else
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            @endif
        </div>
        <div>
            <div class="drawer-uname">{{ Auth::user()->name ?? 'User' }}</div>
            <div class="drawer-urole">{{ strtoupper(Auth::user()->role ?? 'Customer') }}</div>
        </div>
    </div>
    @endauth

    {{-- Main navigation --}}
    <div class="d-section-label">Navigation</div>
    <ul class="d-nav">
        <li>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <span class="d-icon"><i class="bi bi-house"></i></span> Home
            </a>
        </li>
        <li>
            <a href="{{ route('restaurantspublic') }}" class="{{ request()->routeIs('restaurantspublic') ? 'active' : '' }}">
                <span class="d-icon"><i class="bi bi-shop"></i></span> Restaurants
            </a>
        </li>
        <li>
            <a href="{{ route('dailymenuitems') }}" class="{{ request()->routeIs('dailymenuitems') ? 'active' : '' }}">
                <span class="d-icon"><i class="bi bi-calendar3"></i></span> Daily Meals
            </a>
        </li>
        <li>
            <a href="{{ route('catering') }}" class="{{ request()->routeIs('catering') ? 'active' : '' }}">
                <span class="d-icon"><i class="bi bi-stars"></i></span> Catering
            </a>
        </li>
        <li>
            <a href="{{ route('ordersindex') }}" class="{{ request()->routeIs('ordersindex') ? 'active' : '' }}">
                <span class="d-icon"><i class="bi bi-bag-check"></i></span> My Orders
            </a>
        </li>
        <li>
            <a href="{{ route('aboutus') }}" class="{{ request()->routeIs('aboutus') ? 'active' : '' }}">
                <span class="d-icon"><i class="bi bi-info-circle"></i></span> About Us
            </a>
        </li>
        <li>
            <a href="{{ route('contactus') }}" class="{{ request()->routeIs('contactus') ? 'active' : '' }}">
                <span class="d-icon"><i class="bi bi-chat-dots"></i></span> Contact
            </a>
        </li>
    </ul>

    {{-- Authenticated extras --}}
    @auth
    <div class="d-section-label">My Account</div>
    <ul class="d-nav">
        <li>
            <a href="{{ route('customer.profile') }}">
                <span class="d-icon"><i class="bi bi-person"></i></span> My Profile
            </a>
        </li>
        <li>
            <a href="{{ route('dashboard') }}">
                <span class="d-icon"><i class="bi bi-grid"></i></span> Dashboard
            </a>
        </li>
        <li>
            <a href="#">
                <span class="d-icon"><i class="bi bi-heart"></i></span> Favourites
            </a>
        </li>
        <li>
            <a href="#">
                <span class="d-icon"><i class="bi bi-gear"></i></span> Settings
            </a>
        </li>
        <li>
            <button class="d-link" onclick="document.getElementById('logout-form').submit()">
                <span class="d-icon" style="background:#FEE2E2;color:#EF4444;"><i class="bi bi-box-arrow-right"></i></span>
                <span style="color:#EF4444;font-weight:600;">Logout</span>
            </button>
        </li>
    </ul>
    @endauth

    {{-- Support --}}
    <div class="d-section-label">Support</div>
    <ul class="d-nav">
        <li>
            <a href="{{ route('customersupport') }}">
                <span class="d-icon"><i class="bi bi-headset"></i></span> Help Center
            </a>
        </li>
        <li>
            <a href="#">
                <span class="d-icon"><i class="bi bi-shield-check"></i></span> Privacy Policy
            </a>
        </li>
        <li>
            <a href="#">
                <span class="d-icon"><i class="bi bi-file-text"></i></span> Terms of Service
            </a>
        </li>
    </ul>

    {{-- Guest auth buttons --}}
    @guest
    <div class="drawer-auth">
        <a href="{{ route('showLoginForm') }}"    class="d-btn-login">Login</a>
        <a href="{{ route('showRegisterForm') }}" class="d-btn-signup">Create Account</a>
    </div>
    @endguest

    <div class="drawer-foot">🍽️ TapEats &nbsp;·&nbsp; Made with ❤️ in Tanzania</div>
</aside>

{{-- ══ MAIN CONTENT ══ --}}
<main>
    @yield('content')
</main>

{{-- ══════════════ FOOTER ══════════════ --}}
<footer class="site-footer">
    <div class="footer-in">
        <div class="row g-4">

            {{-- Brand --}}
            <div class="col-lg-4 col-md-12">
                <div class="footer-brand">🍽️ TapEats</div>
                <p class="footer-tagline">Your trusted food partner for delivery, daily meals, and catering across Tanzania. Fresh food, fast delivery, every day.</p>
                <div class="footer-socials">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                </div>
                <a href="#" class="app-btn">
                    <i class="bi bi-apple"></i>
                    <div><span class="app-btn-sub">Download on the</span><span class="app-btn-name">App Store</span></div>
                </a>
                <a href="#" class="app-btn">
                    <i class="bi bi-google-play"></i>
                    <div><span class="app-btn-sub">Get it on</span><span class="app-btn-name">Google Play</span></div>
                </a>
            </div>

            {{-- Company --}}
            <div class="col-lg-2 col-6 col-md-3">
                <div class="footer-col-title">Company</div>
                <ul class="footer-links">
                    <li><a href="{{ route('aboutus') }}">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Press</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>

            {{-- Services --}}
            <div class="col-lg-2 col-6 col-md-3">
                <div class="footer-col-title">Services</div>
                <ul class="footer-links">
                    <li><a href="{{ route('restaurantspublic') }}">Food Delivery</a></li>
                    <li><a href="{{ route('dailymenuitems') }}">Daily Meals</a></li>
                    <li><a href="{{ route('catering') }}">Catering</a></li>
                    <li><a href="#">Corporate Orders</a></li>
                    <li><a href="#">Gift Cards</a></li>
                </ul>
            </div>

            {{-- Partners --}}
            <div class="col-lg-2 col-6 col-md-3">
                <div class="footer-col-title">Partners</div>
                <ul class="footer-links">
                    <li><a href="{{ route('showRegisterForm') }}">Become a Restaurant</a></li>
                    <li><a href="{{ route('showRegisterForm') }}">Delivery Partner</a></li>
                    <li><a href="{{ route('showRegisterForm') }}">Partner Portal</a></li>
                    <li><a href="{{ route('showLoginForm') }}">Supplier Login</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-lg-2 col-6 col-md-3">
                <div class="footer-col-title">Contact</div>
                <div class="footer-contact-item"><div class="footer-c-icon"><i class="bi bi-telephone"></i></div>+255 628 052 602</div>
                <div class="footer-contact-item"><div class="footer-c-icon"><i class="bi bi-envelope"></i></div>ejossolution@ejossolution.co.tz</div>
                <div class="footer-contact-item"><div class="footer-c-icon"><i class="bi bi-geo-alt"></i></div>Tanzania, Africa</div>
                <div style="margin-top:0.7rem;">
                    <a href="{{ route('customersupport') }}" style="color:var(--brand);font-size:0.8rem;font-weight:600;text-decoration:none;">
                        <i class="bi bi-headset me-1"></i> Help Center →
                    </a>
                </div>
            </div>

        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-in">
            <span>&copy; {{ date('Y') }} TapEats. All rights reserved.</span>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
            <span>Made with <i class="bi bi-heart-fill" style="color:var(--brand);"></i> in Tanzania</span>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle (required for dropdowns etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /* ── Navbar scroll shrink ── */
    const mainNav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        mainNav.classList.toggle('scrolled', window.scrollY > 50);
    }, { passive: true });

    /* ── RIGHT-SIDE DRAWER ── */
    const hamburger     = document.getElementById('hamburgerBtn');
    const drawerClose   = document.getElementById('drawerClose');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const drawer        = document.getElementById('mobileDrawer');

    function openDrawer() {
        document.body.classList.add('drawer-open');
        document.body.style.overflow = 'hidden';   // lock scroll
        hamburger.setAttribute('aria-expanded', 'true');
        drawer.setAttribute('aria-hidden', 'false');
    }

    function closeDrawer() {
        document.body.classList.remove('drawer-open');
        document.body.style.overflow = '';
        hamburger.setAttribute('aria-expanded', 'false');
        drawer.setAttribute('aria-hidden', 'true');
    }

    hamburger.addEventListener('click', () =>
        document.body.classList.contains('drawer-open') ? closeDrawer() : openDrawer()
    );
    drawerClose.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
    window.addEventListener('resize', () => { if (window.innerWidth >= 992) closeDrawer(); });
</script>

@stack('scripts')
</body>
</html>