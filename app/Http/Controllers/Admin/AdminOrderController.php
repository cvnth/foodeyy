<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // 1. Show the View
    public function index()
    {
        return view('admin.orders');
    }

    // 2. Fetch Data (API)
    public function getOrdersJson(Request $request)
    {
        // Eager load items.menuItem so the receipt modal works
        $query = Order::with(['user', 'items.menuItem']); 

        // Filter by Status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search Logic (Order ID or User Name)
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%$search%");
                  });
            });
        }

        // Sort by newest first
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($orders);
    }

    // 3. Update Order Status
    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->status = $newStatus;
        
        // AUTO-UPDATE PAYMENT: If status becomes 'delivered', mark as paid automatically
        if ($newStatus === 'delivered' && $order->payment_status === 'unpaid') {
            $order->payment_status = 'paid';
        }

        $order->save();

        // Notify User if status changed
        if ($oldStatus !== $newStatus) {
            $message = "Your order #ORD-" . str_pad($order->id, 6, '0', STR_PAD_LEFT) . " is now " . ucfirst($newStatus) . ".";
            
            Notification::create([
                'user_id' => $order->user_id,
                'title'   => 'Order Update',
                'message' => $message,
                'type'    => 'info'
            ]);
        }

        return response()->json(['message' => 'Status updated successfully']);
    }

    // 4. Toggle Payment Status (Paid/Unpaid)
    public function togglePayment(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        // Toggle logic
        $order->payment_status = ($order->payment_status === 'paid') ? 'unpaid' : 'paid';
        $order->save();

        return response()->json([
            'message' => 'Payment status updated', 
            'new_status' => $order->payment_status
        ]);
    }
}