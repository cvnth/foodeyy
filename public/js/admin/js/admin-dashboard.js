// Complete Admin Dashboard JavaScript
class AdminDashboard {
    constructor() {
        this.currentPage = 1;
        this.itemsPerPage = 10;
        this.currentFilter = 'all';
        this.init();
    }

    async init() {
        try {
            // Load components first
            await this.loadComponents();
            
            // Initialize data
            this.initializeData();
            
            // Then initialize the dashboard
            this.checkAdminAuth();
            this.loadDashboardData();
            this.initEventListeners();
            
            console.log('Admin dashboard initialized successfully');
        } catch (error) {
            console.error('Failed to initialize dashboard:', error);
        }
    }

    async loadComponents() {
        try {
            await this.loadComponent('admin-sidebar', 'components/admin-sidebar.html');
            await this.loadComponent('admin-header', 'components/admin-header.html');
        } catch (error) {
            console.error('Error loading components:', error);
        }
    }

    async loadComponent(elementId, filePath) {
        return new Promise((resolve, reject) => {
            fetch(filePath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Failed to load ${filePath}: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.innerHTML = data;
                        resolve();
                    } else {
                        reject(new Error(`Element with id ${elementId} not found`));
                    }
                })
                .catch(error => {
                    console.error('Error loading component:', error);
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.innerHTML = `
                            <div style="color: red; padding: 20px; border: 2px solid red;">
                                <h4>Error loading component</h4>
                                <p>${error.message}</p>
                                <p>Make sure the file exists at: ${filePath}</p>
                            </div>
                        `;
                    }
                    reject(error);
                });
        });
    }

    initializeData() {
        // Check if we need to generate mock data
        const existingProducts = localStorage.getItem('products');
        const existingUsers = localStorage.getItem('users');
        const existingOrders = localStorage.getItem('orders');
        
        if (!existingProducts || !existingUsers || !existingOrders) {
            this.generateMockData();
        }
    }

    generateMockData() {
        // Use the MockDataGenerator if available, or create minimal data
        if (window.MockDataGenerator) {
            new MockDataGenerator();
        } else {
            this.createSampleData();
        }
    }

    createSampleData() {
        // Create sample orders if none exist
        if (!localStorage.getItem('orders') || JSON.parse(localStorage.getItem('orders')).length === 0) {
            const sampleOrders = [
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
                },
                {
                    id: 'ORD-002',
                    customerName: 'Maria Santos',
                    customerPhone: '+639182345678',
                    items: [
                        { id: '2', name: 'Margherita Pizza', quantity: 1, price: '₱299' },
                        { id: '6', name: 'Chocolate Brownie', quantity: 2, price: '₱90' }
                    ],
                    total: '₱479',
                    status: 'preparing',
                    paymentMethod: 'Credit Card',
                    paymentStatus: 'paid',
                    orderDate: new Date(Date.now() - 86400000).toISOString(),
                    deliveryType: 'pickup'
                }
            ];
            localStorage.setItem('orders', JSON.stringify(sampleOrders));
        }

        // Create sample users if none exist
        if (!localStorage.getItem('users') || JSON.parse(localStorage.getItem('users')).length === 0) {
            const sampleUsers = [
                { 
                    id: '1', 
                    name: 'John Doe', 
                    email: 'john@example.com', 
                    phone: '+639171234567',
                    joinDate: '2024-01-15',
                    totalOrders: 5,
                    totalSpent: '₱2,450',
                    membership: 'premium',
                    status: 'active',
                    avatar: 'JD'
                },
                { 
                    id: '2', 
                    name: 'Maria Santos', 
                    email: 'maria@example.com', 
                    phone: '+639182345678',
                    joinDate: '2024-01-10',
                    totalOrders: 8,
                    totalSpent: '₱3,890',
                    membership: 'vip',
                    status: 'active',
                    avatar: 'MS'
                }
            ];
            localStorage.setItem('users', JSON.stringify(sampleUsers));
        }

        // Create sample products if none exist
        if (!localStorage.getItem('products') || JSON.parse(localStorage.getItem('products')).length === 0) {
            const sampleProducts = [
                { 
                    id: '1', 
                    name: 'Classic Beef Burger', 
                    price: '₱120', 
                    category: 'Burgers',
                    available: true,
                    featured: true
                },
                { 
                    id: '2', 
                    name: 'Margherita Pizza', 
                    price: '₱299', 
                    category: 'Pizza',
                    available: true,
                    featured: false
                }
            ];
            localStorage.setItem('products', JSON.stringify(sampleProducts));
        }
    }

    checkAdminAuth() {
        const isAdminLoggedIn = localStorage.getItem('isAdminLoggedIn');
        const adminUser = JSON.parse(localStorage.getItem('adminUser') || '{}');
        
        if (!isAdminLoggedIn || !adminUser.name) {
            this.showNotification('Please log in as administrator', 'warning');
            setTimeout(() => {
                window.location.href = '../auth.html?admin=true';
            }, 1000);
            return;
        }
        
        this.displayAdminInfo(adminUser);
    }

    displayAdminInfo(adminUser) {
        // Update user info in header
        const adminNameEl = document.querySelector('.user-info h4');
        const adminRoleEl = document.querySelector('.user-info p');
        
        if (adminNameEl) adminNameEl.textContent = adminUser.name;
        if (adminRoleEl) adminRoleEl.textContent = adminUser.role || 'Administrator';
        
        // Update avatar with initials
        const avatarEl = document.querySelector('.user-avatar');
        if (avatarEl && adminUser.name) {
            const initials = adminUser.name.split(' ').map(n => n[0]).join('').toUpperCase();
            avatarEl.textContent = initials;
        }
    }

    loadDashboardData() {
        this.loadStats();
        this.loadRecentOrders();
        this.updateTrendIndicators();
    }

    loadStats() {
        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const products = JSON.parse(localStorage.getItem('products')) || [];
        
        // Calculate total revenue (handle both ₱ and $ formats)
        const totalRevenue = orders.reduce((sum, order) => {
            if (order.status !== 'cancelled') {
                const amount = parseFloat(order.total.replace(/[^\d.]/g, '')) || 0;
                return sum + amount;
            }
            return sum;
        }, 0);
        
        const totalOrders = orders.length;
        const pendingOrders = orders.filter(order => 
            order.status === 'pending' || order.status === 'preparing' || order.status === 'ready'
        ).length;
        const totalUsers = users.filter(user => user.status === 'active').length;
        const totalProducts = products.filter(product => product.available).length;
        
        // Update the DOM
        this.updateStatCard('.admin-stat-card.revenue h3', `₱${totalRevenue.toLocaleString()}`);
        this.updateStatCard('.admin-stat-card.orders h3', totalOrders.toString());
        this.updateStatCard('.admin-stat-card.pending h3', pendingOrders.toString());
        this.updateStatCard('.admin-stat-card.users h3', totalUsers.toString());
    }

    updateStatCard(selector, value) {
        const element = document.querySelector(selector);
        if (element) {
            element.textContent = value;
        }
    }

    updateTrendIndicators() {
        // This would typically compare with previous period data
        // For now, we'll set some default trends
        const trends = document.querySelectorAll('.trend');
        trends.forEach(trend => {
            // Simulate some trend data
            const isPositive = Math.random() > 0.3;
            const percentage = (Math.random() * 20 + 5).toFixed(0);
            const text = isPositive ? 
                `${percentage}% from last week` : 
                `${percentage}% from last week`;
            
            trend.innerHTML = isPositive ?
                `<i class="material-icons">arrow_upward</i><span>${text}</span>` :
                `<i class="material-icons">arrow_downward</i><span>${text}</span>`;
            
            trend.className = `trend ${isPositive ? 'up' : 'down'}`;
        });
    }

    loadRecentOrders() {
        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        // Sort by date, most recent first
        const recentOrders = orders.sort((a, b) => new Date(b.orderDate) - new Date(a.orderDate)).slice(0, 5);
        const tableBody = document.getElementById('recent-orders-table');
        
        if (!tableBody) {
            console.error('Recent orders table not found');
            return;
        }
        
        if (recentOrders.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #7f8c8d;">
                        <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">receipt</i>
                        <p>No orders found</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        tableBody.innerHTML = recentOrders.map(order => `
            <tr>
                <td><strong>${order.id}</strong></td>
                <td>${order.customerName}</td>
                <td>${order.items.length} items</td>
                <td>${order.total}</td>
                <td>
                    <span class="status-badge status-${order.status}">
                        ${this.capitalizeFirstLetter(order.status)}
                    </span>
                </td>
                <td>${this.formatDate(order.orderDate)}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn btn-view" onclick="adminDashboard.viewOrder('${order.id}')" title="View Order">
                            <i class="material-icons">visibility</i>
                        </button>
                        <button class="action-btn btn-edit" onclick="adminDashboard.editOrder('${order.id}')" title="Edit Order">
                            <i class="material-icons">edit</i>
                        </button>
                        <button class="action-btn btn-delete" onclick="adminDashboard.deleteOrder('${order.id}')" title="Delete Order">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // Order Management Methods
    viewOrder(orderId) {
        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        const order = orders.find(o => o.id === orderId);
        
        if (order) {
            this.showOrderModal(order, 'view');
        } else {
            this.showNotification('Order not found', 'error');
        }
    }

    editOrder(orderId) {
        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        const order = orders.find(o => o.id === orderId);
        
        if (order) {
            this.showOrderModal(order, 'edit');
        } else {
            this.showNotification('Order not found', 'error');
        }
    }

    deleteOrder(orderId) {
        if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
            return;
        }
        
        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        const updatedOrders = orders.filter(order => order.id !== orderId);
        
        localStorage.setItem('orders', JSON.stringify(updatedOrders));
        this.showNotification('Order deleted successfully', 'success');
        this.loadDashboardData();
    }

    showOrderModal(order, mode) {
        const modal = document.createElement('div');
        modal.className = 'modal show';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>${mode === 'view' ? 'View' : 'Edit'} Order ${order.id}</h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="order-details">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <p><strong>Customer:</strong> ${order.customerName}</p>
                                <p><strong>Phone:</strong> ${order.customerPhone}</p>
                                <p><strong>Order Date:</strong> ${this.formatDate(order.orderDate)}</p>
                            </div>
                            <div>
                                <p><strong>Total:</strong> ${order.total}</p>
                                <p><strong>Payment:</strong> ${order.paymentMethod} (${order.paymentStatus})</p>
                                <p><strong>Delivery:</strong> ${order.deliveryType}</p>
                            </div>
                        </div>
                        
                        <p><strong>Status:</strong> 
                            ${mode === 'edit' ? 
                                `<select id="orderStatus">
                                    <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="preparing" ${order.status === 'preparing' ? 'selected' : ''}>Preparing</option>
                                    <option value="ready" ${order.status === 'ready' ? 'selected' : ''}>Ready</option>
                                    <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                                    <option value="picked-up" ${order.status === 'picked-up' ? 'selected' : ''}>Picked Up</option>
                                    <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                </select>` : 
                                `<span class="status-badge status-${order.status}">${this.capitalizeFirstLetter(order.status)}</span>`
                            }
                        </p>
                        
                        ${order.deliveryAddress ? `
                            <p><strong>Delivery Address:</strong> 
                                ${order.deliveryAddress.street}, ${order.deliveryAddress.city} ${order.deliveryAddress.zipCode}
                            </p>
                        ` : ''}
                        
                        ${order.specialInstructions ? `
                            <p><strong>Special Instructions:</strong> ${order.specialInstructions}</p>
                        ` : ''}
                        
                        <h3 style="margin-top: 20px;">Order Items:</h3>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                            ${order.items.map(item => `
                                <div style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #ddd;">
                                    <span>${item.name} × ${item.quantity}</span>
                                    <span style="font-weight: bold;">${item.price}</span>
                                </div>
                            `).join('')}
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; font-weight: bold; border-top: 2px solid #ddd;">
                                <span>Total:</span>
                                <span>${order.total}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    ${mode === 'edit' ? `
                        <button class="btn-save" onclick="adminDashboard.updateOrder('${order.id}')">Save Changes</button>
                    ` : ''}
                    <button class="btn-cancel close-modal">Close</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal handlers
        modal.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => modal.remove());
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    updateOrder(orderId) {
        const status = document.getElementById('orderStatus').value;
        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        const orderIndex = orders.findIndex(o => o.id === orderId);
        
        if (orderIndex !== -1) {
            orders[orderIndex].status = status;
            
            // Update timestamps based on status changes
            const now = new Date().toISOString();
            if (status === 'delivered' && !orders[orderIndex].actualDelivery) {
                orders[orderIndex].actualDelivery = now;
            } else if (status === 'cancelled' && !orders[orderIndex].cancelledAt) {
                orders[orderIndex].cancelledAt = now;
                orders[orderIndex].cancellationReason = 'Updated by admin';
            }
            
            localStorage.setItem('orders', JSON.stringify(orders));
            this.showNotification('Order updated successfully', 'success');
            this.loadDashboardData();
            document.querySelector('.modal')?.remove();
        } else {
            this.showNotification('Order not found', 'error');
        }
    }

    // User Management Methods
    loadUsers() {
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const tableBody = document.getElementById('users-table-body');
        
        if (!tableBody) return;
        
        if (users.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #7f8c8d;">
                        <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">people</i>
                        <p>No users found</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        const filteredUsers = this.filterUsers(users);
        const paginatedUsers = this.paginateData(filteredUsers);
        
        tableBody.innerHTML = paginatedUsers.map(user => `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="user-avatar-small">${user.avatar}</div>
                        <div>
                            <div style="font-weight: 600;">${user.name}</div>
                            <div style="font-size: 12px; color: #7f8c8d;">${user.email}</div>
                        </div>
                    </div>
                </td>
                <td>${user.phone}</td>
                <td>${this.formatDate(user.joinDate)}</td>
                <td>${user.totalOrders}</td>
                <td>${user.totalSpent}</td>
                <td>
                    <span class="membership-badge membership-${user.membership}">
                        ${this.capitalizeFirstLetter(user.membership)}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-${user.status}">
                        ${this.capitalizeFirstLetter(user.status)}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn btn-view" onclick="adminDashboard.viewUser('${user.id}')" title="View User">
                            <i class="material-icons">visibility</i>
                        </button>
                        ${user.status === 'active' ? `
                            <button class="action-btn btn-suspend" onclick="adminDashboard.suspendUser('${user.id}')" title="Suspend User">
                                <i class="material-icons">pause</i>
                            </button>
                        ` : `
                            <button class="action-btn btn-activate" onclick="adminDashboard.activateUser('${user.id}')" title="Activate User">
                                <i class="material-icons">play_arrow</i>
                            </button>
                        `}
                        <button class="action-btn btn-delete" onclick="adminDashboard.deleteUser('${user.id}')" title="Delete User">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        
        this.updatePagination(filteredUsers.length);
    }

    filterUsers(users) {
        if (this.currentFilter === 'all') return users;
        return users.filter(user => user.status === this.currentFilter);
    }

    paginateData(data) {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return data.slice(start, end);
    }

    updatePagination(totalItems) {
        const totalPages = Math.ceil(totalItems / this.itemsPerPage);
        const paginationElement = document.getElementById('pagination');
        
        if (!paginationElement) return;
        
        let paginationHTML = '';
        
        // Previous button
        paginationHTML += `
            <button class="page-btn" onclick="adminDashboard.previousPage()" ${this.currentPage === 1 ? 'disabled' : ''}>
                <i class="material-icons">chevron_left</i>
            </button>
        `;
        
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `
                <button class="page-btn ${i === this.currentPage ? 'active' : ''}" onclick="adminDashboard.goToPage(${i})">
                    ${i}
                </button>
            `;
        }
        
        // Next button
        paginationHTML += `
            <button class="page-btn" onclick="adminDashboard.nextPage()" ${this.currentPage === totalPages ? 'disabled' : ''}>
                <i class="material-icons">chevron_right</i>
            </button>
        `;
        
        paginationElement.innerHTML = paginationHTML;
    }

    goToPage(page) {
        this.currentPage = page;
        this.loadUsers();
    }

    previousPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.loadUsers();
        }
    }

    nextPage() {
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const filteredUsers = this.filterUsers(users);
        const totalPages = Math.ceil(filteredUsers.length / this.itemsPerPage);
        
        if (this.currentPage < totalPages) {
            this.currentPage++;
            this.loadUsers();
        }
    }

    viewUser(userId) {
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const user = users.find(u => u.id === userId);
        
        if (user) {
            this.showUserModal(user, 'view');
        } else {
            this.showNotification('User not found', 'error');
        }
    }

    suspendUser(userId) {
        if (!confirm('Are you sure you want to suspend this user?')) {
            return;
        }
        
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const userIndex = users.findIndex(u => u.id === userId);
        
        if (userIndex !== -1) {
            users[userIndex].status = 'suspended';
            localStorage.setItem('users', JSON.stringify(users));
            this.showNotification('User suspended successfully', 'success');
            this.loadUsers();
        }
    }

    activateUser(userId) {
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const userIndex = users.findIndex(u => u.id === userId);
        
        if (userIndex !== -1) {
            users[userIndex].status = 'active';
            localStorage.setItem('users', JSON.stringify(users));
            this.showNotification('User activated successfully', 'success');
            this.loadUsers();
        }
    }

    deleteUser(userId) {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            return;
        }
        
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const updatedUsers = users.filter(user => user.id !== userId);
        
        localStorage.setItem('users', JSON.stringify(updatedUsers));
        this.showNotification('User deleted successfully', 'success');
        this.loadUsers();
    }

    showUserModal(user, mode) {
        const modal = document.createElement('div');
        modal.className = 'modal show';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>${mode === 'view' ? 'View' : 'Edit'} User: ${user.name}</h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                        <div class="user-avatar-small" style="width: 60px; height: 60px; font-size: 18px;">${user.avatar}</div>
                        <div>
                            <h3 style="margin: 0 0 5px 0;">${user.name}</h3>
                            <p style="margin: 0; color: #7f8c8d;">${user.email}</p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <p><strong>Phone:</strong> ${user.phone}</p>
                            <p><strong>Join Date:</strong> ${this.formatDate(user.joinDate)}</p>
                            <p><strong>Last Order:</strong> ${user.lastOrder ? this.formatDate(user.lastOrder) : 'No orders yet'}</p>
                        </div>
                        <div>
                            <p><strong>Total Orders:</strong> ${user.totalOrders}</p>
                            <p><strong>Total Spent:</strong> ${user.totalSpent}</p>
                            <p><strong>Membership:</strong> 
                                <span class="membership-badge membership-${user.membership}">
                                    ${this.capitalizeFirstLetter(user.membership)}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    ${user.address ? `
                        <div style="margin-top: 15px;">
                            <p><strong>Address:</strong></p>
                            <p>${user.address.street}, ${user.address.city} ${user.address.zipCode}</p>
                        </div>
                    ` : ''}
                    
                    ${user.preferences && user.preferences.length > 0 ? `
                        <div style="margin-top: 15px;">
                            <p><strong>Preferences:</strong></p>
                            <p>${user.preferences.join(', ')}</p>
                        </div>
                    ` : ''}
                </div>
                <div class="modal-actions">
                    <button class="btn-cancel close-modal">Close</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal handlers
        modal.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => modal.remove());
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    // Menu Management Methods
    loadMenu() {
        const products = JSON.parse(localStorage.getItem('products')) || [];
        const menuGrid = document.getElementById('menu-grid');
        
        if (!menuGrid) return;
        
        if (products.length === 0) {
            menuGrid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #7f8c8d;">
                    <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">restaurant_menu</i>
                    <p>No menu items found</p>
                </div>
            `;
            return;
        }
        
        menuGrid.innerHTML = products.map(product => `
            <div class="menu-card">
                <div class="menu-card-image">
                    <img src="${product.image || 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop'}" alt="${product.name}">
                    ${!product.available ? '<div style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Out of Stock</div>' : ''}
                </div>
                <div class="menu-card-content">
                    <div class="menu-card-header">
                        <div>
                            <div class="menu-card-title">${product.name}</div>
                            <div class="menu-card-price">${product.price}</div>
                        </div>
                        <span class="status-badge status-${product.available ? 'active' : 'inactive'}">
                            ${product.available ? 'Available' : 'Unavailable'}
                        </span>
                    </div>
                    <div class="menu-card-description">
                        ${product.description || 'No description available'}
                    </div>
                    <div class="menu-card-meta">
                        <div>${product.category}</div>
                        <div class="menu-card-rating">
                            <i class="material-icons">star</i>
                            <span>${product.rating || '4.0'}</span>
                        </div>
                    </div>
                    <div class="menu-card-actions">
                        <button class="action-btn btn-edit" onclick="adminDashboard.editMenuItem('${product.id}')">
                            <i class="material-icons">edit</i> Edit
                        </button>
                        <button class="action-btn btn-delete" onclick="adminDashboard.deleteMenuItem('${product.id}')">
                            <i class="material-icons">delete</i> Delete
                        </button>
                        <button class="action-btn ${product.available ? 'btn-suspend' : 'btn-activate'}" onclick="adminDashboard.toggleMenuItem('${product.id}')">
                            <i class="material-icons">${product.available ? 'pause' : 'play_arrow'}</i>
                            ${product.available ? 'Disable' : 'Enable'}
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    editMenuItem(productId) {
        const products = JSON.parse(localStorage.getItem('products')) || [];
        const product = products.find(p => p.id === productId);
        
        if (product) {
            this.showProductModal(product, 'edit');
        } else {
            this.showNotification('Product not found', 'error');
        }
    }

    deleteMenuItem(productId) {
        if (!confirm('Are you sure you want to delete this menu item? This action cannot be undone.')) {
            return;
        }
        
        const products = JSON.parse(localStorage.getItem('products')) || [];
        const updatedProducts = products.filter(product => product.id !== productId);
        
        localStorage.setItem('products', JSON.stringify(updatedProducts));
        this.showNotification('Menu item deleted successfully', 'success');
        this.loadMenu();
    }

    toggleMenuItem(productId) {
        const products = JSON.parse(localStorage.getItem('products')) || [];
        const productIndex = products.findIndex(p => p.id === productId);
        
        if (productIndex !== -1) {
            products[productIndex].available = !products[productIndex].available;
            localStorage.setItem('products', JSON.stringify(products));
            this.showNotification(`Menu item ${products[productIndex].available ? 'enabled' : 'disabled'} successfully`, 'success');
            this.loadMenu();
        }
    }

    showProductModal(product, mode) {
        const modal = document.createElement('div');
        modal.className = 'modal show';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>${mode === 'view' ? 'View' : 'Edit'} Product: ${product.name}</h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" id="productName" value="${product.name}" ${mode === 'view' ? 'readonly' : ''}>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="productPrice">Price</label>
                                <input type="text" id="productPrice" value="${product.price}" ${mode === 'view' ? 'readonly' : ''}>
                            </div>
                            <div class="form-group">
                                <label for="productCategory">Category</label>
                                <select id="productCategory" ${mode === 'view' ? 'disabled' : ''}>
                                    <option value="Burgers" ${product.category === 'Burgers' ? 'selected' : ''}>Burgers</option>
                                    <option value="Pizza" ${product.category === 'Pizza' ? 'selected' : ''}>Pizza</option>
                                    <option value="Pasta" ${product.category === 'Pasta' ? 'selected' : ''}>Pasta</option>
                                    <option value="Salads" ${product.category === 'Salads' ? 'selected' : ''}>Salads</option>
                                    <option value="Seafood" ${product.category === 'Seafood' ? 'selected' : ''}>Seafood</option>
                                    <option value="Desserts" ${product.category === 'Desserts' ? 'selected' : ''}>Desserts</option>
                                    <option value="Beverages" ${product.category === 'Beverages' ? 'selected' : ''}>Beverages</option>
                                    <option value="Appetizers" ${product.category === 'Appetizers' ? 'selected' : ''}>Appetizers</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="productDescription">Description</label>
                            <textarea id="productDescription" rows="3" ${mode === 'view' ? 'readonly' : ''}>${product.description || ''}</textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="productPreparationTime">Preparation Time (minutes)</label>
                                <input type="number" id="productPreparationTime" value="${product.preparationTime || ''}" ${mode === 'view' ? 'readonly' : ''}>
                            </div>
                            <div class="form-group">
                                <label for="productCalories">Calories</label>
                                <input type="number" id="productCalories" value="${product.calories || ''}" ${mode === 'view' ? 'readonly' : ''}>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="productAvailable" ${product.available ? 'checked' : ''} ${mode === 'view' ? 'disabled' : ''}>
                                Available
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="productFeatured" ${product.featured ? 'checked' : ''} ${mode === 'view' ? 'disabled' : ''}>
                                Featured Item
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-actions">
                    ${mode === 'edit' ? `
                        <button class="btn-save" onclick="adminDashboard.updateProduct('${product.id}')">Save Changes</button>
                    ` : ''}
                    <button class="btn-cancel close-modal">Close</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal handlers
        modal.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => modal.remove());
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    updateProduct(productId) {
        const products = JSON.parse(localStorage.getItem('products')) || [];
        const productIndex = products.findIndex(p => p.id === productId);
        
        if (productIndex !== -1) {
            products[productIndex].name = document.getElementById('productName').value;
            products[productIndex].price = document.getElementById('productPrice').value;
            products[productIndex].category = document.getElementById('productCategory').value;
            products[productIndex].description = document.getElementById('productDescription').value;
            products[productIndex].preparationTime = parseInt(document.getElementById('productPreparationTime').value) || 0;
            products[productIndex].calories = parseInt(document.getElementById('productCalories').value) || 0;
            products[productIndex].available = document.getElementById('productAvailable').checked;
            products[productIndex].featured = document.getElementById('productFeatured').checked;
            
            localStorage.setItem('products', JSON.stringify(products));
            this.showNotification('Product updated successfully', 'success');
            this.loadMenu();
            document.querySelector('.modal')?.remove();
        }
    }

    // Utility Methods
    capitalizeFirstLetter(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }

    formatDate(dateString) {
        if (!dateString) return 'N/A';
        
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'Invalid Date';
            
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (error) {
            return 'Invalid Date';
        }
    }

    initEventListeners() {
        // Add logout event listener after a short delay to ensure component is loaded
        setTimeout(() => {
            const logoutBtn = document.getElementById('admin-logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => this.handleAdminLogout());
            }
            
            // Add refresh button if exists
            const refreshBtn = document.getElementById('refresh-btn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => this.loadDashboardData());
            }
            
            // Add search functionality for orders
            const searchOrders = document.getElementById('searchOrders');
            if (searchOrders) {
                searchOrders.addEventListener('input', (e) => this.handleOrderSearch(e));
            }
            
            // Add search functionality for users
            const searchUsers = document.getElementById('searchUsers');
            if (searchUsers) {
                searchUsers.addEventListener('input', (e) => this.handleUserSearch(e));
            }
            
            // Add filter buttons for users
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', (e) => this.handleUserFilter(e));
            });
            
            // Add menu management buttons
            const addMenuBtn = document.getElementById('add-menu-btn');
            if (addMenuBtn) {
                addMenuBtn.addEventListener('click', () => this.showAddProductModal());
            }
        }, 500);
    }

    handleOrderSearch(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#recent-orders-table tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    handleUserSearch(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#users-table-body tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    handleUserFilter(e) {
        const filter = e.target.dataset.filter || 'all';
        this.currentFilter = filter;
        this.currentPage = 1;
        
        // Update active state of filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === filter);
        });
        
        this.loadUsers();
    }

    showAddProductModal() {
        const newProduct = {
            id: 'prod-' + Date.now(),
            name: '',
            price: '',
            category: 'Burgers',
            description: '',
            preparationTime: 15,
            calories: 0,
            available: true,
            featured: false
        };
        
        this.showProductModal(newProduct, 'add');
    }

    handleAdminLogout() {
        localStorage.removeItem('isAdminLoggedIn');
        localStorage.removeItem('adminUser');
        this.showNotification('Logged out successfully', 'success');
        setTimeout(() => {
            window.location.href = '../auth.html?admin=true';
        }, 1500);
    }

    showNotification(message, type = 'info') {
        // Remove any existing notifications
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icons = {
            success: 'check_circle',
            error: 'error',
            warning: 'warning',
            info: 'info'
        };
        
        notification.innerHTML = `
            <i class="material-icons">${icons[type] || 'info'}</i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
        
        // Click to dismiss
        notification.addEventListener('click', () => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        });
    }
}

// Initialize the dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.adminDashboard = new AdminDashboard();
});

// Make methods available globally for HTML onclick handlers
window.viewOrder = (orderId) => window.adminDashboard?.viewOrder(orderId);
window.editOrder = (orderId) => window.adminDashboard?.editOrder(orderId);
window.deleteOrder = (orderId) => window.adminDashboard?.deleteOrder(orderId);
window.viewUser = (userId) => window.adminDashboard?.viewUser(userId);
window.suspendUser = (userId) => window.adminDashboard?.suspendUser(userId);
window.activateUser = (userId) => window.adminDashboard?.activateUser(userId);
window.deleteUser = (userId) => window.adminDashboard?.deleteUser(userId);
window.editMenuItem = (productId) => window.adminDashboard?.editMenuItem(productId);
window.deleteMenuItem = (productId) => window.adminDashboard?.deleteMenuItem(productId);
window.toggleMenuItem = (productId) => window.adminDashboard?.toggleMenuItem(productId);
window.goToPage = (page) => window.adminDashboard?.goToPage(page);
window.previousPage = () => window.adminDashboard?.previousPage();
window.nextPage = () => window.adminDashboard?.nextPage();

// Page-specific initialization
function initializeOrdersPage() {
    if (window.adminDashboard) {
        // Orders page specific initialization
        window.adminDashboard.loadRecentOrders();
    }
}

function initializeUsersPage() {
    if (window.adminDashboard) {
        // Users page specific initialization
        window.adminDashboard.loadUsers();
    }
}

function initializeMenuPage() {
    if (window.adminDashboard) {
        // Menu page specific initialization
        window.adminDashboard.loadMenu();
    }
}

// Auto-detect page and initialize accordingly
document.addEventListener('DOMContentLoaded', function() {
    const path = window.location.pathname;
    if (path.includes('orders.html')) {
        initializeOrdersPage();
    } else if (path.includes('users.html')) {
        initializeUsersPage();
    } else if (path.includes('menu.html')) {
        initializeMenuPage();
    }
});