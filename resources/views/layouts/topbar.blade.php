<header class="topbar">

    <div class="topbar-left">

        <button type="button" class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">
            <i class="fa-solid fa-bars"></i>
        </button>

    </div>

    <div class="topbar-right">

        <div class="dropdown">
            <button type="button" class="profile-btn dropdown-toggle" id="profileBtn" data-bs-toggle="dropdown" aria-expanded="false" style=" background-size: contain;
                                background-image: url('{{ asset('storage/' . env('PROFILE_IMAGE_URL') . '/default.png') }}');">
            </button>

            <ul class="dropdown-menu">
                <li class="w-100">
                    <form class="logout" action="/logout" method="POST">
                        @csrf
                        <button class="btn text-danger w-100" type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- <div class="profile-wrapper">

            <div class="profile-user-info">
                <span class="profile-user-name">
                    {{ auth()->user()->name ?? 'Recruiter' }}
                </span>
            </div>

            <button type="button" class="profile-chevron" id="profileChevron">
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="profile-dropdown" id="profileDropdown">

                <div class="dropdown-profile-header">
                    <div class="dropdown-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>
                        <strong>
                            {{ auth()->user()->name ?? 'Recruiter' }}
                        </strong>

                        <small>Recruiter Account</small>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <a href="{{ route('profile.student') }}">
                    <i class="fa-regular fa-circle-user"></i>
                    <span>Profile</span>
                    <i class="fa-solid fa-arrow-up-right-from-square ms-auto"></i>
                </a>

                <a href="#">
                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </a>

                <div class="dropdown-divider"></div>

                <form class="logout" action="/logout" method="POST">
                    @csrf

                    <i class="fa-solid fa-right-from-bracket"></i>

                    <button type="submit">
                        Logout
                    </button>
                </form>

            </div>

        </div> -->

    </div>

</header>
