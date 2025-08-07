<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Home')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{{ asset('js/jquery.js') }}"></script>
    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Light Mode (default) */
        body.light-mode {
            background: linear-gradient(135deg, #b4bde4, #82ebd7);
        }

        /* Dark Mode */
        body.dark-mode {
            background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
        }

        .nav-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="{{ (isset($theme_mode) && $theme_mode == 'dark') ? 'dark-mode' : 'light-mode' }}">

    {{-- Navbar --}}
    @include('user.layouts.navbar')

    {{-- Page Content --}}
    <main class="py-4">
        @yield('content')
    </main>
    @stack('scripts')
    {{-- Footer --}}
    @include('user.layouts.footer')

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
