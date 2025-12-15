@extends('admin.AdminDashboard')

@section('title', 'Manage Orders')
@section('page-title', 'Manage Orders')

@section('page-content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="table-header">
        <h2>Manage Orders</h2>
        <div class="search-box">
            <i class="material-icons">search</i>
            <input type="text" id="search-orders" placeholder="Search orders (ID or Name)..." onkeyup="debounceSearch()">
        </div>
    </div>
    
    <div class="orders-filter">
        <button class="filter-btn active" onclick="filterOrders('all', this)">All Orders</button>
        <button class="filter-btn" onclick="filterOrders('pending', this)">Pending</button>
        <button class="filter-btn" onclick="filterOrders('preparing', this)">Preparing</button>
        <button class="filter-btn" onclick="filterOrders('ready', this)">Ready</button>
        <button class="filter-btn" onclick="filterOrders('delivered', this)">Delivered</button>
        <button class="filter-btn" onclick="filterOrders('cancelled', this)">Cancelled</button>
    </div>

    <div class="orders-table-container">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Payment Info</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="orders-table-body"></tbody>
        </table>

        <div class="pagination" id="pagination" style="margin-top: 20px; display: flex; gap: 5px; justify-content: center;"></div>
    </div>

    <div class="modal-overlay" id="receiptModal" onclick="closeReceipt(event)">
        <div class="receipt-box">
            <button class="close-modal-btn" onclick="closeReceiptBtn()">×</button>
            
            <div class="receipt-header">
                <h2>ORDER RECEIPT</h2>
                <div class="receipt-meta">
                    <p id="r-id">#ORD-000000</p>
                    <p id="r-customer" style="color:#e67e22; font-weight:bold;">Customer Name</p>
                    <p id="r-date">Date</p>
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
                <p>Status: <span id="r-status" style="font-weight:bold; color:#333;">PENDING</span></p>
                <p>Payment: <span id="r-method" style="text-transform: uppercase;">COD</span></p>
                
                <div id="r-address-box" style="margin-top:10px; padding:10px; background:#f9f9f9; border-radius:5px; text-align:left;">
                    <div style="margin-bottom: 5px;">
                        <strong>Delivery Address:</strong><br>
                        <span id="r-address">N/A</span>
                    </div>
                    
                    <div id="r-landmark-container" style="margin-bottom: 5px; display:none;">
                        <strong>Landmark:</strong><br>
                        <span id="r-landmark" style="color:#e67e22;">N/A</span>
                    </div>

                    <div>
                        <strong>Contact Number:</strong><br>
                        <span id="r-phone">N/A</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/admin-orders.js') }}"></script>
@endsection