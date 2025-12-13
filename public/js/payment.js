// public/js/payment.js

let currentDeliveryType = 'delivery';
let currentPaymentMethod = 'cod';

// Access variables passed from Blade
const SUBTOTAL = window.appConfig.subtotal;
const DELIVERY_FEE = window.appConfig.deliveryFee;

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
        btn.innerHTML = 'Try Again';
    });
}