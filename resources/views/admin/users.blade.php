@extends('admin.AdminDashboard')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('page-content')
    <div class="users-header">
        <h2>Manage Users</h2>
    </div>

    <div class="filter-buttons">
        <button class="filter-btn active" data-status="all">All Users</button>
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
<script src="{{ asset('js/admin/admin-users.js') }}"></script>
@endpush