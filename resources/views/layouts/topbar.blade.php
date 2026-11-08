<header class="topbar">

    <div class="topbar-left">

        <button type="button" class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">
            <i class="fa-solid fa-bars"></i>
        </button>

    </div>

    <div class="topbar-right">

        <form class="logout" action="/logout" method="POST">
            @csrf
            <button class="btn btn-danger w-100" type="submit">Logout</button>
        </form>

    </div>

</header>
