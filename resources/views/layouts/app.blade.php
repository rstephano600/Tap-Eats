
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'TapEats') }}</title>
        <link rel="icon" href="{{ asset('images/logo/ejossolution.png') }}" type="image/png">
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <style>
        :root {
            --bs-darkblue: #001f3f;
            --bs-accent: #FFA726;
            --sidebar-width: 260px;
        }

        body { font-family: 'Instrument Sans', sans-serif; background-color: #f8f9fa; }

        /* Sidebar & Wrapper */
        #wrapper { overflow-x: hidden; display: flex; min-height: 100vh; }
        
        #sidebar-wrapper {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background-color: var(--bs-darkblue);
            transition: margin .25s ease-out;
            z-index: 1000;
        }

        #page-content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            #sidebar-wrapper { margin-left: calc(var(--sidebar-width) * -1); }
            #wrapper.toggled #sidebar-wrapper { margin-left: 0; }
        }

        @media (min-width: 992px) {
            #wrapper.toggled #sidebar-wrapper { margin-left: calc(var(--sidebar-width) * -1); }
        }

        /* Custom Styles */
        .bg-darkblue { background-color: var(--bs-darkblue) !important; }
        .text-accent { color: var(--bs-accent) !important; }
        .btn-accent { background-color: var(--bs-accent); color: var(--bs-darkblue); font-weight: 600; border: none; }
        .btn-accent:hover { background-color: #e09400; color: #fff; }

        .list-group-item {
            border: none;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.7);
            background: transparent;
            transition: 0.3s;
        }
        .list-group-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .list-group-item.active { 
            background: rgba(255,255,255,0.1) !important; 
            color: var(--bs-accent) !important;
            border-left: 4px solid var(--bs-accent);
        }
    </style>
</head>
<body>
    <div id="wrapper">
        @include('layouts.partials.asidebar')

        <div id="page-content-wrapper">
            @include('layouts.partials.header')

            <main class="container-fluid py-4 flex-grow-1">
                @yield('content')
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('wrapper').classList.toggle('toggled');
        });
    </script>
</body>
</html>