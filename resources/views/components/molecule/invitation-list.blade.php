@php
$list = [
['status' => 'Pending', 'class' => 'active'],
['status' => 'Accepted', 'class' => ''],
['status' => 'Rejected', 'class' => ''],
['status' => 'Expired', 'class' => ''],
['status' => 'Withdrawn', 'class' => ''],
];
@endphp

<aside class="shortlist-sidebar" id="listContainer">

    <x-molecule.nav-tabs :list="$list" />

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
