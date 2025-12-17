@extends('layouts.user')

@section('title', 'Notifications')

@section('content')
   

    <div class="top-header">
        <h1 id="page-title">Notifications</h1>
    </div>

    <div class="page-header-actions">
        <p style="color: #666;">
            You have <strong>{{ $unreadCount }}</strong> unread notifications
        </p>
        
        @if($unreadCount > 0)
            {{-- FIX: Using correct route name --}}
            <a href="{{ route('notifications.read') }}" class="btn-mark-read">
                <i class="material-icons" style="font-size: 18px;">done_all</i>
                Mark all as read
            </a>
        @endif
    </div>

    <div class="notif-container">
        
        @forelse($notifications as $notif)
            @php
                // Logic to determine Icon and Color based on 'type' or title content
                $icon = 'notifications'; 
                $bgColor = '#3b82f6'; // Default Blue

                // Safely handle null titles
                $titleLower = strtolower($notif->title ?? '');

                if (($notif->type === 'success') || str_contains($titleLower, 'delivered') || str_contains($titleLower, 'success')) {
                    $icon = 'check_circle';
                    $bgColor = '#10b981'; // Green
                } 
                elseif (($notif->type === 'warning') || str_contains($titleLower, 'preparing')) {
                    $icon = 'soup_kitchen';
                    $bgColor = '#f59e0b'; // Orange
                }
                elseif (($notif->type === 'danger') || str_contains($titleLower, 'cancelled')) {
                    $icon = 'error';
                    $bgColor = '#ef4444'; // Red
                }
            @endphp

            {{-- HTML Structure matches your OG code exactly --}}
            <div class="notif-page-item {{ $notif->is_read ? '' : 'unread' }}">
                <div class="notif-icon-large" style="background-color: {{ $bgColor }};">
                    <i class="material-icons">{{ $icon }}</i>
                </div>
                <div class="notif-content">
                    <h3>{{ $notif->title ?? 'New Notification' }}</h3>
                    
                    {{-- FIX: Printing message column directly --}}
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
@endsection