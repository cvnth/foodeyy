// js/orders.js
document.addEventListener('DOMContentLoaded', function() {
    loadOrderHistory();
});

function loadOrderHistory() {
    const ordersGrid = document.getElementById('orders-grid');
    let orders = JSON.parse(localStorage.getItem('orders')) || [];
    
    // If no orders, add some sample data
    if (orders.length === 0) {
        orders = [
            {
                id: 'ORD-001',
                date: '2023-10-15',
                status: 'delivered',
                items: [
                    { name: 'Beef Steak', price: '₱250', quantity: 1, image: 'https://images.unsplash.com/photo-1546964124-0cce460f38ef?auto=format&fit=crop&w=500&q=80' },
                    { name: 'Chicken Teriyaki', price: '₱180', quantity: 2, image: 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=500&q=80' }
                ],
                deliveryOption: 'delivery',
                paymentMethod: 'cod',
                total: '₱610'
            },
            {
                id: 'ORD-002',
                date: '2023-10-10',
                status: 'delivered',
                items: [
                    { name: 'Sweet and Sour Pork', price: '₱210', quantity: 1, image: 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=500&q=80' },
                    { name: 'Fried Rice', price: '₱120', quantity: 1, image: 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=500&q=80' }
                ],
                deliveryOption: 'pickup',
                paymentMethod: 'gcash',
                total: '₱330'
            },
            {
                id: 'ORD-003',
                date: '2023-10-05',
                status: 'cancelled',
                items: [
                    { name: 'Beef Steak', price: '₱250', quantity: 2, image: 'https://images.unsplash.com/photo-1546964124-0cce460f38ef?auto=format&fit=crop&w=500&q=80' }
                ],
                deliveryOption: 'delivery',
                paymentMethod: 'cod',
                total: '₱500'
            },
            {
                id: 'ORD-004',
                date: '2023-09-28',
                status: 'delivered',
                items: [
                    { name: 'Grilled Salmon', price: '₱320', quantity: 1, image: 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=500&q=80' },
                    { name: 'Beef Ramen', price: '₱220', quantity: 1, image: 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=500&q=80' }
                ],
                deliveryOption: 'delivery',
                paymentMethod: 'gcash',
                total: '₱590'
            },
            {
                id: 'ORD-005',
                date: '2023-09-22',
                status: 'delivered',
                items: [
                    { name: 'Kung Pao Chicken', price: '₱190', quantity: 2, image: 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=500&q=80' },
                    { name: 'Sweet and Sour Pork', price: '₱210', quantity: 1, image: 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=500&q=80' }
                ],
                deliveryOption: 'pickup',
                paymentMethod: 'cod',
                total: '₱590'
            },
            {
                id: 'ORD-006',
                date: '2023-09-15',
                status: 'delivered',
                items: [
                    { name: 'Chicken Teriyaki', price: '₱180', quantity: 3, image: 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=500&q=80' }
                ],
                deliveryOption: 'delivery',
                paymentMethod: 'gcash',
                total: '₱590'
            }
        ];
        localStorage.setItem('orders', JSON.stringify(orders));
    }
    
    if (orders.length === 0) {
        ordersGrid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <i class="material-icons" style="font-size: 64px; color: #bdc3c7; margin-bottom: 20px;">receipt</i>
                <h3 style="color: #7f8c8d; margin-bottom: 10px;">No orders yet</h3>
                <p style="color: #95a5a6;">Your order history will appear here after you place your first order.</p>
                <a href="index.html" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 20px;">
                    Start Ordering
                </a>
            </div>
        `;
        return;
    }
    
    ordersGrid.innerHTML = orders.map(order => {
        // Get the first item's image for the order card
        const firstItemImage = order.items[0]?.image || 'https://images.unsplash.com/photo-1546964124-0cce460f38ef?auto=format&fit=crop&w=500&q=80';
        // Show only first 2 items, indicate if there are more
        const previewItems = order.items.slice(0, 2);
        const hasMoreItems = order.items.length > 2;
        
        return `
        <div class="order-card">
            <div class="order-card-image">
                <img src="${firstItemImage}" alt="Order ${order.id}">
                <div class="order-status-badge status-${order.status}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</div>
            </div>
            <div class="order-card-content">
                <div class="order-card-header">
                    <div class="order-id">${order.id}</div>
                    <div class="order-date">${order.date}</div>
                </div>
                <div class="order-items-preview">
                    ${previewItems.map(item => `
                        <div class="order-item-preview">
                            <span class="order-item-name">${item.quantity}x ${item.name}</span>
                            <span class="order-item-price">${item.price}</span>
                        </div>
                    `).join('')}
                    ${hasMoreItems ? `<div class="more-items">+${order.items.length - 2} more items</div>` : ''}
                </div>
                <div class="order-meta">
                    <div class="order-meta-item">
                        <i class="material-icons">${order.deliveryOption === 'delivery' ? 'delivery_dining' : 'store'}</i>
                        <span>${order.deliveryOption === 'delivery' ? 'Delivery' : 'Pick-up'}</span>
                    </div>
                    <div class="order-meta-item">
                        <i class="material-icons">${order.paymentMethod === 'cod' ? 'money' : 'smartphone'}</i>
                        <span>${order.paymentMethod === 'cod' ? 'COD' : 'GCash'}</span>
                    </div>
                </div>
                <div class="order-card-footer">
                    <div class="order-total">${order.total}</div>
                    <button class="reorder-btn" data-order-id="${order.id}">
                        <i class="material-icons">replay</i> Reorder
                    </button>
                </div>
            </div>
        </div>
        `;
    }).join('');
    
    // Add event listeners for reorder buttons
    initReorderButtons();
}

function initReorderButtons() {
    document.querySelectorAll('.reorder-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            reorderItems(orderId);
        });
    });
}

function reorderItems(orderId) {
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const order = orders.find(o => o.id === orderId);
    
    if (!order) {
        showNotification('Order not found', 'error');
        return;
    }
    
    // Add all items from the order to the cart
    order.items.forEach(item => {
        const foodData = getFoodDataByName(item.name);
        if (foodData) {
            addItemToCart(foodData, item.quantity);
        }
    });
    
    showNotification('Items from order added to cart!', 'success');
    
    // Redirect to cart
    setTimeout(() => {
        window.location.href = 'cart.html';
    }, 1000);
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
}