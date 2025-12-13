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

    <style>
        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 9999;
            display: none; justify-content: center; align-items: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.active { display: flex; opacity: 1; }
        .receipt-box {
            background: white; width: 400px; padding: 25px; border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); transform: translateY(20px); transition: transform 0.3s ease;
            position: relative;
        }
        .modal-overlay.active .receipt-box { transform: translateY(0); }
        .close-modal-btn { position: absolute; top: 10px; right: 15px; font-size: 24px; border: none; background: none; cursor: pointer; }
        
        .receipt-header { text-align: center; border-bottom: 2px dashed #eee; padding-bottom: 15px; margin-bottom: 15px; }
        .receipt-header h2 { margin: 0; color: #333; letter-spacing: 2px; }
        .receipt-meta { font-size: 0.9rem; color: #666; margin-top: 5px; line-height: 1.4; }
        
        .receipt-items { max-height: 250px; overflow-y: auto; margin-bottom: 15px; }
        .receipt-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem; }
        
        .receipt-divider { border-top: 2px dashed #eee; margin: 10px 0; }
        .receipt-summary .row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 0.9rem; color: #666; }
        .receipt-total { display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem; margin-top: 10px; }

        /* Table Buttons */
        .action-container { display: flex; gap: 5px; align-items: center; }
        .btn-view { 
            background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; 
            width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; 
        }
        .btn-view:hover { background: #4338ca; color: white; }
        
        /* Badges */
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: bold; color: white; }
        .status-pending { background-color: #f59e0b; }
        .status-preparing { background-color: #3b82f6; }
        .status-ready { background-color: #8b5cf6; }
        .status-delivered { background-color: #10b981; }
        .status-cancelled { background-color: #ef4444; }

        /* Payment Badges */
        .pay-badge {
            border: none; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; cursor: pointer; text-transform: uppercase; transition: transform 0.1s;
        }
        .pay-badge:active { transform: scale(0.95); }
        .pay-paid { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
        .pay-unpaid { background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
        
        .page-btn { padding: 5px 10px; border: 1px solid #ddd; background: white; cursor: pointer; }
        .page-btn.active { background: #e67e22; color: white; border-color: #e67e22; }
    </style>

    <script src="{{ asset('js/admin-orders.js') }}"></script>
@endsection