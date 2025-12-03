
@extends('admin.AdminDashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('page-content')
    {{-- This is the unique HTML content for the Dashboard --}}
    <div class="admin-stats-grid">
        <div class="admin-stat-card revenue">
            <h3>₱0</h3>
            <p>Total Revenue</p>
            <div class="trend up"><i class="material-icons">arrow_upward</i><span>Calculating...</span></div>
        </div>
        <div class="admin-stat-card orders">
            <h3>0</h3>
            <p>Total Orders</p>
            <div class="trend up"><i class="material-icons">arrow_upward</i><span>Calculating...</span></div>
        </div>
        <div class="admin-stat-card pending">
            <h3>0</h3>
            <p>Pending Orders</p>
            <div class="trend down"><i class="material-icons">arrow_downward</i><span>Calculating...</span></div>
        </div>
        <div class="admin-stat-card users">
            <h3>0</h3>
            <p>Total Users</p>
            <div class="trend up"><i class="material-icons">arrow_upward</i><span>Calculating...</span></div>
        </div>
    </div>

    <div class="recent-orders">
        <h3>Recent Orders</h3>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="recent-orders-table">
                </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/admin-dashboard.js') }}"></script>
@endpush