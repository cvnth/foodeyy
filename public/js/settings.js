// js/settings.js
document.addEventListener('DOMContentLoaded', function() {
    loadSettings();
    initSaveButton();
});

function loadSettings() {
    // Load saved settings from localStorage if available
    const savedSettings = JSON.parse(localStorage.getItem('userSettings')) || {};
    
    // Set form values from saved settings or defaults
    document.getElementById('name').value = savedSettings.name || 'John Smith';
    document.getElementById('email').value = savedSettings.email || 'john.smith@example.com';
    document.getElementById('phone').value = savedSettings.phone || '+63 912 345 6789';
    document.getElementById('cuisine').value = savedSettings.cuisine || 'Chinese';
    document.getElementById('notifications').checked = savedSettings.notifications !== false;
    document.getElementById('newsletter').checked = savedSettings.newsletter !== false;
    document.getElementById('card').value = savedSettings.card || '**** **** **** 1234';
    document.getElementById('address1').value = savedSettings.address1 || '123 Main Street, Manila, Philippines';
    document.getElementById('address2').value = savedSettings.address2 || '';
}

function initSaveButton() {
    document.getElementById('save-settings').addEventListener('click', function() {
        saveSettings();
    });
}

function saveSettings() {
    const settings = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        cuisine: document.getElementById('cuisine').value,
        notifications: document.getElementById('notifications').checked,
        newsletter: document.getElementById('newsletter').checked,
        card: document.getElementById('card').value,
        address1: document.getElementById('address1').value,
        address2: document.getElementById('address2').value
    };
    
    // Validate required fields
    if (!settings.name || !settings.email || !settings.phone) {
        showNotification('Please fill in all required fields', 'error');
        return;
    }
    
    // Save to localStorage
    localStorage.setItem('userSettings', JSON.stringify(settings));
    
    // Update user data in main.js
    const userInfo = document.querySelector('.user-info h4');
    if (userInfo) {
        userInfo.textContent = settings.name;
    }
    
    showNotification('Settings saved successfully!', 'success');
    
    // Simulate API call delay
    const saveBtn = document.getElementById('save-settings');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="material-icons">check</i> Saved';
    saveBtn.disabled = true;
    
    setTimeout(() => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    }, 2000);
}