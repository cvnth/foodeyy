// js/dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    initDashboard();
});

function initDashboard() {
    initFoodItemClick();
    initFilterButtons();
    initAddToCart();
    initFavorites();
    initSearch();
    loadUserData();
}

function initFoodItemClick() {
    document.querySelectorAll('.food-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.add-to-cart') && !e.target.closest('.favorite-btn')) {
                const foodId = this.getAttribute('data-food-id');
                showFoodDetails(foodId);
            }
        });
    });
}

function showFoodDetails(foodId) {
    // Create modal for food details
    const foodData = getFoodDataById(foodId);
    
    const modal = document.createElement('div');
    modal.className = 'food-details-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    `;
    
    modal.innerHTML = `
        <div class="food-details-section" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
            <button class="back-to-list" onclick="this.closest('.food-details-modal').remove()">
                <i class="material-icons">close</i> Close
            </button>
            <div class="food-details-container">
                <div class="food-details-header">
                    <div class="food-details-image">
                        <img src="${foodData.image}" alt="${foodData.name}">
                    </div>
                    <div class="food-details-info">
                        <h2>${foodData.name}</h2>
                        <div class="food-details-price">${foodData.price}</div>
                        <div class="food-details-rating">
                            ${generateStarRating(foodData.rating)}
                            <span>(${foodData.reviews} reviews)</span>
                        </div>
                        <p class="food-details-description">${foodData.description}</p>
                        <div class="food-details-actions">
                            <div class="quantity-selector">
                                <button class="quantity-btn minus">-</button>
                                <span class="quantity">1</span>
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <button class="add-to-cart-large" data-food-id="${foodData.id}">
                                <i class="material-icons">add_shopping_cart</i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                <div class="food-details-meta">
                    <div class="meta-card">
                        <h4>Preparation Time</h4>
                        <p>${foodData.prepTime}</p>
                    </div>
                    <div class="meta-card">
                        <h4>Calories</h4>
                        <p>${foodData.calories}</p>
                    </div>
                    <div class="meta-card">
                        <h4>Spice Level</h4>
                        <p>${foodData.spiceLevel}</p>
                    </div>
                    <div class="meta-card">
                        <h4>Category</h4>
                        <p>${foodData.category}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Add event listeners for the modal
    modal.querySelector('.add-to-cart-large').addEventListener('click', function() {
        const quantity = parseInt(modal.querySelector('.quantity').textContent);
        addItemToCart(foodData, quantity);
        showAddToCartFeedback(this);
        setTimeout(() => modal.remove(), 1500);
    });
    
    modal.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const quantityElement = this.closest('.quantity-selector').querySelector('.quantity');
            let quantity = parseInt(quantityElement.textContent);
            
            if (this.classList.contains('plus')) {
                quantity++;
            } else if (this.classList.contains('minus') && quantity > 1) {
                quantity--;
            }
            
            quantityElement.textContent = quantity;
        });
    });
}

function initFilterButtons() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            const category = this.textContent.toLowerCase();
            filterFoodItems(category);
        });
    });
}

function filterFoodItems(category) {
    const foodCards = document.querySelectorAll('.food-card');
    foodCards.forEach(card => {
        const foodName = card.querySelector('h3').textContent.toLowerCase();
        if (category === 'all') card.style.display = 'block';
        else {
            const match = checkFoodCategory(foodName, category);
            card.style.display = match ? 'block' : 'none';
        }
    });
}

function checkFoodCategory(foodName, category) {
    const categories = {
        'western': ['steak', 'pizza', 'burger', 'pasta'],
        'chinese': ['sweet and sour', 'fried rice', 'noodles', 'dim sum'],
        'japanese': ['teriyaki', 'ramen', 'sushi', 'tempura'],
        'desserts': ['cake', 'ice cream', 'pie', 'pudding']
    };
    return categories[category]?.some(k => foodName.includes(k)) || false;
}

function initSearch() {
    const searchInput = document.querySelector('.search-bar input');
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase().trim();
        const foodCards = document.querySelectorAll('.food-card');
        foodCards.forEach(card => {
            const foodName = card.querySelector('h3').textContent.toLowerCase();
            card.style.display = term === '' || foodName.includes(term) ? 'block' : 'none';
        });
    });
}

function initAddToCart() {
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
}

function addItemToCart(foodData, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItem = cart.find(item => item.id === foodData.id);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: foodData.id,
            name: foodData.name,
            price: foodData.price,
            image: foodData.image,
            quantity: quantity
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification(`${foodData.name} added to cart!`, 'success');
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

function initFavorites() {
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const card = this.closest('.food-card');
            const foodId = card.getAttribute('data-food-id');
            const foodData = getFoodDataById(foodId);
            
            toggleFavorite(foodData, this);
        });
    });
}

function toggleFavorite(foodData, button) {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const index = favorites.findIndex(f => f.id === foodData.id);
    
    if (index === -1) {
        favorites.push(foodData);
        button.innerHTML = '<i class="material-icons">favorite</i>';
        button.classList.add('active');
        showNotification(`${foodData.name} added to favorites!`, 'success');
    } else {
        favorites.splice(index, 1);
        button.innerHTML = '<i class="material-icons">favorite_border</i>';
        button.classList.remove('active');
        showNotification(`${foodData.name} removed from favorites`, 'info');
    }
    
    localStorage.setItem('favorites', JSON.stringify(favorites));
    updateFavoritesCount();
}