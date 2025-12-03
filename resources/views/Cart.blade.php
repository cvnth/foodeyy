<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FoodHub - My Cart</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
</head>
<body>
    <div class="container">

        <!-- Same Sidebar as Dashboard -->
        @include('components.user.UserSidebar')

        <main class="main-content">

            <!-- Same Header Style -->
            @include('components.user.UserHeader')

            <!-- CART CONTENT -->
            <div class="cart-page p-8">

                <div class="cart-summary mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Your Cart (6 items)</h2>
                    <button class="clear-cart-btn">Clear Cart</button>
                </div>

                <div class="cart-items">

                    <!-- Item 2 -->
                    <div class="cart-item">
                        <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=500" alt="Ramen">
                        <div class="item-details">
                            <h3>Spicy Beef Ramen</h3>
                            <p>Ramen Nagi • Trinoma</p>
                            <p class="price">₱420.00</p>
                        </div>
                        <div class="quantity">
                            <button>-</button>
                            <span>1</span>
                            <button>+</button>
                        </div>
                        <div class="total">₱420.00</div>
                        <button class="remove-item">Remove</button>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row"><span>Subtotal</span><span>₱2,018.00</span></div>
                    <div class="summary-row"><span>Delivery Fee</span><span>₱69.00</span></div>
                    <div class="summary-row"><span>Tax</span><span>₱242.00</span></div>
                    <div class="summary-total"><span>Total</span><span>₱2,329.00</span></div>
                    <a href="{{ route('payment.sheet') }}" class="checkout-btn">
                             Proceed to Checkout
                    </a>

                </div>
            </div>
        </main>
    </div>
</body>
</html>