<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Settings</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: { preflight: false, container: false }
      }
    </script>
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">

            <div class="p-8 pb-20"> <div class="top-header mb-10">
                    <h1 class="text-4xl font-bold" style="font-size: 2rem; color: #e67e22;">Account Settings</h1>
                </div>

                <div class="settings-container" style="max-width: 800px; margin: 0 auto;">
                    
                    <form id="profileForm" onsubmit="saveProfile(event)" style="margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 40px;">
                        <h3 class="text-2xl font-bold mb-6" style="font-size: 1.5rem; margin-bottom: 20px; color: #2c3e50;">Profile Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label style="display:block; margin-bottom:8px; font-weight:600;">Full Name</label>
                                <input type="text" id="name" value="{{ auth()->user()->name }}" required
                                       style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                            </div>
                            
                            <div class="form-group">
                                <label style="display:block; margin-bottom:8px; font-weight:600;">Email Address</label>
                                <input type="email" id="email" value="{{ auth()->user()->email }}" required
                                       style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                            </div>
                            
                            <div class="form-group">
                                <label style="display:block; margin-bottom:8px; font-weight:600;">Phone Number</label>
                                <input type="tel" id="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="+63..."
                                       style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label style="display:block; margin-bottom:8px; font-weight:600;">Delivery Address</label>
                            <textarea id="address" rows="3" placeholder="Enter complete address"
                                      style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px; font-family: inherit;">{{ auth()->user()->address ?? '' }}</textarea>
                        </div>

                        <div class="text-right mt-6" style="margin-top: 20px; text-align: right;">
                            <button type="submit" id="saveProfileBtn"
                                    style="background: #e67e22; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                                Save Profile
                            </button>
                        </div>
                    </form>

                    <form id="passwordForm" onsubmit="changePassword(event)" style="margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 40px;">
                        <h3 class="text-2xl font-bold mb-6" style="font-size: 1.5rem; margin-bottom: 20px; color: #2c3e50;">Security</h3>
                        
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="display:block; margin-bottom:8px; font-weight:600;">Current Password</label>
                            <input type="password" id="current_password" required placeholder="••••••••"
                                   style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label style="display:block; margin-bottom:8px; font-weight:600;">New Password</label>
                                <input type="password" id="new_password" required placeholder="Min. 8 characters"
                                       style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                            </div>
                            <div class="form-group">
                                <label style="display:block; margin-bottom:8px; font-weight:600;">Confirm Password</label>
                                <input type="password" id="new_password_confirmation" required placeholder="Confirm new password"
                                       style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                            </div>
                        </div>

                        <div class="text-right mt-6" style="margin-top: 20px; text-align: right;">
                            <button type="submit" id="savePasswordBtn"
                                    style="background: #2c3e50; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                                Update Password
                            </button>
                        </div>
                    </form>

                    <div class="danger-zone" style="background: #fff5f5; border: 1px solid #fed7d7; padding: 25px; border-radius: 12px;">
                        <h3 style="color: #c53030; font-size: 1.25rem; font-weight: bold; margin-bottom: 10px;">Danger Zone</h3>
                        <p style="color: #718096; margin-bottom: 20px; font-size: 0.9rem;">
                            Once you delete your account, there is no going back. Please be certain.
                        </p>
                        
                        <button type="button" onclick="confirmDelete()"
                                style="background: white; color: #c53030; border: 1px solid #c53030; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                            Delete Account
                        </button>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
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
                // If validation error (e.g. wrong password), show specific message
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
    </script>
</body>
</html>