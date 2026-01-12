<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TapEats | Register</title>
    <link rel="icon" href="{{ asset('images/logo/ejossolution.png') }}" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 31, 63, 0.1); 
        }
    </style>
</head>
<body>

    <div class="auth-card card p-4 p-md-5">
        <div class="card-body">
            <h1 class="card-title text-center text-darkblue fw-bold mb-4">
                Join TapEats
            </h1>
            <p class="text-center text-muted mb-4">
                Choose your account type to get started.
            </p>

            <form method="POST" action="{{ route('showRegisterForm') }}">
                @csrf

                <!-- Name Input -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Kasika Ejos">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required placeholder="ejos@solutions.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone Input -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Email Address</label>
                    <input id="phone" type="phone" class="form-control @error('phone') is-invalid @enderror" 
                           name="phone" value="{{ old('phone') }}" required placeholder="eg. 0657856790">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password" placeholder="••••••••">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" 
                           name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                </div>

                <!-- Role Selection -->
                <!-- <div class="mb-4">
                    <label class="form-label d-block">Account Type</label>
                    <div class="btn-group w-100" role="group" aria-label="Role selection">
                        
                        <input type="radio" class="btn-check" name="role" id="role_customer" value="customer" 
                               autocomplete="off" checked>
                        <label class="btn btn-outline-darkblue w-50" for="role_customer">
                            <i class="bi bi-person me-1"></i> Customer
                        </label>

                        <input type="radio" class="btn-check" name="role" id="role_supplier" value="supplier" 
                               autocomplete="off">
                        <label class="btn btn-outline-darkblue w-50" for="role_supplier">
                            <i class="bi bi-shop me-1"></i> Supplier
                        </label>
                    </div>
                    @error('role')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div> -->

                <!-- Register Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-accent btn-lg rounded-pill shadow-sm">
                        Register Account <i class="bi bi-person-plus-fill ms-2"></i>
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <hr>
            <p class="text-center mt-3 mb-0">
                Already have an account? 
                <a href="{{ route('showLoginForm') }}" class="text-darkblue fw-semibold">Log In Here</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>