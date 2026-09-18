<div id="shortlistContent" class="shortlist-content flex-grow-1 p-2"></div>

@push('childScript')
<script type="module">
    window.interviewDetail = {
        current: null,
        educations: [],

        reload(response) {
            Object.assign(this.current, response);
            this.render()
        },

        async load(id){
            try {
                let interviewDetail = await xApiInterview.getInterviewById("{{ route('interviews.getInterviewById', ['id' => '__ID__']) }}", id);
                let receiverId = interviewDetail.data.interviewee.id

                let listEducationReceiver = await xApiEducation.getEducationByUserProfileId("{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}", receiverId);

                this.educations = listEducationReceiver.data
                this.current = interviewDetail.data
                let imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + this.current.interviewee.profile_image

                $(".shortlist-content").html(xinterview.recruiter.mainContent(this.current, imageUrl, this.educations));

            } catch (xhr) {
                console.error(xhr);

            }
        },

        clear(){
            $("#shortlistContent").html(
                xcommon.noSelected("No Interview Selected Yet", "", "btn-toggle-filter toggleFilter", "Interview")
            );
        }
    };
</script>
@endpush


