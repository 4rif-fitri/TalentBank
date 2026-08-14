<header class="topbar">

    <div class="topbar-left">
        <button type="button" class="menu-toggle" id="menuToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div class="topbar-right">

        <button type="button" class="profile-btn" id="profileBtn" style="background-position: center; background-size: cover;">
            <!-- <i class="fa-solid fa-ellipsis"></i> -->
        </button>

        <div class="profile-dropdown" id="profileDropdown">
            <a href="{{ route("profile.student") }}">
                <i class="fa-solid fa-circle-user"></i>
                Profile
            </a>

            <form class="logout" action="{{ route("logout") }}" method="post">
                @csrf
                <i class="fa-solid fa-right-from-bracket"></i>
                <button style="all: unset;" type="submit">Logout</button>
            </form>
        </div>

    </div>
</header>
