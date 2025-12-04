// admin/js/admin-orders.js
let currentPage = 1;
const ordersPerPage = 10;
let currentFilter = 'all';
let currentSort = { field: 'date', direction: 'desc' };

document.addEventListener('DOMContentLoaded', function() {
    checkAdminAuth();
    initializeOrdersData();
    loadOrders();
    initFilters();
    initSearch();
    initSorting();
    updateStats();
});

function checkAdminAuth() {
    const isAdminLoggedIn = localStorage.getItem('isAdminLoggedIn');
    const adminUser = JSON.parse(localStorage.getItem('adminUser') || '{}');
    
    if (!isAdminLoggedIn || !adminUser.name) {
        window.location.href = '../auth.html?admin=true';
        return;
    }
}

function initializeOrdersData() {
    // If no orders exist, use the mock data from admin-dashboard
    const existingOrders = localStorage.getItem('orders');
    if (!existingOrders) {
        // Try to initialize from admin-dashboard mock data
        if (window.MockDataGenerator) {
            new MockDataGenerator();
        } else {
            // Fallback to simple data
            const defaultOrders = [
                {
                    id: 'ORD-001',
                    customerName: 'John Doe',
                    customerPhone: '+639171234567',
                    items: [
                        { id: '1', name: 'Classic Beef Burger', quantity: 2, price: '₱120' },
                        { id: '7', name: 'Iced Caramel Macchiato', quantity: 1, price: '₱140' }
                    ],
                    total: '₱380',
                    status: 'delivered',
                    paymentMethod: 'GCash',
                    paymentStatus: 'paid',
                    orderDate: new Date().toISOString(),
                    deliveryType: 'delivery'
                }
            ];
            localStorage.setItem('orders', JSON.stringify(defaultOrders));
        }
    }
}

function loadOrders() {
    let orders = JSON.parse(localStorage.getItem('orders')) || [];
    
    // Apply search filter
    const searchTerm = document.getElementById('search-orders')?.value.toLowerCase() || '';
    if (searchTerm) {
        orders = orders.filter(order => 
            order.id.toLowerCase().includes(searchTerm) ||
            order.customerName?.toLowerCase().includes(searchTerm) ||
            order.customerPhone?.includes(searchTerm) ||
            order.items.some(item => item.name.toLowerCase().includes(searchTerm))
        );
    }
    
    // Apply status filter
    if (currentFilter !== 'all') {
        orders = orders.filter(order => order.status === currentFilter);
    }
    
    // Apply sorting
    orders.sort((a, b) => {
        const aDate = new Date(a.orderDate || a.date);
        const bDate = new Date(b.orderDate || b.date);
        
        if (currentSort.field === 'date') {
            return currentSort.direction === 'asc' 
                ? aDate - bDate
                : bDate - aDate;
        }
        if (currentSort.field === 'amount') {
            const amountA = parseFloat((a.total || '0').replace(/[^\d.]/g, '')) || 0;
            const amountB = parseFloat((b.total || '0').replace(/[^\d.]/g, '')) || 0;
            return currentSort.direction === 'asc' ? amountA - amountB : amountB - amountA;
        }
        if (currentSort.field === 'customer') {
            const nameA = a.customerName || `Customer ${a.id.slice(-3)}`;
            const nameB = b.customerName || `Customer ${b.id.slice(-3)}`;
            return currentSort.direction === 'asc' 
                ? nameA.localeCompare(nameB)
                : nameB.localeCompare(nameA);
        }
        return 0;
    });
    
    displayOrders(orders);
    setupPagination(orders);
    updateStats(orders);
}

function displayOrders(orders) {
    const startIndex = (currentPage - 1) * ordersPerPage;
    const paginatedOrders = orders.slice(startIndex, startIndex + ordersPerPage);
    const tableBody = document.getElementById('orders-table-body');
    
    if (!tableBody) return;
    
    if (paginatedOrders.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #7f8c8d;">
                    <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">receipt</i>
                    <p>No orders found</p>
                    ${currentFilter !== 'all' ? '<p>Try changing your filters</p>' : ''}
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = paginatedOrders.map(order => `
        <tr>
            <td><strong>${order.id}</strong></td>
            <td>
                <div style="font-weight: 600;">${order.customerName || `Customer ${order.id.slice(-3)}`}</div>
                <div style="font-size: 12px; color: #7f8c8d;">${order.customerPhone || 'N/A'}</div>
            </td>
            <td>
                <div style="max-width: 200px;">
                    ${order.items.slice(0, 2).map(item => `
                        <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                            <span>${item.quantity}x ${item.name}</span>
                            <span style="color: #7f8c8d;">${item.price}</span>
                        </div>
                    `).join('')}
                    ${order.items.length > 2 ? `
                        <div class="more-items" style="color: #3498db; font-size: 12px; margin-top: 5px;">
                            +${order.items.length - 2} more items
                        </div>
                    ` : ''}
                </div>
            </td>
            <td style="font-weight: 600; color: #2c3e50;">${order.total}</td>
            <td>
                <span class="status-badge status-${order.status}">
                    ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                </span>
            </td>
            <td>
                <div style="font-size: 14px;">${formatOrderDate(order.orderDate || order.date)}</div>
                <div style="font-size: 12px; color: #7f8c8d;">${order.deliveryType || 'Delivery'}</div>
            </td>
            <td>
                <div style="font-size: 12px; color: #7f8c8d;">${order.paymentMethod || 'N/A'}</div>
                <span class="status-badge status-${order.paymentStatus || 'pending'}">
                    ${(order.paymentStatus || 'pending').charAt(0).toUpperCase() + (order.paymentStatus || 'pending').slice(1)}
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn btn-view" onclick="viewOrderDetails('${order.id}')" title="View Details">
                        <i class="material-icons">visibility</i>
                    </button>
                    <button class="action-btn btn-edit" onclick="editOrderStatus('${order.id}')" title="Edit Status">
                        <i class="material-icons">edit</i>
                    </button>
                    <button class="action-btn btn-update" onclick="quickUpdateStatus('${order.id}')" title="Quick Update">
                        <i class="material-icons">update</i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function formatOrderDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    } catch {
        return 'Invalid Date';
    }
}

function setupPagination(orders) {
    const totalPages = Math.ceil(orders.length / ordersPerPage);
    const pagination = document.getElementById('pagination');
    
    if (!pagination) return;
    
    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }
    
    let paginationHTML = `
        <button class="page-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
            <i class="material-icons">chevron_left</i>
        </button>
    `;
    
    // Show page numbers
    const maxPages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
    let endPage = Math.min(totalPages, startPage + maxPages - 1);
    
    if (endPage - startPage + 1 < maxPages) {
        startPage = Math.max(1, endPage - maxPages + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        paginationHTML += `
            <button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">
                ${i}
            </button>
        `;
    }
    
    paginationHTML += `
        <button class="page-btn" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
            <i class="material-icons">chevron_right</i>
        </button>
    `;
    
    pagination.innerHTML = paginationHTML;
    
    // Update results count
    const resultsCount = document.getElementById('results-count');
    if (resultsCount) {
        const startIndex = (currentPage - 1) * ordersPerPage + 1;
        const endIndex = Math.min(currentPage * ordersPerPage, orders.length);
        resultsCount.textContent = `Showing ${startIndex}-${endIndex} of ${orders.length} orders`;
    }
}

function changePage(page) {
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const totalPages = Math.ceil(orders.length / ordersPerPage);
    
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        loadOrders();
    }
}

function initFilters() {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-status');
            currentPage = 1;
            loadOrders();
        });
    });
}

function initSearch() {
    const searchInput = document.getElementById('search-orders');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadOrders();
            }, 300);
        });
    }
}

function initSorting() {
    document.querySelectorAll('.orders-table th[data-sort]').forEach(th => {
        th.addEventListener('click', function() {
            const field = this.getAttribute('data-sort');
            if (currentSort.field === field) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.field = field;
                currentSort.direction = 'asc';
            }
            currentPage = 1;
            loadOrders();
            updateSortIcons();
        });
    });
}

function updateSortIcons() {
    document.querySelectorAll('.orders-table th[data-sort]').forEach(th => {
        const icon = th.querySelector('.material-icons');
        if (th.getAttribute('data-sort') === currentSort.field) {
            icon.textContent = currentSort.direction === 'asc' ? 'arrow_upward' : 'arrow_downward';
            icon.style.color = '#3498db';
        } else {
            icon.textContent = 'unfold_more';
            icon.style.color = '#7f8c8d';
        }
    });
}

function updateStats(orders = null) {
    if (!orders) {
        orders = JSON.parse(localStorage.getItem('orders')) || [];
    }
    
    const stats = {
        total: orders.length,
        pending: orders.filter(o => o.status === 'pending').length,
        preparing: orders.filter(o => o.status === 'preparing').length,
        ready: orders.filter(o => o.status === 'ready').length,
        delivered: orders.filter(o => o.status === 'delivered').length
    };
    
    // Update stats badges if they exist
    Object.keys(stats).forEach(stat => {
        const element = document.getElementById(`${stat}-orders-count`);
        if (element) {
            element.textContent = stats[stat];
        }
    });
}

function viewOrderDetails(orderId) {
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const order = orders.find(o => o.id === orderId);
    
    if (!order) {
        showNotification('Order not found', 'error');
        return;
    }
    
    // Create modal for order details
    const modal = document.createElement('div');
    modal.className = 'modal show';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Order Details - ${order.id}</h2>
                <button class="close-modal" onclick="this.closest('.modal').remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="display: grid; gap: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <h4>Customer Information</h4>
                            <p><strong>Name:</strong> ${order.customerName || 'N/A'}</p>
                            <p><strong>Phone:</strong> ${order.customerPhone || 'N/A'}</p>
                            <p><strong>Order Date:</strong> ${formatOrderDate(order.orderDate)}</p>
                        </div>
                        <div>
                            <h4>Order Information</h4>
                            <p><strong>Status:</strong> 
                                <span class="status-badge status-${order.status}">
                                    ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                </span>
                            </p>
                            <p><strong>Payment:</strong> ${order.paymentMethod} (${order.paymentStatus})</p>
                            <p><strong>Delivery:</strong> ${order.deliveryType}</p>
                        </div>
                    </div>
                    
                    ${order.deliveryAddress ? `
                        <div>
                            <h4>Delivery Address</h4>
                            <p>${order.deliveryAddress.street}, ${order.deliveryAddress.city} ${order.deliveryAddress.zipCode}</p>
                        </div>
                    ` : ''}
                    
                    <div>
                        <h4>Order Items</h4>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                            ${order.items.map(item => `
                                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e0e0e0;">
                                    <div>
                                        <div style="font-weight: 600;">${item.name}</div>
                                        <div style="font-size: 12px; color: #7f8c8d;">Quantity: ${item.quantity}</div>
                                    </div>
                                    <div style="font-weight: 600;">${item.price}</div>
                                </div>
                            `).join('')}
                            <div style="display: flex; justify-content: space-between; padding: 12px 0; font-weight: bold; font-size: 16px;">
                                <span>Total:</span>
                                <span>${order.total}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${order.specialInstructions ? `
                        <div>
                            <h4>Special Instructions</h4>
                            <p>${order.specialInstructions}</p>
                        </div>
                    ` : ''}
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Close</button>
                <button class="btn btn-primary" onclick="editOrderStatus('${order.id}'); this.closest('.modal').remove()">Edit Status</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function editOrderStatus(orderId) {
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const order = orders.find(o => o.id === orderId);
    
    if (!order) {
        showNotification('Order not found', 'error');
        return;
    }
    
    const statusOptions = ['pending', 'preparing', 'ready', 'delivered', 'picked-up', 'cancelled'];
    const newStatus = prompt(
        `Update status for order ${orderId}:\n\nAvailable options: ${statusOptions.join(', ')}`,
        order.status
    );
    
    if (newStatus && statusOptions.includes(newStatus)) {
        order.status = newStatus;
        
        // Update timestamp if needed
        const now = new Date().toISOString();
        if (newStatus === 'delivered' && !order.actualDelivery) {
            order.actualDelivery = now;
        } else if (newStatus === 'cancelled' && !order.cancelledAt) {
            order.cancelledAt = now;
        }
        
        localStorage.setItem('orders', JSON.stringify(orders));
        showNotification(`Order ${orderId} status updated to ${newStatus}`, 'success');
        loadOrders();
    } else if (newStatus) {
        showNotification('Invalid status. Please use: ' + statusOptions.join(', '), 'error');
    }
}

function quickUpdateStatus(orderId) {
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const order = orders.find(o => o.id === orderId);
    
    if (!order) return;
    
    const statusFlow = {
        'pending': 'preparing',
        'preparing': 'ready',
        'ready': order.deliveryType === 'pickup' ? 'picked-up' : 'delivered',
        'delivered': 'delivered',
        'picked-up': 'picked-up',
        'cancelled': 'cancelled'
    };
    
    const nextStatus = statusFlow[order.status];
    
    if (nextStatus && nextStatus !== order.status) {
        order.status = nextStatus;
        
        // Update timestamp
        const now = new Date().toISOString();
        if (nextStatus === 'delivered' && !order.actualDelivery) {
            order.actualDelivery = now;
        } else if (nextStatus === 'picked-up' && !order.actualDelivery) {
            order.actualDelivery = now;
        }
        
        localStorage.setItem('orders', JSON.stringify(orders));
        showNotification(`Order ${orderId} status updated to ${nextStatus}`, 'success');
        loadOrders();
    } else {
        showNotification('Order is already at final status', 'info');
    }
}

function exportOrders() {
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const csv = convertToCSV(orders);
    downloadCSV(csv, `orders-export-${new Date().toISOString().split('T')[0]}.csv`);
    showNotification('Orders exported successfully', 'success');
}

function convertToCSV(orders) {
    const headers = ['Order ID', 'Customer', 'Phone', 'Items', 'Total', 'Status', 'Payment Method', 'Order Date'];
    const rows = orders.map(order => [
        order.id,
        order.customerName || '',
        order.customerPhone || '',
        order.items.map(item => `${item.quantity}x ${item.name}`).join('; '),
        order.total,
        order.status,
        order.paymentMethod || '',
        formatOrderDate(order.orderDate)
    ]);
    
    return [headers, ...rows].map(row => 
        row.map(field => `"${field}"`).join(',')
    ).join('\n');
}

function downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="material-icons">${getNotificationIcon(type)}</i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 4000);
}

function getNotificationIcon(type) {
    const icons = {
        success: 'check_circle',
        error: 'error',
        warning: 'warning',
        info: 'info'
    };
    return icons[type] || 'info';
}

// Make functions available globally
window.viewOrderDetails = viewOrderDetails;
window.editOrderStatus = editOrderStatus;
window.quickUpdateStatus = quickUpdateStatus;
window.changePage = changePage;