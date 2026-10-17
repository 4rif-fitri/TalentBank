@props(['route','routeName','icon','label',])

<div class="sidebar-item">
    <a href="{{ $route }}" class="sidebar-link {{ request()->routeIs($routeName) ? 'active' : '' }}">

        <i class="{{ $icon }}"></i>

        <span class="menu-text">
            {{ $label }}
        </span>
    </a>
</div>
