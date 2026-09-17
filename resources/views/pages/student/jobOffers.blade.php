@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">

<div class="content p-4 job-offers-page">

    <section class="job-offers-header">
        <div class="job-offers-heading">
            <span class="section-eyebrow">OPPORTUNITIES</span>
            <h1>Job Offers</h1>
        </div>
    </section>

    <div class="talent-layout">

        <aside class="results-panel" id="filterPanel">

            <nav class="offer-tabs" aria-label="Job offer status">
                <button type="button" class="offer-tab active" data-status="Pending">
                    Pending
                    <!-- <span class="tab-count">2</span> -->
                </button>

                <button type="button" class="offer-tab nav-link" data-status="Accepted">
                    Accepted
                </button>

                <button type="button" class="offer-tab nav-link" data-status="Rejected">
                    Rejected
                </button>

                <button type="button" class="offer-tab nav-link" data-status="Expired">
                    Expired
                </button>

                <button type="button" class="offer-tab nav-link" data-status="Withdrawn">
                    Withdrawn
                </button>
            </nav>

            <div class="invitation-list p-2 d-flex flex-column gap-2"></div>

        </aside>

        <div class="filter-panel flex-grow-1 offers-list" id="shortlistContent" id="offersList"></div>
    </div>
</div>

<div class="filter-overlay"></div>
@endsection

@section('script')
<script type="module">
    let currentJobOffers = []
    let currentJobOffer = null
    let currentStatus = "Pending"

    async function getJobOffersByStatusAndReceiverId(status) {

        try {
            let response = await xApiJobOffer.getJobOffersByStatusAndReceiverId("{{ route('jobOffers.getJobOffersByStatusAndReceiverId') }}", status)
            if(!response) return

            $(".invitation-list").empty()

            response.data.forEach(offer => {
                console.log(offer);

                let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + offer.position.organization.organization_logo
                $(".invitation-list").append(xjobOffer.student.sideList(offer, imageUrl))
            })


        } catch (error) {
            console.error(error);
        }
    }

    async function getJobOfferById(id) {
        try {
            let response = await xApiJobOffer.getJobOfferById("{{ route('jobOffers.getJobOfferById', ['id' => '__ID__']) }}",id)
            if(!response) return

            let offer = response.data

            let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + offer.position.organization.organization_logo
            $("#shortlistContent").html(xjobOffer.student.mainContent(response.data, imageUrl))

        } catch (error) {
            console.error(error);
        }
    }

    async function handleAcceptJobOffer() {
        let id = $(this).data("id")

        let data = {
            "_method": "PUT",
            "_token": $('meta[name="csrf-token"]').attr("content"),
        }

        try {
            let response = await xApiJobOffer.acceptJobOffer("{{ route('jobOffers.acceptJobOffer', ['id' => '__ID__']) }}", id, data)
            if (!response) return

            xalert.success("Job Offer Accepted", "You have successfully accepted the job offer.")

        } catch (error) {
            console.error(error);
        }
    }

    async function handleRejectJobOffer() {
        let id = $(this).data("id")

        let data = {
            "_method": "PUT",
            "_token": $('meta[name="csrf-token"]').attr("content"),
        }

        try {
            let response = await xApiJobOffer.rejectJobOffer("{{ route('jobOffers.rejectJobOffer', ['id' => '__ID__']) }}", id, data)
            if (!response) return

            xalert.success("Job Offer rejected", "You have successfully rejected the job offer.")

        } catch (error) {
            console.error(error);
        }
    }

    function handleChangeStatus () {
        $(".offer-tab").removeClass("active")
        $(this).addClass("active");
        let status = $(this).data("status")

        if (status == currentStatus) return
        currentStatus = status

        getJobOffersByStatusAndReceiverId(status)
    }

    function handleJobOfferDetail () {
        $(this).addClass("active").siblings().removeClass("active")
        $(".toggleFilter").removeClass("d-block").addClass("d-none")
        let id = $(this).data("id")
        getJobOfferById(id)
    }

    $(document).on("click", ".toggleFilter, .invitation-item, .list-item, .filter-overlay", toggle);
    $(document).on("click", ".offer-tab", handleChangeStatus)
    $(document).on("click", ".invitation-item ", handleJobOfferDetail)
    $(document).on("click", "#btnAcceptInvitation", handleAcceptJobOffer)
    $(document).on("click", "#btnRejectInvitation", handleRejectJobOffer)
    getJobOffersByStatusAndReceiverId(currentStatus)

</script>
@endsection
