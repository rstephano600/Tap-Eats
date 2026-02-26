




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
        
        <div class="auth-visual">
            <div id="loginCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="4000">
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=1200" class="d-block w-100" alt="Restaurant Interior">
                    </div>
                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&q=80&w=1200" class="d-block w-100" alt="Delicious Pizza">
                    </div>
                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&q=80&w=1200" class="d-block w-100" alt="Plated Food">
                    </div>
                </div>
                <div class="visual-overlay-content">
                    <h1 class="display-3 fw-bold mb-3">Welcome Back!</h1>
                    <p class="fs-4 fw-light">Savor the moment. Your favorite meals are just a few clicks away.</p>
                </div>
            </div>
        </div>

        <div class="auth-form-container">
            <div class="login-box">
                <div class="text-center mb-5">
                    <img src="{{ asset('images/logo/ejossolution.png') }}" alt="TapEats Logo" height="70" class="mb-4">
                    <h2 class="text-darkblue fw-bold">Sign In</h2>
                    <p class="text-muted">Enter your credentials to access your account.</p>
                </div>

                <form method="POST" action="{{ route('showLoginForm') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username or Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input id="login_field" type="text" class="form-control @error('login_field') is-invalid @enderror" 
                                   name="login" value="{{ old('login_field') }}" required autofocus placeholder="username or email">
                        </div>
                        @error('login_field') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required placeholder="••••••••">
                        </div>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="remember">Keep me signed in</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-darkblue fw-semibold small text-decoration-none" href="{{ route('password.request') }}">Forgot?</a>
                        @endif
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-accent btn-lg rounded-pill">
                            Log In <i class="bi bi-box-arrow-in-right ms-2"></i>
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-muted small">Don't have an account? 
                            <a href="{{ route('showRegisterForm') }}" class="text-darkblue fw-bold text-decoration-none">Create Account</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>