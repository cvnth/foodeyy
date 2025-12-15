@extends('admin.AdminDashboard')

@section('title', 'Admin Profile')
@section('page-title', 'Admin Profile')

@section('page-content')
    <div class="profile-container">
        
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success" style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #10b981;">
                <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">check_circle</i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert-danger" style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ef4444;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-sections">
            
            <!-- 1. PERSONAL INFORMATION -->
            <div class="profile-section">
                <h3>Personal Information</h3>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" id="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="full-name">Full Name</label>
                        <input type="text" name="name" id="full-name" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" value="{{ ucfirst(Auth::user()->role ?? 'Administrator') }}" disabled style="background-color: #f3f4f6; color: #6b7280;">
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn-cancel">Reset</button>
                        <button type="submit" class="btn-save">Save Info</button>
                    </div>
                </form>
            </div>

            <!-- 2. CHANGE PASSWORD -->
            <div class="profile-section" style="margin-top: 30px;">
                <h3>Change Password</h3>
                
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required>
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save" style="background-color: #333;">Update Password</button>
                    </div>
                </form>
            </div>

            <!-- 3. SYSTEM CONFIGURATION (DELIVERY FEE) -->
            <div class="profile-section" style="margin-top: 30px;">
                <h3>System Configuration</h3>
                
                {{-- We point this form to the settings update route --}}
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="delivery_fee">Delivery Fee (₱)</label>
                        @php
                            // Fetch current fee directly from DB to display it
                            $currentFee = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'delivery_fee')->value('value') ?? '49.00';
                        @endphp
                        <input type="number" step="0.01" name="delivery_fee" id="delivery_fee" value="{{ $currentFee }}" required>
                        <small style="color: #666; display: block; margin-top: 5px;">This fee applies to all new delivery orders.</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save" style="background-color: #27ae60;">Update Fee</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection