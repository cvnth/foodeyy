@extends('auth.auth')

@section('form')

    <img src="{{ asset('images/logo.png') }}" class="form-logo" alt="Foodeyy Logo" />

    <h2>Login</h2>

    {{-- Show success or error flash messages --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


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
