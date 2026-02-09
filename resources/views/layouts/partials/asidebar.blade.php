<div class="border-end bg-darkblue" id="sidebar-wrapper">
<div class="sidebar-heading text-white fw-bold border-bottom border-secondary p-4 d-flex align-items-center">
    <img src="{{ asset('images/logo/ejossolution.png') }}" alt="TapEats Logo" class="me-2" style="height: 40px; width: auto; object-fit: contain;">
    <span>TapEats</span>
</div>
    
    <div class="list-group list-group-flush px-2 mt-3">
        <small class="text-uppercase text-muted fw-bold mb-2 ps-3" style="font-size: 0.65rem; letter-spacing: 1px;">Main</small>
        
        <a href="{{ url('/dashboard') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> Dashboard
        </a>

        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. orders
        </a>
        <a href="{{ route('menu-items.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. menu items
        </a>
        <a href="{{ route('menu-item-variants.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. menu items variants
        </a>
        <a href="{{ route('menu-item-addons.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. menu items Addons
        </a>
        <a href="{{ route('menu-categories.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. MEnu categories
        </a>
        <a href="{{ route('supplier.suppliers.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. your Business
        </a>
        <a href="{{ route('supplierlocations') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. your Business Location
        </a>
        <a href="{{ route('supplier.financial.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. your Financial Informations
        </a>
        <a href="{{ route('suppliers.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Suppliers
        </a>
        <a href="{{ route('supplierlocations') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Suppliers Locations
        </a>
        <a href="{{ route('busines-types.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Business Types
        </a>
        <a href="{{ route('service-types.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Services Types
        </a>





        <!-- <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Roles
        </a> -->
        <!-- <a href="{{ route('role-users.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Users Roles
        </a> 

        <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Permissions
        </a>
        <a href="{{ route('permission-roles.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Permissions Roles
        </a> 
        <a href="{{ route('user-permissions.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. USers Permissions
        </a> 

        <br><br>
        <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Your Profile
        </a> 
        <a href="{{ route('customer.addresses.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Your Address
        </a> 


@permission('delivery-food')
            <a href="{{ route('users.create') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> Add User
        </a> 
@endpermission

        @if(auth()->user()->hasPermission('manage-roles'))
<a href="{{ route('roles.index') }}" class="list-group-item">
    <i class="bi bi-person-badge"></i> Roles
</a>
@endif




        <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Users
        </a>
        <a href="{{ route('guest-sessions.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Guests Users
        </a>
        <a href="{{ route('customer-profiles.index') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> M. Profiles
        </a>

-->

        @if(Auth::check())
            @php $role = Auth::user()->role; @endphp

            @if(in_array($role, ['super_admin', 'admin']))
                <small class="text-uppercase text-muted fw-bold mt-4 mb-2 ps-3" style="font-size: 0.65rem; letter-spacing: 1px;">Administration</small>
                <a href="{{ url('/admin/users') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Users
                </a>
                <a href="{{ url('/admin/suppliers') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('admin/suppliers*') ? 'active' : '' }}">
                    <i class="bi bi-shop-window me-2"></i> Suppliers
                </a>
            @endif

            @if($role === 'supplier')
                <small class="text-uppercase text-muted fw-bold mt-4 mb-2 ps-3" style="font-size: 0.65rem; letter-spacing: 1px;">Store Management</small>
                <a href="{{ url('/supplier/menu') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('supplier/menu*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text me-2"></i> My Menu
                </a>
                <a href="{{ url('/supplier/orders') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('supplier/orders*') ? 'active' : '' }}">
                    <i class="bi bi-bag-plus me-2"></i> Orders Received
                </a>
            @endif

            @if($role === 'customer')
                <small class="text-uppercase text-muted fw-bold mt-4 mb-2 ps-3" style="font-size: 0.65rem; letter-spacing: 1px;">Hungry?</small>
                <a href="{{ url('/menu') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('menu*') ? 'active' : '' }}">
                    <i class="bi bi-search me-2"></i> Browse Food
                </a>
                <a href="{{ url('/orders') }}" class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('orders*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history me-2"></i> Order History
                </a>
            @endif
        @endif
    </div>
</div>



<!-- 


<div class="border-end bg-darkblue" id="sidebar-wrapper">

    {{-- Brand --}}
    <div class="sidebar-heading text-white fw-bold border-bottom border-secondary p-4 d-flex align-items-center">
        <img src="{{ asset('images/logo/ejossolution.png') }}"
             alt="TapEats Logo"
             class="me-2"
             style="height: 40px; width: auto; object-fit: contain;">
        <span>TapEats</span>
    </div>

    <div class="list-group list-group-flush px-2 mt-3">

        {{-- ================= MAIN ================= --}}


        @permission('manage-roles')
        <a href="{{ url('/dashboard') }}"
           class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> Dashboard
        </a>
        @endpermission

        {{-- ================= ADMIN ================= --}}
        @permission('manage-roles')
        <small class="text-uppercase text-muted fw-bold mt-4 ps-3"
               style="font-size: 0.65rem; letter-spacing: 1px;">
            Administration
        </small>

            @permission('manage-roles')
            <a href="{{ route('users.index') }}"
               class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('users*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Users
            </a>
            @endpermission

            @permission('manage-roles')
            <a href="{{ route('roles.index') }}"
               class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('roles*') ? 'active' : '' }}">
                <i class="bi bi-person-badge me-2"></i> Roles
            </a>
            @endpermission

            @permission('manage-roles')
            <a href="{{ route('permissions.index') }}"
               class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('permissions*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock me-2"></i> Permissions
            </a>
            @endpermission

            @permission('assign_roles')
            <a href="{{ route('role-users.index') }}"
               class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('role-users*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> User Roles
            </a>
            @endpermission

            @permission('manage-roles')
            <a href="{{ route('permission-roles.index') }}"
               class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('permission-roles*') ? 'active' : '' }}">
                <i class="bi bi-key me-2"></i> Role Permissions
            </a>
            @endpermission
        @endpermission


        {{-- ================= SUPPLIER ================= --}}
        @permission('supplier_access')
        <small class="text-uppercase text-muted fw-bold mt-4 mb-2 ps-3"
               style="font-size: 0.65rem; letter-spacing: 1px;">
            Store Management
        </small>

        @permission('manage_menu')
        <a href="{{ url('/supplier/menu') }}"
           class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('supplier/menu*') ? 'active' : '' }}">
            <i class="bi bi-journal-text me-2"></i> My Menu
        </a>
        @endpermission

        @permission('view_supplier_orders')
        <a href="{{ url('/supplier/orders') }}"
           class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('supplier/orders*') ? 'active' : '' }}">
            <i class="bi bi-bag-plus me-2"></i> Orders Received
        </a>
        @endpermission
        @endpermission


        {{-- ================= CUSTOMER ================= --}}
        @permission('customer_access')
        <small class="text-uppercase text-muted fw-bold mt-4 mb-2 ps-3"
               style="font-size: 0.65rem; letter-spacing: 1px;">
            Hungry?
        </small>

        @permission('browse_menu')
        <a href="{{ url('/menu') }}"
           class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('menu*') ? 'active' : '' }}">
            <i class="bi bi-search me-2"></i> Browse Food
        </a>
        @endpermission

        @permission('view_orders')
        <a href="{{ url('/orders') }}"
           class="list-group-item list-group-item-action rounded mb-1 {{ Request::is('orders*') ? 'active' : '' }}">
            <i class="bi bi-clock-history me-2"></i> Order History
        </a>
        @endpermission
        @endpermission

    </div>
</div> -->
