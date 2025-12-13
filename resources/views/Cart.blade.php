<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FoodHub - My Cart</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=2" />
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            @include('components.user.UserHeader')

            <div class="cart-page">
                
                <div class="cart-header">
                    <h2>Your Cart <span id="cartCount" style="font-size: 0.9rem; color: #888; font-weight: normal;">(Loading...)</span></h2>
                    <button class="clear-cart-btn" onclick="clearCart()">Clear Cart</button>
                </div>

                <div class="cart-items-container" id="cartItemsList">
                    <p style="padding: 20px; text-align: center; color: #888;">Loading cart...</p>
                </div>

                <div class="order-summary-box">
                    <h3>Order Summary</h3>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="summarySubtotal">₱0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee</span>
                        <span id="summaryDelivery">₱{{ number_format($deliveryFee ?? 49, 2) }}</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="summaryTotal">₱0.00</span>
                    </div>

                    <a href="{{ route('payment.sheet') }}" class="checkout-btn">
                        Proceed to Checkout
                    </a>
                </div>

            </div>
        </main>
    </div>

    <script>
        window.appConfig = {
            deliveryFee: {{ $deliveryFee ?? 49.00 }}
        };
    </script>

    <script src="{{ asset('js/cart.js') }}"></script>
</body>
</html>