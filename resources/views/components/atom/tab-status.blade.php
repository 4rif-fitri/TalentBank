@props(['status','class'])
<li data-status="{{ $status }}" class="nav-item" style="list-style: none;">
    <button data-status="{{ $status }}" class="nav-link offer-tab text-black {{ $class }}">
        {{ $status }}
    </button>
</li>
