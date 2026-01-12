<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2">
    <div class="container-fluid">
        <button class="btn btn-light border shadow-sm" id="sidebarToggle" type="button">
            <i class="bi bi-text-indent-left fs-5"></i>
        </button>

        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown me-3">
                <button class="btn btn-light position-relative rounded-circle p-2" data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        3
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-0" style="width: 300px;">
                    <div class="bg-darkblue text-white p-3 rounded-top">
                        <h6 class="mb-0">Notifications</h6>
                    </div>
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center py-2" href="#">
                            <div class="bg-light-primary rounded-circle p-2 me-3">
                                <i class="bi bi-box-seam text-primary"></i>
                            </div>
                            <div>
                                <p class="mb-0 small fw-bold">Order Confirmed</p>
                                <small class="text-muted">Kitchen is preparing your meal.</small>
                            </div>
                        </a>
                    </div>
                    <a class="dropdown-item text-center small text-primary py-2 border-top" href="#">View All</a>
                </div>
            </div>

            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <div class="text-end me-2 d-none d-sm-block">
                        <div class="fw-bold small text-dark">{{ Auth::user()->name ?? 'Guest User' }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ strtoupper(Auth::user()->role ?? 'Visitor') }}</div>
                    </div>
                    <div class="avatar-circle">
                        @if(Auth::check() && Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}" class="rounded-circle border" width="40" height="40">
                        @else
                            <div class="bg-darkblue text-white rounded-circle d-flex align-items-center justify-content-center border border-2 border-white shadow-sm" style="width: 40px; height: 40px;">
                                {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                            </div>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>