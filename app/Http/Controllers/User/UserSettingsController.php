<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserSettingsController extends Controller
{
    public function index()
    {
        return view('settings');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return response()->json(['message' => 'Profile updated successfully!', 'user' => $user]);
    }

    // 1. CHANGE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password', // Laravel's built-in rule matches DB password
            'password' => 'required|string|min:8|confirmed', // Checks password_confirmation field
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password updated successfully!']);
    }

    // 2. DELETE ACCOUNT
    public function deleteAccount(Request $request)
    {
        // Require password confirmation before deleting
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        
        Auth::logout(); // Logout first
        $user->delete(); // Then delete

        return response()->json(['message' => 'Account deleted', 'redirect' => route('login')]);
    }
}