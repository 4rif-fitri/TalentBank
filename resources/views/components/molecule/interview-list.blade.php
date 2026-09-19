@php
$list = [
['status' => 'Scheduled', 'class' => 'active'],
['status' => 'Completed', 'class' => ''],
['status' => 'Cancelled', 'class' => ''],
];
@endphp


<aside class="shortlist-sidebar" id="           ">
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
        <x-molecule.nav-tabs :list="$list" />

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
