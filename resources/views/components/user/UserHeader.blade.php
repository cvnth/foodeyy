<div class="top-header">
    <h1 id="page-title">Welcome back, {{ Auth::user()->name }}!</h1>
    
    <div class="user-profile">
        
        {{-- 1. FETCH NOTIFICATIONS DATA DIRECTLY --}}
        @php
            $headerNotifications = \App\Models\Notification::where('user_id', Auth::id())
                                    ->orderBy('created_at', 'desc')
                                    ->take(5) // Limit to 5 for the dropdown
                                    ->get();
            
            $headerUnreadCount = \App\Models\Notification::where('user_id', Auth::id())
                                    ->where('is_read', false)
                                    ->count();
        @endphp

        <div class="notification-box" onclick="toggleNotifications(event)">
            <i class="material-icons">notifications</i>
            
            @if($headerUnreadCount > 0)
                <span class="notification-dot">{{ $headerUnreadCount }}</span>
            @endif

            <div class="notification-dropdown" id="notificationDropdown">
                
                <div class="dropdown-header">
                    <span class="header-title">Notifications</span>
                    @if($headerUnreadCount > 0)
                        <a href="{{ route('notifications.read') }}" class="mark-read" 
                           onclick="event.stopPropagation();">Mark all read</a>
                    @endif
                </div>

                <div class="dropdown-body">
                    {{-- LOOP THROUGH DATABASE NOTIFICATIONS --}}
                    @forelse($headerNotifications as $notif)
                        @php
                            // Determine Icon and Color Class based on title/type
                            $icon = 'notifications';
                            $colorClass = 'blue'; // CSS class needs to be defined or inline style used

                            if (str_contains(strtolower($notif->title), 'delivered') || $notif->type == 'success') {
                                $icon = 'check_circle';
                                $colorClass = 'green';
                            } elseif (str_contains(strtolower($notif->title), 'preparing') || $notif->type == 'warning') {
                                $icon = 'soup_kitchen';
                                $colorClass = 'orange';
                            } elseif (str_contains(strtolower($notif->title), 'cancelled') || $notif->type == 'danger') {
                                $icon = 'error';
                                $colorClass = 'red'; // You might need to add .red class to CSS
                            }
                        @endphp

                        <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                            <div class="notif-icon {{ $colorClass }}">
                                <i class="material-icons">{{ $icon }}</i>
                            </div>
                            <div class="notif-info">
                                <p class="notif-title">{{ $notif->title }}</p>
                                <p class="notif-desc">{!! Str::limit($notif->message, 50) !!}</p>
                                <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        {{-- EMPTY STATE --}}
                        <div style="padding: 20px; text-align: center; color: #999;">
                            <i class="material-icons" style="font-size: 32px; color: #ddd;">notifications_none</i>
                            <p style="font-size: 0.85rem; margin-top: 5px;">No notifications yet</p>
                        </div>
                    @endforelse
                </div>

                <div class="dropdown-footer">
                    <a href="{{ route('notifications.all') }}">View All Notifications</a>
                </div>
            </div>
        </div>

        <div class="user-info">
            <h4>{{ Auth::user()->name }}</h4>
        </div>

        <div class="user-avatar">
            @php
                $name = Auth::user()->name;
                $words = explode(' ', trim($name));
                $initials = '';
                if (count($words) >= 2) {
                    $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
                } else {
                    $initials = strtoupper(substr($name, 0, 2));
                }
            @endphp
            {{ $initials }}
        </div>
    </div>
</div>

<script>
    function toggleNotifications(event) {
        event.stopPropagation(); // Stop click from reaching the document
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const bell = document.querySelector('.notification-box');
        
        // If dropdown is open, and click is NOT inside dropdown AND NOT inside bell
        if (dropdown && dropdown.classList.contains('active')) {
            if (!dropdown.contains(event.target) && !bell.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        }
    });
</script>