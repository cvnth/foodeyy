document.addEventListener("DOMContentLoaded", () => {
    // These listeners handle form toggling and navigation links
    const loginBtn = document.getElementById("loginBtn");
    const signupBtn = document.getElementById("signupBtn");
    const switchToSignup = document.getElementById("switchToSignup");
    const switchToLogin = document.getElementById("switchToLogin");
    const switchToIndex = document.getElementById("switchToIndex");
    
    // Logout button must submit a POST request to Laravel's logout route.
    // Ensure your logout button is wrapped in a form or uses axios/fetch if not.
    const logoutBtn = document.getElementById("logoutBtn");

    if (loginBtn) loginBtn.addEventListener("click", () => toggleForm("login"));
    if (signupBtn) signupBtn.addEventListener("click", () => toggleForm("signup"));

    if (switchToSignup)
        switchToSignup.addEventListener("click", e => { e.preventDefault(); toggleForm("signup"); });

    if (switchToLogin)
        switchToLogin.addEventListener("click", e => { e.preventDefault(); toggleForm("login"); });

    if (switchToIndex)
        switchToIndex.addEventListener("click", e => { e.preventDefault(); window.location.href = "/"; });

    if (logoutBtn) {
        logoutBtn.addEventListener("click", handleLogout);
    }
});

function toggleForm(type) {
    const loginForm = document.getElementById("loginForm");
    const signupForm = document.getElementById("signupForm");
    if (type === "login") {
        signupForm?.classList.remove("active");
        loginForm?.classList.add("active");
    } else {
        loginForm?.classList.remove("active");
        signupForm?.classList.add("active");
    }
}

// ------------------------------
// LARAVEL INTEGRATION & UX
// ------------------------------

// Laravel handles the actual logout, this function ensures the POST request is sent.
function handleLogout(e) {
    e.preventDefault();
    // Assuming your logout button is outside a form, we submit a hidden form for the POST request
    const logoutForm = document.createElement('form');
    logoutForm.action = '/logout'; // This must match your Route::post('/logout', ...)
    logoutForm.method = 'POST';
    
    // Add CSRF token for security
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    // This Blade directive must be rendered in your login.blade.php where you link the JS
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content; 

    logoutForm.appendChild(csrfToken);
    document.body.appendChild(logoutForm);
    logoutForm.submit();
}

// ------------------------------
// Custom Notification (Kept from your original JS)
// ------------------------------
function showNotification(msg, type = "info") {
    document.querySelectorAll(".notification").forEach(n => n.remove());
    const note = document.createElement("div");
    note.className = `notification notification-${type}`;
    note.innerHTML = `<span>${msg}</span>`;
    note.style = `
        position: fixed; top: 20px; right: 20px; background: ${
            type === "success" ? "#27ae60" : "#e74c3c"
        }; color: white; padding: 10px 20px; border-radius: 5px; z-index: 9999;
    `;
    document.body.appendChild(note);
    setTimeout(() => note.remove(), 3000);
}

function showNotification(message, type = "success") {
    const note = document.createElement("div");
    note.className = `notification ${type}`;
    note.innerText = message;

    document.body.appendChild(note);

    setTimeout(() => {
        note.classList.add("show");
    }, 10);

    setTimeout(() => {
        note.classList.remove("show");
        setTimeout(() => note.remove(), 300);
    }, 3000);

    function showNotification(message, type = 'success') {
    // 1. Remove any existing toast to prevent stacking (optional)
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();

    // 2. Determine Icon based on type
    let iconName = 'check_circle'; // Default success icon
    if (type === 'error') iconName = 'error';
    if (type === 'info') iconName = 'info';

    // 3. Create the HTML Element
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    
    // Note: Assuming you have Material Icons loaded (which you do in dashboard layouts)
    // If not, remove the <i> tag or use simple text/emoji like ✔ or ✖
    toast.innerHTML = `
        <i class="material-icons">${iconName}</i>
        <span>${message}</span>
    `;

    // 4. Add to Document Body
    document.body.appendChild(toast);

    // 5. Trigger Animation (Small delay needed for CSS transition to catch)
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    // 6. Remove after 4 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        // Wait for slide-out animation to finish before removing from DOM
        setTimeout(() => {
            toast.remove();
        }, 400); 
    }, 4000);
}
}
