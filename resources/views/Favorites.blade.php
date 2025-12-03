<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Favorites</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            @include('components.user.UserHeader')

            <div class="p-8">
                <h1 class="text-4xl font-bold text-orange-600 mb-8">Your Favorite Foods</h1>

                <div class="favorites-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

                    <!-- Favorite Item 2 -->
                    <div class="food-card">
                        <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=500" alt="Ramen">
                        <div class="food-info">
                            <h3>Spicy Beef Ramen</h3>
                            <p>₱420.00</p>
                            <div class="food-meta">
                                <div class="rating">
                                    <i class="material-icons">star</i><i class="material-icons">star</i><i class="material-icons">star</i><i class="material-icons">star</i><i class="material-icons">star_half</i>
                                    <span>(67)</span>
                                </div>
                                <button class="favorite-btn text-red-600">
                                    <i class="material-icons">favorite</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <p class="text-gray-500 text-lg">You have no more favorites.</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>