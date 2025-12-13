// public/js/admin-orders.js

let currentStatus = 'all';
let searchQuery = '';
let currentPage = 1;
let fetchedOrders = []; 

document.addEventListener('DOMContentLoaded', () => {
    fetchOrders();
});

// 1. FETCH ORDERS
function fetchOrders(page = 1) {
    currentPage = page;
    const url = `/admin/orders/json?page=${page}&status=${currentStatus}&search=${searchQuery}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            fetchedOrders = data.data; // Store raw data for modal usage
            renderTable(fetchedOrders);
            renderPagination(data);
        })
        .catch(err => console.error(err));
}

// 2. RENDER TABLE
function renderTable(orders) {
    const tbody = document.getElementById('orders-table-body');
    tbody.innerHTML = '';

    if (orders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:20px;">No orders found</td></tr>';
        return;
    }

    orders.forEach((order, index) => {
        let badgeClass = 'status-pending';
        if(order.status === 'delivered') badgeClass = 'status-delivered';
        if(order.status === 'cancelled') badgeClass = 'status-cancelled';
        if(order.status === 'preparing') badgeClass = 'status-preparing';
        if(order.status === 'ready') badgeClass = 'status-ready';

        // Payment Status Logic
        let payClass = order.payment_status === 'paid' ? 'pay-paid' : 'pay-unpaid';
        let payText = order.payment_status ? order.payment_status.toUpperCase() : 'UNPAID';

        // Method Display
        let methodDisplay = order.payment_method === 'gcash' 
            ? '<span style="font-size:0.75rem; color:#3b82f6; font-weight:bold;">GCash</span>' 
            : '<span style="font-size:0.75rem; color:#666; font-weight:bold;">COD</span>';

        // Status Options
        const statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled', 'picked-up'];
        let optionsHtml = statuses.map(s => 
            `<option value="${s}" ${s === order.status ? 'selected' : ''}>${s.charAt(0).toUpperCase() + s.slice(1)}</option>`
        ).join('');

        tbody.innerHTML += `
            <tr>
                <td>#ORD-${String(order.id).padStart(6, '0')}</td>
                <td>${order.user ? order.user.name : 'Guest'}</td>
                <td>₱${parseFloat(order.total_amount).toFixed(2)}</td>
                
                <td>
                    <div style="display:flex; flex-direction:column; align-items:flex-start; gap:4px;">
                        ${methodDisplay}
                        <button onclick="togglePayment(${order.id})" class="pay-badge ${payClass}" title="Click to toggle payment">
                            ${payText}
                        </button>
                    </div>
                </td>

                <td><span class="status-badge ${badgeClass}">${order.status.toUpperCase()}</span></td>
                <td>${new Date(order.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="action-container">
                        <select onchange="updateStatus(${order.id}, this.value)" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                            ${optionsHtml}
                        </select>
                        <button class="btn-view" onclick="openAdminReceipt(${index})" title="View Receipt">
                            <i class="material-icons" style="font-size: 18px;">receipt</i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

// 3. OPEN RECEIPT MODAL
function openAdminReceipt(index) {
    const order = fetchedOrders[index]; 
    if(!order) return;

    // Populate Basic Info
    document.getElementById('r-id').textContent = '#ORD-' + String(order.id).padStart(6, '0');
    document.getElementById('r-customer').textContent = order.user ? order.user.name : 'Guest Customer';
    document.getElementById('r-date').textContent = new Date(order.created_at).toLocaleString();
    document.getElementById('r-status').textContent = order.status.toUpperCase();
    document.getElementById('r-method').textContent = order.payment_method;
    
    // Address & Phone Logic
    if(order.delivery_type === 'delivery') {
        document.getElementById('r-address-box').style.display = 'block';
        document.getElementById('r-address').textContent = order.address || 'No address provided';
        
        // Landmark
        const landmarkBox = document.getElementById('r-landmark-container');
        if(order.landmark) {
            landmarkBox.style.display = 'block';
            document.getElementById('r-landmark').textContent = order.landmark;
        } else {
            landmarkBox.style.display = 'none';
        }

    } else {
        document.getElementById('r-address-box').style.display = 'none';
    }

    // Phone Number
    let phoneNum = order.phone || (order.user ? order.user.phone : 'N/A');
    document.getElementById('r-phone').textContent = phoneNum;

    // Items List
    const list = document.getElementById('r-items-list');
    list.innerHTML = '';
    
    let subtotal = 0;
    if(order.items && order.items.length > 0) {
        order.items.forEach(item => {
            const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
            subtotal += itemTotal;
            const itemName = item.menu_item ? item.menu_item.name : 'Deleted Item';
            
            list.innerHTML += `
                <div class="receipt-row">
                    <span>${item.quantity}× ${itemName}</span>
                    <span>₱${itemTotal.toFixed(2)}</span>
                </div>
            `;
        });
    } else {
        list.innerHTML = '<p style="text-align:center;color:#999">No items found</p>';
    }

    // Calculate Delivery Fee
    const totalPaid = parseFloat(order.total_amount);
    let deliveryFee = Math.max(0, totalPaid - subtotal);

    if(order.delivery_type !== 'delivery') {
        deliveryFee = 0.00;
    }
    
    document.getElementById('r-subtotal').textContent = '₱' + subtotal.toFixed(2);
    document.getElementById('r-delivery').textContent = '₱' + deliveryFee.toFixed(2);
    document.getElementById('r-total').textContent = '₱' + totalPaid.toFixed(2);

    // Show Modal
    const modal = document.getElementById('receiptModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('active'), 10);
}

function closeReceipt(e) {
    if (e.target.id === 'receiptModal') closeReceiptBtn();
}

function closeReceiptBtn() {
    const modal = document.getElementById('receiptModal');
    modal.classList.remove('active');
    setTimeout(() => modal.style.display = 'none', 300);
}

// 4. UPDATE STATUS
function updateStatus(id, newStatus) {
    if(!confirm(`Change status to ${newStatus}?`)) {
        fetchOrders(currentPage); 
        return;
    }

    fetch(`/admin/orders/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        fetchOrders(currentPage);
    })
    .catch(err => alert('Error updating status'));
}

// 5. TOGGLE PAYMENT
function togglePayment(id) {
    if(!confirm('Toggle payment status for this order?')) return;

    fetch(`/admin/orders/${id}/payment`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        fetchOrders(currentPage);
    })
    .catch(err => alert('Error updating payment status'));
}

// 6. FILTERS & SEARCH
function filterOrders(status, btn) {
    currentStatus = status;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    fetchOrders(1);
}

let timeout = null;
function debounceSearch() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        searchQuery = document.getElementById('search-orders').value;
        fetchOrders(1);
    }, 500);
}

function renderPagination(data) {
    const container = document.getElementById('pagination');
    container.innerHTML = '';
    data.links.forEach(link => {
        if (link.url) {
            const activeClass = link.active ? 'active' : '';
            let label = link.label.replace('&laquo;', '«').replace('&raquo;', '»');
            container.innerHTML += `<button class="page-btn ${activeClass}" onclick="fetchOrders(${link.url.split('page=')[1]})">${label}</button>`;
        }
    });
}