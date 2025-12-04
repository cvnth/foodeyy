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

        // Fetch notifications sorted by newest first
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Count unread
        $unreadCount = $notifications->where('is_read', false)->count();

        // Return the view 'Notification.blade.php'
        return view('Notification', compact('notifications', 'unreadCount'));
    }

    // 2. Mark All as Read
    public function markAllRead()
    {
        $userId = Auth::id();
        
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}