<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'TapEats') }}</title>

    <link rel="icon" href="{{ asset('images/logo/ejossolution.png') }}" type="image/png">
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    {{-- Bootstrap CSS (CDN only) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons (CDN only) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

    {{-- Google Maps --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>

    {{-- Custom CSS --}}
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
<!-- Bootstrap 5 bundle — includes Popper, required for dropdowns -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <style>
    :root {
        --bs-darkblue: #001f3f;
        --bs-accent: #FFA726;
        --sidebar-width: 260px;
        --header-height: 62px; /* match your navbar height */
    }

    body {
        font-family: 'Instrument Sans', sans-serif;
        background-color: #f8f9fa;
        overflow: hidden; /* prevent body scroll */
    }

    /* =====================
       WRAPPER
    ===================== */
    #wrapper {
        display: flex;
        height: 100vh; /* full viewport height */
        overflow: hidden;
    }

    /* =====================
       SIDEBAR — FIXED
    ===================== */
    #sidebar-wrapper {
        width: var(--sidebar-width);
        min-width: var(--sidebar-width);
        max-width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: var(--bs-darkblue);
        transition: transform 0.25s ease-out;

        /* Custom scrollbar for sidebar */
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.15) transparent;
    }
    #sidebar-wrapper::-webkit-scrollbar {
        width: 4px;
    }
    #sidebar-wrapper::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.15);
        border-radius: 4px;
    }

    /* =====================
       PAGE CONTENT
    ===================== */
    #page-content-wrapper {
        margin-left: var(--sidebar-width);
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
        transition: margin-left 0.25s ease-out;
        min-width: 0;
    }

    /* =====================
       HEADER — FIXED (sticky within content)
    ===================== */
    #page-content-wrapper .navbar {
        position: sticky;
        top: 0;
        z-index: 1040;
        height: var(--header-height);
        flex-shrink: 0; /* don't shrink header */
    }

    /* =====================
       MAIN CONTENT — SCROLLABLE
    ===================== */
    #page-content-wrapper main {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;

        /* Custom scrollbar for content */
        scrollbar-width: thin;
        scrollbar-color: #dee2e6 transparent;
    }
    #page-content-wrapper main::-webkit-scrollbar {
        width: 6px;
    }
    #page-content-wrapper main::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 6px;
    }
    #page-content-wrapper main::-webkit-scrollbar-thumb:hover {
        background: #adb5bd;
    }

    /* =====================
       FOOTER — FIXED BOTTOM
    ===================== */
    #page-content-wrapper footer {
        flex-shrink: 0; /* don't shrink footer */
        z-index: 1039;
    }

    /* =====================
       RESPONSIVE — MOBILE
    ===================== */
    @media (max-width: 991.98px) {
        #sidebar-wrapper {
            transform: translateX(calc(var(--sidebar-width) * -1));
        }
        #wrapper.toggled #sidebar-wrapper {
            transform: translateX(0);
        }
        #page-content-wrapper {
            margin-left: 0;
        }

        /* Overlay when sidebar opens on mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1049;
        }
        #wrapper.toggled #sidebar-overlay {
            display: block;
        }
    }

    /* =====================
       DESKTOP TOGGLED — hide sidebar
    ===================== */
    @media (min-width: 992px) {
        #wrapper.toggled #sidebar-wrapper {
            transform: translateX(calc(var(--sidebar-width) * -1));
        }
        #wrapper.toggled #page-content-wrapper {
            margin-left: 0;
        }
    }

    /* rest of your existing styles below... */
    .bg-darkblue { background-color: var(--bs-darkblue) !important; }
    .text-accent  { color: var(--bs-accent) !important; }
    .btn-accent   { background-color: var(--bs-accent); color: var(--bs-darkblue); font-weight: 600; border: none; }
    .btn-accent:hover { background-color: #e09400; color: #fff; }

    /* Sidebar items */
    #sidebar-wrapper .list-group-item {
        border: none;
        padding: 0.65rem 1rem;
        color: rgba(255,255,255,0.75);
        background: transparent;
        font-size: 0.875rem;
    }
    #sidebar-wrapper .list-group-item:hover {
        background: rgba(255,255,255,0.08);
        color: #fff;
    }
    #sidebar-wrapper .list-group-item.active {
        background: rgba(255,255,255,0.12) !important;
        color: var(--bs-accent) !important;
        border-left: 3px solid var(--bs-accent);
        font-weight: 600;
    }
    .sidebar-toggle .toggle-icon {
        transition: transform 0.25s ease;
    }
    .sidebar-toggle[aria-expanded="true"] .toggle-icon {
        transform: rotate(180deg);
    }
    #sidebar-wrapper .collapse .list-group-item {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.6);
        padding-left: 2.5rem;
    }
    #sidebar-wrapper .collapse .list-group-item:hover {
        color: #fff;
        background: rgba(255,255,255,0.06);
    }
    #sidebar-wrapper .sidebar-label {
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-weight: 700;
        padding-left: 1rem;
        margin-top: 1rem;
        margin-bottom: 0.25rem;
        color: rgba(255,255,255,0.35);
        display: block;
    }

    /* DataTables Global Styling */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.4rem 0.85rem;
        font-size: 0.85rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #001f3f;
        box-shadow: 0 0 0 3px rgba(0, 31, 63, 0.1);
        outline: none;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.35rem 2rem 0.35rem 0.75rem;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 0.8rem;
        color: #6c757d;
        padding-top: 0.75rem;
    }

    /* Pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        margin: 0 2px;
        font-size: 0.82rem;
        border: 1px solid transparent !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #001f3f !important;
        color: #fff !important;
        border-color: #001f3f !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f0f4f8 !important;
        color: #001f3f !important;
        border-color: #dee2e6 !important;
    }

    /* Export Buttons */
    .dt-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .dt-buttons .btn {
        border-radius: 7px;
        font-size: 0.78rem;
        padding: 0.35rem 0.75rem;
        font-weight: 500;
    }

    /* Table */
    table.datatable thead th {
        background-color: #001f3f;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.4px;
        border: none;
        white-space: nowrap;
    }
    table.datatable thead th:hover {
        background-color: #002d5a;
        color: #FFA726;
    }
    table.datatable tbody tr:hover {
        background-color: #f0f4f8;
    }
    table.datatable tbody td {
        font-size: 0.85rem;
        vertical-align: middle;
        color: #344054;
    }
    table.datatable tbody tr:nth-child(even) {
        background-color: #fafafa;
    }
</style>
    @stack('styles')
</head>
<body>


<div id="wrapper">
    {{-- Mobile overlay (closes sidebar when clicking outside) --}}
    <div id="sidebar-overlay"></div>

    @include('layouts.partials.asidebar')

    <div id="page-content-wrapper">
        @include('layouts.partials.header')
        <main class="container-fluid py-4">
            @include('layouts.partials.alerts')
            @include('sweetalert::alert')
            @include('layouts.partials.confirm-modal')
            @yield('content')
        </main>
        @include('layouts.partials.footer')
    </div>
</div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Bootstrap JS (CDN only — bundle includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('assets/js/custom.js') }}"></script>


    {{-- DataTables JS --}}
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

{{-- Export dependencies --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const toggle  = document.getElementById('sidebarToggle');
    const wrapper = document.getElementById('wrapper');
    const overlay = document.getElementById('sidebar-overlay');

    // Toggle sidebar
    if (toggle && wrapper) {
        toggle.addEventListener('click', function () {
            wrapper.classList.toggle('toggled');
        });
    }

    // Close sidebar on mobile when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function () {
            wrapper.classList.remove('toggled');
        });
    }

    // Keep active collapse open on load
    document.querySelectorAll('.collapse').forEach(function (collapseEl) {
        if (collapseEl.querySelector('.list-group-item.active')) {
            new bootstrap.Collapse(collapseEl, { toggle: false }).show();
            var trigger = document.querySelector('[href="#' + collapseEl.id + '"]');
            if (trigger) trigger.setAttribute('aria-expanded', 'true');
        }
    });

        // Global DataTable Initializer
        $('.datatable').each(function () {
            var exportTitle = $(this).data('title') || 'TapEats Export';

            $(this).DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, 1000, -1],
                    ['10', '25', '50', '100', '250', '500', '1000', 'All']
                ],
                dom: '<"row align-items-center mb-3"<"col-md-6 d-flex align-items-center gap-2"lB><"col-md-6"f>>rtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer me-1"></i> Print',
                        className: 'btn btn-sm btn-outline-secondary',
                        exportOptions: { columns: ':visible:not(.no-export)' },
                        title: exportTitle,
                        customize: function (win) {
                            $(win.document.body).css('font-family', 'Instrument Sans, sans-serif');
                            $(win.document.body).find('h1').text(exportTitle).css({
                                'font-size': '18px',
                                'color': '#001f3f',
                                'margin-bottom': '20px'
                            });
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
                        className: 'btn btn-sm btn-outline-danger',
                        exportOptions: { columns: ':visible:not(.no-export)' },
                        title: exportTitle,
                        orientation: 'landscape',
                        pageSize: 'A4',
                        customize: function (doc) {
                            doc.defaultStyle.fontSize = 9;
                            doc.styles.tableHeader.fillColor = '#001f3f';
                            doc.styles.tableHeader.color = '#ffffff';
                            doc.styles.tableHeader.fontSize = 10;
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                        className: 'btn btn-sm btn-outline-success',
                        exportOptions: { columns: ':visible:not(.no-export)' },
                        title: exportTitle,
                    },
                    {
                        extend: 'csv',
                        text: '<i class="bi bi-filetype-csv me-1"></i> CSV',
                        className: 'btn btn-sm btn-outline-info',
                        exportOptions: { columns: ':visible:not(.no-export)' },
                        title: exportTitle,
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns me-1"></i> Columns',
                        className: 'btn btn-sm btn-outline-dark',
                    }
                ],
                language: {
                    search: '',
                    searchPlaceholder: '🔍 Search anything...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing <strong>_START_</strong> to <strong>_END_</strong> of <strong>_TOTAL_</strong> entries',
                    infoEmpty: 'No entries found',
                    infoFiltered: '<span class="text-warning">(filtered from _MAX_ total)</span>',
                    emptyTable: '<div class="text-center py-4 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No data available</div>',
                    zeroRecords: '<div class="text-center py-4 text-muted"><i class="bi bi-search fs-2 d-block mb-2"></i>No matching records found</div>',
                    paginate: {
                        first: '<i class="bi bi-chevron-double-left"></i>',
                        last: '<i class="bi bi-chevron-double-right"></i>',
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    }
                }
            });
        });

    }); // end DOMContentLoaded
</script>
</body>
</html>