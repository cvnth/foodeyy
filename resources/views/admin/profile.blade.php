@extends('admin.AdminDashboard')

@section('title', 'Admin Profile')
@section('page-title', 'Admin Profile')

@section('page-content')
    <div class="profile-container">
        <div class="profile-sections">
            <div class="profile-section">
                <h3>Personal Information</h3>
                <form id="profile-form">
                    <div class="form-group">
                        <label for="full-name">Full Name</label>
                        {{-- Use Auth to pre-fill data from the database --}}
                        <input type="text" id="full-name" value="{{ Auth::user()->name ?? 'Admin User' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" value="{{ Auth::user()->email ?? 'admin@foodhub.com' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" value="{{ Auth::user()->phone ?? '+63 912 345 6789' }}">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" disabled>
                            {{-- Use Auth::user()->role if you have a role column --}}
                            <option>{{ Auth::user()->role ?? 'Administrator' }}</option>
                            <option>Manager</option>
                            <option>Staff</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="resetForm()">Cancel</button>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/admin-profile.js') }}"></script>
@endpush