<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Your Favorites</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: { preflight: false, container: false }
      }
    </script>
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            @include('components.user.UserHeader')

            <div style="padding: 30px;">
                <h1 style="font-size: 2rem; font-weight: bold; color: #e67e22; margin-bottom: 20px;">Your Favorite Foods</h1>

                <div id="loading" style="text-align: center; padding: 40px; color: #888;">
                    <p>Loading your favorites...</p>
                </div>

                <div id="favoritesGrid" class="food-grid" style="display: none;">
                    </div>

                <div id="emptyState" style="text-align: center; margin-top: 50px; display: none;">
                    <i class="material-icons" style="font-size: 64px; color: #ddd;">favorite_border</i>
                    <p style="color: #888; font-size: 1.1rem; margin-top: 10px;">You have no favorites yet.</p>
                    <a href="{{ route('user.dashboard') }}" style="color: #e67e22; text-decoration: none; font-weight: 500; margin-top: 10px; display: inline-block; border: 1px solid #e67e22; padding: 8px 16px; border-radius: 4px;">Browse Menu</a>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/favorites.js') }}"></script>
</body>
</html>