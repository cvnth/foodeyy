// admin/js/admin-users.js
let currentPage = 1;
const usersPerPage = 10;
let currentFilter = { status: 'all', membership: 'all' };
let currentSort = { field: 'joinDate', direction: 'desc' };

document.addEventListener('DOMContentLoaded', function() {
    checkAdminAuth();
    initializeUsersData();
    loadUsers();
    initFilters();
    initSearch();
    initSorting();
    updateUserStats();
});

function checkAdminAuth() {
    const isAdminLoggedIn = localStorage.getItem('isAdminLoggedIn');
    const adminUser = JSON.parse(localStorage.getItem('adminUser') || '{}');
    
    if (!isAdminLoggedIn || !adminUser.name) {
        window.location.href = '../auth.html?admin=true';
        return;
    }
}

function initializeUsersData() {
    // Check if users data exists, if not create it
    const existingUsers = localStorage.getItem('users');
    if (!existingUsers) {
        // Create sample users data that matches the structure from admin-dashboard
        const sampleUsers = [
            {
                id: '1',
                name: 'Maria Santos',
                email: 'maria.santos@email.com',
                phone: '+639171234567',
                joinDate: '2024-01-15',
                lastOrder: '2024-03-15',
                totalOrders: 12,
                totalSpent: '₱4,560',
                membership: 'premium',
                status: 'active',
                avatar: 'MS',
                address: {
                    street: '123 Main Street',
                    city: 'Manila',
                    zipCode: '1000'
                },
                preferences: ['burgers', 'pizza', 'coffee']
            },
            {
                id: '2',
                name: 'Juan Dela Cruz',
                email: 'juan.dc@email.com',
                phone: '+639182345678',
                joinDate: '2024-02-01',
                lastOrder: '2024-03-14',
                totalOrders: 8,
                totalSpent: '₱2,890',
              
                status: 'active',
                avatar: 'JC',
                address: {
                    street: '456 Oak Avenue',
                    city: 'Quezon City',
                    zipCode: '1100'
                },
                preferences: ['seafood', 'salads']
            },
            {
                id: '3',
                name: 'Anna Reyes',
                email: 'anna.reyes@email.com',
                phone: '+639193456789',
                joinDate: '2024-01-20',
                lastOrder: '2024-03-10',
                totalOrders: 15,
                totalSpent: '₱6,780',
                membership: 'vip',
                status: 'active',
                avatar: 'AR',
                address: {
                    street: '789 Pine Road',
                    city: 'Makati',
                    zipCode: '1200'
                },
                preferences: ['desserts', 'pasta', 'beverages']
            },
            {
                id: '4',
                name: 'Carlos Lopez',
                email: 'carlos.lopez@email.com',
                phone: '+639104567890',
                joinDate: '2024-02-15',
                lastOrder: '2024-03-08',
                totalOrders: 5,
                totalSpent: '₱1,450',
                membership: 'regular',
                status: 'active',
                avatar: 'CL',
                address: {
                    street: '321 Elm Street',
                    city: 'Pasig',
                    zipCode: '1600'
                },
                preferences: ['burgers', 'wings']
            },
            {
                id: '5',
                name: 'Sofia Garcia',
                email: 'sofia.garcia@email.com',
                phone: '+639115678901',
                joinDate: '2024-01-10',
                lastOrder: '2024-02-28',
                totalOrders: 3,
                totalSpent: '₱890',
                membership: 'regular',
                status: 'inactive',
                avatar: 'SG',
                address: {
                    street: '654 Maple Drive',
                    city: 'Taguig',
                    zipCode: '1630'
                },
                preferences: ['pizza', 'salads']
            },
            {
                id: '6',
                name: 'Miguel Torres',
                email: 'miguel.torres@email.com',
                phone: '+639126789012',
                joinDate: '2024-03-01',
                lastOrder: '2024-03-16',
                totalOrders: 7,
                totalSpent: '₱2,340',
                membership: 'premium',
                status: 'active',
                avatar: 'MT',
                address: {
                    street: '987 Cedar Lane',
                    city: 'Mandaluyong',
                    zipCode: '1550'
                },
                preferences: ['seafood', 'appetizers']
            },
            {
                id: '7',
                name: 'Elena Mendoza',
                email: 'elena.mendoza@email.com',
                phone: '+639137890123',
                joinDate: '2024-02-20',
                lastOrder: '2024-03-12',
                totalOrders: 9,
                totalSpent: '₱3,120',
                membership: 'premium',
                status: 'suspended',
                avatar: 'EM',
                address: {
                    street: '147 Birch Avenue',
                    city: 'Paranaque',
                    zipCode: '1700'
                },
                preferences: ['desserts', 'beverages']
            },
            {
                id: '8',
                name: 'Roberto Lim',
                email: 'roberto.lim@email.com',
                phone: '+639148901234',
                joinDate: '2024-01-25',
                lastOrder: '2024-03-13',
                totalOrders: 11,
                totalSpent: '₱4,150',
                membership: 'vip',
                status: 'active',
                avatar: 'RL',
                address: {
                    street: '258 Walnut Street',
                    city: 'San Juan',
                    zipCode: '1500'
                },
                preferences: ['pizza', 'pasta', 'wings']
            }
        ];
        
        localStorage.setItem('users', JSON.stringify(sampleUsers));
        console.log('Sample users data created successfully');
    }
}

function loadUsers() {
    // Get users from localStorage - use 'users' key to match admin-dashboard
    const usersData = localStorage.getItem('users');
    
    if (!usersData) {
        console.error('No users data found in localStorage');
        displayNoUsers();
        return;
    }
    
    try {
        const parsedUsers = JSON.parse(usersData);
        console.log('Loaded users:', parsedUsers.length);
        displayUsers(parsedUsers);
        setupPagination(parsedUsers);
        updateUserStats(parsedUsers);
    } catch (error) {
        console.error('Error parsing users data:', error);
        displayNoUsers();
    }
}

function displayUsers(usersData = []) {
    let filteredUsers = [...usersData];
    
    console.log('Displaying users:', filteredUsers.length);
    
    // Apply search filter
    const searchTerm = document.getElementById('search-users')?.value.toLowerCase() || '';
    if (searchTerm) {
        filteredUsers = filteredUsers.filter(user => 
            user.name.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm) ||
            (user.phone && user.phone.includes(searchTerm)) ||
            (user.id && user.id.toString().includes(searchTerm))
        );
    }
    
    // Apply status filter
    if (currentFilter.status !== 'all') {
        filteredUsers = filteredUsers.filter(user => user.status === currentFilter.status);
    }
    
    // Apply membership filter
    if (currentFilter.membership !== 'all') {
        filteredUsers = filteredUsers.filter(user => user.membership === currentFilter.membership);
    }
    
    // Apply sorting
    filteredUsers.sort((a, b) => {
        if (currentSort.field === 'joinDate') {
            const dateA = new Date(a.joinDate);
            const dateB = new Date(b.joinDate);
            return currentSort.direction === 'asc' ? dateA - dateB : dateB - dateA;
        }
        if (currentSort.field === 'totalOrders') {
            return currentSort.direction === 'asc' ? a.totalOrders - b.totalOrders : b.totalOrders - a.totalOrders;
        }
        if (currentSort.field === 'name') {
            return currentSort.direction === 'asc' 
                ? a.name.localeCompare(b.name)
                : b.name.localeCompare(a.name);
        }
        if (currentSort.field === 'totalSpent') {
            const spentA = parseFloat((a.totalSpent || '0').replace(/[^\d.]/g, '')) || 0;
            const spentB = parseFloat((b.totalSpent || '0').replace(/[^\d.]/g, '')) || 0;
            return currentSort.direction === 'asc' ? spentA - spentB : spentB - spentA;
        }
        return 0;
    });
    
    const startIndex = (currentPage - 1) * usersPerPage;
    const paginatedUsers = filteredUsers.slice(startIndex, startIndex + usersPerPage);
    const tableBody = document.getElementById('users-table-body');
    
    if (!tableBody) {
        console.error('Users table body not found');
        return;
    }
    
    if (paginatedUsers.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #7f8c8d;">
                    <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">people</i>
                    <p>No users found</p>
                    ${searchTerm || currentFilter.status !== 'all' || currentFilter.membership !== 'all' ? 
                        '<p>Try adjusting your search or filters</p>' : 
                        '<p>No users registered yet</p>'
                    }
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = paginatedUsers.map(user => `
        <tr>
            <td>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="user-avatar-small">${user.avatar || getUserInitials(user.name)}</div>
                    <div>
                        <div style="font-weight: 600; color: #2c3e50;">${user.name}</div>
                        <div style="font-size: 12px; color: #7f8c8d;">ID: ${user.id}</div>
                    </div>
                </div>
            </td>
            <td>
                <div>${user.email}</div>
                <div style="font-size: 12px; color: #7f8c8d;">${user.phone || 'N/A'}</div>
            </td>
            <td style="text-align: center;">
                <div style="font-weight: 600; color: #2c3e50; font-size: 16px;">${user.totalOrders || 0}</div>
                <div style="font-size: 12px; color: #7f8c8d;">orders</div>
            </td>
            <td style="font-weight: 600; color: #2c3e50;">${user.totalSpent || '₱0'}</td>
            <td>
                <div style="font-size: 14px;">${formatUserDate(user.joinDate)}</div>
                <div style="font-size: 12px; color: #7f8c8d;">${user.lastOrder ? formatUserDate(user.lastOrder) : 'No orders'}</div>
            </td>
            <td>
                <span class="status-badge status-${user.status}">
                    ${user.status ? user.status.charAt(0).toUpperCase() + user.status.slice(1) : 'Unknown'}
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn btn-view" onclick="viewUserDetails('${user.id}')" title="View Details">
                        <i class="material-icons">visibility</i>
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteUser('${user.id}')" title="Delete User">
                        <i class="material-icons">delete</i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    updateResultsCount(filteredUsers.length);
}

function getUserInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase();
}

function formatUserDate(dateString) {
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

function displayNoUsers() {
    const tableBody = document.getElementById('users-table-body');
    if (tableBody) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #7f8c8d;">
                    <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">people</i>
                    <p>No users data available</p>
                    <button onclick="initializeUsersData(); loadUsers();" class="btn btn-primary" style="margin-top: 10px;">
                        Generate Sample Data
                    </button>
                </td>
            </tr>
        `;
    }
}

function setupPagination(usersData = []) {
    let filteredUsers = [...usersData];
    
    // Apply filters for pagination count
    if (currentFilter.status !== 'all') {
        filteredUsers = filteredUsers.filter(user => user.status === currentFilter.status);
    }
    if (currentFilter.membership !== 'all') {
        filteredUsers = filteredUsers.filter(user => user.membership === currentFilter.membership);
    }
    
    const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
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
    
    // Show page numbers (max 5 pages)
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
}

function updateResultsCount(totalUsers) {
    const resultsCount = document.getElementById('results-count');
    if (resultsCount) {
        const startIndex = (currentPage - 1) * usersPerPage + 1;
        const endIndex = Math.min(currentPage * usersPerPage, totalUsers);
        resultsCount.textContent = `Showing ${startIndex}-${endIndex} of ${totalUsers} users`;
    }
}

function changePage(page) {
    const usersData = JSON.parse(localStorage.getItem('users') || '[]');
    let filteredUsers = [...usersData];
    
    // Apply filters to get accurate page count
    if (currentFilter.status !== 'all') {
        filteredUsers = filteredUsers.filter(user => user.status === currentFilter.status);
    }
    if (currentFilter.membership !== 'all') {
        filteredUsers = filteredUsers.filter(user => user.membership === currentFilter.membership);
    }
    
    const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
    
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        loadUsers();
    }
}

function initFilters() {
    // Status filters
    document.querySelectorAll('.filter-btn[data-status]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn[data-status]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter.status = this.getAttribute('data-status');
            currentPage = 1;
            loadUsers();
        });
    });
    
    // Membership filters
    document.querySelectorAll('.filter-btn[data-membership]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn[data-membership]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter.membership = this.getAttribute('data-membership');
            currentPage = 1;
            loadUsers();
        });
    });
    
    // Clear filters button
    const clearFiltersBtn = document.getElementById('clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            currentFilter = { status: 'all', membership: 'all' };
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            document.querySelector('.filter-btn[data-status="all"]')?.classList.add('active');
            document.querySelector('.filter-btn[data-membership="all"]')?.classList.add('active');
            currentPage = 1;
            loadUsers();
        });
    }
}

function initSearch() {
    const searchInput = document.getElementById('search-users');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadUsers();
            }, 300);
        });
    }
}

function initSorting() {
    document.querySelectorAll('.users-table th[data-sort]').forEach(th => {
        th.addEventListener('click', function() {
            const field = this.getAttribute('data-sort');
            if (currentSort.field === field) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.field = field;
                currentSort.direction = 'asc';
            }
            currentPage = 1;
            loadUsers();
            updateSortIcons();
        });
    });
}

function updateSortIcons() {
    document.querySelectorAll('.users-table th[data-sort]').forEach(th => {
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

function viewUserDetails(userId) {
    const usersData = JSON.parse(localStorage.getItem('users') || '[]');
    const user = usersData.find(u => u.id === userId);
    
    if (!user) {
        showNotification('User not found', 'error');
        return;
    }
    
    // Create modal for user details
    const modal = document.createElement('div');
    modal.className = 'modal show';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2>User Details - ${user.name}</h2>
                <button class="close-modal" onclick="this.closest('.modal').remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="display: grid; gap: 25px;">
                    <div style="display: flex; align-items: center; gap: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                        <div class="user-avatar-small" style="width: 60px; height: 60px; font-size: 20px;">${user.avatar || getUserInitials(user.name)}</div>
                        <div>
                            <h3 style="margin: 0 0 5px 0; color: #2c3e50;">${user.name}</h3>
                            <p style="margin: 0; color: #7f8c8d;">${user.email}</p>
                            <div style="display: flex; gap: 10px; margin-top: 8px;">
                                <span class="status-badge status-${user.status}">${user.status}</span>
                                <span class="membership-badge membership-${user.membership}">${user.membership}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <h4>Contact Information</h4>
                            <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                            <p><strong>Member Since:</strong> ${formatUserDate(user.joinDate)}</p>
                            <p><strong>Last Order:</strong> ${user.lastOrder ? formatUserDate(user.lastOrder) : 'No orders yet'}</p>
                        </div>
                        <div>
                            <h4>Order Statistics</h4>
                            <p><strong>Total Orders:</strong> ${user.totalOrders || 0}</p>
                            <p><strong>Total Spent:</strong> ${user.totalSpent || '₱0'}</p>
                            <p><strong>Average Order:</strong> ${calculateAverageOrder(user)}</p>
                        </div>
                    </div>
                    
                    ${user.address ? `
                        <div>
                            <h4>Delivery Address</h4>
                            <p>${user.address.street}, ${user.address.city} ${user.address.zipCode}</p>
                        </div>
                    ` : ''}
                    
                    ${user.preferences && user.preferences.length > 0 ? `
                        <div>
                            <h4>Food Preferences</h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                ${user.preferences.map(pref => `
                                    <span style="background: #e8f4fd; color: #3498db; padding: 4px 12px; border-radius: 15px; font-size: 12px;">
                                        ${pref}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Close</button>
                <button class="btn btn-primary" onclick="editUser('${user.id}'); this.closest('.modal').remove()">Edit User</button>
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

function calculateAverageOrder(user) {
    if (!user.totalOrders || user.totalOrders === 0) return '₱0';
    const totalSpent = parseFloat((user.totalSpent || '0').replace(/[^\d.]/g, '')) || 0;
    const average = totalSpent / user.totalOrders;
    return `₱${average.toFixed(2)}`;
}

function editUser(userId) {
    const usersData = JSON.parse(localStorage.getItem('users') || '[]');
    const user = usersData.find(u => u.id === userId);
    
    if (!user) {
        showNotification('User not found', 'error');
        return;
    }
    
    const modal = document.createElement('div');
    modal.className = 'modal show';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Edit User - ${user.name}</h2>
                <button class="close-modal" onclick="this.closest('.modal').remove()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-user-form">
                    <div class="form-group">
                        <label for="edit-user-name">Name</label>
                        <input type="text" id="edit-user-name" value="${user.name}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-user-email">Email</label>
                        <input type="email" id="edit-user-email" value="${user.email}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-user-phone">Phone</label>
                        <input type="tel" id="edit-user-phone" value="${user.phone || ''}">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-user-status">Status</label>
                            <select id="edit-user-status">
                                <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                                <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                <option value="suspended" ${user.status === 'suspended' ? 'selected' : ''}>Suspended</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-user-membership">Membership</label>
                            <select id="edit-user-membership">
                                <option value="regular" ${user.membership === 'regular' ? 'selected' : ''}>Regular</option>
                                <option value="premium" ${user.membership === 'premium' ? 'selected' : ''}>Premium</option>
                                <option value="vip" ${user.membership === 'vip' ? 'selected' : ''}>VIP</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Cancel</button>
                <button class="btn btn-primary" onclick="saveUserChanges('${user.id}')">Save Changes</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function saveUserChanges(userId) {
    const usersData = JSON.parse(localStorage.getItem('users') || '[]');
    const userIndex = usersData.findIndex(u => u.id === userId);
    
    if (userIndex === -1) {
        showNotification('User not found', 'error');
        return;
    }
    
    usersData[userIndex].name = document.getElementById('edit-user-name').value;
    usersData[userIndex].email = document.getElementById('edit-user-email').value;
    usersData[userIndex].phone = document.getElementById('edit-user-phone').value;
    usersData[userIndex].status = document.getElementById('edit-user-status').value;
    usersData[userIndex].membership = document.getElementById('edit-user-membership').value;
    
    localStorage.setItem('users', JSON.stringify(usersData));
    showNotification('User updated successfully', 'success');
    document.querySelector('.modal.show')?.remove();
    loadUsers();
}

function toggleUserStatus(userId, newStatus) {
    const usersData = JSON.parse(localStorage.getItem('users') || '[]');
    const userIndex = usersData.findIndex(u => u.id === userId);
    
    if (userIndex === -1) {
        showNotification('User not found', 'error');
        return;
    }
    
    const user = usersData[userIndex];
    const oldStatus = user.status;
    user.status = newStatus;
    
    localStorage.setItem('users', JSON.stringify(usersData));
    showNotification(`User ${user.name} status changed from ${oldStatus} to ${newStatus}`, 'success');
    loadUsers();
}

function deleteUser(userId) {
    const usersData = JSON.parse(localStorage.getItem('users') || '[]');
    const userIndex = usersData.findIndex(u => u.id === userId);
    
    if (userIndex === -1) {
        showNotification('User not found', 'error');
        return;
    }
    
    const user = usersData[userIndex];
    
    if (confirm(`Are you sure you want to delete user "${user.name}"? This action cannot be undone and will remove all their data.`)) {
        usersData.splice(userIndex, 1);
        localStorage.setItem('users', JSON.stringify(usersData));
        showNotification('User deleted successfully', 'success');
        loadUsers();
    }
}

function updateUserStats(usersData = []) {
    const totalUsers = usersData.length;
    const activeUsers = usersData.filter(u => u.status === 'active').length;
    const premiumUsers = usersData.filter(u => u.membership === 'premium' || u.membership === 'vip').length;
    const newThisMonth = usersData.filter(u => {
        const joinDate = new Date(u.joinDate);
        const now = new Date();
        return joinDate.getMonth() === now.getMonth() && joinDate.getFullYear() === now.getFullYear();
    }).length;
    
    // Update stats elements if they exist
    const elements = {
        'total-users': totalUsers,
        'active-users': activeUsers,
        'premium-users': premiumUsers,
        'new-users': newThisMonth
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    });
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
window.viewUserDetails = viewUserDetails;
window.editUser = editUser;
window.toggleUserStatus = toggleUserStatus;
window.deleteUser = deleteUser;
window.changePage = changePage;
window.initializeUsersData = initializeUsersData;