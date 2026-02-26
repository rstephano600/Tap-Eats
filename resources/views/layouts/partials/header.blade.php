{{-- ══ FIXED LOGOUT FORM — outside all nav/ul/li tags ══ --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top" style="padding: 0.6rem 1.25rem; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
    <div class="container-fluid px-0">

        {{-- Sidebar Toggle --}}
        <button class="btn sidebar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

        {{-- Page Title --}}
        <span class="ms-3 fw-semibold text-dark d-none d-md-inline" style="font-size: 1rem; letter-spacing: 0.2px;">
            @yield('page_title', 'Dashboard')
        </span>

        {{-- Right Side --}}
        <div class="ms-auto d-flex align-items-center gap-2">

            {{-- Search (desktop) --}}
            <div class="input-group input-group-sm d-none d-lg-flex me-2" style="width: 220px;">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control bg-light border-start-0 ps-0"
                       placeholder="Search..."
                       style="box-shadow: none;">
            </div>

            {{-- ══ NOTIFICATIONS DROPDOWN ══ --}}
            <div class="dropdown">
                {{--
                    FIX: use type="button" and ensure Bootstrap 5 JS is loaded.
                    data-bs-toggle="dropdown" only works with Bootstrap 5 JS bundle.
                --}}
                <button type="button"
                        class="btn icon-btn position-relative"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        aria-label="Notifications">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="notif-badge" id="notifCount">3</span>
                </button>

                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0 rounded-3"
                     style="width: 320px;">

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between px-3 py-3 rounded-top"
                         style="background: #001f3f;">
                        <h6 class="mb-0 text-white fw-semibold">
                            <i class="bi bi-bell me-2"></i>Notifications
                        </h6>
                        <span class="badge rounded-pill" style="background: #FFA726; font-size: 0.7rem;">
                            3 New
                        </span>
                    </div>

                    {{-- Notification Items --}}
                    <div class="notif-list">
                        <a class="dropdown-item notif-item d-flex align-items-start py-3 px-3" href="#">
                            <div class="notif-icon bg-primary-subtle text-primary me-3">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-semibold small">Order Confirmed</p>
                                <p class="mb-0 text-muted" style="font-size: 0.78rem;">Kitchen is preparing your meal.</p>
                                <p class="mb-0 text-muted" style="font-size: 0.7rem; margin-top: 2px;">
                                    <i class="bi bi-clock me-1"></i>2 mins ago
                                </p>
                            </div>
                            <span class="notif-dot"></span>
                        </a>

                        <a class="dropdown-item notif-item d-flex align-items-start py-3 px-3" href="#">
                            <div class="notif-icon bg-success-subtle text-success me-3">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-semibold small">New Supplier Registered</p>
                                <p class="mb-0 text-muted" style="font-size: 0.78rem;">A new supplier joined TapEats.</p>
                                <p class="mb-0 text-muted" style="font-size: 0.7rem; margin-top: 2px;">
                                    <i class="bi bi-clock me-1"></i>15 mins ago
                                </p>
                            </div>
                            <span class="notif-dot"></span>
                        </a>

                        <a class="dropdown-item notif-item d-flex align-items-start py-3 px-3" href="#">
                            <div class="notif-icon bg-warning-subtle text-warning me-3">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-semibold small">Low Stock Alert</p>
                                <p class="mb-0 text-muted" style="font-size: 0.78rem;">Some menu items are running low.</p>
                                <p class="mb-0 text-muted" style="font-size: 0.7rem; margin-top: 2px;">
                                    <i class="bi bi-clock me-1"></i>1 hr ago
                                </p>
                            </div>
                            {{-- No dot = already read --}}
                        </a>
                    </div>

                    {{-- Footer --}}
                    <a class="dropdown-item text-center py-2 border-top small fw-semibold"
                       style="color: #001f3f;" href="#">
                        View All Notifications <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- Divider --}}
            <div style="width: 1px; height: 28px; background: #dee2e6;"></div>

            {{-- ══ USER DROPDOWN ══ --}}
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-1"
                   href="#"
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">

                    {{-- Avatar --}}
                    @if(Auth::check() && Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}"
                             class="rounded-circle border border-2"
                             style="width: 38px; height: 38px; object-fit: cover; border-color: #FFA726 !important;"
                             alt="Avatar">
                    @else
                        <div class="user-avatar-initials">
                            {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                        </div>
                    @endif

                    {{-- Name & Role --}}
                    <div class="text-start d-none d-sm-block lh-sm">
                        <div class="fw-semibold small text-dark" style="font-size: 0.85rem;">
                            {{ Auth::user()->name ?? 'Guest User' }}
                        </div>
                        <div class="text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ strtoupper(Auth::user()->role ?? 'Visitor') }}
                        </div>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3 p-1"
                    style="min-width: 200px;">
                    <li>
                        <div class="px-3 py-2 mb-1 rounded-2" style="background: #f8f9fa;">
                            <div class="fw-semibold small text-dark">{{ Auth::user()->name ?? 'Guest' }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 py-2" href="{{ route('customer.profile') }}">
                            <i class="bi bi-person me-2 text-primary"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 py-2" href="#">
                            <i class="bi bi-gear me-2 text-secondary"></i> Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>

                    {{--
                        FIX: logout is a plain <a> that calls submitLogout().
                        The actual <form> lives OUTSIDE the nav (above), so it
                        is never trapped inside a <ul>/<li>, which is invalid HTML
                        and can silently break form submission in some browsers.
                    --}}
                    <li>
                        <a class="dropdown-item rounded-2 py-2 text-danger"
                           href="#"
                           onclick="submitLogout(event)">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

        </div>{{-- end right side --}}
    </div>
</nav>

<style>
    .sidebar-toggle-btn {
        background: transparent;
        border: none;
        color: #444;
        padding: 0.4rem 0.6rem;
        border-radius: 8px;
        transition: background 0.2s;
    }
    .sidebar-toggle-btn:hover {
        background: #f0f0f0;
        color: #001f3f;
    }

    .icon-btn {
        background: transparent;
        border: none;
        color: #555;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: background 0.2s, color 0.2s;
    }
    .icon-btn:hover {
        background: #f0f0f0;
        color: #001f3f;
    }

    .notif-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: #e53935;
        color: #fff;
        font-size: 0.58rem;
        font-weight: 700;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }

    .notif-item {
        border-bottom: 1px solid #f2f2f2;
        transition: background 0.15s;
    }
    .notif-item:hover { background: #fafafa !important; }
    .notif-item:last-of-type { border-bottom: none; }

    .notif-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    .notif-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #FFA726;
        flex-shrink: 0;
        margin-top: 4px;
    }

    .user-avatar-initials {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #001f3f;
        color: #FFA726;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #FFA726;
        flex-shrink: 0;
    }

    .dropdown-menu .dropdown-item:hover { background: #f5f5f5; }
    .form-control:focus { box-shadow: none; border-color: #ced4da; }
</style>

<script>
    /**
     * submitLogout — called by the Logout anchor.
     * Finds the form that lives outside <nav> and submits it.
     */
    function submitLogout(e) {
        e.preventDefault();
        const form = document.getElementById('logout-form');
        if (form) {
            form.submit();
        } else {
            console.error('Logout form not found in DOM.');
        }
    }

    /**
     * Sidebar toggle — fires a custom event so your layout JS can react.
     */
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.body.classList.toggle('sidebar-collapsed');
        this.dispatchEvent(new CustomEvent('sidebar-toggle', { bubbles: true }));
    });
</script>