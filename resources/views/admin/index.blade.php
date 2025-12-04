@extends('admin.AdminDashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('page-content')
    {{-- STATS GRID --}}
    <div class="admin-stats-grid">
        <div class="admin-stat-card revenue">
            <h3>₱{{ number_format($revenue, 2) }}</h3>
            <p>Total Revenue</p>
            <div class="trend up">
                <i class="material-icons">monetization_on</i>
                <span>Collected</span>
            </div>
        </div>

        <div class="admin-stat-card orders">
            <h3>{{ $totalOrders }}</h3>
            <p>Total Orders</p>
            <div class="trend up">
                <i class="material-icons">shopping_basket</i>
                <span>All time</span>
            </div>
        </div>

        <div class="admin-stat-card pending">
            <h3>{{ $pendingOrders }}</h3>
            <p>Pending Orders</p>
            <div class="trend {{ $pendingOrders > 0 ? 'down' : 'up' }}">
                <i class="material-icons">pending_actions</i>
                <span>Action needed</span>
            </div>
        </div>

        <div class="admin-stat-card users">
            <h3>{{ $totalUsers }}</h3>
            <p>Total Users</p>
            <div class="trend up">
                <i class="material-icons">group</i>
                <span>Registered</span>
            </div>
        </div>
    </div>

    {{-- RECENT ORDERS TABLE --}}
    <div class="recent-orders">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3>Recent Orders</h3>
            <a href="{{ route('admin.orders') }}" style="color: #e67e22; text-decoration: none; font-size: 0.9rem;">View All</a>
        </div>

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
                @forelse($recentOrders as $order)
                    @php
                        // Badge Logic
                        $badgeClass = match($order->status) {
                            'delivered' => 'status-delivered',
                            'cancelled' => 'status-cancelled',
                            'pending'   => 'status-pending',
                            default     => 'status-preparing'
                        };
                    @endphp
                    <tr>
                        <td>#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->user ? $order->user->name : 'Guest' }}</td>
                        <td>{{ $order->items->sum('quantity') }} items</td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="status-badge {{ $badgeClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                       <td>
                            <a href="{{ route('admin.orders') }}" class="btn-view" title="View Details">
                                <i class="material-icons">visibility</i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #888;">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- INLINE CSS FOR BADGES (Ensure this is in your CSS file or here) --}}
    <style>
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: bold; color: white; text-transform: uppercase; }
        .status-pending { background-color: #f59e0b; }   /* Orange */
        .status-preparing { background-color: #3b82f6; } /* Blue */
        .status-delivered { background-color: #10b981; } /* Green */
        .status-cancelled { background-color: #ef4444; } /* Red */
        
        .btn-view:hover i { color: #e67e22 !important; }
    </style>
@endsection