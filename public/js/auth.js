// public/js/auth.js

document.addEventListener('DOMContentLoaded', () => {
    // 1. Check for Session Flash Messages
    if (window.authConfig && window.authConfig.session.success) {
        showNotification(window.authConfig.session.success, 'success');
    }
    if (window.authConfig && window.authConfig.session.error) {
        showNotification(window.authConfig.session.error, 'error');
    }
    
    // 2. Check for Validation Errors
    if (window.authConfig && window.authConfig.errors) {
        showNotification(window.authConfig.errors, 'error');
    }
});

function showNotification(message, type = 'success') {
    // 1. Remove existing toast
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();

    // 2. Determine Icon
    let iconName = 'check_circle';
    if (type === 'error') iconName = 'error';
    if (type === 'info') iconName = 'info';

    // 3. Create Element
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    
    // We assume Material Icons CSS is loaded in the main layout
    toast.innerHTML = `
        <i class="material-icons">${iconName}</i>
        <span>${message}</span>
    `;

    // 4. Append to body
    document.body.appendChild(toast);

    // 5. Trigger Animation
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    // 6. Remove after 4 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 400); 
    }, 4000);
}