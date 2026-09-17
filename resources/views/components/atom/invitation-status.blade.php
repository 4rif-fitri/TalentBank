@props(['status'])

<li data-status="{{ $status }}" class="nav-item">
    <button class="nav-link text-black">
        {{ $status }}
    </button>
</li>
