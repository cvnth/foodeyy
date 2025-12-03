@extends('admin.AdminDashboard')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('page-content')
    <div class="users-header">
        <h2>Manage Users</h2>
        <div class="search-box">
            <i class="material-icons">search</i>
            <input type="text" id="search-users" placeholder="Search users...">
        </div>
    </div>

    <div class="filter-buttons">
        <button class="filter-btn active" data-status="all">All Users</button>
        <button class="filter-btn" data-status="active">Active</button>
        <button class="filter-btn" data-status="inactive">Inactive</button>
        <button class="filter-btn" data-status="suspended">Suspended</button>
    </div>

    <div class="users-table-container">
        <table class="users-table">
            <thead>
                <tr>
                    <th data-sort="name">User</th>
                    <th data-sort="email">Email</th>
                    <th>Phone</th>
                    <th data-sort="orders">Orders</th>
                    <th data-sort="joined">Joined Date</th>
                    <th data-sort="status">Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                </tbody>
        </table>

        <div class="pagination" id="pagination">
            </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/admin-users.js') }}"></script>
@endpush