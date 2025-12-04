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
        corePlugins: {
          preflight: false,
          container: false
        }
      }
    </script>
    
    <style>
        /* Custom animation for heart click */
        .favorite-btn:active { transform: scale(0.8); }
        .favorite-btn { transition: transform 0.2s, color 0.2s; }
        .heart-active { color: #ef4444 !important; } /* Red color */
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
    let allMenuItems = []; 
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.addEventListener('DOMContentLoaded', () => {
        fetchMenuData();
        setupSearchAndFilters();
    });

    /* =========================================
       1. FETCH DATA
       ========================================= */
    function fetchMenuData() {
        fetch('/user/menu/json')
            .then(response => response.json())
            .then(data => {
                allMenuItems = data;
                renderFoodGrid(allMenuItems);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('foodGrid').innerHTML = 
                    '<p style="grid-column:1/-1; text-align:center;">Failed to load menu.</p>';
            });
    }

    /* =========================================
       2. RENDER GRID
       ========================================= */
    function renderFoodGrid(items) {
        const grid = document.getElementById('foodGrid');
        
        if (items.length === 0) {
            grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px;">No items found.</p>';
            return;
        }

        grid.innerHTML = items.map(item => {
            const isFav = item.is_favorited; 
            const heartIcon = isFav ? 'favorite' : 'favorite_border';
            const heartClass = isFav ? 'heart-active' : '';

            return `
            <div class="food-card cursor-pointer" onclick="openFoodModal(${item.id})">
                <img src="${item.image_url || 'https://via.placeholder.com/500'}" 
                     alt="${item.name}" 
                     onerror="this.src='https://via.placeholder.com/500?text=No+Image'" />
                
                <div class="food-info">
                    <h3>${item.name}</h3>
                    <p>₱${parseFloat(item.price).toFixed(2)}</p>
                    <div class="food-meta">
                        <div class="rating">
                            ${generateStarRating(item.rating || 5)}
                            <span>(${item.review_count || 0})</span>
                        </div>
                        <div>
                            <button class="favorite-btn ${heartClass}" 
                                    id="fav-btn-${item.id}"
                                    onclick="toggleFavorite(event, ${item.id})">
                                <i class="material-icons">${heartIcon}</i>
                            </button>
                            
                            <button class="add-to-cart" onclick="quickAddToCart(event, ${item.id}, '${item.name}')">
                                <i class="material-icons">add_shopping_cart</i> Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `}).join('');
    }

    /* =========================================
       3. FAVORITE LOGIC (With Alerts!)
       ========================================= */
    function toggleFavorite(e, id) {
        e.stopPropagation();

        const btn = document.getElementById(`fav-btn-${id}`);
        const icon = btn.querySelector('i');
        const isCurrentlyActive = btn.classList.contains('heart-active');
        
        // Optimistic UI Update
        if (isCurrentlyActive) {
            btn.classList.remove('heart-active');
            icon.textContent = 'favorite_border';
        } else {
            btn.classList.add('heart-active');
            icon.textContent = 'favorite';
        }

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
            // Update local state
            const item = allMenuItems.find(i => i.id === id);
            if(item) item.is_favorited = !isCurrentlyActive;

            // SHOW NOTIFICATION
            if (data.status === 'added') {
                showNotification("Added to Favorites!", "success");
            } else {
                showNotification("Removed from Favorites", "info");
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Connection Error', 'error');
            // Revert UI on error
            if (isCurrentlyActive) {
                btn.classList.add('heart-active');
                icon.textContent = 'favorite';
            } else {
                btn.classList.remove('heart-active');
                icon.textContent = 'favorite_border';
            }
        });
    }

    /* =========================================
       4. NOTIFICATION SYSTEM (New!)
       ========================================= */
    function showNotification(message, type = 'success') {
        // Define colors
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-gray-600'
        };
        const colorClass = colors[type] || colors.success;
        const iconName = type === 'success' ? 'check_circle' : (type === 'error' ? 'error' : 'info');

        // Create Element
        const toast = document.createElement('div');
        // Tailwind classes for fixed positioning and styling
        toast.className = `fixed bottom-5 right-5 ${colorClass} text-white px-6 py-3 rounded-lg shadow-xl flex items-center gap-3 transform transition-all duration-300 translate-y-10 opacity-0 z-50`;
        
        toast.innerHTML = `
            <i class="material-icons text-white text-xl">${iconName}</i>
            <span class="font-medium text-sm">${message}</span>
        `;

        document.body.appendChild(toast);

        // Trigger Animation In
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        });

        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => toast.remove(), 300); // Wait for fade out
        }, 3000);
    }

   /* =========================================
   ADD TO CART (Real Database Connection)
   ========================================= */
    function addToCart(id, qty, name) {
        // 1. Show "Adding..." state (Optional UI feedback)
        showNotification(`Adding ${name}...`, 'info');

        // 2. Send Data to Backend
        fetch('/user/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN // Essential for security
            },
            body: JSON.stringify({
                menu_item_id: id,
                quantity: qty
            })
        })
        .then(async res => {
            const data = await res.json();
            
            if (!res.ok) {
                throw new Error(data.message || 'Failed to add item');
            }
            
            // 3. Success Feedback
            showNotification(`Successfully added ${qty}x ${name} to cart!`, 'success');

            // 4. UPDATE SIDEBAR BADGE (Added This!)
            if (window.updateCartBadge) {
                window.updateCartBadge();
            }
        })
        .catch(err => {
            console.error(err);
            // 5. Error Feedback
            if(err.message.includes('login')) {
                showNotification('Please login to add items.', 'error');
                window.location.href = '/login';
            } else {
                showNotification(err.message, 'error');
            }
        });
    }

    /* =========================================
       5. SEARCH & FILTER
       ========================================= */
    function setupSearchAndFilters() {
        const searchInput = document.getElementById('searchInput');
        const filterBtns = document.querySelectorAll('.filter-btn');

        searchInput.addEventListener('input', (e) => {
            filterData(e.target.value.toLowerCase(), getActiveCategory());
        });

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                filterData(searchInput.value.toLowerCase(), btn.dataset.category);
            });
        });
    }

    function getActiveCategory() {
        return document.querySelector('.filter-btn.active').dataset.category;
    }

    function filterData(searchTerm, category) {
        const filtered = allMenuItems.filter(item => {
            const matchesSearch = item.name.toLowerCase().includes(searchTerm);
            const itemCatName = item.category ? item.category.name : 'Uncategorized';
            const matchesCategory = category === 'all' || 
                              itemCatName.toLowerCase() === category.toLowerCase();
            return matchesSearch && matchesCategory;
        });
        renderFoodGrid(filtered);
    }

    /* =========================================
       6. MODAL LOGIC
       ========================================= */
    function openFoodModal(id) {
        const item = allMenuItems.find(i => i.id === id);
        if (!item) return;

        const modal = document.getElementById('foodDetailsModal');
        
        const imgEl = document.getElementById('detailImage');
        imgEl.src = item.image_url || 'https://via.placeholder.com/500';
        imgEl.onerror = function() { this.src = 'https://via.placeholder.com/500?text=No+Image'; };

        document.getElementById('detailName').textContent = item.name;
        document.getElementById('detailPrice').textContent = '₱' + parseFloat(item.price).toFixed(2);
        document.getElementById('detailDesc').textContent = item.description || 'No description available.';
        document.getElementById('detailPrep').textContent = (item.preparation_time || '15') + ' mins';
        document.getElementById('detailCat').textContent = item.category ? item.category.name : 'General';
        document.getElementById('detailReviews').textContent = `(${item.review_count || 0} reviews)`;
        document.getElementById('detailStars').innerHTML = generateStarRating(item.rating || 5);

        modal.querySelector('.quantity').textContent = '1';
        modal.classList.remove('hidden');

        const addBtn = document.getElementById('detailAddBtn');
        const newBtn = addBtn.cloneNode(true);
        addBtn.parentNode.replaceChild(newBtn, addBtn);
        
        newBtn.onclick = () => {
            const qty = parseInt(modal.querySelector('.quantity').textContent);
            addToCart(item.id, qty, item.name);
            modal.classList.add('hidden');
        };
    }

    /* =========================================
       7. HELPERS
       ========================================= */
    function generateStarRating(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) html += '<i class="material-icons" style="color:#f59e0b">star</i>';
            else html += '<i class="material-icons" style="color:#d1d5db">star_border</i>';
        }
        return html;
    }

    function quickAddToCart(e, id, name) {
        e.stopPropagation();
        addToCart(id, 1, name);
    }


    const modal = document.getElementById('foodDetailsModal');
    const closeBtn = document.getElementById('closeDetailsBtn');

    if(closeBtn) closeBtn.onclick = () => modal.classList.add('hidden');
    modal.onclick = (e) => {
        if (e.target === modal) modal.classList.add('hidden');
    };

    modal.addEventListener('click', e => {
        const btn = e.target.closest('.quantity-btn');
        if (!btn) return;
        const qtyEl = modal.querySelector('.quantity');
        let qty = parseInt(qtyEl.textContent);
        if (btn.dataset.action === 'plus') qty++;
        if (btn.dataset.action === 'minus' && qty > 1) qty--;
        qtyEl.textContent = qty;
    });

    /* =========================================
       8. SESSION ALERTS (LOGIN SUCCESS)
       ========================================= */
    // This runs immediately when page loads to check for backend messages
    
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => {
            // Slight delay to ensure UI is ready
            setTimeout(() => {
                showNotification("{{ session('success') }}", 'success');
            }, 500);
        });
    @endif

    @if(session('error'))
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                showNotification("{{ session('error') }}", 'error');
            }, 500);
        });
    @endif

    
    </script>
</body>
</html>