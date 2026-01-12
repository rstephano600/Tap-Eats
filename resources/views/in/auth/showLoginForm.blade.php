<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TapEats | Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo/ejossolution.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Custom Dark Blue Theme Styling (Reused from app.blade.php) */
        :root {
            --bs-darkblue: #001f3f; /* Deep Navy Blue */
            --bs-accent: #FFA726;   /* Vibrant Orange/Gold */
        }
        .bg-darkblue { background-color: var(--bs-darkblue) !important; }
        .text-darkblue { color: var(--bs-darkblue) !important; }
        .btn-accent { 
            background-color: var(--bs-accent); 
            border-color: var(--bs-accent); 
            color: var(--bs-darkblue); 
            font-weight: bold;
        }
        .btn-accent:hover { 
            background-color: #e09400; 
            border-color: #e09400; 
            color: #fff; 
        }
        /* Layout for Centering the Form */
        body {
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .auth-card {
            max-width: 450px;
            width: 90%;
            border-radius: 0.5rem;
            box-shadow: 0 1px 5px rgba(179, 190, 201, 0.1); /* Subtle shadow using dark blue */
        }
    </style>
</head>
<body>

    <div class="auth-card card p-4 p-md-5">
        <div class="card-body">
            <h1 class="card-title text-center text-darkblue fw-bold mb-4">
                TapEats Login
            </h1>
            <p class="text-center text-muted mb-4">
                Sign in to manage your orders or supplier portal.
            </p>

            <form method="POST" action="{{ route('showLoginForm') }}">
                @csrf

                <!-- Username/Email Input -->
                <div class="mb-3">
                    <label for="login_field" class="form-label">Username or Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input id="login_field" type="text" class="form-control @error('login_field') is-invalid @enderror" 
                               name="login" value="{{ old('login_field') }}" required autofocus placeholder="Enter username or email">
                        @error('login_field')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="text-darkblue fw-semibold" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-accent btn-lg rounded-pill shadow-sm">
                        Log In <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <hr>
            <p class="text-center mt-3 mb-0">
                New to TapEats? 
                <a href="{{ route('showRegisterForm') }}" class="text-darkblue fw-semibold">Create an Account</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>