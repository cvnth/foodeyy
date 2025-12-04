<div class="top-header">
    <h1 id="page-title">Admin Dashboard</h1>
    
    <div class="user-profile">
        
        {{-- 1. FETCH ADMIN NOTIFICATIONS --}}
        @php
            // Fetch notifications for the logged-in Admin
            $adminNotifs = \App\Models\Notification::where('user_id', Auth::id())
                                                ->orderBy('created_at', 'desc')
                                                ->take(20) // Increased to 20 so you can test the scrollbar
                                                ->get();
            
            $adminUnread = \App\Models\Notification::where('user_id', Auth::id())
                                                ->where('is_read', false)
                                                ->count();
        @endphp

        {{-- 2. NOTIFICATION BELL --}}
        <div class="notification-box" onclick="toggleAdminNotifs(event)">
            <i class="material-icons">notifications</i>
            
            @if($adminUnread > 0)
                <span class="notification-dot">{{ $adminUnread }}</span>
            @endif

            {{-- DROPDOWN --}}
            <div class="notification-dropdown" id="adminNotifDropdown">
                <div class="dropdown-header">
                    <span class="header-title">Admin Alerts</span>
                    @if($adminUnread > 0)
                        <a href="{{ route('notifications.read') }}" class="mark-read">Mark all read</a>
                    @endif
                </div>

                {{-- UPDATED: Added 'notification-scroll' class here --}}
                <div class="dropdown-body notification-scroll">
                    @forelse($adminNotifs as $notif)
                        <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                            <div class="notif-icon blue">
                                <i class="material-icons">inventory_2</i>
                            </div>
                            <div class="notif-info">
                                <p class="notif-title">{{ $notif->title }}</p>
                                <p class="notif-desc">{{ $notif->message }}</p>
                                <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="padding: 20px; text-align: center; color: #999;">
                            <p style="font-size: 0.85rem;">No new notifications</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="user-info">
            <p>Administrator</p>
        </div>
        <div class="user-avatar" style="background-color: #e74c3c;">AU</div>
    </div>
</div>

{{-- 3. JAVASCRIPT & CSS --}}
<script>
    function toggleAdminNotifs(event) {
        event.stopPropagation();
        document.getElementById('adminNotifDropdown').classList.toggle('active');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('adminNotifDropdown');
        const bell = document.querySelector('.notification-box');
        if (dropdown && !dropdown.contains(event.target) && !bell.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });
</script>

<style>
    /* Reuse the same styles from User Dashboard, ensuring Z-Index is high */
    .notification-box {
        position: relative;
        cursor: pointer;
        margin-right: 20px;
        display: flex;
        align-items: center;
    }
    
    .notification-box i { font-size: 24px; color: #666; }
    
    .notification-dot {
        position: absolute; top: -5px; right: -5px;
        background: #ef4444; color: white;
        font-size: 10px; padding: 2px 5px;
        border-radius: 10px; border: 2px solid white;
    }

    .notification-dropdown {
        position: absolute;
        top: 40px; right: 0;
        width: 320px; /* Slightly wider for better readability */
        background: white;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        display: none;
        flex-direction: column;
        z-index: 9999; /* High Z-Index */
        border: 1px solid #eee;
        overflow: hidden; /* Ensures header corners stay rounded */
    }

    .notification-dropdown.active { display: flex; }

    .dropdown-header {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        display: flex; justify-content: space-between; align-items: center;
        background: #f8f9fa; 
    }
    .header-title { font-weight: bold; font-size: 0.9rem; color: #333; }
    .mark-read { font-size: 0.75rem; color: #e67e22; text-decoration: none; font-weight: 600; }
    .mark-read:hover { text-decoration: underline; }

    .notif-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f1f1;
        display: flex; gap: 12px;
        transition: background 0.2s;
        cursor: default;
    }
    .notif-item:hover { background: #fff8f0; /* Very light orange tint on hover */ }
    .notif-item.unread { background: #eef2ff; border-left: 3px solid #e67e22; }

    .notif-icon {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .notif-icon.blue { background: #fff3e0; color: #e67e22; } /* Changed to Orange theme */
    .notif-icon i { font-size: 18px; }

    .notif-info p { margin: 0; line-height: 1.4; }
    .notif-title { font-weight: 600; font-size: 0.85rem; color: #333; }
    .notif-desc { font-size: 0.8rem; color: #666; margin-top: 2px; }
    .notif-time { font-size: 0.7rem; color: #999; display: block; margin-top: 4px; }

    /* --- SCROLLBAR LOGIC --- */
    .notification-scroll {
        max-height: 350px; /* Limits height to trigger scroll */
        overflow-y: auto;  /* Vertical scroll only */
        overflow-x: hidden;
    }

    /* Webkit Scrollbar Styling */
    .notification-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .notification-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notification-scroll::-webkit-scrollbar-thumb {
        background: #d1d5db; /* Light gray by default */
        border-radius: 10px;
    }

    /* Hover state for scrollbar */
    .notification-scroll:hover::-webkit-scrollbar-thumb {
        background: #e67e22; /* Becomes orange when you hover the list */
    }
</style>