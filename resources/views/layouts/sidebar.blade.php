<aside class="sidebar">

    <!-- LOGO -->
    <div class="sidebar-logo d-flex justify-content-center">
        <i class="fa-solid fa-graduation-cap"></i>
        <span>TalentBank</span>
    </div>

    <!-- MENU -->
    <nav class="sidebar-menu">

        @if (array_intersect(session('roles') ?? [], ['Recruiter']))
        <x-sidebar-link :route="route('recruiter.index')" routeName="recruiter.index" icon="fa-solid fa-gauge"
            label="Dashboard" />
        <x-sidebar-link :route="route('recruiter.profiles')" routeName="recruiter.profiles"
            icon="fa-solid fa-user-graduate" label="Talent" />
        <x-sidebar-link :route="route('recruiter.likeTalent')" routeName="recruiter.likeTalent"
            icon="fa-solid fa-heart" label="Saved Talent" />
        <x-sidebar-link :route="route('recruiter.position')" routeName="recruiter.position"
            icon="fa-solid fa-address-book" label="Position " />
        <x-sidebar-link :route="route('recruiter.invitation')" routeName="recruiter.invitation"
            icon="fa-solid fa-paper-plane" label="Invitations" />
        <x-sidebar-link :route="route('recruiter.interview')" routeName="recruiter.interview"
            icon="fa-solid fa-calendar-days" label="Interviews" />
        <x-sidebar-link :route="route('recruiter.jobOffer')" routeName="recruiter.jobOffer"
            icon="fa-solid fa-briefcase" label="Job Offers" />
        <x-sidebar-link :route="route('recruiter.hiredTalent')" routeName="recruiter.hiredTalent"
            icon="fa-solid fa-user-tie" label="Hired Talent" />
        <x-sidebar-link :route="route('recruiter.message')" routeName="recruiter.message" icon="fa-solid fa-comment"
            label="Messages" />
        <x-sidebar-link :route="route('recruiter.setting')" routeName="recruiter.setting" icon="fa-solid fa-gear"
            label="Settings" />
        @elseif(array_intersect(session('roles') ?? [], ['Student']))
        <x-sidebar-link :route="route('student.index')" routeName="student.index" icon="fa-solid fa-gauge"
            label="Dashboard" />
        <x-sidebar-link :route="route('profile.student')" routeName="profile.student" icon="fa-solid fa-user"
            label="My Profile" />
        <x-sidebar-link :route="route('student.settings')" routeName="student.settings" icon="fa-solid fa-user"
            label="Resume" />
        <x-sidebar-link :route="route('student.invitations')" routeName="student.invitations" icon="fa-solid fa-file"
            label="Invitations" />
        <x-sidebar-link :route="route('student.interviews')" routeName="student.interviews" icon="fa-solid fa-envelope"
            label="Interviews" />
        <x-sidebar-link :route="route('student.jobOffers')" routeName="student.jobOffers" icon="fa-solid fa-briefcase"
            label="Job Offers" />
        <x-sidebar-link :route="route('student.messages')" routeName="student.messages" icon="fa-solid fa-comment"
            label="Messages" />
        <x-sidebar-link :route="route('student.settings')" routeName="student.settings" icon="fa-solid fa-gear"
            label="Settings" />
        @endif
    </nav>
</aside>
