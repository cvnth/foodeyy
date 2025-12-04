@extends('auth.auth')

@section('form')

{{-- CSS FOR INVISIBLE SCROLLING --}}
<style>
    .form-scroll-container {
        max-height: 85vh; /* Limits height */
        overflow-y: auto; /* Enables vertical scrolling */
        width: 100%;
        
        /* Hide scrollbar for IE, Edge and Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .form-scroll-container::-webkit-scrollbar {
        display: none;
    }
</style>

    <div class="form-scroll-container">
        <img src="{{ asset('images/logo.png') }}" class="form-logo" alt="Foodeyy Logo" />
        <h2>Sign up</h2>

        {{-- Success & Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="scrollable-form">
            @csrf

            <div class="input-group">
                <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required />
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required />
            </div>

            <div class="input-group">
                <input type="text" name="phone" placeholder="Cellphone Number (e.g. 09171234567)" 
                       value="{{ old('phone') }}" required />
            </div>

            <div class="input-group">
                <input type="text" name="address" placeholder="Complete Address (Street, Barangay, City)" 
                       value="{{ old('address') }}" required />
            </div>

            <div class="input-group password-field">
                <input type="password" name="password" placeholder="Create Password" required />
            </div>

            <div class="input-group password-field">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
            </div>

            <label class="terms-checkbox">
                <input type="checkbox" name="terms" required />
                <span>I agree to the <a href="#" target="_blank">Terms and Conditions</a></span>
            </label>

            <button type="submit" class="btn">Create Account</button>

            <p class="switch-text">
                Already have an account?
                <a href="{{ route('login') }}">Login here</a>
            </p>
        </form>
    </div>
@endsection