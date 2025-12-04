<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. SHOW ORDER HISTORY
    public function index()
    {
        $userId = Auth::id();

        $orders = Order::where('user_id', $userId)
            ->with('items.menuItem')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order-history', compact('orders'));
    }

    // 2. SHOW PAYMENT PAGE
    public function showPaymentPage()
    {
        $userId = Auth::id();
        
        $cartItems = Cart::with('menuItem')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->menuItem->price;
        });

        // FETCH DYNAMIC FEE FOR VIEW
        $feeSetting = DB::table('settings')->where('key', 'delivery_fee')->first();
        $deliveryFee = $feeSetting ? (float)$feeSetting->value : 49.00;

        return view('payment', compact('cartItems', 'subtotal', 'deliveryFee'));
    }

    // 3. PLACE ORDER (CRITICAL FIX HERE)
    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,gcash',
            'delivery_type' => 'required|in:delivery,pickup',
            'address' => 'required_if:delivery_type,delivery',
        ]);

        $userId = Auth::id();
        $cartItems = Cart::with('menuItem')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        try {
            DB::beginTransaction();

            // 1. FETCH DYNAMIC FEE FROM DB
            $feeSetting = DB::table('settings')->where('key', 'delivery_fee')->first();
            $dynamicFee = $feeSetting ? (float)$feeSetting->value : 49.00;

            // 2. CALCULATE TOTALS
            $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->menuItem->price);
            
            // Use dynamic fee if delivery, else 0
            $deliveryFee = ($request->delivery_type === 'delivery') ? $dynamicFee : 0; 
            
            $totalAmount = $subtotal + $deliveryFee;

            // 3. CREATE ORDER
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'delivery_type' => $request->delivery_type,
                'total_amount' => $totalAmount,
                'address' => $request->address,
                'instructions' => $request->instructions,
            ]);

            // 4. MOVE ITEMS
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item->menu_item_id,
                    'quantity' => $item->quantity,
                    'price' => $item->menuItem->price,
                ]);
            }

            // 5. CLEAR CART
            Cart::where('user_id', $userId)->delete();

            // 6. NOTIFICATIONS
            $methodName = strtoupper($request->payment_method);
            $formattedId = str_pad($order->id, 6, '0', STR_PAD_LEFT);
            
            // User Notification
            Notification::create([
                'user_id' => $userId,
                'title'   => 'Order Placed Successfully',
                'message' => "Your order #ORD-{$formattedId} amounting to ₱" . number_format($totalAmount, 2) . " via {$methodName} has been received.",
                'type'    => 'success', 
                'is_read' => false
            ]);

            // Admin Notification
            $adminUser = User::find(1); // Assuming ID 1 is Admin
            if ($adminUser) {
                Notification::create([
                    'user_id' => $adminUser->id,
                    'title'   => 'New Order Received',
                    'message' => "Customer " . Auth::user()->name . " placed order #ORD-{$formattedId}.",
                    'type'    => 'info', 
                    'is_read' => false
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully!',
                'redirect_url' => route('orders.history')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Order failed: ' . $e->getMessage()], 500);
        }
    }
}