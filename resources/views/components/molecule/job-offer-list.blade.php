@php
$list = [
['status' => 'Pending', 'class' => 'active'],
['status' => 'Accepted', 'class' => ''],
['status' => 'Declined', 'class' => ''],
['status' => 'Withdrawn', 'class' => ''],
['status' => 'Expired', 'class' => ''],
];
@endphp

<aside class="shortlist-sidebar" id="listContainer">

    <x-molecule.nav-tabs :list="$list" />

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
                    $("#recruitment-invitation-list").append(xjobOffer.recruiter.sideBar(offer, imageUrl))
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
