<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FoodHub - Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: { preflight: false, container: false }
      }
    </script>
    
    <style>
        .favorite-btn:active { transform: scale(0.8); }
        .favorite-btn { transition: transform 0.2s, color 0.2s; }
        .heart-active { color: #ef4444 !important; }
    </style>
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            @include('components.user.UserHeader')

            <div class="search-filter">
                <div class="search-bar">
                    <i class="material-icons">search</i>
                    <input type="text" id="searchInput" placeholder="Search for food..." />
                </div>

                <div class="filter-buttons">
                    <button class="filter-btn active" data-category="all">All</button>
                    <button class="filter-btn" data-category="Western">Western</button>
                    <button class="filter-btn" data-category="Chinese">Chinese</button>
                    <button class="filter-btn" data-category="Japanese">Japanese</button>
                    <button class="filter-btn" data-category="Filipino">Filipino</button>
                    <button class="filter-btn" data-category="Desserts">Desserts</button>
                </div>
            </div>

            <div class="food-grid" id="foodGrid">
                <p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #888;">
                    Loading menu...
                </p>
            </div>
        </main>
    </div>

    @include('menu-details')

    <script>
        window.appConfig = {
            session: {
                success: "{{ session('success') }}",
                error: "{{ session('error') }}"
            }
        };
    </script>

    <script src="{{ asset('js/user-dashboard.js') }}"></script>
</body>
</html>