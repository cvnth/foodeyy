// js/main.js
// Common utility functions
function showNotification(message, type = 'info') {
    const icons = { 
        success: 'check_circle', 
        warning: 'warning', 
        error: 'error', 
        info: 'info' 
    };
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `<i class="material-icons">${icons[type]}</i><span>${message}</span>`;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.querySelector('.stat-cart');
    if (cartCountElement) {
        cartCountElement.closest('.stat-card').querySelector('h3').textContent = totalItems;
    }
}

function updateFavoritesCount() {
    const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const favoritesCountElement = document.querySelector('.stat-favorites');
    if (favoritesCountElement) {
        favoritesCountElement.closest('.stat-card').querySelector('h3').textContent = favorites.length;
    }
}

function loadUserData() {
    // Load from saved settings or use defaults
    const savedSettings = JSON.parse(localStorage.getItem('userSettings')) || {};
    const user = { 
        name: savedSettings.name || 'John Smith', 
        membership: 'Premium Member', 
        orders: 12, 
        favorites: 8, 
        cartItems: 3 
    };
    
    const pageTitle = document.getElementById('page-title');
    if (pageTitle && !pageTitle.textContent.includes('Your')) {
        pageTitle.textContent = `Welcome back, ${user.name.split(' ')[0]}!`;
    }
    
    const userInfo = document.querySelector('.user-info h4');
    const userMembership = document.querySelector('.user-info p');
    if (userInfo) userInfo.textContent = user.name;
    if (userMembership) userMembership.textContent = user.membership;
    
    updateFavoritesCount();
    updateCartCount();
}

// Cart management functions
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

// Initialize with sample data if not present
if (!localStorage.getItem('favorites')) {
    localStorage.setItem('favorites', JSON.stringify([]));
}

if (!localStorage.getItem('cart')) {
    localStorage.setItem('cart', JSON.stringify([]));
}

if (!localStorage.getItem('orders')) {
    localStorage.setItem('orders', JSON.stringify([]));
}

if (!localStorage.getItem('userSettings')) {
    localStorage.setItem('userSettings', JSON.stringify({}));
}

// js/main.js

function handleLogout() {
  // Clear all session/local data
  localStorage.removeItem('isLoggedIn');
  localStorage.removeItem('isAdminLoggedIn');
  localStorage.removeItem('userData');

  // Redirect to login page
  window.location.replace('auth.html');
}

function loadUserData() {
  const user = JSON.parse(localStorage.getItem('userData'));
  if (user) {
    document.getElementById('userName').textContent = user.name || 'User';
    document.getElementById('userEmail').textContent = user.email || 'user@example.com';
    if (user.image) {
      document.getElementById('userProfilePic').src = user.image;
    }
  }
}
