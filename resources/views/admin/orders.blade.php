@extends('admin.AdminDashboard')

@section('title', 'Manage Orders')
@section('page-title', 'Manage Orders')

@section('page-content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="table-header">
        <h2>Manage Orders</h2>
        <div class="search-box">
            <i class="material-icons">search</i>
            <input type="text" id="search-orders" placeholder="Search orders (ID or Name)..." onkeyup="debounceSearch()">
        </div>
    </div>
    
    <div class="orders-filter">
        <button class="filter-btn active" onclick="filterOrders('all', this)">All Orders</button>
        <button class="filter-btn" onclick="filterOrders('pending', this)">Pending</button>
        <button class="filter-btn" onclick="filterOrders('preparing', this)">Preparing</button>
        <button class="filter-btn" onclick="filterOrders('ready', this)">Ready</button>
        <button class="filter-btn" onclick="filterOrders('delivered', this)">Delivered</button>
        <button class="filter-btn" onclick="filterOrders('cancelled', this)">Cancelled</button>
    </div>

    <div class="orders-table-container">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Payment Info</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
                <!-- Data populated by JS -->
            </tbody>
        </table>

        <div class="pagination" id="pagination" style="margin-top: 20px; display: flex; gap: 5px; justify-content: center;">
        </div>
    </div>

    <!-- ========================== -->
    <!-- ADMIN RECEIPT MODAL        -->
    <!-- ========================== -->
    <div class="modal-overlay" id="receiptModal" onclick="closeReceipt(event)">
        <div class="receipt-box">
            <button class="close-modal-btn" onclick="closeReceiptBtn()">×</button>
            
            <div class="receipt-header">
                <h2>ORDER RECEIPT</h2>
                <div class="receipt-meta">
                    <p id="r-id">#ORD-000000</p>
                    <p id="r-customer" style="color:#e67e22; font-weight:bold;">Customer Name</p>
                    <p id="r-date">Date</p>
                </div>
            </div>

            <div class="receipt-items" id="r-items-list">
                <!-- Items injected here -->
            </div>

            <div class="receipt-divider"></div>

            <div class="receipt-summary">
                <div class="row">
                    <span>Subtotal</span>
                    <span id="r-subtotal">₱0.00</span>
                </div>
                <div class="row">
                    <span>Delivery Fee</span>
                    <span id="r-delivery">₱0.00</span>
                </div>
                <div class="receipt-total">
                    <span>TOTAL</span>
                    <span id="r-total">₱0.00</span>
                </div>
            </div>

            <div style="margin-top: 20px; text-align: center; font-size: 0.8rem; color: #aaa;">
                <p>Status: <span id="r-status" style="font-weight:bold; color:#333;">PENDING</span></p>
                <p>Payment: <span id="r-method" style="text-transform: uppercase;">COD</span></p>
                <div id="r-address-box" style="margin-top:10px; padding:10px; background:#f9f9f9; border-radius:5px; text-align:left;">
                    <strong>Delivery Address:</strong><br>
                    <span id="r-address">N/A</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 9999;
            display: none; justify-content: center; align-items: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.active { display: flex; opacity: 1; }
        .receipt-box {
            background: white; width: 400px; padding: 25px; border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); transform: translateY(20px); transition: transform 0.3s ease;
            position: relative;
        }
        .modal-overlay.active .receipt-box { transform: translateY(0); }
        .close-modal-btn { position: absolute; top: 10px; right: 15px; font-size: 24px; border: none; background: none; cursor: pointer; }
        
        .receipt-header { text-align: center; border-bottom: 2px dashed #eee; padding-bottom: 15px; margin-bottom: 15px; }
        .receipt-header h2 { margin: 0; color: #333; letter-spacing: 2px; }
        .receipt-meta { font-size: 0.9rem; color: #666; margin-top: 5px; line-height: 1.4; }
        
        .receipt-items { max-height: 250px; overflow-y: auto; margin-bottom: 15px; }
        .receipt-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem; }
        
        .receipt-divider { border-top: 2px dashed #eee; margin: 10px 0; }
        .receipt-summary .row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 0.9rem; color: #666; }
        .receipt-total { display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem; margin-top: 10px; }

        /* Table Buttons */
        .action-container { display: flex; gap: 5px; align-items: center; }
        .btn-view { 
            background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; 
            width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; 
        }
        .btn-view:hover { background: #4338ca; color: white; }
        
        /* Badges */
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: bold; color: white; }
        .status-pending { background-color: #f59e0b; }
        .status-preparing { background-color: #3b82f6; }
        .status-ready { background-color: #8b5cf6; }
        .status-delivered { background-color: #10b981; }
        .status-cancelled { background-color: #ef4444; }

        /* Payment Badges */
        .pay-badge {
            border: none; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; cursor: pointer; text-transform: uppercase; transition: transform 0.1s;
        }
        .pay-badge:active { transform: scale(0.95); }
        .pay-paid { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; } /* Light Green */
        .pay-unpaid { background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; } /* Light Red */
        
        .page-btn { padding: 5px 10px; border: 1px solid #ddd; background: white; cursor: pointer; }
        .page-btn.active { background: #e67e22; color: white; border-color: #e67e22; }
    </style>

    <script>
        let currentStatus = 'all';
        let searchQuery = '';
        let currentPage = 1;
        
        // GLOBAL VARIABLE TO STORE FETCHED ORDERS
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

                // Method Display (Visual Helper for Admin)
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
                        
                        <!-- UPDATED PAYMENT COLUMN -->
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
                                <!-- View Receipt Button: Passes Index -->
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
            const order = fetchedOrders[index]; // Get object from global array
            if(!order) return;

            // Populate Modal
            document.getElementById('r-id').textContent = '#ORD-' + String(order.id).padStart(6, '0');
            document.getElementById('r-customer').textContent = order.user ? order.user.name : 'Guest Customer';
            document.getElementById('r-date').textContent = new Date(order.created_at).toLocaleString();
            document.getElementById('r-status').textContent = order.status.toUpperCase();
            document.getElementById('r-method').textContent = order.payment_method;
            
            // Address Handling
            if(order.delivery_type === 'delivery') {
                document.getElementById('r-address-box').style.display = 'block';
                document.getElementById('r-address').textContent = order.address || 'No address provided';
            } else {
                document.getElementById('r-address-box').style.display = 'none';
            }

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

            // CALCULATE DELIVERY FEE LOGIC
            // Total Paid - Subtotal = Delivery Fee
            const totalPaid = parseFloat(order.total_amount);
            let deliveryFee = Math.max(0, totalPaid - subtotal);

            // Ensure 0 if pickup
            if(order.delivery_type !== 'delivery') {
                deliveryFee = 0.00;
            }
            
            document.getElementById('r-subtotal').textContent = '₱' + subtotal.toFixed(2);
            document.getElementById('r-delivery').textContent = '₱' + deliveryFee.toFixed(2);
            document.getElementById('r-total').textContent = '₱' + totalPaid.toFixed(2);

            // Show
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
                // Optional: Toast notification here
                fetchOrders(currentPage);
            })
            .catch(err => alert('Error updating status'));
        }

        // 5. TOGGLE PAYMENT (NEW)
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
                fetchOrders(currentPage); // Refresh table to show new status
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
    </script>
@endsection