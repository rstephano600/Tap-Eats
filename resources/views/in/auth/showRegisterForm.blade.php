<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TapEats | Create Account</title>
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

        .auth-visual {
            flex: 1;
            position: relative;
            display: none;
            background: var(--darkblue);
            overflow: hidden;
        }
        @media (min-width: 992px) {
            .auth-visual { display: block; }
            .auth-form-container { width: 45%; }
        }

        .carousel-item img {
            height: 100vh;
            object-fit: cover;
            filter: brightness(0.4) contrast(1.1);
        }

        .visual-overlay-content {
            position: absolute;
            bottom: 10%;
            left: 10%;
            right: 10%;
            color: #fff;
            z-index: 10;
        }
        .visual-overlay-content h1 {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 0.75rem;
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
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }
        .visual-features {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .visual-feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.8);
        }
        .visual-feature-item i {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255,167,38,0.2);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.85rem;
        }

        /* =====================
           FORM PANEL
        ===================== */
        .auth-form-container {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 2rem;
            background: #f8f9fa;
            overflow-y: auto;
        }

        .register-box {
            width: 100%;
            max-width: 440px;
            padding: 0.5rem 0;
        }

        /* =====================
           FORM ELEMENTS
        ===================== */
        .form-label {
            font-size: 0.82rem;
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
            font-size: 1rem;
        }
        .form-control {
            border: none;
            padding: 0.72rem 0.75rem 0.72rem 0;
            font-size: 0.88rem;
            background: transparent;
            color: #1a1a2e;
        }
        .form-control:focus {
            box-shadow: none;
            background: transparent;
        }
        .form-control::placeholder { color: #adb5bd; }

        /* Password toggle */
        .password-toggle {
            background: transparent;
            border: none;
            color: #9ca3af;
            padding: 0 14px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: color 0.2s;
            flex-shrink: 0;
        }
        .password-toggle:hover { color: var(--darkblue); }

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
        .btn-accent:active { transform: translateY(0); }

        /* Password strength bar */
        .strength-bar-wrap {
            display: flex;
            gap: 4px;
            margin-top: 6px;
        }
        .strength-bar-seg {
            height: 3px;
            flex: 1;
            border-radius: 3px;
            background: #e9ecef;
            transition: background 0.3s;
        }
        .strength-label {
            font-size: 0.72rem;
            margin-top: 4px;
            font-weight: 600;
        }

        /* =====================
           ALERTS
        ===================== */
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
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .auth-alert-error {
            background: rgba(220,53,69,0.07);
            border: 1px solid rgba(220,53,69,0.25);
            color: #b02a37;
        }
        .auth-alert-success {
            background: rgba(40,167,69,0.07);
            border: 1px solid rgba(40,167,69,0.25);
            color: #1a6b30;
        }
        .auth-alert-icon {
            font-size: 1.2rem;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .auth-alert-title {
            font-weight: 700;
            font-size: 0.82rem;
            margin-bottom: 3px;
        }
        .auth-alert-message {
            font-size: 0.8rem;
            line-height: 1.45;
        }
        .auth-alert-list {
            margin: 4px 0 0 0;
            padding-left: 1rem;
            font-size: 0.79rem;
            line-height: 1.8;
        }
        .auth-alert-close {
            background: transparent;
            border: none;
            color: inherit;
            opacity: 0.5;
            cursor: pointer;
            padding: 0;
            font-size: 0.75rem;
            flex-shrink: 0;
            margin-left: auto;
            transition: opacity 0.2s;
        }
        .auth-alert-close:hover { opacity: 1; }

        /* Inline field errors */
        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #dc3545;
            font-size: 0.76rem;
            margin-top: 5px;
            animation: alertSlideDown 0.25s ease forwards;
        }
        .field-error i { font-size: 0.75rem; }

        /* Field success tick */
        .field-success {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #28a745;
            font-size: 0.76rem;
            margin-top: 5px;
        }

        /* Step progress */
        .register-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 1.5rem;
        }
        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #dee2e6;
            transition: all 0.3s;
        }
        .step-dot.active {
            width: 24px;
            border-radius: 4px;
            background: var(--accent);
        }

        .text-darkblue { color: var(--darkblue) !important; }
        .login-logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0,31,63,0.15));
        }
    </style>
        <style>
        :root {
            --bs-darkblue: #001f3f; 
            --bs-accent: #FFA726;   
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
        }

        /* Split Screen Container */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Left Side: Visual/Carousel */
        .auth-visual {
            flex: 1;
            position: relative;
            display: none; /* Hidden on mobile */
            background-color: var(--bs-darkblue);
        }

        /* Right Side: Form */
        .auth-form-container {
            width: 100%;
            max-width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: #f8f9fa;
        }

        @media (min-width: 992px) {
            .auth-visual { display: block; }
            .auth-form-container { width: 40%; }
        }

        /* Carousel Image Styling */
        .carousel-item img {
            height: 100vh;
            object-fit: cover;
            filter: brightness(0.6);
        }

        .visual-overlay-text {
            position: absolute;
            bottom: 10%;
            left: 10%;
            color: white;
            z-index: 10;
        }

        /* Form Styling */
        .register-box {
            width: 100%;
            max-width: 450px;
        }

        .btn-accent { 
            background-color: var(--bs-accent); 
            border-color: var(--bs-accent); 
            color: var(--bs-darkblue); 
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-accent:hover { 
            background-color: #e09400; 
            color: #fff; 
            transform: translateY(-2px);
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 167, 38, 0.25);
            border-color: var(--bs-accent);
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    {{-- =====================
         LEFT — Visual Panel
    ===================== --}}
    <div class="auth-visual">
        <div id="authCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel">
            <div class="carousel-inner h-100">
                <div class="carousel-item active h-100" data-bs-interval="5000">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=1200"
                         class="d-block w-100" alt="Food">
                </div>
                <div class="carousel-item h-100" data-bs-interval="5000">
                    <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&q=80&w=1200"
                         class="d-block w-100" alt="Food 2">
                </div>
                <div class="carousel-item h-100" data-bs-interval="5000">
                    <img src="https://images.unsplash.com/photo-1473093226795-af9932fe5856?auto=format&fit=crop&q=80&w=1200"
                         class="d-block w-100" alt="Food 3">
                </div>
            </div>
        </div>

        <div class="visual-overlay-content">
            <div class="visual-badge">
                <i class="bi bi-star-fill"></i> Join 50,000+ Food Lovers
            </div>
            <h1>Start Your Food Journey Today!</h1>
            <p style="color:rgba(255,255,255,0.7);font-size:0.95rem;line-height:1.6;">
                Create your free account and get access to hundreds of restaurants, exclusive deals, and fast delivery.
            </p>
            <div class="visual-features">
                <div class="visual-feature-item">
                    <i class="bi bi-lightning-fill"></i>
                    Fast delivery in under 30 minutes
                </div>
                <div class="visual-feature-item">
                    <i class="bi bi-shield-check-fill"></i>
                    Secure & easy payments
                </div>
                <div class="visual-feature-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    Real-time order tracking
                </div>
                <div class="visual-feature-item">
                    <i class="bi bi-gift-fill"></i>
                    Exclusive deals for new users
                </div>
            </div>
        </div>
    </div>

    {{-- =====================
         RIGHT — Form Panel
    ===================== --}}
    <div class="auth-form-container">
        <div class="register-box">

            {{-- Logo --}}
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo/ejossolution.png') }}"
                     alt="TapEats Logo" class="login-logo mb-3">
                <h2 class="fw-bold mb-1" style="color:var(--darkblue);">Create Account</h2>
                <p class="text-muted mb-0" style="font-size:0.84rem;">
                    Join TapEats and start ordering today
                </p>
            </div>

            {{-- Progress dots --}}
            <div class="register-steps">
                <div class="step-dot active"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
            </div>

            {{-- =====================
                 SESSION ALERTS
            ===================== --}}
            @if(session('error'))
            <div class="auth-alert auth-alert-error">
                <i class="bi bi-shield-exclamation auth-alert-icon"></i>
                <div class="flex-grow-1">
                    <div class="auth-alert-title">Registration Failed</div>
                    <div class="auth-alert-message">{{ session('error') }}</div>
                </div>
                <button class="auth-alert-close" onclick="this.closest('.auth-alert').remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            @if(session('success'))
            <div class="auth-alert auth-alert-success">
                <i class="bi bi-check-circle-fill auth-alert-icon"></i>
                <div class="flex-grow-1">
                    <div class="auth-alert-title">Success!</div>
                    <div class="auth-alert-message">{{ session('success') }}</div>
                </div>
                <button class="auth-alert-close" onclick="this.closest('.auth-alert').remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            {{-- Multiple validation errors summary --}}
            @if($errors->any())
            <div class="auth-alert auth-alert-error">
                <i class="bi bi-exclamation-triangle-fill auth-alert-icon"></i>
                <div class="flex-grow-1">
                    <div class="auth-alert-title">
                        {{ $errors->count() }} error(s) need your attention
                    </div>
                    <ul class="auth-alert-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="auth-alert-close" onclick="this.closest('.auth-alert').remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            {{-- =====================
                 REGISTER FORM
            ===================== --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Full Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="bi bi-person me-1 text-muted"></i> Full Name
                    </label>
                    <div class="input-group {{ $errors->has('name') ? 'is-invalid-group' : '' }}">
                        <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                        <input id="name" type="text"
                               class="form-control"
                               name="name"
                               value="{{ old('name') }}"
                               required autofocus
                               placeholder="e.g. Kasika Ejos">
                    </div>
                    @error('name')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @else
                        @if(old('name'))
                        <div class="field-success">
                            <i class="bi bi-check-circle-fill"></i> Looks good!
                        </div>
                        @endif
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-1 text-muted"></i> Email Address
                    </label>
                    <div class="input-group {{ $errors->has('email') ? 'is-invalid-group' : '' }}">
                        <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                        <input id="email" type="email"
                               class="form-control"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               placeholder="you@example.com">
                    </div>
                    @error('email')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @else
                        @if(old('email'))
                        <div class="field-success">
                            <i class="bi bi-check-circle-fill"></i> Email looks valid!
                        </div>
                        @endif
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">
                        <i class="bi bi-phone me-1 text-muted"></i> Phone Number
                        <span class="text-muted fw-normal" style="font-size:0.72rem;">
                            (Tanzanian number)
                        </span>
                    </label>
                    <div class="input-group {{ $errors->has('phone') ? 'is-invalid-group' : '' }}">
                        <span class="input-group-text">
                            <span style="font-size:0.8rem;">🇹🇿</span>
                            <span class="ms-1 text-muted" style="font-size:0.78rem;">+255</span>
                        </span>
                        <input id="phone" type="text"
                               class="form-control"
                               name="phone"
                               value="{{ old('phone') }}"
                               required
                               placeholder="0657 856 790"
                               maxlength="13">
                    </div>
                    @error('phone')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @else
                        <div class="text-muted mt-1" style="font-size:0.72rem;">
                            <i class="bi bi-info-circle me-1"></i>
                            Accepted formats: 0657856790 or +255657856790
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-1 text-muted"></i> Password
                    </label>
                    <div class="input-group {{ $errors->has('password') ? 'is-invalid-group' : '' }}">
                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                        <input id="password" type="password"
                               class="form-control"
                               name="password"
                               required
                               placeholder="Min. 6 characters"
                               oninput="checkStrength(this.value)">
                        <button type="button" class="password-toggle" onclick="togglePass('password', 'eyeIcon1')" title="Show/hide">
                            <i class="bi bi-eye" id="eyeIcon1"></i>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="strength-bar-wrap" id="strengthBar">
                        <div class="strength-bar-seg" id="seg1"></div>
                        <div class="strength-bar-seg" id="seg2"></div>
                        <div class="strength-bar-seg" id="seg3"></div>
                        <div class="strength-bar-seg" id="seg4"></div>
                    </div>
                    <div class="strength-label text-muted" id="strengthLabel"></div>
                    @error('password')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>



                {{-- Terms --}}
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               id="terms" name="terms" required
                               {{ old('terms') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted"
                               for="terms" style="font-size:0.8rem;">
                            I agree to the
                            <a href="#" style="color:var(--darkblue);font-weight:600;">Terms of Service</a>
                            and
                            <a href="#" style="color:var(--darkblue);font-weight:600;">Privacy Policy</a>
                        </label>
                    </div>
                    @error('terms')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-accent btn-lg rounded-pill">
                        <i class="bi bi-person-plus me-2"></i> Create My Account
                    </button>
                </div>

                {{-- Login Link --}}
                <div class="text-center">
                    <p class="text-muted mb-0" style="font-size:0.83rem;">
                        Already have an account?
                        <a href="{{ route('showLoginForm') }}"
                           class="fw-bold text-decoration-none"
                           style="color:var(--darkblue);">
                            Sign In
                        </a>
                    </p>
                </div>

            </form>

            {{-- Footer --}}
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
    // =============================================
    // Password show/hide toggle
    // =============================================
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // =============================================
    // Password strength checker
    // =============================================
    function checkStrength(val) {
        const segs  = [
            document.getElementById('seg1'),
            document.getElementById('seg2'),
            document.getElementById('seg3'),
            document.getElementById('seg4'),
        ];
        const label = document.getElementById('strengthLabel');

        // Reset
        segs.forEach(s => s.style.background = '#e9ecef');
        label.textContent = '';

        if (!val) return;

        let score = 0;
        if (val.length >= 6)  score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { color: '#dc3545', text: 'Weak',      textColor: '#dc3545' },
            { color: '#FFA726', text: 'Fair',      textColor: '#FFA726' },
            { color: '#0dcaf0', text: 'Good',      textColor: '#0dcaf0' },
            { color: '#28a745', text: 'Strong 💪', textColor: '#28a745' },
        ];

        for (let i = 0; i < score; i++) {
            segs[i].style.background = levels[score - 1].color;
        }

        label.textContent  = levels[score - 1].text;
        label.style.color  = levels[score - 1].textColor;
    }

    // =============================================
    // Auto-dismiss alerts after 7 seconds
    // =============================================
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.auth-alert').forEach(function (alert) {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                alert.style.opacity    = '0';
                alert.style.transform  = 'translateY(-8px)';
                setTimeout(() => alert.remove(), 400);
            }, 7000);
        });
    });
</script>

</body>
</html>