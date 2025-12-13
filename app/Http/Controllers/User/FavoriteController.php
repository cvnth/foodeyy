<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // 1. Show the Favorites Page
    public function index()
    {
        return view('favorites'); // Ensure resources/views/user/favorites.blade.php exists
    }

    // 2. Get Favorites JSON (for the Favorites Page)
    public function getFavoritesJson()
    {
        $userId = Auth::id();
        
        $favorites = Favorite::where('user_id', $userId)
            ->with('menuItem.category') // Load the menu item data
            ->orderBy('created_at', 'desc')
            ->get();

        // Extract the menu items, removing any that might be null (deleted items)
        $items = $favorites->map(function ($fav) {
            return $fav->menuItem;
        })->filter()->values(); // <--- Filter removes nulls, values resets keys

        return response()->json($items);
    }

    // 3. Toggle Favorite (Add/Remove)
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Please login first'], 401);
        }

        $request->validate(['menu_item_id' => 'required|exists:menu_items,id']);
        
        $userId = Auth::id();
        $menuId = $request->menu_item_id;

        $exists = Favorite::where('user_id', $userId)->where('menu_item_id', $menuId)->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed', 'message' => 'Removed from favorites']);
        } else {
            Favorite::create(['user_id' => $userId, 'menu_item_id' => $menuId]);
            return response()->json(['status' => 'added', 'message' => 'Added to favorites']);
        }
    }
}