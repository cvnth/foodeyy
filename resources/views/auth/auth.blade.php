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