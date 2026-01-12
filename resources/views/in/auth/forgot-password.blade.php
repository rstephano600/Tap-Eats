<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TapEats | Reset Password</title>
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
                Set New Password
            </h1>
            <p class="text-center text-muted mb-4">
                Please enter your email and a new, secure password.
            </p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                
                <!-- Password Reset Token (Hidden) -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input (Pre-filled) -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email', $request->email) }}" required autofocus readonly>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password Input -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Confirm Password Input -->
                <div class="mb-4">
                    <label for="password-confirm" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" required placeholder="••••••••">
                    </div>
                </div>

                <!-- Reset Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-accent btn-lg rounded-pill shadow-sm">
                        Reset Password <i class="bi bi-check-circle-fill ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>