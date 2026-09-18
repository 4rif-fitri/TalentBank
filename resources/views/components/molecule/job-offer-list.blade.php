<aside class="shortlist-sidebar" id="listContainer">

    <div class="d-flex flex-column justify-content-between mb-3 pb-3">
        <div class="d-flex justify-content-between w-100">
            <h5 class="m-0 fw-bold">Your Job Offers</h5>
            <button type="button"
                class="btnShowModalAddShortlist btn btn-outline-primary d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-plus fs-5"></i>
            </button>
        </div>

        <ul class="nav nav-tabs">
            <x-atom.tab-status status="Pending" />
            <x-atom.tab-status status="Accepted" />
            <x-atom.tab-status status="Declined" />
            <x-atom.tab-status status="Withdrawn" />
            <x-atom.tab-status status="Expired" />
        </ul>
    </div>

    <div id="recruitment-invitation-list" class="d-flex flex-column gap-2"></div>
</aside>


@push('childScript')
<script type="module">
    window.interviewList = {
        status: "Pending",

        async load(status){
            try {
                let response = await xApiJobOffer.getJobOffersByStatusAndSenderId(
                    "{{ route('jobOffers.getJobOffersByStatusAndSenderId') }}",
                    status
                );

                if (!response) return

                let jobOffers = response.data
                $("#recruitment-invitation-list").empty()
                jobOffers.forEach(offer => {
                    let imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + offer.receiver.profile_image
                    $("#recruitment-invitation-list").append(xjobOffer.recruiter.sideBarItem(offer, imageUrl))
                });

            } catch (error) {
                console.error(error);
            }
        },

        render(){

        },


    };

</script>
@endpush
