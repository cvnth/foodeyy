<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FoodHub - Dashboard</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')
        <main class="main-content">
            @include('components.user.UserHeader')

            <div class="search-filter">
                <div class="search-bar">
                    <i class="material-icons">search</i>
                    <input type="text" placeholder="Search for food..." />
                </div>

                <div class="filter-buttons">
                    <button class="filter-btn active">All</button>
                    <button class="filter-btn">Western</button>
                    <button class="filter-btn">Chinese</button>
                    <button class="filter-btn">Japanese</button>
                    <button class="filter-btn">Desserts</button>
                </div>
            </div>

            <!-- FOOD GRID -->
            <div class="food-grid">
                <!-- CARD 1 -->
                <div class="food-card cursor-pointer" data-food-id="1">
                    <img src="https://images.unsplash.com/photo-1546964124-0cce460f38ef?auto=format&fit=crop&w=500&q=80" alt="Beef Steak" />
                    <div class="food-info">
                        <h3>Beef Steak</h3>
                        <p>₱250</p>
                        <div class="food-meta">
                            <div class="rating">
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star_half</i>
                                <span>(42)</span>
                            </div>
                            <div>
                                <button class="favorite-btn"><i class="material-icons">favorite_border</i></button>
                                <button class="add-to-cart"><i class="material-icons">add_shopping_cart</i> Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="food-card cursor-pointer" data-food-id="2">
                    <img src="https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=500&q=80" alt="Chicken Teriyaki" />
                    <div class="food-info">
                        <h3>Chicken Teriyaki</h3>
                        <p>₱180</p>
                        <div class="food-meta">
                            <div class="rating">
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <span>(56)</span>
                            </div>
                            <div>
                                <button class="favorite-btn"><i class="material-icons">favorite_border</i></button>
                                <button class="add-to-cart"><i class="material-icons">add_shopping_cart</i> Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="food-card cursor-pointer" data-food-id="3">
                    <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=500&q=80" alt="Salad" />
                    <div class="food-info">
                        <h3>Fresh Salad</h3>
                        <p>₱120</p>
                        <div class="food-meta">
                            <div class="rating">
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star_border</i>
                                <span>(38)</span>
                            </div>
                            <div>
                                <button class="favorite-btn"><i class="material-icons">favorite_border</i></button>
                                <button class="add-to-cart"><i class="material-icons">add_shopping_cart</i> Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 4 -->
                <div class="food-card cursor-pointer" data-food-id="4">
                    <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?auto=format&fit=crop&w=500&q=80" alt="Pancakes" />
                    <div class="food-info">
                        <h3>Pancakes</h3>
                        <p>₱95</p>
                        <div class="food-meta">
                            <div class="rating">
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star_half</i>
                                <span>(67)</span>
                            </div>
                            <div>
                                <button class="favorite-btn"><i class="material-icons">favorite_border</i></button>
                                <button class="add-to-cart"><i class="material-icons">add_shopping_cart</i> Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 5 -->
                <div class="food-card cursor-pointer" data-food-id="5">
                    <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=500&q=80" alt="Pasta" />
                    <div class="food-info">
                        <h3>Spaghetti Carbonara</h3>
                        <p>₱220</p>
                        <div class="food-meta">
                            <div class="rating">
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star_half</i>
                                <span>(89)</span>
                            </div>
                            <div>
                                <button class="favorite-btn"><i class="material-icons">favorite_border</i></button>
                                <button class="add-to-cart"><i class="material-icons">add_shopping_cart</i> Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 6 -->
                <div class="food-card cursor-pointer" data-food-id="6">
                    <img src="https://images.unsplash.com/photo-1563379926898-05f4575a45d8?auto=format&fit=crop&w=500&q=80" alt="Burger" />
                    <div class="food-info">
                        <h3>Classic Burger</h3>
                        <p>₱160</p>
                        <div class="food-meta">
                            <div class="rating">
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <i class="material-icons">star</i>
                                <span>(124)</span>
                            </div>
                            <div>
                                <button class="favorite-btn"><i class="material-icons">favorite_border</i></button>
                                <button class="add-to-cart"><i class="material-icons">add_shopping_cart</i> Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Include the modal -->
    @include('menu-details')

    <!-- CLICK → MODAL OPEN SCRIPT -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('foodDetailsModal');
        const closeBtn = document.getElementById('closeDetailsBtn');

        // Mock food data - replace with your food-data.js
        function getFoodDataById(id) {
            const foodData = {
                1: {
                    id: 1,
                    name: "Beef Steak",
                    price: "₱250",
                    image: "https://images.unsplash.com/photo-1546964124-0cce460f38ef?auto=format&fit=crop&w=500&q=80",
                    description: "Juicy grilled beef steak with special house seasoning, served with mashed potatoes and fresh vegetables. Perfectly cooked to your preference with our signature herb butter.",
                    prepTime: "20-25 mins",
                    calories: "450 cal", 
                    spiceLevel: "Medium", 
                    category: "Western",
                    rating: 4.5,
                    reviews: 42
                },
                2: {
                    id: 2,
                    name: "Chicken Teriyaki",
                    price: "₱180",
                    image: "https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=500&q=80",
                    description: "Grilled chicken glazed with authentic teriyaki sauce, served with steamed rice and mixed vegetables. A perfect balance of sweet and savory flavors.",
                    prepTime: "15-20 mins",
                    calories: "380 cal", 
                    spiceLevel: "Mild", 
                    category: "Japanese",
                    rating: 5,
                    reviews: 56
                },
                3: {
                    id: 3,
                    name: "Fresh Salad",
                    price: "₱120",
                    image: "https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=500&q=80",
                    description: "Crisp mixed greens with cherry tomatoes, cucumbers, olives, carrots, and your choice of dressing. A refreshing and healthy option for any meal.",
                    prepTime: "10 mins",
                    calories: "180 cal", 
                    spiceLevel: "None", 
                    category: "Healthy",
                    rating: 4,
                    reviews: 38
                },
                4: {
                    id: 4,
                    name: "Pancakes",
                    price: "₱95",
                    image: "https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?auto=format&fit=crop&w=500&q=80",
                    description: "Fluffy buttermilk pancakes served with maple syrup, fresh berries, and a dollop of whipped cream. Perfect for breakfast or dessert.",
                    prepTime: "15 mins",
                    calories: "320 cal", 
                    spiceLevel: "None", 
                    category: "Desserts",
                    rating: 4.5,
                    reviews: 67
                },
                5: {
                    id: 5,
                    name: "Spaghetti Carbonara",
                    price: "₱220",
                    image: "https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=500&q=80",
                    description: "Classic Italian pasta with creamy egg sauce, crispy pancetta, and parmesan cheese. Authentic recipe with a rich and satisfying flavor.",
                    prepTime: "18-22 mins",
                    calories: "520 cal", 
                    spiceLevel: "Mild", 
                    category: "Italian",
                    rating: 4.5,
                    reviews: 89
                },
                6: {
                    id: 6,
                    name: "Classic Burger",
                    price: "₱160",
                    image: "https://images.unsplash.com/photo-1563379926898-05f4575a45d8?auto=format&fit=crop&w=500&q=80",
                    description: "Juicy beef patty with lettuce, tomato, onion, pickles, and our special sauce on a toasted brioche bun. Served with crispy fries.",
                    prepTime: "12-15 mins",
                    calories: "680 cal", 
                    spiceLevel: "Mild", 
                    category: "Western",
                    rating: 5,
                    reviews: 124
                }
            };
            return foodData[id] || null;
        }

        // -------------------------------
        // OPEN MODAL WHEN CLICKING FOOD CARD
        // -------------------------------
        document.querySelectorAll('.food-card').forEach(card => {
            card.addEventListener('click', e => {
                // Prevent modal if clicking Add or Favorite
                if (e.target.closest('.add-to-cart') || e.target.closest('.favorite-btn')) return;

                const id = card.dataset.foodId;
                const food = getFoodDataById(id);
                if (!food) return;

                // Fill Modal Data
                document.getElementById('detailImage').src = food.image;
                document.getElementById('detailImage').alt = food.name;
                document.getElementById('detailName').textContent = food.name;
                document.getElementById('detailPrice').textContent = food.price;
                document.getElementById('detailDesc').textContent = food.description;
                document.getElementById('detailPrep').textContent = food.prepTime || '—';
                // Removed: Calories and Spice Level population
                document.getElementById('detailCat').textContent = food.category || 'Main';
                document.getElementById('detailReviews').textContent = `(${food.reviews || 0} reviews)`;
                document.getElementById('detailStars').innerHTML = generateStarRating(food.rating || 4.5);

                // Reset quantity
                modal.querySelector('.quantity').textContent = '1';

                modal.classList.remove('hidden');

                // Add to Cart from inside modal
                document.getElementById('detailAddBtn').onclick = () => {
                    const qty = parseInt(modal.querySelector('.quantity').textContent);
                    addItemToCart(food, qty);
                    modal.classList.add('hidden');
                };
            });
        });

        // -------------------------------
        // QUANTITY BUTTON FIX
        // -------------------------------
        modal.addEventListener('click', e => {
            const btn = e.target.closest('.quantity-btn');
            if (!btn) return;

            const qtyEl = modal.querySelector('.quantity');
            let qty = parseInt(qtyEl.textContent);

            if (btn.dataset.action === 'plus') qty++;
            if (btn.dataset.action === 'minus' && qty > 1) qty--;

            qtyEl.textContent = qty;
        });

        // -------------------------------
        // CLICK OUTSIDE MODAL → CLOSE
        // -------------------------------
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.add('hidden');
        });

        // -------------------------------
        // CLOSE BUTTON
        // -------------------------------
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // -------------------------------
        // FAVORITE BUTTON FUNCTIONALITY
        // -------------------------------
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const icon = btn.querySelector('i');
                if (icon.textContent === 'favorite_border') {
                    icon.textContent = 'favorite';
                    btn.classList.add('active');
                    showNotification('Added to favorites!', 'success');
                } else {
                    icon.textContent = 'favorite_border';
                    btn.classList.remove('active');
                    showNotification('Removed from favorites!', 'info');
                }
            });
        });

        // -------------------------------
        // ADD TO CART BUTTON FUNCTIONALITY
        // -------------------------------
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const foodCard = btn.closest('.food-card');
                const foodId = foodCard.dataset.foodId;
                const food = getFoodDataById(foodId);
                if (food) {
                    addItemToCart(food, 1);
                    showNotification(`Added ${food.name} to cart!`, 'success');
                }
            });
        });

    });

    // STAR RATING HELPER
    function generateStarRating(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) html += '<i class="material-icons text-yellow-500">star</i>';
            else if (i - 0.5 <= rating) html += '<i class="material-icons text-yellow-500">star_half</i>';
            else html += '<i class="material-icons text-gray-400">star_border</i>';
        }
        return html;
    }

    function addItemToCart(food, quantity) {
        console.log(`Added ${quantity}x ${food.name} to cart`);
        // You can add cart functionality here
        
        // For demo purposes, show a notification
        showNotification(`Added ${quantity}x ${food.name} to cart!`, 'success');
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <i class="material-icons">${getNotificationIcon(type)}</i>
            <span>${message}</span>
        `;

        // Add to body
        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.4s ease-in-out';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 400);
        }, 3000);
    }

    function getNotificationIcon(type) {
        const icons = {
            success: 'check_circle',
            warning: 'warning',
            error: 'error',
            info: 'info'
        };
        return icons[type] || 'info';
    }

    // Search functionality
    document.querySelector('.search-bar input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const foodCards = document.querySelectorAll('.food-card');
        
        foodCards.forEach(card => {
            const foodName = card.querySelector('h3').textContent.toLowerCase();
            if (foodName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Filter functionality
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.textContent.toLowerCase();
            const foodCards = document.querySelectorAll('.food-card');
            
            foodCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else {
                    // This would need to be connected to actual food data
                    // For now, we'll just show all cards
                    card.style.display = 'block';
                }
            });
        });
    });
</script>

</body>
</html>