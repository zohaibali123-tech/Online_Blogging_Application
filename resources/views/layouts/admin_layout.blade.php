<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('js/jquery.js') }}"></script>
    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- Optional: Admin CSS --}}
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #212529;
        }

        .admin-navbar {
            background-color: #0d6efd;
            color: white;
        }

        .admin-navbar .nav-link {
            color: white !important;
        }

        .admin-navbar .nav-link:hover {
            text-decoration: underline;
            opacity: 0.9;
        }

        .admin-sidebar {
            color: white;
            min-height: 100vh;
        }

        .admin-sidebar .nav-link {
            color: #ccc;
            transition: all 0.3s ease;
        }

        .admin-sidebar .nav-link.active,
        .admin-sidebar .nav-link:hover {
            color: #fff;
            background-color: #0d6efd;
            padding-left: 20px;
        }

        .admin-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                position: absolute;
                z-index: 1050;
                width: 250px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>

    {{-- Admin Navbar --}}
    @include('admin.layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            {{-- Admin Sidebar --}}
            <div class="col-md-3 col-lg-2 p-0 admin-sidebar" id="sidebar">
                @include('admin.layouts.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="col-md-9 col-lg-10 py-4 px-4">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
    @stack('head')

    {{-- Admin Footer --}}
    @include('admin.layouts.footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Optional JS to toggle sidebar on mobile --}}
    <script>
        const toggleBtn = document.querySelector('.navbar-toggler');
        const sidebar = document.getElementById('sidebar');

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    </script>
</body>
</html>
