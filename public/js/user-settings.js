// public/js/user-settings.js

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// 1. SAVE PROFILE
function saveProfile(e) {
    e.preventDefault();
    const btn = document.getElementById('saveProfileBtn');
    const originalText = btn.textContent;
    btn.textContent = 'Saving...';
    btn.disabled = true;

    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value
    };

    fetch('/user/settings/update', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify(formData)
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Update failed');
        showNotification('Profile updated successfully!', 'success');
    })
    .catch(err => showNotification(err.message, 'error'))
    .finally(() => {
        btn.textContent = originalText;
        btn.disabled = false;
    });
}

// 2. CHANGE PASSWORD
function changePassword(e) {
    e.preventDefault();
    const btn = document.getElementById('savePasswordBtn');
    const originalText = btn.textContent;
    btn.textContent = 'Updating...';
    btn.disabled = true;

    const payload = {
        current_password: document.getElementById('current_password').value,
        password: document.getElementById('new_password').value,
        password_confirmation: document.getElementById('new_password_confirmation').value
    };

    fetch('/user/settings/password', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify(payload)
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Password update failed');
        showNotification('Password changed successfully!', 'success');
        document.getElementById('passwordForm').reset();
    })
    .catch(err => {
        const msg = err.message.includes('password') ? err.message : 'Ensure current password is correct and new passwords match.';
        showNotification(msg, 'error');
    })
    .finally(() => {
        btn.textContent = originalText;
        btn.disabled = false;
    });
}

// 3. DELETE ACCOUNT
function confirmDelete() {
    const password = prompt("To confirm deletion, please type your password:");
    if (!password) return;

    fetch('/user/settings/delete', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ password: password })
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Delete failed');
        alert('Account deleted successfully.');
        window.location.href = data.redirect || '/login';
    })
    .catch(err => {
        showNotification(err.message || 'Incorrect password.', 'error');
    });
}

// Notification Helper
function showNotification(message, type = 'success') {
    const colors = { success: 'bg-green-500', error: 'bg-red-500' };
    const colorClass = colors[type] || colors.success;
    const iconName = type === 'success' ? 'check_circle' : 'error';

    const toast = document.createElement('div');
    toast.className = `fixed bottom-5 right-5 ${colorClass} text-white px-6 py-3 rounded-lg shadow-xl flex items-center gap-3 transform transition-all duration-300 translate-y-10 opacity-0 z-50`;
    toast.innerHTML = `<i class="material-icons text-white text-xl">${iconName}</i><span class="font-medium text-sm">${message}</span>`;

    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
    setTimeout(() => {
        toast.classList.add('translate-y-10', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}