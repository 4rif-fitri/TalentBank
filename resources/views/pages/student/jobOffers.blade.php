@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">

<div class="content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Job Offers</h3>
    </div>

    <div class="talent-layout">

        <aside class="results-panel" id="filterPanel">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button data-status="Pending" class="nav-link active text-primary">Pending</button>
                    </li>
                    <li class="nav-item">
                        <button data-status="Accepted" class="nav-link text-body">Accepted</button>
                    </li>
                    <li class="nav-item">
                        <button data-status="Rejected" class="nav-link text-body">Rejected</button>
                    </li>
                    <li class="nav-item">
                        <button data-status="Expired" class="nav-link text-body">Expired</button>
                    </li>
                    <li class="nav-item">
                        <button data-status="Withdrawn" class="nav-link text-body">Withdrawn</button>
                    </li>
                </ul>
            </div>

            <div class="invitation-list p-2 d-flex flex-column gap-2"></div>

        </aside>

        <div class="filter-panel flex-grow-1" id="shortlistContent">

            <div class="d-flex flex-column border-0 p-3 d-flex justify-content-center align-items-center ">
                <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                <h4 class="mt-2">No Interview Selected Yet</h4>
                <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                    <i class="fa-solid fa-filter"></i>
                    Interview
                </button>
            </div>

        </div>
    </div>
</div>

<div class="filter-overlay" onclick="toggleFilter()"></div>
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
        $(".nav-item button").removeClass("active text-primary").addClass("text-body");
        $(this).find("button").removeClass("text-body").addClass("active text-primary");
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

    $(document).on("click", ".toggleFilter, .invitation-item, .list-item", toggle);
    $(document).on("click", ".nav-item button", handleChangeStatus)
    $(document).on("click", ".invitation-item ", handleJobOfferDetail)
    $(document).on("click", "#btnAcceptInvitation", handleAcceptJobOffer)
    $(document).on("click", "#btnRejectInvitation", handleRejectJobOffer)
    getJobOffersByStatusAndReceiverId(currentStatus)

</script>
@endsection
