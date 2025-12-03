// js/cart.js
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    initCheckoutButton();
});

function loadCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <i class="material-icons" style="font-size: 64px; color: #bdc3c7; margin-bottom: 20px;">shopping_cart</i>
                <h3 style="color: #7f8c8d; margin-bottom: 10px;">Your cart is empty</h3>
                <p style="color: #95a5a6;">Add some delicious food from our menu!</p>
                <a href="index.html" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 20px;">
                    Browse Menu
                </a>
            </div>
        `;
        updateCartSummary([]);
        return;
    }
    
    cartItemsContainer.innerHTML = cart.map(item => `
        <div class="cart-item" data-food-id="${item.id}">
            <img src="${item.image}" alt="${item.name}">
            <div class="cart-item-details">
                <h4>${item.name}</h4>
                <div class="cart-item-price">${item.price}</div>
            </div>
            <div class="cart-item-controls modern">
                <div class="quantity-controls">
                    <button class="quantity-btn minus" data-item-id="${item.id}">
                        <i class="material-icons">remove</i>
                    </button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="quantity-btn plus" data-item-id="${item.id}">
                        <i class="material-icons">add</i>
                    </button>
                </div>
                <button class="remove-btn modern" data-item-id="${item.id}">
                    <i class="material-icons">delete</i>
                    <span>Remove</span>
                </button>
            </div>
        </div>
    `).join('');
    
    // Add event listeners for cart controls
    initCartControls();
    updateCartSummary(cart);
}

function initCartControls() {
    // Quantity buttons
    document.querySelectorAll('.cart-item .quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const foodId = cartItem.getAttribute('data-food-id');
            const quantityElement = cartItem.querySelector('.quantity');
            let quantity = parseInt(quantityElement.textContent);
            
            if (this.classList.contains('plus')) {
                quantity++;
            } else if (this.classList.contains('minus') && quantity > 1) {
                quantity--;
            }
            
            quantityElement.textContent = quantity;
            updateCartItemQuantity(foodId, quantity);
        });
    });
    
    // Remove buttons
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const foodId = cartItem.getAttribute('data-food-id');
            removeFromCart(foodId);
        });
    });
}

function updateCartItemQuantity(foodId, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const item = cart.find(item => item.id == foodId);
    
    if (item) {
        item.quantity = quantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        updateCartSummary(cart);
    }
}

function removeFromCart(foodId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(item => item.id != foodId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    loadCart();
    showNotification('Item removed from cart', 'info');
}

function updateCartSummary(cart) {
    const subtotal = cart.reduce((sum, item) => {
        const price = parseInt(item.price.replace('₱', ''));
        return sum + (price * item.quantity);
    }, 0);
    
    const deliveryFee = 50;
    const tax = 0;
    const total = subtotal + deliveryFee + tax;
    
    document.getElementById('subtotal').textContent = `₱${subtotal}`;
    document.getElementById('tax').textContent = `₱${tax}`;
    document.getElementById('total').textContent = `₱${total}`;
}

function initCheckoutButton() {
    document.getElementById('checkout-btn').addEventListener('click', function() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cart.length === 0) {
            showNotification('Your cart is empty', 'warning');
            return;
        }
        
        // Redirect to payment page
        window.location.href = 'payment.html';
    });
}