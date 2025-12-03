<aside class="sidebar">
  <div class="logo">
    <img src="{{ asset('images/logo.png') }}" class="form-logo" alt="Foodeyy Logo" />
  </div>

  <ul class="sidebar-menu">
    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="material-icons">dashboard</i> Dashboard</a></li>
    <li><a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders') ? 'active' : '' }}"><i class="material-icons">list_alt</i> Manage Orders</a></li>
    <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="material-icons">people</i> Manage Users</a></li>
    <li><a href="{{ route('admin.menu.index') }}" class="{{ request()->routeIs('admin.menu.index') ? 'active' : '' }}"><i class="material-icons">restaurant_menu</i> Manage Menu</a></li>
    <li><a href="{{ route('admin.sales') }}" class="{{ request()->routeIs('admin.sales') ? 'active' : '' }}"><i class="material-icons">bar_chart</i> Sales Report</a></li>
    <li><a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}"><i class="material-icons">person</i> Profile</a></li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="all: unset; display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: white; cursor: pointer; width: 100%;">
                <i class="material-icons">logout</i> Logout
            </button>
        </form>
    </li>
  </ul>
</aside>