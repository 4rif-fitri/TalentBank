<div id="shortlistContent" class="shortlist-content flex-grow-1 p-2">

    <div class="card bg-body border-0 p-3 d-flex flex-column align-items-center">
        <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
        <h4 class="mt-2">No Invitation Selected Yet</h4>
        <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            Invitations
        </button>
    </div>

</div>

@push('childScript')
<script type="module">
    window.invitationDetail = {
        current: null,
        educations: [],

        reload(response){
            Object.assign(this.current, response);
            this.render()
        },

        async load(id) {

            try {
                const response = await xApiInvite.getInvitationById(
                    "{{ route('invitations.getInvitationById', ['id' => '__ID__']) }}",
                    id
                );

                if (!response) return;

                if (this.current?.id == response.data.id) {
                    return;
                }

                this.current = response.data;

                const educationResponse =
                    await xApiEducation.getEducationById(
                        "{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}",
                        this.current.receiver.id
                    );

                if (!educationResponse) return;

                this.educations = educationResponse.data;

                this.render();

            } catch (error) {
                console.error(error);
            }
        },

        render() {

            const imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" +
                                this.current.receiver.profile_image;

            $("#shortlistContent").html(
                xinvitation.recruiter.mainContent(
                    this.current,imageUrl,this.educations
                )
            );
        },

        clear() {
            $("#shortlistContent")
                .html(
                    xcommon.noSelected("No Invitation Selected Yet","", "btn-toggle-filter toggleFilter","Invitation")
                );
        }
    };
</script>
@endpush


