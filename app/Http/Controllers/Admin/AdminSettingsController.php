<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSettingsController extends Controller
{
    public function index()
    {
        // Fetch current settings
        $settings = DB::table('settings')->pluck('value', 'key');
        
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'delivery_fee' => 'required|numeric|min:0',
        ]);

        // Update Delivery Fee
        DB::table('settings')->updateOrInsert(
            ['key' => 'delivery_fee'],
            ['value' => $request->delivery_fee]
        );

        // You can add more settings here easily later (e.g., tax rate, site name)

        return back()->with('success', 'Settings updated successfully!');
    }
}