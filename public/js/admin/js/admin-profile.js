// admin/js/admin-profile.js
document.addEventListener('DOMContentLoaded', function() {
    checkAdminAuth();
    loadAdminProfile();
    initProfileForm();
    loadRecentActivity();
});

function loadAdminProfile() {
    const adminProfile = JSON.parse(localStorage.getItem('adminProfile')) || getDefaultAdminProfile();
    
    // Update profile information
    document.getElementById('admin-name').textContent = adminProfile.name;
    document.getElementById('admin-email').textContent = adminProfile.email;
    document.getElementById('admin-avatar').textContent = getInitials(adminProfile.name);
    
    // Update form fields
    document.getElementById('full-name').value = adminProfile.name;
    document.getElementById('email').value = adminProfile.email;
    document.getElementById('phone').value = adminProfile.phone;
}

function getDefaultAdminProfile() {
    return {
        name: 'Admin User',
        email: 'admin@foodhub.com',
        phone: '+63 912 345 6789',
        role: 'Administrator',
        joinDate: new Date().toISOString().split('T')[0]
    };
}

function getInitials(name) {
    return name.split(' ').map(word => word[0]).join('').toUpperCase();
}

function initProfileForm() {
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveProfileChanges();
    });
}

function saveProfileChanges() {
    const profileData = {
        name: document.getElementById('full-name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        role: document.getElementById('role').value
    };

    // Validate data
    if (!profileData.name || !profileData.email) {
        showNotification('Please fill in all required fields', 'error');
        return;
    }

    // Save to localStorage
    localStorage.setItem('adminProfile', JSON.stringify(profileData));
    
    // Update displayed information
    document.getElementById('admin-name').textContent = profileData.name;
    document.getElementById('admin-email').textContent = profileData.email;
    document.getElementById('admin-avatar').textContent = getInitials(profileData.name);

    showNotification('Profile updated successfully!', 'success');
    
    // Add to activity log
    addActivity('Profile updated', 'You updated your profile information');
}

function resetForm() {
    loadAdminProfile();
    showNotification('Form reset to saved values', 'info');
}

function changePassword() {
    const newPassword = prompt('Enter new password:');
    if (newPassword) {
        // In a real application, this would make an API call to change the password
        showNotification('Password changed successfully!', 'success');
        addActivity('Password changed', 'You changed your account password');
    }
}

function enable2FA() {
    const enable2FA = confirm('Enable Two-Factor Authentication? This will require a verification code for future logins.');
    if (enable2FA) {
        showNotification('Two-Factor Authentication enabled!', 'success');
        addActivity('2FA enabled', 'You enabled two-factor authentication');
    }
}

function viewLoginHistory() {
    // In a real application, this would show a modal with login history
    showNotification('Login history would be displayed here', 'info');
}

function loadRecentActivity() {
    const activities = JSON.parse(localStorage.getItem('adminActivities')) || getDefaultActivities();
    displayActivities(activities);
}

function getDefaultActivities() {
    return [
        {
            id: 1,
            title: 'Logged in',
            description: 'You logged into the admin dashboard',
            time: new Date().toISOString(),
            icon: 'login'
        },
        {
            id: 2,
            title: 'Order updated',
            description: 'You updated order ORD-001 status to delivered',
            time: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
            icon: 'edit'
        },
        {
            id: 3,
            title: 'Menu item added',
            description: 'You added "Grilled Salmon" to the menu',
            time: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString(),
            icon: 'add'
        },
        {
            id: 4,
            title: 'User managed',
            description: 'You suspended user John Smith',
            time: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString(),
            icon: 'people'
        }
    ];
}

function displayActivities(activities) {
    const activityList = document.getElementById('activity-list');
    
    activityList.innerHTML = activities.map(activity => `
        <div class="activity-item">
            <div class="activity-icon">
                <i class="material-icons">${activity.icon}</i>
            </div>
            <div class="activity-details">
                <div class="activity-title">${activity.title}</div>
                <div class="activity-description">${activity.description}</div>
                <div class="activity-time">${formatActivityTime(activity.time)}</div>
            </div>
        </div>
    `).join('');
}

function formatActivityTime(timeString) {
    const time = new Date(timeString);
    const now = new Date();
    const diffMs = now - time;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins} minutes ago`;
    if (diffHours < 24) return `${diffHours} hours ago`;
    if (diffDays < 7) return `${diffDays} days ago`;
    return time.toLocaleDateString();
}

function addActivity(title, description) {
    const activities = JSON.parse(localStorage.getItem('adminActivities')) || getDefaultActivities();
    
    activities.unshift({
        id: Date.now(),
        title: title,
        description: description,
        time: new Date().toISOString(),
        icon: 'check_circle'
    });

    // Keep only recent activities
    if (activities.length > 20) {
        activities.pop();
    }

    localStorage.setItem('adminActivities', JSON.stringify(activities));
    displayActivities(activities);
}