<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Show register page
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle login — UNCHANGED (email + password only)
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            return Auth::user()->is_admin
                ? redirect()->intended('/admin/dashboard')
                : redirect()->intended('/user/dashboard');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    // Handle registration — NOW WITH PHONE & ADDRESS
    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required|string|regex:/^09[0-9]{9}$/|size:11|unique:users,phone',
            'address'               => 'required|string|max:500',
            'password'              => 'required|min:8|confirmed',
            'terms'                 => 'required|accepted',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'address'           => $request->address,
            'password'          => Hash::make($request->password),
            'is_admin'          => false,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/user/dashboard')
            ->with('success', 'Welcome, ' . $user->name . '! Your account has been created successfully.');
    }

    // Admin Dashboard — protected
    public function adminDashboard()
    {
        if (!Auth::user()->is_admin) {
            return redirect('/user/dashboard')
                ->with('error', 'Access denied. Administrators only.');
        }

        return view('admin.AdminDashboard');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'You have been logged out successfully.');
    }
}