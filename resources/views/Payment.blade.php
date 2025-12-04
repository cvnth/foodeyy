<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Complete Payment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <style>
        /* Small helper to show/hide forms */
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            <div class="payment-page-container">
                <div class="payment-main">
                    <div class="payment-header">
                        <h2>Complete Your Order</h2>
                        <p>Review details below</p>
                    </div>

                    <div class="delivery-options">
                        <div class="delivery-option active" onclick="setDeliveryType('delivery')" id="opt-delivery">
                            <i class="material-icons">delivery_dining</i>
                            <h4>Delivery</h4>
                            <p>Get your food delivered</p>
                            <!-- Dynamic Fee Display -->
                            <small>+₱{{ number_format($deliveryFee, 2) }} fee</small>
                        </div>
                        <div class="delivery-option" onclick="setDeliveryType('pickup')" id="opt-pickup">
                            <i class="material-icons">store</i>
                            <h4>Pick-up</h4>
                            <p>Pick up at store</p>
                            <small>No fee</small>
                        </div>
                    </div>

                    <div class="delivery-form" id="delivery-form">
                        <h3 class="section-title">Delivery Address</h3>
                        <div class="form-group">
                            <label>Full Address</label>
                            <textarea id="addressInput" rows="3" placeholder="Street, Barangay, City..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Instructions (Optional)</label>
                            <textarea id="instructionsInput" rows="2" placeholder="Gate code, landmarks..."></textarea>
                        </div>
                    </div>

                    <div class="payment-methods">
                        <div class="payment-method active" onclick="setPaymentMethod('cod')" id="meth-cod">
                            <i class="material-icons">money</i>
                            <p>Cash on Delivery</p>
                        </div>
                        <div class="payment-method" onclick="setPaymentMethod('gcash')" id="meth-gcash">
                            <i class="material-icons">smartphone</i>
                            <p>GCash</p>
                        </div>
                    </div>

                    <div class="payment-actions">
                        <a href="{{ route('cart.index') }}" class="back-to-cart">Back to Cart</a>
                        
                        <button class="confirm-payment" onclick="placeOrder()">
                            <i class="material-icons">shopping_cart_checkout</i>
                            <!-- Initial Button Text Calculation -->
                            <span id="btnText">Place Order – ₱{{ number_format($subtotal + $deliveryFee, 2) }}</span>
                        </button>
                    </div>
                </div>

                <div class="payment-sidebar">
                    <h3 class="section-title">Order Summary</h3>
                    
                    <div class="order-items-list">
                        @foreach($cartItems as $item)
                        <div class="order-item-row">
                            <span class="item-name">{{ $item->quantity }} × {{ $item->menuItem->name }}</span>
                            <span class="item-price">₱{{ number_format($item->quantity * $item->menuItem->price, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <!-- Dynamic Fee ID for JS updating -->
                        <span id="summaryDeliveryFee">₱{{ number_format($deliveryFee, 2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="summaryTotal">₱{{ number_format($subtotal + $deliveryFee, 2) }}</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Data passed from Controller
        const SUBTOTAL = {{ $subtotal }};
        // INTEGRATED: Dynamic Fee from Database
        const DELIVERY_FEE = {{ $deliveryFee }}; 
        
        let currentDeliveryType = 'delivery';
        let currentPaymentMethod = 'cod';

        function setDeliveryType(type) {
            currentDeliveryType = type;

            // UI Updates
            document.getElementById('opt-delivery').classList.toggle('active', type === 'delivery');
            document.getElementById('opt-pickup').classList.toggle('active', type === 'pickup');
            
            // Show/Hide Address Form
            const form = document.getElementById('delivery-form');
            form.style.display = type === 'delivery' ? 'block' : 'none';

            // Calculate Totals logic
            const fee = type === 'delivery' ? DELIVERY_FEE : 0;
            const total = SUBTOTAL + fee;

            // Update Text
            document.getElementById('summaryDeliveryFee').textContent = '₱' + fee.toFixed(2);
            document.getElementById('summaryTotal').textContent = '₱' + total.toFixed(2);
            document.getElementById('btnText').textContent = 'Place Order – ₱' + total.toFixed(2);
        }

        function setPaymentMethod(method) {
            currentPaymentMethod = method;
            document.getElementById('meth-cod').classList.toggle('active', method === 'cod');
            document.getElementById('meth-gcash').classList.toggle('active', method === 'gcash');
        }

        function placeOrder() {
            const btn = document.querySelector('.confirm-payment');
            const address = document.getElementById('addressInput').value;
            const instructions = document.getElementById('instructionsInput').value;

            // Validation
            if(currentDeliveryType === 'delivery' && !address.trim()) {
                alert('Please enter your delivery address.');
                return;
            }

            // Disable button
            btn.innerHTML = 'Processing...';
            btn.disabled = true;

            // AJAX Request
            fetch('/user/order/place', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    delivery_type: currentDeliveryType,
                    payment_method: currentPaymentMethod,
                    address: address,
                    instructions: instructions
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.redirect_url) {
                    alert('Order Placed Successfully!');
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error: ' + data.message);
                    btn.disabled = false;
                    btn.innerHTML = 'Try Again';
                }
            })
            .catch(err => {
                console.error(err);
                alert('Something went wrong.');
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>