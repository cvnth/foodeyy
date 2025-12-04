<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSalesController extends Controller
{
    // 1. Show the View
    public function index()
    {
        return view('admin.sales'); // Ensure file is named resources/views/admin/sales.blade.php
    }

    // 2. Fetch Data via AJAX (for filters)
    public function getSalesData(Request $request)
    {
        // Default to last 7 days if no range provided
        $range = $request->input('range', '7');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Determine Date Range
        if ($range === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            $days = (int) $range;
            $start = Carbon::now()->subDays($days)->startOfDay();
            $end = Carbon::now()->endOfDay();
        }

        // Base Query (Completed orders only for Revenue)
        // We assume 'delivered' or 'completed' means money is earned
        $completedOrders = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['delivered', 'completed']);

        // A. KPI Cards
        $totalRevenue = $completedOrders->sum('total_amount');
        $totalOrdersCount = Order::whereBetween('created_at', [$start, $end])->count();
        $averageOrderValue = $totalOrdersCount > 0 ? $totalRevenue / $totalOrdersCount : 0;

        // B. Revenue Trend Chart (Group by Date)
        $revenueTrend = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['delivered', 'completed'])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // C. Order Status Distribution (Pie Chart)
        $statusDist = Order::whereBetween('created_at', [$start, $end])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // D. Top Selling Items
        $topItems = OrderItem::whereHas('order', function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->whereIn('status', ['delivered', 'completed']);
            })
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.price * order_items.quantity) as total_earned'))
            ->groupBy('menu_items.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return response()->json([
            'revenue' => $totalRevenue,
            'orders' => $totalOrdersCount,
            'average' => $averageOrderValue,
            'trend' => $revenueTrend,
            'status_dist' => $statusDist,
            'top_items' => $topItems
        ]);
    }
}