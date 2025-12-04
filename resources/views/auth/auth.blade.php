<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foodeyy | Authentication</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>
    <div class="main-container">
        <div class="left-section">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Foodeyy Logo" />
            </div>
            <div class="left-content">
                <h2>Good Morning</h2>
                <p>Rise And Shine, It's Foodey Time</p>

                <div class="promo-box">
                    <img src="{{ asset('images/food.png') }}" alt="Promo Dish" />
                    <div class="promo-text">
                        <h3>Experience our delicious new dish</h3>
                        <p>30% OFF</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-section">
            <header>
                <nav>
                    <ul>
                        <li><a href="#about">ABOUT US</a></li>
                        <li><a href="{{ route('login') }}" id="loginBtn">LOGIN</a></li>
                        <li><a href="{{ route('register') }}" id="signupBtn">SIGN UP</a></li>
                    </ul>
                </nav>
            </header>

            <div class="form-popup active">
                <div class="form-box">
                    @yield('form')
                </div>
            </div>

        </div>
    </div>

    
    
    {{-- 🛑 INSERT THE NOTIFICATION SCRIPT HERE (RIGHT BEFORE </body>) 🛑 --}}
    <script>
        // This script reads the session flashes set by the AuthController

        @if (session('success'))
            showNotification("{{ session('success') }}", 'success');
        @endif

        @if (session('error'))
            showNotification("{{ session('error') }}", 'error');
        @endif

        // Display validation errors (e.g., "The email field is required")
        @if ($errors->any())
            showNotification("{{ $errors->first() }}", 'error'); 
        @endif

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
    </script>
    {{-- END NOTIFICATION SCRIPT --}}
</body>
</html>