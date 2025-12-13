// public/js/cart.js

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Use the dynamic fee passed from Blade, default to 49 if missing
const DELIVERY_FEE = window.appConfig.deliveryFee || 49.00;

document.addEventListener('DOMContentLoaded', () => {
    loadCart();
});

// 1. LOAD CART
function loadCart() {
    fetch('/user/cart/json')
        .then(res => res.json())
        .then(data => {
            renderCartItems(data.items);
            
            // Calculate subtotal from items to ensure accuracy
            let calculatedSubtotal = 0;
            if(data.items && data.items.length > 0) {
                calculatedSubtotal = data.items.reduce((sum, item) => {
                    return sum + (parseFloat(item.menu_item.price) * parseInt(item.quantity));
                }, 0);
            }

            updateSummary(calculatedSubtotal);
        })
        .catch(err => console.error(err));
}

// 2. RENDER ITEMS
function renderCartItems(items) {
    const container = document.getElementById('cartItemsList');
    document.getElementById('cartCount').textContent = `(${items.length} items)`;

    if (items.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <i class="material-icons" style="font-size: 48px; color: #ddd;">shopping_cart</i>
                <p style="color: #888; margin-top: 10px;">Your cart is empty.</p>
                <a href="/user/dashboard" style="color: #e67e22; font-weight: bold;">Go to Menu</a>
            </div>`;
        
        // If empty, reset summary
        updateSummary(0);
        return;
    }

    container.innerHTML = items.map(item => `
        <div class="cart-item" id="cart-item-${item.id}">
            <img src="${item.menu_item.image_url || 'https://via.placeholder.com/500'}" 
                 alt="${item.menu_item.name}"
                 onerror="this.src='https://via.placeholder.com/500?text=No+Image'">
            
            <div class="item-details">
                <h3>${item.menu_item.name}</h3>
                <p class="price">₱${parseFloat(item.menu_item.price).toFixed(2)}</p>
            </div>
            
            <div class="quantity-controls">
                <button class="qty-btn minus" onclick="updateQty(${item.id}, ${item.quantity - 1})">-</button>
                <span>${item.quantity}</span>
                <button class="qty-btn plus" onclick="updateQty(${item.id}, ${item.quantity + 1})">+</button>
            </div>
            
            <div class="item-total">₱${(item.menu_item.price * item.quantity).toFixed(2)}</div>
            
            <button class="remove-btn" onclick="removeItem(${item.id})">
                <i class="material-icons">close</i>
            </button>
        </div>
    `).join('');
}

// 3. UPDATE SUMMARY (Uses Dynamic Fee)
function updateSummary(subtotalInput) {
    const subtotal = parseFloat(subtotalInput);
    
    // Logic: If cart is empty, fee is 0. If cart has items, fee is the global setting.
    const currentFee = subtotal > 0 ? DELIVERY_FEE : 0;
    const total = subtotal + currentFee;
    
    document.getElementById('summarySubtotal').textContent = `₱${subtotal.toFixed(2)}`;
    document.getElementById('summaryDelivery').textContent = `₱${currentFee.toFixed(2)}`;
    document.getElementById('summaryTotal').textContent = `₱${total.toFixed(2)}`;
}

// 4. UPDATE QUANTITY
function updateQty(id, newQty) {
    if (newQty < 1) return; 

    fetch(`/user/cart/update/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ quantity: newQty })
    }).then(() => loadCart());
}

// 5. REMOVE ITEM
function removeItem(id) {
    if(!confirm('Remove this item?')) return;

    fetch(`/user/cart/remove/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
    }).then(() => loadCart());
}

// 6. CLEAR CART
function clearCart() {
    if(!confirm('Clear your entire cart?')) return;

    fetch('/user/cart/clear', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
    }).then(() => loadCart());
}