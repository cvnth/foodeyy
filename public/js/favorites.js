// js/favorites.js
document.addEventListener('DOMContentLoaded', function() {
    loadFavorites();
});

function loadFavorites() {
    const favoritesGrid = document.getElementById('favorites-grid');
    const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    
    if (favorites.length === 0) {
        favoritesGrid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <i class="material-icons" style="font-size: 64px; color: #bdc3c7; margin-bottom: 20px;">favorite_border</i>
                <h3 style="color: #7f8c8d; margin-bottom: 10px;">No favorites yet</h3>
                <p style="color: #95a5a6;">Start adding your favorite foods from the dashboard!</p>
                <a href="index.html" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 20px;">
                    Browse Foods
                </a>
            </div>
        `;
        return;
    }
    
    favoritesGrid.innerHTML = favorites.map(food => `
        <div class="food-card" data-food-id="${food.id}">
            <img src="${food.image}" alt="${food.name}">
            <div class="food-info">
                <h3>${food.name}</h3>
                <p>${food.price}</p>
                <div class="food-meta">
                    <div class="rating">
                        ${generateStarRating(food.rating)}
                        <span>(${food.reviews})</span>
                    </div>
                    <div>
                        <button class="favorite-btn active"><i class="material-icons">favorite</i></button>
                        <button class="add-to-cart"><i class="material-icons">add_shopping_cart</i> Add</button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    // Reattach event listeners
    initFavoritesInteractions();
}

function initFavoritesInteractions() {
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const card = this.closest('.food-card');
            const foodId = card.getAttribute('data-food-id');
            const foodData = getFoodDataById(foodId);
            
            addItemToCart(foodData, 1);
            showAddToCartFeedback(this);
        });
    });
    
    // Remove from favorites functionality
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const card = this.closest('.food-card');
            const foodId = card.getAttribute('data-food-id');
            const foodData = getFoodDataById(foodId);
            
            toggleFavorite(foodData, this);
            // Remove card from DOM after a short delay
            setTimeout(() => {
                card.remove();
                // Reload if no favorites left
                const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
                if (favorites.length === 0) {
                    loadFavorites();
                }
            }, 300);
        });
    });
    
    // Food card click for details
    document.querySelectorAll('.food-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.add-to-cart') && !e.target.closest('.favorite-btn')) {
                const foodId = this.getAttribute('data-food-id');
                // You can implement a modal similar to dashboard.js
                showNotification('Food details would open here', 'info');
            }
        });
    });
}

function toggleFavorite(foodData, button) {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const index = favorites.findIndex(f => f.id === foodData.id);
    
    if (index !== -1) {
        favorites.splice(index, 1);
        showNotification(`${foodData.name} removed from favorites`, 'info');
    }
    
    localStorage.setItem('favorites', JSON.stringify(favorites));
    updateFavoritesCount();
}

function showAddToCartFeedback(button) {
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="material-icons">check</i> Added';
    button.style.backgroundColor = '#4CAF50';
    button.disabled = true;
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.style.backgroundColor = '';
        button.disabled = false;
    }, 1500);
}