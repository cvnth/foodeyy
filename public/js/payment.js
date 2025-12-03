// js/payment.js
document.addEventListener('DOMContentLoaded', function() {
    loadOrderSummary();
    initPayment();
});

function loadOrderSummary() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const orderItemsContainer = document.getElementById('order-items');
    
    if (cart.length === 0) {
        orderItemsContainer.innerHTML = '<p>No items in cart</p>';
        return;
    }
    
    orderItemsContainer.innerHTML = cart.map(item => `
        <div class="order-item">
            <span>${item.quantity}x ${item.name}</span>
            <span>${item.price}</span>
        </div>
    `).join('');
    
    updateOrderSummaryForDelivery('delivery');
}

function updateOrderSummaryForDelivery(optionType) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    const subtotal = cart.reduce((sum, item) => {
        const price = parseInt(item.price.replace('₱', ''));
        return sum + (price * item.quantity);
    }, 0);
    
    const deliveryFee = optionType === 'delivery' ? 50 : 0;
    const total = subtotal + deliveryFee;
    
    document.getElementById('order-subtotal').textContent = `₱${subtotal}`;
    document.getElementById('order-delivery-fee').textContent = `₱${deliveryFee}`;
    document.getElementById('order-total').textContent = `₱${total}`;
}

function initPayment() {
    // Delivery option selection
    document.querySelectorAll('.delivery-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.delivery-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            
            const optionType = this.getAttribute('data-option');
            document.querySelectorAll('.delivery-form').forEach(form => form.classList.remove('active'));
            document.getElementById(`${optionType}-form`).classList.add('active');
            
            updateOrderSummaryForDelivery(optionType);
        });
    });
    
    // Payment method selection
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', function() {
            document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            
            const methodType = this.getAttribute('data-method');
            document.querySelectorAll('.payment-form').forEach(form => form.classList.remove('active'));
            document.getElementById(`${methodType}-form`).classList.add('active');
        });
    });
    
    // Back to cart button
    document.getElementById('back-to-cart').addEventListener('click', function() {
        window.location.href = 'cart.html';
    });
    
    // Confirm payment button
    document.getElementById('confirm-payment').addEventListener('click', function() {
        processPayment();
    });
}

function processPayment() {
    const selectedOption = document.querySelector('.delivery-option.active').getAttribute('data-option');
    const selectedMethod = document.querySelector('.payment-method.active').getAttribute('data-method');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (cart.length === 0) {
        showNotification('Your cart is empty', 'warning');
        return;
    }
    
    // Validate form based on delivery option
    let isValid = true;
    if (selectedOption === 'delivery') {
        const address = document.getElementById('address').value;
        if (!address) {
            isValid = false;
            showNotification('Please enter your delivery address', 'error');
        }
    } else if (selectedOption === 'pickup') {
        const pickupTime = document.getElementById('pickup-time').value;
        if (!pickupTime) {
            isValid = false;
            showNotification('Please select a pick-up time', 'error');
        }
    }
    
    // Validate form based on payment method
    if (selectedMethod === 'gcash') {
        const gcashNumber = document.getElementById('gcash-number').value;
        if (!gcashNumber) {
            isValid = false;
            showNotification('Please enter your GCash number', 'error');
        }
    }
    
    if (!isValid) return;
    
    // Simulate payment processing
    showNotification('Processing your order...', 'info');
    
    setTimeout(() => {
        // Create order
        const orderId = 'ORD-' + Date.now().toString().slice(-6);
        const order = {
            id: orderId,
            date: new Date().toISOString().split('T')[0],
            status: 'pending',
            items: cart,
            deliveryOption: selectedOption,
            paymentMethod: selectedMethod,
            total: calculateOrderTotal(cart, selectedOption)
        };
        
        // Save order to history
        let orders = JSON.parse(localStorage.getItem('orders')) || [];
        orders.unshift(order);
        localStorage.setItem('orders', JSON.stringify(orders));
        
        // Clear cart
        localStorage.setItem('cart', JSON.stringify([]));
        updateCartCount();
        
        // Show success message
        let successMessage = `Order #${orderId} has been placed successfully! `;
        if (selectedOption === 'delivery') {
            successMessage += 'Your food will be delivered within 30-45 minutes.';
        } else {
            successMessage += 'Your order will be ready for pick-up at the selected time.';
        }
        
        showNotification(successMessage, 'success');
        
        // Redirect to order history
        setTimeout(() => {
            window.location.href = 'orders.html';
        }, 2000);
    }, 2000);
}

function calculateOrderTotal(cart, optionType) {
    const subtotal = cart.reduce((sum, item) => {
        const price = parseInt(item.price.replace('₱', ''));
        return sum + (price * item.quantity);
    }, 0);
    
    const deliveryFee = optionType === 'delivery' ? 50 : 0;
    return `₱${subtotal + deliveryFee}`;
}