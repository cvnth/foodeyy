{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="sidebar">
     <div class="logo">
        <img src="{{ asset('images/logo.png') }}" class="form-logo" alt="FoodHub Logo" />
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="{{ route('user.dashboard') }}"
               class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="material-icons">dashboard</i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('favorites') }}"
               class="{{ request()->routeIs('favorites') ? 'active' : '' }}">
                <i class="material-icons">favorite</i>
                <span>Favorites</span>
            </a>
        </li>

        <li>
            <a href="{{ route('cart.index') }}"
            class="{{ request()->routeIs('cart.*') ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center;">
                    <i class="material-icons">shopping_cart</i>
                    <span>Cart</span>
                </div>
                
                <span id="globalCartCount" class="cart-badge" style="display: none;">0</span>
            </a>
        </li>

        <li>
            <a href="{{ route('orders.history') }}"
               class="{{ request()->routeIs('orders.history') ? 'active' : '' }}">
                <i class="material-icons">history</i>
                <span>Order History</span>
            </a>
        </li>

        <li>
            <a href="{{ route('settings') }}"
               class="{{ request()->routeIs('settings') ? 'active' : '' }}">
                <i class="material-icons">settings</i>
                <span>Settings</span>
            </a>
        </li>

    </ul>

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

<style>
    /* Badge Styling */
    .cart-badge {
        background-color: #ef4444; /* Bright Red */
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        border-radius: 9999px; /* Pill/Circle shape */
        padding: 2px 8px;
        min-width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px; /* Spacing from right edge */
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Run immediately when page loads
        updateCartBadge();
    });

    // 2. Define Global Function (window.updateCartBadge)
    // This allows the Dashboard "Add to Cart" button to trigger this function!
    window.updateCartBadge = function() {
        fetch('/user/cart/json')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('globalCartCount');
                if(!badge) return;

                // Calculate total quantity (sum of all items)
                let totalItems = 0;
                if(data.items) {
                    totalItems = data.items.reduce((sum, item) => sum + parseInt(item.quantity), 0);
                }

                // Update text
                badge.textContent = totalItems;

                // Show badge if > 0, hide if 0
                if (totalItems > 0) {
                    badge.style.display = 'inline-flex';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(err => console.error('Badge update error:', err));
    }
</script>