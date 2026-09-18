<aside class="shortlist-sidebar" id="listContainer">
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
        <ul class="nav nav-tabs">
            <x-atom.tab-status status="Scheduled" class="active" />
            <x-atom.tab-status status="Completed" class="" />
            <x-atom.tab-status status="Cancelled" class="" />
        </ul>
    </div>

    <div id="shortlistList"></div>

</aside>
@push('childScript')
<script type="module">
    window.interviewList = {
        currentStatus: "Scheduled",

        async load(status = this.currentStatus){
            this.currentStatus = status
            console.log(xApiInterview);

            try {
                let response = await xApiInterview.getInterviewsByStatusAndInterviewerId("{{ route('interviews.getInterviewsByStatusAndInterviewerId') }}", status)
                if (!response) return

                this.render(response.data)

            } catch (error) {
                console.error(error);
            }
        },

        render(interviews){
            $("#shortlistList").empty()

            interviews.forEach(interview => {
                $("#shortlistList").append(xinterview.recruiter.sidebar(interview))
            });
        },

        async refresh(){
            await this.load(this.currentStatus);
        }
    };

</script>
@endpush
