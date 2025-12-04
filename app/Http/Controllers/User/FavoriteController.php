<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log

class FavoriteController extends Controller
{
    public function index()
    {
        return view('favorites');
    }

    public function getFavoritesJson()
    {
        if (!Auth::check()) {
            return response()->json([]); // Return empty if not logged in
        }

        $favorites = Favorite::with('menuItem')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $items = $favorites->map(function ($fav) {
            return $fav->menuItem;
        });

        return response()->json($items);
    }

    public function toggle(Request $request)
    {
        // 1. Check Login
        if (!Auth::check()) {
            return response()->json(['message' => 'You must be logged in.'], 401);
        }

        // 2. Debugging: Log what we received
        Log::info('Favorite Toggle Request:', $request->all());

        try {
            // 3. Validate
            $request->validate([
                'menu_item_id' => 'required|integer|exists:menu_items,id'
            ]);
            
            $userId = Auth::id();
            $menuId = $request->menu_item_id;

            // 4. Check for existing
            $exists = Favorite::where('user_id', $userId)
                ->where('menu_item_id', $menuId)
                ->first();

            if ($exists) {
                $exists->delete();
                return response()->json(['status' => 'removed', 'message' => 'Removed from favorites']);
            } else {
                // 5. Create new
                $fav = Favorite::create([
                    'user_id' => $userId,
                    'menu_item_id' => $menuId
                ]);
                
                return response()->json([
                    'status' => 'added', 
                    'message' => 'Added to favorites',
                    'debug' => $fav // Send back the created object to verify
                ]);
            }

        } catch (\Exception $e) {
            // 6. Return exact error to browser
            return response()->json([
                'message' => 'Database Error: ' . $e->getMessage()
            ], 500);
        }
    }
}