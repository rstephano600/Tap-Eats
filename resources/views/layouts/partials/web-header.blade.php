<nav class="navbar navbar-expand-lg py-3 sticky-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="btn btn-light border-0 me-3 rounded-circle shadow-sm" id="sidebarToggle">
                <i class="bi bi-list fs-4 text-dark"></i>
            </button>
            <h5 class="m-0 fw-bold d-none d-md-block text-dark">Welcome back, <span class="text-primary-custom">Foodie!</span></h5>
        </div>

        <div class="search-box d-none d-lg-flex mx-auto w-50 py-1">
            <i class="bi bi-search ms-3 my-auto text-muted"></i>
            <input type="text" class="form-control" placeholder="Search for food, groceries, or drinks...">
            <button class="btn btn-primary bg-primary-custom border-0 ms-2">Find</button>
        </div>

        <div class="d-flex align-items-center">
            <a href="#" class="btn position-relative me-3 text-dark fs-5">
                <i class="bi bi-cart3"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    2
                </span>
            </a>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown">
                    <div class="bg-primary-custom rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                        <i class="bi bi-person"></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                    <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>