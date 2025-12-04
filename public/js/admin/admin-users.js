// public/admin/js/admin-users.js

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// GLOBAL STATE
let currentPage = 1;
let currentSearch = '';
let currentStatus = 'all'; // Changed to match your filter buttons
let usersData = []; 

document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    initFilters();
    initSearch();
});

/* ============================================================
   1. LOAD USERS (API)
   ============================================================ */
function loadUsers(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('users-table-body');
    
    // UI: Loading State
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 40px; color: #7f8c8d;">Loading users data...</td></tr>';
    }

    // Build API URL with Search and Status params
    const params = new URLSearchParams({
        page: page,
        search: currentSearch,
        status: currentStatus // Sends 'all', 'active', 'inactive', or 'suspended'
    });

    fetch(`/admin/users/json?${params.toString()}`, {
        headers: { 
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(result => {
        usersData = result.data; 
        displayUsers(usersData);
        setupPagination(result); 
    })
    .catch(error => {
        console.error('Error:', error);
        if (tbody) tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; color:red;">Error loading data.</td></tr>';
    });
}

/* ============================================================
   2. RENDER TABLE
   ============================================================ */
function displayUsers(users = []) {
    const tableBody = document.getElementById('users-table-body');

    if (!tableBody) return;

    if (users.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #7f8c8d;">
                    <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">people_outline</i>
                    <p>No users found matching "${currentStatus}"</p>
                </td>
            </tr>
        `;
        return;
    }

    tableBody.innerHTML = users.map(user => {
        const isBlocked = user.is_blocked == 1; 
        const roleName = user.is_admin == 1 ? 'Admin' : 'User';
        
        // Status Badge Logic
        let badgeHTML = '';
        if (isBlocked) {
            badgeHTML = `<span class="status-badge status-cancelled">Suspended</span>`;
        } else {
            badgeHTML = `<span class="status-badge status-active">Active</span>`;
        }

        // Button Logic (Block vs Unblock)
        const blockBtnIcon = isBlocked ? 'lock_open' : 'block';
        const blockBtnTitle = isBlocked ? 'Unblock User' : 'Suspend User';
        const blockBtnStyle = isBlocked 
            ? 'background-color: #27ae60; color: white;' // Green for Unblock
            : 'background-color: #e74c3c; color: white;'; // Red for Block

        return `
        <tr style="${isBlocked ? 'background-color: #fff5f5;' : ''}">
            <td>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="user-avatar-small">${getUserInitials(user.name)}</div>
                    <div>
                        <div style="font-weight: 600; color: #2c3e50;">${user.name}</div>
                        <div style="font-size: 12px; color: #7f8c8d;">ID: ${user.id}</div>
                    </div>
                </div>
            </td>
            <td>${user.email}</td>
            <td>${user.phone || '—'}</td>
            <td style="text-align: center;">
                <div style="font-weight: 600; color: #2c3e50;">${user.orders_count || 0}</div>
            </td>
            <td>
                <div style="font-size: 14px;">${formatUserDate(user.created_at)}</div>
            </td>
            <td>${badgeHTML}</td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn btn-view" onclick="viewUserDetails(${user.id})" title="View Details">
                        <i class="material-icons">visibility</i>
                    </button>
                    
                    <button class="action-btn" 
                            style="${blockBtnStyle} border:none; padding:8px; border-radius:8px; cursor:pointer;"
                            onclick="toggleBlockUser(${user.id}, ${isBlocked})" 
                            title="${blockBtnTitle}">
                        <i class="material-icons">${blockBtnIcon}</i>
                    </button>
                </div>
            </td>
        </tr>
    `}).join('');
}

/* ============================================================
   3. FILTERS (Functionality Added Here)
   ============================================================ */
function initFilters() {
    const buttons = document.querySelectorAll('.filter-btn');
    
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            // 1. Visual Update
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // 2. Logic Update
            currentStatus = this.getAttribute('data-status'); // Gets 'all', 'active', 'suspended'
            
            // 3. Reload Data
            loadUsers(1); 
        });
    });
}

/* ============================================================
   4. TOGGLE BLOCK/UNBLOCK
   ============================================================ */
function toggleBlockUser(id, isCurrentlyBlocked) {
    const action = isCurrentlyBlocked ? 'UNBLOCK' : 'SUSPEND';
    
    if (!confirm(`Are you sure you want to ${action} this user?`)) return;

    fetch(`/admin/users/${id}/toggle-block`, {
        method: 'PATCH',
        headers: { 
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(async res => {
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Action failed');
        return json;
    })
    .then(data => {
        alert(data.message);
        loadUsers(currentPage); // Reload to apply changes and check if user still fits current filter
    })
    .catch(err => {
        console.error(err);
        alert(err.message);
    });
}

/* ============================================================
   5. VIEW MODAL
   ============================================================ */
function viewUserDetails(userId) {
    const user = usersData.find(u => u.id == userId);
    if (!user) return;

    const isBlocked = user.is_blocked == 1;
    const statusText = isBlocked ? 'Suspended' : 'Active';
    const statusClass = isBlocked ? 'status-cancelled' : 'status-active';

    const modal = document.createElement('div');
    modal.className = 'modal-overlay show';
    modal.style.display = 'flex';
    
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 style="margin:0; color:white;">User Details</h3>
                <button class="close-modal" onclick="this.closest('.modal-overlay').remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="display: flex; align-items: center; gap: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                    <div class="user-avatar-small" style="width: 60px; height: 60px; font-size: 20px;">
                        ${getUserInitials(user.name)}
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; color: #2c3e50;">${user.name}</h3>
                        <p style="margin: 0; color: #7f8c8d;">${user.email}</p>
                        <div style="margin-top: 8px;">
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div>
                        <h4 style="margin-bottom: 10px; color: #50392c;">Contact Info</h4>
                        <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                        <p><strong>Address:</strong> ${user.address || 'N/A'}</p>
                        <p><strong>Joined:</strong> ${formatUserDate(user.created_at)}</p>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 10px; color: #50392c;">Account Stats</h4>
                        <p><strong>Total Orders:</strong> ${user.orders_count || 0}</p>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="this.closest('.modal-overlay').remove()">Close</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
}

/* ============================================================
   6. PAGINATION & HELPERS
   ============================================================ */
function setupPagination(meta) {
    const container = document.getElementById('pagination');
    if (!container) return;

    if (meta.last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = `
        <button class="page-btn" onclick="loadUsers(${meta.current_page - 1})" ${meta.prev_page_url ? '' : 'disabled'}>
            <i class="material-icons">chevron_left</i>
        </button>
    `;

    for (let i = 1; i <= meta.last_page; i++) {
        if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
            html += `
                <button class="page-btn ${i === meta.current_page ? 'active' : ''}" onclick="loadUsers(${i})">
                    ${i}
                </button>
            `;
        }
    }

    html += `
        <button class="page-btn" onclick="loadUsers(${meta.current_page + 1})" ${meta.next_page_url ? '' : 'disabled'}>
            <i class="material-icons">chevron_right</i>
        </button>
    `;

    container.innerHTML = html;
}

function initSearch() {
    const searchInput = document.getElementById('search-users');
    let debounceTimer;
    
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                currentSearch = e.target.value;
                loadUsers(1);
            }, 400);
        });
    }
}

function getUserInitials(name) {
    if (!name) return '??';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}

function formatUserDate(dateString) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', year: 'numeric'
    });
}

// Exports
window.viewUserDetails = viewUserDetails;
window.toggleBlockUser = toggleBlockUser;
window.loadUsers = loadUsers;