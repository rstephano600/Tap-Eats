<footer class="footer-wrapper mt-auto">
    <div class="container-fluid">
        <div class="row align-items-center gy-2">

            {{-- Left: Branding --}}
            <div class="col-md-4 text-center text-md-start">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                    <img src="{{ asset('images/logo/ejossolution.png') }}" alt="TapEats" style="height: 22px; width: auto; opacity: 0.85;">
                    <span class="footer-brand">TapEats</span>
                </div>
                <p class="footer-copy mb-0 mt-1">
                    &copy; {{ date('Y') }} TapEats. Built with <span class="text-danger">❤️</span> for Foodies.
                </p>
            </div>

            {{-- Center: Quick Links --}}
            <div class="col-md-4 text-center d-none d-md-block">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <a href="{{ url('/dashboard') }}" class="footer-link">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                    <span class="footer-divider">•</span>
                    <a href="{{ route('customer.profile') }}" class="footer-link">
                        <i class="bi bi-person me-1"></i>Profile
                    </a>
                    <span class="footer-divider">•</span>
                    <a href="#" class="footer-link">
                        <i class="bi bi-question-circle me-1"></i>Help
                    </a>
                </div>
            </div>

            {{-- Right: User Badge & Version --}}
            <div class="col-md-4 text-center text-md-end">
                <div class="d-flex align-items-center justify-content-center justify-content-md-end gap-2">

                    {{-- System Status --}}
                    <span class="footer-status">
                        <span class="status-dot"></span> System Online
                    </span>

                    <span class="footer-divider">|</span>

                    {{-- Logged in user --}}
                    @if(Auth::check())
                        <span class="footer-user-badge">
                            <i class="bi bi-person-fill me-1"></i>
                            {{ Auth::user()->username ?? Auth::user()->name }}
                            <span class="footer-role-tag">{{ strtoupper(Auth::user()->role ?? 'user') }}</span>
                        </span>
                    @else
                        <span class="footer-user-badge text-muted">
                            <i class="bi bi-person-slash me-1"></i> Guest
                        </span>
                    @endif

                </div>

                {{-- Version --}}
                <p class="footer-version mb-0 mt-1">v1.0.0 &nbsp;·&nbsp; {{ date('D, d M Y') }}</p>
            </div>

        </div>
    </div>
</footer>

<style>
    .footer-wrapper {
        background: #fff;
        border-top: 1px solid #e9ecef;
        padding: 0.85rem 1.5rem;
        box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.04);
    }

    /* Branding */
    .footer-brand {
        font-weight: 700;
        font-size: 0.95rem;
        color: #001f3f;
        letter-spacing: 0.3px;
    }

    .footer-copy {
        font-size: 0.72rem;
        color: #adb5bd;
    }

    /* Quick Links */
    .footer-link {
        font-size: 0.78rem;
        color: #6c757d;
        text-decoration: none;
        transition: color 0.2s;
    }
    .footer-link:hover {
        color: #001f3f;
    }

    .footer-divider {
        color: #dee2e6;
        font-size: 0.75rem;
    }

    /* System Status */
    .footer-status {
        font-size: 0.72rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #28a745;
        box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.2);
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {
        0%   { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
        70%  { box-shadow: 0 0 0 6px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    /* User Badge */
    .footer-user-badge {
        font-size: 0.75rem;
        font-weight: 600;
        color: #001f3f;
        background: #f0f4f8;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 0.25rem 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }

    .footer-role-tag {
        font-size: 0.62rem;
        font-weight: 700;
        background: #FFA726;
        color: #001f3f;
        border-radius: 10px;
        padding: 1px 6px;
        margin-left: 5px;
        letter-spacing: 0.5px;
    }

    /* Version & Date */
    .footer-version {
        font-size: 0.68rem;
        color: #ced4da;
        letter-spacing: 0.3px;
    }
</style>