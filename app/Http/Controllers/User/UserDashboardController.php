<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    // 1. Show Dashboard View
    public function index()
    {
        return view('UserDashboard'); 
    }

    // 2. Fetch Menu Data (Read-Only)
    public function getMenuJson()
    {
        // Get all menu items
        $items = MenuItem::with('category')
            ->where('is_available', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get list of IDs favorited by this user
        $favoriteIds = [];
        if(Auth::check()) {
            $favoriteIds = Favorite::where('user_id', Auth::id())
                ->pluck('menu_item_id')
                ->toArray();
        }

        // Mark items as favorited (for the red heart icon)
        $items->transform(function($item) use ($favoriteIds) {
            $item->is_favorited = in_array($item->id, $favoriteIds);
            return $item;
        });

        return response()->json($items);
    }
}