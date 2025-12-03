<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
   public function index() { return view('settings'); }
    public function update(Request $request) {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        auth()->user()->update($request->only('name', 'phone', 'address'));
        return back()->with('success', 'Profile updated!');
    }
}
