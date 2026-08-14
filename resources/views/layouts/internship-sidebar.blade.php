<aside class="sidebar">

    <!-- LOGO -->
    <div class="sidebar-logo d-flex justify-content-center">
        <i class="fa-solid fa-graduation-cap"></i>
        <span>TalentBank</span>
    </div>

    <!-- MENU -->
    <nav class="sidebar-menu">

        <!-- Dashboard -->
        <x-sidebar-link :route="route('home')" routeName="home" icon="fa-solid fa-gauge" label="Dashboard" />

        <x-sidebar-link :route="route('profile.student')" routeName="profile.student" icon="fa-solid fa-user" label="My Profile" />

        <x-sidebar-link :route="route('invitations')" routeName="invitations" icon="fa-solid fa-file" label="Invitations" />

        <x-sidebar-link :route="route('interviews')" routeName="interviews" icon="fa-solid fa-envelope" label="Interviews" />

        <x-sidebar-link :route="route('jobOffers')" routeName="jobOffers" icon="fa-solid fa-calendar-days" label="Job Offers" />

        <x-sidebar-link :route="route('messages')" routeName="messages" icon="fa-solid fa-briefcase" label="Messages" />

        <x-sidebar-link :route="route('settings')" routeName="settings" icon="fa-solid fa-message" label="Settings" />

    </nav>
</aside>
