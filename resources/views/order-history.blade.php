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
            @include('components.user.UserHeader')

            <!-- ORDER HISTORY PAGE – uses only existing CSS classes -->
            <div class="p-8">

                <!-- Page Title – reusing top-header style -->
                <div class="top-header mb-8">
                    <h1 class="text-4xl font-bold">Your Order History</h1>
                </div>

                <div class="space-y-8">

                    <!-- ==== ORDER CARD (reuses existing .order-card from your CSS) ==== -->
                    <div class="order-card">
                        <!-- Image preview row (optional – you already have this in dashboard.css) -->
                        <div class="order-card-image">
                            <img src="https://images.unsplash.com/photo-1565299624946-b28f40dc2212?w=800" alt="Order">
                            <div class="order-status-badge status-delivered">Delivered</div>
                        </div>

                        <div class="order-card-content">
                            <div class="order-card-header">
                                <div>
                                    <div class="order-id">#ORD-2025-0481</div>
                                    <div class="order-date">Delivered on Nov 18, 2025</div>
                                </div>
                                <div class="order-total text-2xl font-bold text-orange-600">₱987.00</div>
                            </div>

                            <div class="order-items-preview mt-4">
                                <div class="order-item-preview">
                                    <span class="order-item-name">2× Chicken Joy Bucket</span>
                                    <span class="order-item-price">₱1,598.00</span>
                                </div>
                                <div class="order-item-preview">
                                    <span class="order-item-name">1× Jolly Spaghetti</span>
                                    <span class="order-item-price">₱55.00</span>
                                </div>
                            </div>

                            <div class="order-card-footer">
                                <div class="order-meta">
                                    <div class="order-meta-item">
                                        <span class="material-icons text-sm">store</span>
                                        <span>Jollibee • SM North EDSA</span>
                                    </div>
                                </div>
                                <button class="reorder-btn">
                                    <span class="material-icons">refresh</span>
                                    Reorder
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End of one order card – duplicate as needed -->

                </div>
            </div>
        </main>
    </div>
</body>
</html>