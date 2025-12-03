<div class="top-header">
    <h1 id="page-title">Welcome back, {{ Auth::user()->name }}!</h1>
    
    <div class="user-profile">
        <div class="user-info">
            <h4>{{ Auth::user()->name }}</h4>
        </div>

        {{-- Dynamic Avatar with Real Initials --}}
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