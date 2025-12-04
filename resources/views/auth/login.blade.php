@extends('auth.auth')

@section('form')

    <img src="{{ asset('images/logo.png') }}" class="form-logo" alt="Foodeyy Logo" />

    <h2>Login</h2>



    {{-- LOGIN FORM --}}
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="input-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="admin@admin.com" 
                value="{{ old('email') }}" 
                required 
            />
        </div>

        <div class="input-group password-field">
            <label for="password">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="••••••••" 
                required 
            />
        </div>

        <button type="submit" class="btn">Login</button>
    </form>

@endsection
