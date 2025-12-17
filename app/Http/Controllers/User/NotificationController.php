<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // 1. Show Notifications Page
    public function index()
    {
        $userId = Auth::id();

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // FIX: Using 'is_read' (boolean) because 'read_at' does not exist in your DB
        $unreadCount = $notifications->where('is_read', false)->count();

        // Matches your file name "Notification.blade.php"
        return view('Notification', compact('notifications', 'unreadCount'));
    }

    // 2. Mark All as Read
    public function markAllRead()
    {
        $userId = Auth::id();
        
        // FIX: Update 'is_read' to true (1)
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}