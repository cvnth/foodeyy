@extends('admin.AdminDashboard')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('page-content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Manage Users</h2>
        <div class="search-box" style="position: relative; display: flex; align-items: center;">
            <i class="material-icons" style="position: absolute; left: 10px; color: #888;">search</i>
            <input type="text" id="search-users" placeholder="Search by name, email..." 
                   style="padding: 8px 8px 8px 35px; border: 1px solid #ddd; border-radius: 5px; width: 300px; outline: none;">
        </div>
    </div>

    <div class="filter-buttons" style="margin-bottom: 15px; display: flex; gap: 10px;">
        <button class="filter-btn active" style="padding: 6px 15px; border: 1px solid #333; background: #333; color: white; border-radius: 20px; cursor: pointer; font-size: 0.9rem;">
            All Users
        </button>
    </div>

    <div class="users-table-container" style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 20px; overflow-x: auto;">
        <table class="users-table" style="width: 100%; border-collapse: collapse; min-width: 800px;">
            <thead>
                <tr style="background: #f8f9fa; color: #666; font-weight: 600; border-bottom: 2px solid #eee;">
                    <th style="text-align: left; padding: 12px 15px;">User</th>
                    <th style="text-align: left; padding: 12px 15px;">Email</th>
                    <th style="text-align: left; padding: 12px 15px;">Phone</th>
                    <th style="text-align: center; padding: 12px 15px;">Orders</th>
                    <th style="text-align: left; padding: 12px 15px;">Joined Date</th>
                    <th style="text-align: left; padding: 12px 15px;">Status</th>
                    <th style="text-align: left; padding: 12px 15px;">Actions</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                <tr><td colspan="7" style="text-align:center; padding:20px; color:#666;">Loading users...</td></tr>
            </tbody>
        </table>

        <div class="pagination" id="pagination" style="margin-top: 20px; display: flex; gap: 5px; justify-content: center;">
            </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/admin-users.js') }}"></script>
@endpush