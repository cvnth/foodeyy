<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate Total Revenue (Only count 'delivered' or 'completed' orders)
        // Check if your orders table uses 'delivered' or 'completed' status strings
        $revenue = Order::whereIn('status', ['delivered', 'completed'])->sum('total_amount');

        // 2. Total Orders Count
        $totalOrders = Order::count();

        // 3. Pending Orders Count
        $pendingOrders = Order::where('status', 'pending')->count();

        // 4. Total Users (FIXED: Removed 'role' check because column doesn't exist)
        $totalUsers = User::count(); 

        // 5. Get Recent 5 Orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'revenue', 
            'totalOrders', 
            'pendingOrders', 
            'totalUsers', 
            'recentOrders'
        ));
    }
}