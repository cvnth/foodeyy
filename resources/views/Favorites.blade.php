<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Favorites</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: {
          preflight: false,
          container: false
        }
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
                    <a href="{{ route('user.dashboard') }}" style="color: #e67e22; text-decoration: none; font-weight: 500; margin-top: 10px; display: inline-block;">Browse Menu</a>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadFavorites();
        });

        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function loadFavorites() {
            const grid = document.getElementById('favoritesGrid');
            const loading = document.getElementById('loading');
            const empty = document.getElementById('emptyState');

            fetch('/user/favorites/json')
                .then(res => res.json())
                .then(data => {
                    loading.style.display = 'none';
                    
                    if (data.length === 0) {
                        grid.innerHTML = '';
                        grid.style.display = 'none';
                        empty.style.display = 'block';
                        return;
                    }

                    empty.style.display = 'none';
                    grid.style.display = 'grid'; // Use grid layout from dashboard.css
                    
                    grid.innerHTML = data.map(item => `
                        <div class="food-card" id="card-${item.id}">
                            <img src="${item.image_url || 'https://via.placeholder.com/500'}" 
                                 alt="${item.name}" 
                                 onerror="this.src='https://via.placeholder.com/500?text=No+Image'" />
                            
                            <div class="food-info">
                                <h3>${item.name}</h3>
                                <p>₱${parseFloat(item.price).toFixed(2)}</p>
                                <div class="food-meta">
                                    <div class="rating">
                                        ${generateStars(item.rating || 5)}
                                        <span>(${item.review_count || 0})</span>
                                    </div>
                                    <div>
                                        <button class="favorite-btn" 
                                                style="color: #ef4444;"
                                                onclick="removeFavorite(${item.id})"
                                                title="Remove from favorites">
                                            <i class="material-icons">favorite</i>
                                        </button>
                                        
                                        <button class="add-to-cart" onclick="addToCart(${item.id}, '${item.name}')">
                                            <i class="material-icons">add_shopping_cart</i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(err => console.error(err));
        }

        function removeFavorite(id) {
            if(!confirm('Remove this item from your favorites?')) return;

            fetch('/user/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ menu_item_id: id })
            })
            .then(res => res.json())
            .then(data => {
                // Animate removal
                const card = document.getElementById(`card-${id}`);
                if(card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    // Remove from DOM after animation
                    setTimeout(() => {
                        card.remove();
                        // Check if grid is empty now
                        if(document.getElementById('favoritesGrid').children.length === 0) {
                            loadFavorites(); // Reload to show empty state
                        }
                    }, 300);
                }
            });
        }

        function addToCart(id, name) {
            alert(`Added ${name} to cart!`);
            // Add your Fetch POST logic here for cart
        }

        function generateStars(rating) {
            let stars = '';
            for(let i=1; i<=5; i++) {
                // Use inline styles to ensure stars are colored correctly without Tailwind reliance
                stars += i <= rating 
                    ? '<i class="material-icons" style="color:#f59e0b; font-size:16px;">star</i>' 
                    : '<i class="material-icons" style="color:#d1d5db; font-size:16px;">star_border</i>';
            }
            return stars;
        }
    </script>
</body>
</html>