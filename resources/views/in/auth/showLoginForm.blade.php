<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TapEats | Login</title>
    <link rel="icon" href="{{ asset('images/logo/ejossolution.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --darkblue: #001f3f;
            --accent:   #FFA726;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f8f9fa;
        }

        /* =====================
           LAYOUT
        ===================== */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Left visual panel */
        .auth-visual {
            flex: 1;
            position: relative;
            display: none;
            background: var(--darkblue);
            overflow: hidden;
        }
        @media (min-width: 992px) {
            .auth-visual { display: block; }
            .auth-form-container { width: 42%; }
        }

        .carousel-item img {
            height: 100vh;
            object-fit: cover;
            filter: brightness(0.45) contrast(1.1);
        }

        .visual-overlay-content {
            position: absolute;
            bottom: 12%;
            left: 10%;
            right: 10%;
            color: #fff;
            z-index: 10;
        }
        .visual-overlay-content h1 {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .visual-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,167,38,0.18);
            border: 1px solid rgba(255,167,38,0.4);
            color: var(--accent);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 1.25rem;
        }

        /* Right form panel */
        .auth-form-container {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
            background: #f8f9fa;
            overflow-y: auto;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        /* =====================
           FORM ELEMENTS
        ===================== */
        .form-label {
            font-size: 0.83rem;
            font-weight: 600;
            color: #344054;
            margin-bottom: 6px;
        }

        .input-group {
            border: 1.5px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-group:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255,167,38,0.12);
        }
        .input-group.is-invalid-group {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220,53,69,0.1);
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #9ca3af;
            padding: 0 14px;
        }
        .form-control {
            border: none;
            padding: 0.72rem 0.75rem 0.72rem 0;
            font-size: 0.88rem;
            background: transparent;
        }
        .form-control:focus {
            box-shadow: none;
            background: transparent;
        }

        /* Submit Button */
        .btn-accent {
            background: var(--accent);
            border: none;
            color: var(--darkblue);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.75rem;
            letter-spacing: 0.3px;
            transition: all 0.25s ease;
        }
        .btn-accent:hover {
            background: #e09400;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255,167,38,0.35);
            color: #fff;
        }
        .btn-accent:active {
            transform: translateY(0);
        }

        /* =====================
           ALERT STYLES
        ===================== */

        /* Session error (account deactivated etc.) */
        .auth-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            font-size: 0.84rem;
            animation: alertSlideDown 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        @keyframes alertSlideDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-alert-error {
            background: rgba(220, 53, 69, 0.07);
            border: 1px solid rgba(220, 53, 69, 0.25);
            color: #b02a37;
        }
        .auth-alert-success {
            background: rgba(40, 167, 69, 0.07);
            border: 1px solid rgba(40, 167, 69, 0.25);
            color: #1a6b30;
        }
        .auth-alert-warning {
            background: rgba(255, 167, 38, 0.1);
            border: 1px solid rgba(255, 167, 38, 0.35);
            color: #7d4e00;
        }

        .auth-alert-icon {
            font-size: 1.2rem;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .auth-alert-title {
            font-weight: 700;
            font-size: 0.82rem;
            margin-bottom: 2px;
        }
        .auth-alert-message {
            font-size: 0.8rem;
            line-height: 1.45;
            opacity: 0.9;
        }
        .auth-alert-close {
            background: transparent;
            border: none;
            color: inherit;
            opacity: 0.5;
            cursor: pointer;
            margin-left: auto;
            padding: 0;
            font-size: 0.75rem;
            flex-shrink: 0;
            transition: opacity 0.2s;
        }
        .auth-alert-close:hover { opacity: 1; }

        /* Inline field errors */
        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #dc3545;
            font-size: 0.77rem;
            margin-top: 5px;
            animation: alertSlideDown 0.25s ease forwards;
        }
        .field-error i { font-size: 0.78rem; }

        /* =====================
           DIVIDER
        ===================== */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.25rem 0;
            color: #adb5bd;
            font-size: 0.75rem;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e9ecef;
        }

        /* =====================
           MISC
        ===================== */
        .text-darkblue { color: var(--darkblue) !important; }

        .form-check-input:checked {
            background-color: var(--darkblue);
            border-color: var(--darkblue);
        }

        .login-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0,31,63,0.15));
        }

        /* Password toggle */
        .password-toggle {
            background: transparent;
            border: none;
            color: #9ca3af;
            padding: 0 14px;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.2s;
        }
        .password-toggle:hover { color: var(--darkblue); }
    </style>
        <style>
        :root {
            --bs-darkblue: #001f3f; 
            --bs-accent: #FFA726;   
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #fff;
        }

        /* Split Screen Container */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Left Side: Dynamic Carousel (Desktop Only) */
        .auth-visual {
            flex: 1;
            position: relative;
            display: none; 
            background-color: var(--bs-darkblue);
        }

        /* Right Side: Login Form */
        .auth-form-container {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background-color: #f8f9fa;
        }

        @media (min-width: 992px) {
            .auth-visual { display: block; }
            .auth-form-container { width: 40%; }
        }

        /* Carousel Image Effects */
        .carousel-item img {
            height: 100vh;
            object-fit: cover;
            filter: brightness(0.5) contrast(1.1);
        }

        .visual-overlay-content {
            position: absolute;
            bottom: 15%;
            left: 10%;
            right: 10%;
            color: white;
            z-index: 10;
        }

        /* Form & Component Styling */
        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .btn-accent { 
            background-color: var(--bs-accent); 
            border-color: var(--bs-accent); 
            color: var(--bs-darkblue); 
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-accent:hover { 
            background-color: #e09400; 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 167, 38, 0.3);
        }

        .input-group-text {
            background-color: transparent;
            border-right: none;
            color: #6c757d;
        }

        .form-control {
            border-left: none;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(255, 167, 38, 0.15);
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    {{-- =====================
         LEFT — Visual Panel
    ===================== --}}
    <div class="auth-visual">
        <div id="loginCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel">
            <div class="carousel-inner h-100">
                <div class="carousel-item active h-100" data-bs-interval="4500">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=1200"
                         class="d-block w-100" alt="Restaurant">
                </div>
                <div class="carousel-item h-100" data-bs-interval="4500">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&q=80&w=1200"
                         class="d-block w-100" alt="Pizza">
                </div>
                <div class="carousel-item h-100" data-bs-interval="4500">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&q=80&w=1200"
                         class="d-block w-100" alt="Food">
                </div>
            </div>
        </div>

        <div class="visual-overlay-content">
            <div class="visual-badge">
                <i class="bi bi-patch-check-fill"></i> Tanzania's #1 Food Platform
            </div>
            <h1>Welcome Back to TapEats!</h1>
            <p class="fs-5 fw-light" style="color:rgba(255,255,255,0.75);">
                Savor the moment. Your favorite meals are just a few clicks away.
            </p>

            {{-- Stats row --}}
            <div class="d-flex gap-4 mt-4">
                <div>
                    <div class="fw-bold fs-4 text-white">500+</div>
                    <div style="font-size:0.78rem;color:rgba(255,255,255,0.55);">Restaurants</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,0.15);"></div>
                <div>
                    <div class="fw-bold fs-4 text-white">50K+</div>
                    <div style="font-size:0.78rem;color:rgba(255,255,255,0.55);">Happy Customers</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,0.15);"></div>
                <div>
                    <div class="fw-bold fs-4 text-white">30min</div>
                    <div style="font-size:0.78rem;color:rgba(255,255,255,0.55);">Avg Delivery</div>
                </div>
            </div>
        </div>
    </div>

    {{-- =====================
         RIGHT — Form Panel
    ===================== --}}
    <div class="auth-form-container">
        <div class="login-box">

            {{-- Logo & Heading --}}
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo/ejossolution.png') }}"
                     alt="TapEats Logo" class="login-logo mb-3">
                <h2 class="fw-bold mb-1" style="color:var(--darkblue);">Sign In</h2>
                <p class="text-muted" style="font-size:0.85rem;">
                    Enter your credentials to access your account.
                </p>
            </div>

            {{-- =====================
                 SESSION ALERTS
            ===================== --}}

            {{-- Account deactivated / suspended --}}
            @if(session('error'))
            <div class="auth-alert auth-alert-error" id="authAlertError">
                <i class="bi bi-shield-exclamation auth-alert-icon"></i>
                <div class="flex-grow-1">
                    <div class="auth-alert-title">Access Denied</div>
                    <div class="auth-alert-message">{{ session('error') }}</div>
                </div>
                <button class="auth-alert-close" onclick="this.closest('.auth-alert').remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            {{-- Success message (e.g. after logout) --}}
            @if(session('success'))
            <div class="auth-alert auth-alert-success" id="authAlertSuccess">
                <i class="bi bi-check-circle-fill auth-alert-icon"></i>
                <div class="flex-grow-1">
                    <div class="auth-alert-title">Success</div>
                    <div class="auth-alert-message">{{ session('success') }}</div>
                </div>
                <button class="auth-alert-close" onclick="this.closest('.auth-alert').remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            {{-- Warning --}}
            @if(session('warning'))
            <div class="auth-alert auth-alert-warning" id="authAlertWarning">
                <i class="bi bi-exclamation-triangle-fill auth-alert-icon"></i>
                <div class="flex-grow-1">
                    <div class="auth-alert-title">Warning</div>
                    <div class="auth-alert-message">{{ session('warning') }}</div>
                </div>
                <button class="auth-alert-close" onclick="this.closest('.auth-alert').remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            {{-- General validation errors summary --}}
            @if($errors->any() && !$errors->has('login') && !$errors->has('password'))
            <div class="auth-alert auth-alert-error">
                <i class="bi bi-exclamation-circle-fill auth-alert-icon"></i>
                <div class="flex-grow-1">
                    <div class="auth-alert-title">Please fix the following errors</div>
                    @foreach($errors->all() as $error)
                        <div class="auth-alert-message">• {{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- =====================
                 LOGIN FORM
            ===================== --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Login Field --}}
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-person me-1 text-muted"></i> Username, Email or Phone
                    </label>
                    <div class="input-group {{ $errors->has('login') ? 'is-invalid-group' : '' }}">
                        <span class="input-group-text">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="login"
                               value="{{ old('login') }}"
                               required
                               autofocus
                               placeholder="Enter username, email or phone">
                    </div>
                    @error('login')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-lock me-1 text-muted"></i> Password
                    </label>
                    <div class="input-group {{ $errors->has('password') ? 'is-invalid-group' : '' }}">
                        <span class="input-group-text">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input type="password"
                               id="passwordInput"
                               class="form-control"
                               name="password"
                               required
                               placeholder="••••••••">
                        <button type="button"
                                class="password-toggle"
                                onclick="togglePassword()"
                                title="Show/Hide password">
                            <i class="bi bi-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="remember" id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted"
                               for="remember" style="font-size:0.82rem;">
                            Keep me signed in
                        </label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-decoration-none fw-semibold"
                           style="color:var(--darkblue);font-size:0.82rem;">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-accent btn-lg rounded-pill">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                    </button>
                </div>

                {{-- Register Link --}}
                <div class="text-center">
                    <p class="text-muted mb-0" style="font-size:0.83rem;">
                        Don't have an account?
                        <a href="{{ route('showRegisterForm') }}"
                           class="fw-bold text-decoration-none"
                           style="color:var(--darkblue);">
                            Create Account
                        </a>
                    </p>
                </div>

            </form>

            {{-- Footer note --}}
            <div class="text-center mt-4">
                <small class="text-muted" style="font-size:0.72rem;">
                    &copy; {{ date('Y') }} TapEats &nbsp;·&nbsp; Built with
                    <span class="text-danger">❤️</span> for Foodies in Tanzania
                </small>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Password show/hide toggle
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('passwordToggleIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // Auto-dismiss alerts after 6 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.auth-alert');
        alerts.forEach(function (alert) {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(() => alert.remove(), 400);
            }, 6000);
        });
    });
</script>

</body>
</html>






