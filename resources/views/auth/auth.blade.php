<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foodeyy | Authentication</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    
    <style>
        .toast-notification {
            position: fixed;
            top: 20px; /* Moved to top-right for better visibility */
            right: 20px;
            background: #ffffff;
            padding: 12px 24px; /* Smaller padding */
            border-radius: 50px; /* Pill shape */
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 99999;
            
            /* CRITICAL: Force compact size */
            width: auto; 
            max-width: 350px;
            height: auto;
            min-height: auto;
            
            /* Animation State: Hidden */
            transform: translateX(120%); 
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* Animation State: Visible */
        .toast-notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        /* Colors & Fonts */
        .toast-notification span {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .toast-notification i { font-size: 20px; }
        .toast-success i { color: #10b981; } /* Green */
        .toast-error i { color: #ef4444; }   /* Red */
        .toast-info i { color: #3b82f6; }    /* Blue */
    </style>
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

    <script>
        window.authConfig = {
            session: {
                success: "{{ session('success') }}",
                error: "{{ session('error') }}"
            },
            // Pass the first validation error if any exist
            errors: "{{ $errors->any() ? $errors->first() : '' }}"
        };
    </script>

    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>