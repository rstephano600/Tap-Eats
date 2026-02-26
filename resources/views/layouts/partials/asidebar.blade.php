<div class="border-end bg-darkblue" id="sidebar-wrapper">
    <div class="sidebar-heading text-white fw-bold border-bottom border-secondary p-4 d-flex align-items-center">
        <img src="{{ asset('images/logo/ejossolution.png') }}" alt="TapEats Logo" class="me-2" style="height: 40px; width: auto; object-fit: contain;">
        <span>TapEats</span>
    </div>

    <div class="list-group list-group-flush px-2 mt-3">

        {{-- MAIN --}}
        <small class="text-uppercase text-muted fw-bold mb-2 ps-3" style="font-size: 0.65rem; letter-spacing: 1px;">Main</small>

        <a href="{{ url('/dashboard') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        @role('super_admin')
        {{-- SUPPLIER GROUP --}}
        <small class="text-uppercase text-white-50 fw-bold mt-3 mb-1 ps-3" style="font-size: 0.65rem; letter-spacing: 1.5px;">Super Admin</small>

        <a class="list-group-item list-group-item-action rounded mb-1 d-flex justify-content-between align-items-center sidebar-toggle"
           data-bs-toggle="collapse" href="#superaAdmin" role="button" aria-expanded="false">
            <span><i class="bi bi-shop me-2"></i> Super Admin</span>
            <i class="bi bi-chevron-down small toggle-icon"></i>
        </a>
        <div class="collapse" id="superaAdmin">
            <a href="{{ route('manageSuppliers') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-info-circle me-2"></i>Manage Suppliers
            </a>
            <a href="{{ route('admin.roles.index') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-person-badge me-2"></i> Roles
            </a>
            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-people me-2"></i> Users
            </a>
            <a href="{{ route('Servicetypes') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-tags me-2"></i> Service Types
            </a>
        </div>
        @endrole

        {{-- SUPPLIER GROUP --}}
        <small class="text-uppercase text-white-50 fw-bold mt-3 mb-1 ps-3" style="font-size: 0.65rem; letter-spacing: 1.5px;">Supplier</small>

        <a class="list-group-item list-group-item-action rounded mb-1 d-flex justify-content-between align-items-center sidebar-toggle"
           data-bs-toggle="collapse" href="#supplierGroup" role="button" aria-expanded="false">
            <span><i class="bi bi-shop me-2"></i> Supplier</span>
            <i class="bi bi-chevron-down small toggle-icon"></i>
        </a>
        <div class="collapse" id="supplierGroup">
            <a href="{{ route('suppliersinformations') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-info-circle me-2"></i> Business Info
            </a>
            <a href="{{ route('supplierlocations') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-geo-alt me-2"></i> Business Location
            </a>
            <a href="{{ route('supplier.financial.index') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-bank me-2"></i> Financial Information
            </a>
            <a href="{{ route('supplierlocations') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-pin-map me-2"></i> Suppliers Locations
            </a>
        </div>

        {{-- ADMIN / USERS GROUP --}}
        <small class="text-uppercase text-white-50 fw-bold mt-3 mb-1 ps-3" style="font-size: 0.65rem; letter-spacing: 1.5px;">Administration</small>

        <a class="list-group-item list-group-item-action rounded mb-1 d-flex justify-content-between align-items-center sidebar-toggle"
           data-bs-toggle="collapse" href="#adminGroup" role="button" aria-expanded="false">
            <span><i class="bi bi-shield-lock me-2"></i> Users & Roles</span>
            <i class="bi bi-chevron-down small toggle-icon"></i>
        </a>
        <div class="collapse" id="adminGroup">
            <a href="{{ route('supplieruserinfo') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-person-workspace me-2"></i> Supplier Users
            </a>

        </div>

        {{-- MENU GROUP --}}
        <small class="text-uppercase text-white-50 fw-bold mt-3 mb-1 ps-3" style="font-size: 0.65rem; letter-spacing: 1.5px;">Menu</small>

        <a class="list-group-item list-group-item-action rounded mb-1 d-flex justify-content-between align-items-center sidebar-toggle"
           data-bs-toggle="collapse" href="#menuGroup" role="button" aria-expanded="false">
            <span><i class="bi bi-menu-button-wide me-2"></i> Menu Management</span>
            <i class="bi bi-chevron-down small toggle-icon"></i>
        </a>
        <div class="collapse" id="menuGroup">
            <a href="{{ route('menu-categories.index') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-grid me-2"></i> Categories
            </a>
            <a href="{{ route('menuItemsInformations') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-egg-fried me-2"></i> Menu Items
            </a>
            <a href="{{ route('menu-item-variants.index') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-layers me-2"></i> Item Variants
            </a>
            <a href="{{ route('menu-item-addons.index') }}" class="list-group-item list-group-item-action rounded mb-1 ps-4">
                <i class="bi bi-plus-square me-2"></i> Item Addons
            </a>
        </div>

        {{-- ORDERS --}}
        <small class="text-uppercase text-white-50 fw-bold mt-3 mb-1 ps-3" style="font-size: 0.65rem; letter-spacing: 1.5px;">Orders</small>

        <a href="{{ route('ordersinformations') }}" class="list-group-item list-group-item-action rounded mb-1">
            <i class="bi bi-bag-check me-2"></i> Orders
        </a>
        <a href="{{ route('completedOrders') }}" class="list-group-item list-group-item-action rounded mb-1">
            <i class="bi bi-bag-check me-2"></i> Completed Orders
        </a>
        <a href="{{ route('cancelledOrders') }}" class="list-group-item list-group-item-action rounded mb-1">
            <i class="bi bi-bag-check me-2"></i> Cancelled Orders
        </a>
        <a href="{{ route('ordersSummary') }}" class="list-group-item list-group-item-action rounded mb-1">
            <i class="bi bi-bag-check me-2"></i> Orders Samaary
        </a>

        {{-- PROFILE --}}
        <small class="text-uppercase text-white-50 fw-bold mt-3 mb-1 ps-3" style="font-size: 0.65rem; letter-spacing: 1.5px;">Account</small>

        <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action rounded mb-1">
            <i class="bi bi-person-circle me-2"></i> My Profile
        </a>

    </div>
</div>

<style>
    .sidebar-toggle {
        cursor: pointer;
    }
    .sidebar-toggle .toggle-icon {
        transition: transform 0.25s ease;
    }
    .sidebar-toggle[aria-expanded="true"] .toggle-icon {
        transform: rotate(180deg);
    }
    #sidebar-wrapper .list-group-item {
        background: transparent;
        color: rgba(255, 255, 255, 0.75);
        border: none;
        font-size: 0.875rem;
    }
    #sidebar-wrapper .list-group-item:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }
    #sidebar-wrapper .list-group-item.active {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        font-weight: 600;
    }
    #sidebar-wrapper .collapse .list-group-item {
        font-size: 0.82rem;
        color: rgba(255, 255, 255, 0.6);
    }
</style>