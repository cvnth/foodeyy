@extends('admin.AdminDashboard')

@section('title', 'Manage Orders')
@section('page-title', 'Manage Orders')

@section('page-content')
    <div class="table-header">
        <h2>Manage Orders</h2>
        <div class="search-box">
            <i class="material-icons">search</i>
            <input type="text" id="search-orders" placeholder="Search orders...">
        </div>
    </div>
    
    <div class="orders-filter">
        <button class="filter-btn active" data-status="all">All Orders</button>
        <button class="filter-btn" data-status="preparing">Preparing</button>
        <button class="filter-btn" data-status="ready">Ready</button>
        <button class="filter-btn" data-status="delivered">Delivered</button>
        <button class="filter-btn" data-status="picked-up">Picked Up</button>
    </div>

    <div class="orders-table-container">
        <table class="orders-table">
            <thead>
                <tr>
                    <th data-sort="id">Order ID <i class="material-icons">unfold_more</i></th>
                    <th data-sort="customer">Customer</th>
                    <th data-sort="items">Items</th>
                    <th data-sort="amount">Amount</th>
                    <th data-sort="status">Status</th>
                    <th data-sort="date">Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
                </tbody>
        </table>

        <div class="pagination" id="pagination">
            </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/admin-orders.js') }}"></script>
@endpush