<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        // FIX: Removed 'user.' prefix because the file is resources/views/favorites.blade.php
        return view('favorites'); 
    }

    public function getFavoritesJson()
    {
        // Fetch favorites for logged-in user
        // We use ->with('menuItem') to ensure the food details are loaded for the JS to read
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('menuItem') 
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($favorites);
    }

    public function toggle(Request $request)
    {
        $userId = Auth::id();
        $menuItemId = $request->menu_item_id;

        $exists = Favorite::where('user_id', $userId)
            ->where('menu_item_id', $menuItemId)
            ->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed', 'message' => 'Removed from favorites']);
        } else {
            Favorite::create([
                'user_id' => $userId,
                'menu_item_id' => $menuItemId
            ]);
            return response()->json(['status' => 'added', 'message' => 'Added to favorites']);
        }
    }
}