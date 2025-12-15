<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Order History</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            <div class="orders-container">
                <div class="top-header mb-6">
                    <h1>Your Order History</h1>
                </div>

                <div class="orders-grid">
                    @forelse($orders as $order)
                        @php
                            $statusClass = match($order->status) {
                                'completed' => 'status-delivered',
                                'cancelled' => 'status-cancelled',
                                default => 'status-pending'
                            };
                            $statusText = ucfirst($order->status);
                            $firstItem = $order->items->first();
                            $imageUrl = $firstItem && $firstItem->menuItem ? $firstItem->menuItem->image_url : null;
                        @endphp

                        <div class="order-card" onclick="showReceipt(this)" 
                             data-json="{{ json_encode($order, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}">
                            
                            <div class="order-card-image">
                                <img src="{{ $imageUrl ?? 'https://via.placeholder.com/500?text=Order' }}" 
                                     alt="Order #{{ $order->id }}"
                                     onerror="this.src='https://via.placeholder.com/500?text=No+Image'">
                                <div class="order-status-badge {{ $statusClass }}">{{ $statusText }}</div>
                            </div>

                            <div class="order-card-content">
                                <div class="order-card-header">
                                    <div>
                                        <div class="order-id">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <div class="order-date">
                                            {{ $order->created_at->format('M d, Y • h:i A') }}
                                        </div>
                                    </div>
                                    <div class="order-total">₱{{ number_format($order->total_amount, 2) }}</div>
                                </div>

                                <div class="order-items-preview">
                                    @foreach($order->items->take(2) as $item)
                                        <div class="order-item-preview">
                                            <span class="order-item-name">
                                                {{ $item->quantity }}× {{ $item->menuItem->name ?? 'Unknown Item' }}
                                            </span>
                                            <span class="order-item-price">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 2)
                                        <div class="more-items">+ {{ $order->items->count() - 2 }} more items</div>
                                    @endif
                                </div>

                                <div class="order-card-footer">
                                    <div class="order-meta">
                                        <div class="order-meta-item">
                                            <i class="material-icons">
                                                {{ $order->delivery_type == 'delivery' ? 'delivery_dining' : 'store' }}
                                            </i>
                                            <span style="text-transform: capitalize">{{ $order->delivery_type }}</span>
                                        </div>
                                        <div class="order-meta-item" style="margin-left: 10px;">
                                            <i class="material-icons">payments</i>
                                            <span style="text-transform: uppercase;">{{ $order->payment_method }}</span>
                                        </div>
                                    </div>
                                    <span style="font-size: 0.8rem; color: #888;">Click to view receipt</span>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div style="grid-column: 1 / -1;" class="empty-state">
                            <i class="material-icons">receipt_long</i>
                            <h3>No orders yet</h3>
                            <a href="{{ route('user.dashboard') }}" class="btn-browse">Browse Menu</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="receiptModal" onclick="closeReceipt(event)">
        <div class="receipt-box">
            <button class="close-modal-btn" onclick="closeReceiptBtn()">×</button>
            
            <div class="receipt-header">
                <h2>FOODEYY</h2>
                <div class="receipt-meta">
                    <p id="r-id">#ORD-000000</p>
                    <p id="r-date">Oct 12, 2025 • 10:30 AM</p>
                    <p id="r-status" style="font-weight: bold; margin-top: 5px;">COMPLETED</p>
                </div>
            </div>

            <div class="receipt-items" id="r-items-list"></div>

            <div class="receipt-divider"></div>

            <div class="receipt-summary">
                <div class="row">
                    <span>Subtotal</span>
                    <span id="r-subtotal">₱0.00</span>
                </div>
                <div class="row">
                    <span>Delivery Fee</span>
                    <span id="r-delivery">₱0.00</span>
                </div>
                <div class="receipt-total">
                    <span>TOTAL</span>
                    <span id="r-total">₱0.00</span>
                </div>
            </div>

            <div style="margin-top: 20px; text-align: center; font-size: 0.8rem; color: #aaa;">
                <p>Payment Method: <span id="r-method" style="text-transform: uppercase; color: #333;">COD</span></p>
                <p>Thank you for ordering!</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/order-history.js') }}"></script>
</body>
</html>