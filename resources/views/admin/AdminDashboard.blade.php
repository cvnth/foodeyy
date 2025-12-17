<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- IMPORTANT for AJAX/API calls --}}
    
    <title>Foodeyy - @yield('title', 'Admin Dashboard')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

{{-- Auth and Access Control --}}
@guest
    <script>
        alert('Please log in to access the admin dashboard.');
        window.location.href = "{{ route('login') }}";
    </script>
    @php return; @endphp
@else
    @if(!Auth::user()->is_admin)
        <script>
            alert('Access denied. This area is for administrators only.');
            window.location.href = "/user/dashboard";
        </script>
        @php return; @endphp
    @endif
@endguest

<div class="container">
    {{-- Using Blade Component for Sidebar --}}
    <x-admin.AdminSidebar/> 

    <main class="main-content">
        {{-- Using Blade Component for Header, passing the dynamic page title --}}
        <x-admin.AdminHeader pageTitle="@yield('page-title', 'Admin Dashboard')" />

        {{-- ALL PAGE SPECIFIC CONTENT IS INJECTED HERE --}}
        @yield('page-content')
        
    </main>
</div>



<script>
    window.currentAdmin = {
        name: "{{ Auth::user()->name }}",
        email: "{{ Auth::user()->email }}"
    };
</script>
@stack('scripts')
</body>
</html>