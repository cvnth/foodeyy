<?php

namespace App\Http\Controllers\User;

use App\Models\Favorite; 
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    // 1. Show the Dashboard View
    public function index()
    {
        // Matches the file name: resources/views/UserDashboard.blade.php
        return view('UserDashboard'); 
    }

    // 2. Fetch Menu Data (API)
 public function getMenuJson()
{
    // 1. Get Items
    $items = MenuItem::with('category')
        ->where('is_available', 1)
        ->orderBy('created_at', 'desc')
        ->get();

    // 2. CHECK FAVORITES (Add this logic)
    $favoriteIds = \App\Models\Favorite::where('user_id', auth()->id())
        ->pluck('menu_item_id')
        ->toArray();

    // 3. Mark items as favorited
    $items->transform(function($item) use ($favoriteIds) {
        $item->is_favorited = in_array($item->id, $favoriteIds);
        return $item;
    });

    return response()->json($items);
}
}