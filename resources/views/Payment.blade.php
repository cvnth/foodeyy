<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Complete Payment</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="container">

        <!-- Same Sidebar & Header as Dashboard -->
        @include('components.user.UserSidebar')
      

        <main class="main-content">
            <div class="p-8">
                <div class="payment-container">

                    <div class="payment-header">
                        <h2>Complete Your Order</h2>
                        <p class="text-xl text-gray-600">Review your items and choose delivery options</p>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h3 class="text-2xl font-bold mb-6 text-orange-600">Order Summary</h3>
                        <div class="order-items space-y-4">
                            <div class="order-item">
                                <span>2 × Chicken Joy Bucket</span>
                                <span class="font-bold">₱1,598.00</span>
                            </div>
                            <div class="order-item">
                                <span>1 × Spicy Beef Ramen</span>
                                <span class="font-bold">₱420.00</span>
                            </div>
                            <div class="order-item">
                                <span>3 × Mango Graham Shake</span>
                                <span class="font-bold">₱405.00</span>
                            </div>
                        </div>
                        <div class="order-total">
                            <div class="summary-row"><span>Subtotal:</span><span>₱2,423.00</span></div>
                            <div class="summary-row delivery-fee"><span>Delivery Fee:</span><span>₱69.00</span></div>
                            <div class="summary-row summary-total"><span>Total:</span><span>₱2,492.00</span></div>
                        </div>
                    </div>

                    <!-- Delivery Options -->
                    <div class="delivery-options">
                        <div class="delivery-option active" data-option="delivery">
                            Delivery Dining Icon
                            <h4>Delivery</h4>
                            <p>Get your food delivered to your door</p>
                            <small>+₱69 delivery fee</small>
                        </div>
                        <div class="delivery-option" data-option="pickup">
                            Store Icon
                            <h4>Pick-up</h4>
                            <p>Save on delivery fee</p>
                            <small>No delivery fee</small>
                        </div>
                    </div>

                    <!-- Delivery Form -->
                    <div class="delivery-form active" id="delivery-form">
                        <h3 class="text-2xl font-bold mb-6">Delivery Address</h3>
                        <div class="form-group">
                            <label class="block font-semibold mb-2">Full Address</label>
                            <textarea class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100" rows="3" placeholder="Enter your complete delivery address">123 Example St., Brgy. San Antonio, Quezon City, Metro Manila</textarea>
                        </div>
                        <div class="form-group mt-4">
                            <label class="block font-semibold mb-2">Delivery Instructions (Optional)</label>
                            <textarea class="w-full p-4 border-2 border-gray-200 rounded-xl" rows="2" placeholder="e.g. Gate code, landmarks, etc.">Please ring the doorbell twice</textarea>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="payment-methods">
                        <div class="payment-method active" data-method="cod">
                            Money Icon
                            <p>Cash on Delivery</p>
                        </div>
                        <div class="payment-method" data-method="gcash">
                            Smartphone Icon
                            <p>GCash</p>
                        </div>
                    </div>

                    <!-- COD Info -->
                    <div class="payment-form active" id="cod-form">
                        <h3 class="text-2xl font-bold text-green-600 mb-4">Cash on Delivery Selected</h3>
                        <p class="text-lg">Please prepare exact change for the rider.</p>
                        <p class="text-lg mt-2">Estimated delivery: <strong>35-45 minutes</strong></p>
                    </div>

                    <!-- GCash Form (hidden by default) -->
                    <div class="payment-form" id="gcash-form">
                        <h3 class="text-2xl font-bold mb-6">Pay with GCash</h3>
                        <div class="form-group">
                            <label class="block font-semibold mb-2">GCash Mobile Number</label>
                            <input type="text" class="w-full p-4 border-2 border-gray-200 rounded-xl" placeholder="09XX XXX XXXX" value="0917 123 4567">
                        </div>
                        <p class="mt-4 text-gray-600">You will receive a payment request on your GCash app.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="payment-actions">
                        <a href="{{ route('cart.index') }}" class="back-to-cart">
                            Back to Cart
                        </a>
                        <button class="confirm-payment">
                            Place Order – ₱2,492.00
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>