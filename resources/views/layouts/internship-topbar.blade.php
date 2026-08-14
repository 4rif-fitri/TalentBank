<header class="topbar">

    <div class="topbar-left">
        <button type="button" class="menu-toggle" id="menuToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div class="topbar-right">

        <button type="button" class="profile-btn" id="profileBtn">
            <i class="fa-solid fa-ellipsis"></i>
        </button>

        <div class="profile-dropdown" id="profileDropdown">
            <a href="profile.html">
                <i class="fa-solid fa-circle-user"></i>
                Profile
            </a>

            <form action="{{ route("logout") }}" method="post">
                @csrf
                <i class="fa-solid fa-right-from-bracket"></i>
                <button type="submit">Logout</button>
            </form>
        </div>

    </div>
</header>
