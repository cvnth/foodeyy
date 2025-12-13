// public/js/order-history.js

function showReceipt(cardElement) {
    try {
        // 1. Get Data Safely
        const jsonString = cardElement.getAttribute('data-json');
        
        if (!jsonString) {
            console.error("No JSON data found on card element");
            return;
        }

        const order = JSON.parse(jsonString);

        // 2. Populate Header
        document.getElementById('r-id').textContent = '#ORD-' + String(order.id).padStart(6, '0');
        document.getElementById('r-date').textContent = new Date(order.created_at).toLocaleString();
        document.getElementById('r-status').textContent = order.status ? order.status.toUpperCase() : 'UNKNOWN';
        document.getElementById('r-method').textContent = order.payment_method;

        // 3. Populate Items & Calculate Subtotal
        const itemsContainer = document.getElementById('r-items-list');
        itemsContainer.innerHTML = ''; // Clear old items
        
        let subtotal = 0;

        if (order.items && order.items.length > 0) {
            order.items.forEach(item => {
                // Use fallback logic for item total if not explicitly provided
                const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
                subtotal += itemTotal;
                
                // Handle nested relation 'menu_item' vs 'menuItem' depending on how Laravel serialized it
                const menuItem = item.menu_item || item.menuItem;
                const itemName = menuItem ? menuItem.name : 'Unknown Item';

                itemsContainer.innerHTML += `
                    <div class="receipt-row">
                        <span>${item.quantity}× ${itemName}</span>
                        <span>₱${itemTotal.toFixed(2)}</span>
                    </div>
                `;
            });
        } else {
            itemsContainer.innerHTML = '<p style="text-align:center; color:#ccc;">No items details available</p>';
        }

        // 4. Calculate Fee (Total Paid - Item Cost)
        const totalPaid = parseFloat(order.total_amount);
        
        // Calculate fee (ensure it's not negative)
        let deliveryFee = Math.max(0, totalPaid - subtotal);
        
        // If it was pick-up, ensure 0.00
        if(order.delivery_type !== 'delivery') {
            deliveryFee = 0.00;
        }

        // 5. Update UI
        document.getElementById('r-subtotal').textContent = '₱' + subtotal.toFixed(2);
        document.getElementById('r-delivery').textContent = '₱' + deliveryFee.toFixed(2);
        document.getElementById('r-total').textContent = '₱' + totalPaid.toFixed(2);

        // 6. Show Modal
        const modal = document.getElementById('receiptModal');
        modal.style.display = 'flex';
        // Trigger reflow for animation
        void modal.offsetWidth;
        modal.classList.add('active');
    } catch (err) {
        console.error("Error opening receipt:", err);
        alert("Failed to open receipt. Please check console for details.");
    }
}

function closeReceipt(e) {
    // Close only if clicking the background overlay
    if (e.target.id === 'receiptModal') {
        closeReceiptBtn();
    }
}

function closeReceiptBtn() {
    const modal = document.getElementById('receiptModal');
    modal.classList.remove('active');
    setTimeout(() => modal.style.display = 'none', 300);
}