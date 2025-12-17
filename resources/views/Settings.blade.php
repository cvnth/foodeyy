@extends('layouts.user')

@section('title', 'Settings')

@section('content')
    <div class="p-8 pb-20"> 
        <div class="top-header mb-10">
            <h1 class="text-4xl font-bold" style="font-size: 2rem; color: #e67e22;">Account Settings</h1>
        </div>

        <div class="settings-container" style="max-width: 800px; margin: 0 auto;">
            
            <form id="profileForm" onsubmit="saveProfile(event)" style="margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 40px;">
                <h3 class="text-2xl font-bold mb-6" style="font-size: 1.5rem; margin-bottom: 20px; color: #2c3e50;">Profile Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label style="display:block; margin-bottom:8px; font-weight:600;">Full Name</label>
                        <input type="text" id="name" value="{{ auth()->user()->name }}" required
                               style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="display:block; margin-bottom:8px; font-weight:600;">Email Address</label>
                        <input type="email" id="email" value="{{ auth()->user()->email }}" required
                               style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="display:block; margin-bottom:8px; font-weight:600;">Phone Number</label>
                        <input type="tel" id="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="+63..."
                               style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label style="display:block; margin-bottom:8px; font-weight:600;">Delivery Address</label>
                    <textarea id="address" rows="3" placeholder="Enter complete address"
                              style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px; font-family: inherit;">{{ auth()->user()->address ?? '' }}</textarea>
                </div>

                <div class="text-right mt-6" style="margin-top: 20px; text-align: right;">
                    <button type="submit" id="saveProfileBtn"
                            style="background: #e67e22; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                        Save Profile
                    </button>
                </div>
            </form>

            <form id="passwordForm" onsubmit="changePassword(event)" style="margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 40px;">
                <h3 class="text-2xl font-bold mb-6" style="font-size: 1.5rem; margin-bottom: 20px; color: #2c3e50;">Security</h3>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:8px; font-weight:600;">Current Password</label>
                    <input type="password" id="current_password" required placeholder="••••••••"
                           style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label style="display:block; margin-bottom:8px; font-weight:600;">New Password</label>
                        <input type="password" id="new_password" required placeholder="Min. 8 characters"
                               style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                    </div>
                    <div class="form-group">
                        <label style="display:block; margin-bottom:8px; font-weight:600;">Confirm Password</label>
                        <input type="password" id="new_password_confirmation" required placeholder="Confirm new password"
                               style="width:100%; padding:12px; border:2px solid #eee; border-radius:10px;">
                    </div>
                </div>

                <div class="text-right mt-6" style="margin-top: 20px; text-align: right;">
                    <button type="submit" id="savePasswordBtn"
                            style="background: #2c3e50; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                        Update Password
                    </button>
                </div>
            </form>

            <div class="danger-zone" style="background: #fff5f5; border: 1px solid #fed7d7; padding: 25px; border-radius: 12px;">
                <h3 style="color: #c53030; font-size: 1.25rem; font-weight: bold; margin-bottom: 10px;">Danger Zone</h3>
                <p style="color: #718096; margin-bottom: 20px; font-size: 0.9rem;">
                    Once you delete your account, there is no going back. Please be certain.
                </p>
                
                <button type="button" onclick="confirmDelete()"
                        style="background: white; color: #c53030; border: 1px solid #c53030; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    Delete Account
                </button>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/user-settings.js') }}"></script>
@endpush