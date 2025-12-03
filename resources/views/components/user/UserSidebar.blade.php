{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="sidebar">
    <!-- Logo -->
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" class="form-logo" alt="FoodHub Logo" />
    </div>

    <!-- Menu -->
    <ul class="sidebar-menu">

        <!-- Dashboard -->
        <li>
            <a href="{{ route('user.dashboard') }}"
               class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="material-icons">dashboard</i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Favorites -->
        <li>
            <a href="{{ route('favorites') }}"
               class="{{ request()->routeIs('favorites') ? 'active' : '' }}">
                <i class="material-icons">favorite</i>
                <span>Favorites</span>
            </a>
        </li>

        <!-- Cart — With Static Badge -->
        <li>
            <a href="{{ route('cart.index') }}"
               class="{{ request()->routeIs('cart.*') ? 'active' : '' }}">
                <i class="material-icons">shopping_cart</i>
                <span>Cart</span>
                <!-- STATIC BADGE — Looks real, no DB needed -->
                <span class="cart-badge">6</span>
            </a>
        </li>

        <!-- Order History -->
        <li>
            <a href="{{ route('orders.history') }}"
               class="{{ request()->routeIs('orders.history') ? 'active' : '' }}">
                <i class="material-icons">history</i>
                <span>Order History</span>
            </a>
        </li>

        <!-- Settings -->
        <li>
            <a href="{{ route('settings') }}"
               class="{{ request()->routeIs('settings') ? 'active' : '' }}">
                <i class="material-icons">settings</i>
                <span>Settings</span>
            </a>
        </li>

    </ul>

    <!-- Logout Button — Premium Style -->
    <div class="logout-wrapper">
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="material-icons">power_settings_new</i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>