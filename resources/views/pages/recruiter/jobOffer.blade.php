@extends('layouts.internship-layouts')

@section('css')
<style>
    .shortlist-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .shortlist-sidebar {
        width: 350px;
        background: #fff;
        height: 80vh !important;
        overflow-y: auto;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .shortlist-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        z-index: 1040;
    }

    @media (max-width: 1300px) {
        .btn-toggle-filter {
            display: block !important;
        }

        .shortlist-layout {
            display: block;
        }

        .shortlist-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 380px;
            max-width: 85vw;
            height: 100vh !important;
            z-index: 1050;
            border-radius: 0;
            overflow-y: auto;
            transform: translateX(-100%);
        }

        body.filter-open .shortlist-sidebar {
            transform: translateX(0);
        }

        body.filter-open .shortlist-overlay {
            display: block;
        }

        body.filter-open {
            overflow: hidden;
        }
    }

    .shortlist-item:hover {
        border: 1px solid #6d7eca !important;
        color: #6d7eca;

        small {
            color: #6d7eca !important;
        }

    }

    .shortlist-item.active {
        border: 1px solid #5267c4 !important;
        color: #5267c4;

        small {
            color: #5267c4 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Job Offers</h3>
        <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            invitation
        </button>
    </div>

    <div class="shortlist-layout">

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
                    <li data-status="Pending" class="nav-item">
                        <button data-status="Pending" class="nav-link text-primary active">Pending</button>
                    </li>
                    <li data-status="Accepted" class="nav-item">
                        <button data-status="Accepted" class="nav-link text-black">Accepted</button>
                    </li>
                    <li data-status="Declined" class="nav-item">
                        <button data-status="Declined" class="nav-link text-black">Declined</button>
                    </li>
                    <li data-status="Withdrawn" class="nav-item">
                        <button data-status="Withdrawn" class="nav-link text-black">Withdrawn</button>
                    </li>
                    <li data-status="Expired" class="nav-item">
                        <button data-status="Expired" class="nav-link text-black">Expired</button>
                    </li>
                </ul>
            </div>

            <div id="recruitment-invitation-list" class="d-flex flex-column gap-2"></div>
        </aside>

        <div id="shortlistContent" class="shortlist-content flex-grow-1 p-2">
            <div class="bg-body border-0 p-3 d-flex flex-column align-items-center">
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
<div class="shortlist-overlay toggleFilter"></div>
<x-modals.job-offer-modal />

@endsection

@section('script')
<script type="module">
    let currentStatus = "Pending"
    let currentJobOffer
    let currentEducation

    async function getJobOffersByStatus(status) {

        try {
            let response = await xApiJobOffer.getJobOffersByStatusAndSenderId(
                "{{ route('jobOffers.getJobOffersByStatusAndSenderId') }}",
                status
            );

            if(!response) return

            debug.log("getJobOffersByStatus", response.data);

            let jobOffers = response.data
            $("#recruitment-invitation-list").empty()
            jobOffers.forEach(offer => {
                let imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + offer.receiver.profile_image
                $("#recruitment-invitation-list").append(xjobOffer.recruiter.sideBarItem(offer, imageUrl))
            });

        } catch (error) {
            console.error(error);
        }
    }

    async function handleWithdrawJobOffer() {
        let id = $(this).data("id")

        let data = {
            _method: "PUT",
            _token: $('meta[name="csrf-token"]').attr("content")
        }

        try {
            let response = await xApiJobOffer.withdrawJobOffer("{{ route('jobOffers.withdrawJobOffer', ['id' => '__ID__']) }}", id, data)
            if(!response) return

            debug.log("getJobOffersByStatus", response.data);

        } catch (error) {
            console.error(error);
        }
    }

    async function handleAddJobOffer(inv_id) {
        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            "invitation_id": inv_id,
            "salary_amount": "9797",
            "salary_period": "a",
            "start_date": "2005-01-25",
            "end_date": "2005-05-29",
            "terms_and_conditions": "Voluptatem animi harum incidunt doloribus. Error veniam ut voluptas non. Dolores reprehenderit atque consequatur est. Non facilis aliquam ipsa aut facere enim temporibus.",
            "benefits": "Voluptatem animi harum incidunt doloribus. Error veniam ut voluptas non. Dolores reprehenderit atque consequatur est. Non facilis aliquam ipsa aut facere enim temporibus.",
            "expires_at": "2027-1-1 00:00:00",
        }

        try {
            let response = await xApiJobOffer.store("{{ route('jobOffers.store') }}")
            if(!response) return

            console.log(response);

        } catch (error) {
            console.error(error);
        }
    }

    function handleFilterJobOfferByStatus() {
        let status = $(this).data("status")
        $(".shortlist-content").html(xcommon.noSelected(""))

        if (currentStatus == status) return
        currentStatus = status
        getJobOffersByStatus(currentStatus)
    }

    function showModalEditJobOffer(){
        let id = $(this).data("id")
        console.log(currentJobOffer);
        $(".offer-candidate-name").text(`Candidate: ${currentJobOffer.receiver.name}`)
        $("#jobOfferPositionName").text(`Position: ${currentJobOffer.position.position_title}`)

        $("#jobOfferModal").find("#start_date").val(currentJobOffer.start_date)
        $("#jobOfferModal").find("#end_date").val(currentJobOffer.end_date)

        $("#jobOfferModal").find("#salary_amount").val(currentJobOffer.salary_amount)
        $("#jobOfferModal").find("#salary_period").val(currentJobOffer.salary_period)
        $("#jobOfferModal").find("#expires_at").val(currentJobOffer.expires_at.split(" ")[0])
        $("#jobOfferModal").find("#benefits").val(currentJobOffer.benefits)
        $("#jobOfferModal").find("#terms_and_conditions").val(currentJobOffer.terms_and_conditions)
        $("#jobOfferModal").find("#job-offer-candicate-id").val(currentJobOffer.receiver.id)
        $("#jobOfferModal").find("#QjobOfferPositionId").val(currentJobOffer.position.id)

        $("#btnAddJobOffer").hide()
        $("#btnUpdateJobOffer").show()
        xmodal.show("jobOfferModal")
    }

    async function handleJobOfferDetails() {
        let jobOfferId = $(this).data("id")

        try {
            let jobOfferResponse = await xApiJobOffer.getJobOfferById("{{ route('jobOffers.getJobOfferById', ['id' => '__ID__']) }}", jobOfferId);
            let educationResponse = await xApiEducation.getEducationByUserProfileId("{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}", jobOfferResponse.data.receiver.id);

            currentJobOffer = jobOfferResponse.data
            currentEducation = educationResponse.data
            let imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + currentJobOffer.receiver.profile_image

            console.log("currentJobOffer", currentJobOffer);
            console.log("currentEducation", currentEducation);

            $(".shortlist-content").html(xjobOffer.recruiter.mainContent(currentJobOffer, imageUrl, currentEducation))
        } catch (error) {
            console.log(error);
        }
    }

    function handleMessageStudent() {
        let id = $(this).data("id")
    }

    async function handleUpdateJobOffer() {
        let id = currentJobOffer.id

        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            'salary_amount': $("#salary_amount").val(),
            'salary_period': $("#salary_period").val(),
            'start_date': $("#start_date").val(),
            'end_date': $("#end_date").val(),
            'terms_and_conditions': $("#terms_and_conditions").val(),
            'benefits': $("#benefits").val(),
            'expires_at': $("#expires_at").val(),
            '_method': "PUT",
        }

        try {
            let response = await xApiEducation.update("{{ route('jobOffers.update', ['id' => '__ID__']) }}", id, data)
            if(!response) return

            debug.log("update", response.data);
            xmodal.hide("jobOfferModal")
            getJobOffersByStatus(currentStatus)
            xalert.fire("Success", response.message, "success")

        } catch (error) {
            console.error(error);
        }
    }

    $(document).on("click", "#btnEditJobOffer", showModalEditJobOffer)
    $(document).on("click", "#btnUpdateJo1bOffer", handleUpdateJobOffer)
    $(document).on("click", "#btnWithdrawJobOffer", handleWithdrawJobOffer)
    $(document).on("click", "#btnMessageStudent", handleMessageStudent)
    $(document).on("click", ".list-item", handleJobOfferDetails)
    $(document).on("click", ".nav-link", handleFilterJobOfferByStatus)
    getJobOffersByStatus(currentStatus)
        $(document).on('click', '.btn-toggle-filter, .shortlist-overlay', function () {
            document.body.classList.toggle('filter-open');
        });
</script>
@endsection
