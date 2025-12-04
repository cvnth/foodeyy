// admin/js/admin-sales.js
let currentDateRange = '7';

document.addEventListener('DOMContentLoaded', function() {
    checkAdminAuth();
    loadSalesData();
    initTimeFilters();
    setDefaultDates();
});

function loadSalesData() {
    loadTopSellingItems();
    updateSalesOverview();
}

function loadTopSellingItems() {
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const menuItems = JSON.parse(localStorage.getItem('adminMenuItems')) || [];
    
    // Calculate item sales
    const itemSales = {};
    orders.forEach(order => {
        order.items.forEach(item => {
            if (!itemSales[item.name]) {
                itemSales[item.name] = {
                    name: item.name,
                    quantity: 0,
                    revenue: 0,
                    image: item.image || getMenuItemImage(item.name, menuItems)
                };
            }
            itemSales[item.name].quantity += item.quantity;
            const price = parseInt(item.price.replace('₱', ''));
            itemSales[item.name].revenue += price * item.quantity;
        });
    });

    // Convert to array and sort by revenue
    const topItems = Object.values(itemSales)
        .sort((a, b) => b.revenue - a.revenue)
        .slice(0, 10);

    displayTopItems(topItems);
}

function getMenuItemImage(itemName, menuItems) {
    const menuItem = menuItems.find(item => item.name === itemName);
    return menuItem ? menuItem.image : 'https://images.unsplash.com/photo-1546964124-0cce460f38ef?auto=format&fit=crop&w=500&q=80';
}

function displayTopItems(items) {
    const itemsList = document.getElementById('top-items-list');
    
    if (items.length === 0) {
        itemsList.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #7f8c8d;">
                <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">restaurant_menu</i>
                <p>No sales data available</p>
            </div>
        `;
        return;
    }

    itemsList.innerHTML = items.map((item, index) => `
        <div class="item-row">
            <div class="item-rank top-${index + 1}">${index + 1}</div>
            <img src="${item.image}" alt="${item.name}" class="item-image">
            <div class="item-info">
                <div class="item-name">${item.name}</div>
                <div class="item-stats">
                    <span>${item.quantity} sold</span>
                    <span class="item-sales">₱${item.revenue} revenue</span>
                </div>
            </div>
        </div>
    `).join('');
}

function updateSalesOverview() {
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    
    // Calculate metrics based on current date range
    const filteredOrders = filterOrdersByDateRange(orders);
    
    const totalRevenue = filteredOrders.reduce((sum, order) => {
        return sum + parseInt(order.total.replace('₱', ''));
    }, 0);
    
    const totalOrders = filteredOrders.length;
    const averageOrderValue = totalOrders > 0 ? totalRevenue / totalOrders : 0;
    
    // Update the overview cards (in a real app, this would update the actual elements)
    console.log('Sales Overview:', {
        totalRevenue,
        totalOrders,
        averageOrderValue
    });
}

function filterOrdersByDateRange(orders) {
    const now = new Date();
    let startDate = new Date();
    
    switch (currentDateRange) {
        case '7':
            startDate.setDate(now.getDate() - 7);
            break;
        case '30':
            startDate.setDate(now.getDate() - 30);
            break;
        case '90':
            startDate.setDate(now.getDate() - 90);
            break;
        case '365':
            startDate.setDate(now.getDate() - 365);
            break;
        case 'custom':
            const customStart = document.getElementById('start-date').value;
            const customEnd = document.getElementById('end-date').value;
            if (customStart && customEnd) {
                return orders.filter(order => {
                    const orderDate = new Date(order.date);
                    return orderDate >= new Date(customStart) && orderDate <= new Date(customEnd);
                });
            }
            break;
    }
    
    return orders.filter(order => new Date(order.date) >= startDate);
}

function initTimeFilters() {
    document.querySelectorAll('.time-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.time-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentDateRange = this.getAttribute('data-range');
            
            if (currentDateRange === 'custom') {
                document.getElementById('start-date').style.display = 'block';
                document.getElementById('end-date').style.display = 'block';
            } else {
                document.getElementById('start-date').style.display = 'none';
                document.getElementById('end-date').style.display = 'none';
                loadSalesData();
            }
        });
    });
}

function setDefaultDates() {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(endDate.getDate() - 7);
    
    document.getElementById('start-date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end-date').value = endDate.toISOString().split('T')[0];
}

function applyDateFilter() {
    if (currentDateRange === 'custom') {
        loadSalesData();
    }
}

function exportReport() {
    // In a real application, this would generate and download a CSV or PDF report
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const filteredOrders = filterOrdersByDateRange(orders);
    
    const reportData = {
        period: getDateRangeLabel(),
        totalRevenue: filteredOrders.reduce((sum, order) => sum + parseInt(order.total.replace('₱', '')), 0),
        totalOrders: filteredOrders.length,
        orders: filteredOrders
    };
    
    // Simulate export
    showNotification('Report exported successfully!', 'success');
    console.log('Exporting report:', reportData);
    
    // In a real implementation, you would:
    // 1. Generate CSV content
    // 2. Create a Blob and download link
    // 3. Trigger download
}

function getDateRangeLabel() {
    switch (currentDateRange) {
        case '7': return 'Last 7 Days';
        case '30': return 'Last 30 Days';
        case '90': return 'Last 90 Days';
        case '365': return 'Last Year';
        case 'custom': 
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;
            return `${start} to ${end}`;
        default: return 'Custom Range';
    }
}