<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Notifications</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        /* specific styles for the full notification page */
        .notif-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-top: 20px;
        }

        .notif-page-item {
            display: flex;
            gap: 20px;
            padding: 20px 25px;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
            cursor: pointer;
        }

        .notif-page-item:hover {
            background-color: #f9fafb;
        }

        .notif-page-item.unread {
            background-color: #fff7ed; /* Light Orange for unread */
            border-left: 4px solid #e67e22;
        }

        .notif-icon-large {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .notif-icon-large i { font-size: 24px; color: white; }

        .notif-content h3 {
            margin: 0 0 5px 0;
            font-size: 1rem;
            color: #333;
        }
        
        .notif-content p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .notif-date {
            font-size: 0.8rem;
            color: #999;
            margin-top: 8px;
            display: block;
        }

        /* Header Actions */
        .page-header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .btn-mark-read {
            background: white;
            border: 1px solid #ddd;
            padding: 8px 16px;
            border-radius: 6px;
            color: #555;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
        .btn-mark-read:hover {
            border-color: #e67e22;
            color: #e67e22;
        }

        /* Empty State */
        .empty-state { text-align: center; padding: 50px 20px; color: #888; }
        .empty-state i { font-size: 64px; margin-bottom: 15px; color: #ddd; }
    </style>
</head>
<body>
    <div class="container">
        @include('components.user.UserSidebar')

        <main class="main-content">
            <div class="top-header">
                <h1 id="page-title">Notifications</h1>
                
                <div class="user-profile">
                    <div class="user-info">
                        <h4>{{ Auth::user()->name }}</h4>
                    </div>
                     <div class="user-avatar">
                        @php
                            $name = Auth::user()->name;
                            $initials = strtoupper(substr($name, 0, 2));
                        @endphp
                        {{ $initials }}
                    </div>
                </div>
            </div>

            <div class="page-header-actions">
                <p style="color: #666;">
                    You have <strong>{{ $unreadCount }}</strong> unread notifications
                </p>
                @if($unreadCount > 0)
                <a href="{{ route('notifications.read') }}" class="btn-mark-read">
                    <i class="material-icons" style="font-size: 18px;">done_all</i>
                    Mark all as read
                </a>
                @endif
            </div>

            <div class="notif-container">
                
                @forelse($notifications as $notif)
                    @php
                        // Logic to determine Icon and Color based on 'type' or content
                        $icon = 'notifications'; 
                        $bgColor = '#3b82f6'; // Default Blue

                        if ($notif->type === 'success' || str_contains(strtolower($notif->title), 'delivered')) {
                            $icon = 'check_circle';
                            $bgColor = '#10b981'; // Green
                        } 
                        elseif ($notif->type === 'warning' || str_contains(strtolower($notif->title), 'preparing')) {
                            $icon = 'soup_kitchen';
                            $bgColor = '#f59e0b'; // Orange
                        }
                        elseif ($notif->type === 'danger' || str_contains(strtolower($notif->title), 'cancelled')) {
                            $icon = 'error';
                            $bgColor = '#ef4444'; // Red
                        }
                    @endphp

                    <div class="notif-page-item {{ $notif->is_read ? '' : 'unread' }}">
                        <div class="notif-icon-large" style="background-color: {{ $bgColor }};">
                            <i class="material-icons">{{ $icon }}</i>
                        </div>
                        <div class="notif-content">
                            <h3>{{ $notif->title }}</h3>
                            <p>{!! $notif->message !!}</p>
                            <span class="notif-date">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                @empty
                    <div class="empty-state">
                        <i class="material-icons">notifications_off</i>
                        <h3>No notifications yet</h3>
                        <p>We'll let you know when your orders are updated.</p>
                    </div>
                @endforelse

            </div>
        </main>
    </div>
</body>
</html>