<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <<title>Foodeyy - @yield('title', 'User Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=2" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: { preflight: false, container: false }
      }
    </script>
    
    <style>
        /* Global Styles shared across user pages */
        .favorite-btn:active { transform: scale(0.8); }
        .favorite-btn { transition: transform 0.2s, color 0.2s; }
        .heart-active { color: #ef4444 !important; }
    </style>

    {{-- Stack for page-specific CSS --}}
    @stack('styles')
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            @include('components.user.UserHeader')

            @yield('content')
        </main>
    </div>

    {{-- Stack for Modals (Menu Details, Receipts, etc) --}}
    @stack('modals')

    {{-- Global Config for JS --}}
    <script>
        window.appConfig = {
            session: {
                success: "{{ session('success') }}",
                error: "{{ session('error') }}"
            }
        };
    </script>

    {{-- Stack for page-specific Scripts --}}
    @stack('scripts')
</body>
</html>