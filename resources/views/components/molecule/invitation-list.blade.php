<aside class="shortlist-sidebar" id="listContainer">

    <div class="d-flex flex-column justify-content-between mb-1 pb-1">
        <div class="d-flex justify-content-between w-100">
            <h5 class="m-0 fw-bold">Your invitation</h5>
            <!-- <button type="button"
                class="btnShowModalAddShortlist btn btn-outline-primary d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-plus fs-5"></i>
            </button> -->
        </div>

        <ul class="nav nav-tabs">
            <x-atom.tab-status status="Pending" class="active"/>
            <x-atom.tab-status status="Accepted" class="" />
            <x-atom.tab-status status="Rejected" class="" />
            <x-atom.tab-status status="Expired" class="" />
            <x-atom.tab-status status="Withdrawn" class="" />
        </ul>
    </div>

    <div id="recruitment-invitation-list" class="p-2 d-flex gap-2 flex-column"></div>
</aside>

@push('childScript')
<script type="module">
    window.invitationList = {

        currentStatus: "Pending",

        async load(status = this.currentStatus) {

            this.currentStatus = status;

            try {
                const response = await xApiInvite.getInvitationsByStatusAndSenderId(
                        "{{ route('invitations.getInvitationsByStatusAndSenderId') }}",
                        status);

                if (!response) return;
                this.render(response.data);

            } catch (error) {
                console.error(error);
            }
        },

        render(invitations) {
            const $list = $("#recruitment-invitation-list");
            $list.empty();

            invitations.forEach(inv => {
                const imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + inv.receiver.profile_image;
                $list.append(xinvitation.recruiter.recruitmentInvitationList(inv,imageUrl));
            });
        },

        async refresh() {
            await this.load(this.currentStatus);
        }
    };
</script>
@endpush
