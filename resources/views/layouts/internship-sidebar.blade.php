<aside class="sidebar">

    <!-- LOGO -->
    <div class="sidebar-logo d-flex justify-content-center">
        <i class="fa-solid fa-graduation-cap"></i>
        <span>TalentBank</span>
    </div>

    <!-- MENU -->
    <nav class="sidebar-menu">

        <x-sidebar-link :route="route('recruiter.index')" routeName="recruiter.index" icon="fa-solid fa-gauge" label="Dashboard" />
        <x-sidebar-link :route="route('recruiter.talents')" routeName="recruiter.talents" icon="fa-solid fa-user-graduate" label="Talent" />
        <x-sidebar-link :route="route('recruiter.savedTalent')" routeName="recruiter.savedTalent" icon="fa-solid fa-heart"
            label="Saved Talent" />
        <x-sidebar-link :route="route('recruiter.shortlists')" routeName="recruiter.shortlists" icon="fa-solid fa-address-book"
            label="Shortlists" />
        <x-sidebar-link :route="route('recruiter.invitations')" routeName="recruiter.invitations" icon="fa-solid fa-paper-plane"
            label="Invitations" />
        <x-sidebar-link :route="route('recruiter.interviews')" routeName="recruiter.interviews" icon="fa-solid fa-calendar-days"
            label="Interviews" />
        <x-sidebar-link :route="route('recruiter.jobOffers')" routeName="recruiter.jobOffers" icon="fa-solid fa-briefcase"
            label="Job Offers" />
        <x-sidebar-link :route="route('recruiter.hiredTalent')" routeName="recruiter.hiredTalent" icon="fa-solid fa-user-tie"
            label="Hired Talent" />
        <x-sidebar-link :route="route('recruiter.messages')" routeName="recruiter.messages" icon="fa-solid fa-comment" label="Messages" />
        <x-sidebar-link :route="route('recruiter.settings')" routeName="recruiter.settings" icon="fa-solid fa-gear" label="Settings" />
    </nav>
</aside>
