<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TapEats | Forgot Password</title>
    <link rel="icon" href="{{ asset('images/logo/ejossolution.png') }}" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Custom Dark Blue Theme Styling (Reused from app.blade.php) */
        :root {
            --bs-darkblue: #001f3f; 
            --bs-accent: #FFA726;   
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
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 31, 63, 0.1);
        }
    </style>
</head>
<body>

    <div class="auth-card card p-4 p-md-5">
        <div class="card-body">
            <h1 class="card-title text-center text-darkblue fw-bold mb-4">
                Reset Password
            </h1>
            <p class="text-center text-muted mb-4">
                Enter your email address to receive a password reset link.
            </p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autofocus placeholder="email@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-accent btn-lg rounded-pill shadow-sm">
                        Email Password Reset Link <i class="bi bi-send-fill ms-2"></i>
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <hr>
            <p class="text-center mt-3 mb-0">
                <a href="{{ route('login') }}" class="text-darkblue fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>