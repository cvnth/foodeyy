<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Added DB import

class CartController extends Controller
{
    // 1. Show Cart Page (UPDATED)
    public function index()
    {
        // Fetch Dynamic Delivery Fee
        $feeSetting = DB::table('settings')->where('key', 'delivery_fee')->first();
        $deliveryFee = $feeSetting ? (float)$feeSetting->value : 49.00; // Default fallback

        // Pass it to the view
        return view('cart', compact('deliveryFee'));
    }

    // GET: Fetch Cart Data (JSON)
    public function getCartJson()
    {
        $userId = Auth::id();
        
        $cartItems = Cart::with('menuItem')
            ->where('user_id', $userId)
            ->get();

        $subtotal = $cartItems->sum(function($item) {
            return $item->menuItem->price * $item->quantity;
        });

        return response()->json([
            'items' => $cartItems,
            'subtotal' => $subtotal
        ]);
    }

    // POST: Add to Cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = auth()->id();

        $cartItem = Cart::where('user_id', $userId)
            ->where('menu_item_id', $request->menu_item_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id' => $userId,
                'menu_item_id' => $request->menu_item_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['message' => 'Item added to cart']);
    }

    // PATCH: Update Quantity
    public function updateQuantity(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated']);
    }

    // DELETE: Remove Item
    public function removeItem($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return response()->json(['message' => 'Item removed']);
    }

    // DELETE: Clear Cart
    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Cart cleared']);
    }
}