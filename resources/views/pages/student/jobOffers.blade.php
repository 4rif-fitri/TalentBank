@extends('layouts.internship-layouts')

@section('css')
<style>
    .talent-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .filter-panel {
        width: 350px;
        background-color: white !important;
        border-radius: 12px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .filter-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        z-index: 1040;
    }

    .results-panel {
        padding: 1rem;
        background-color: #fff;
    }

    @media (max-width: 768px) {
        .talent-layout {
            display: block;
        }

        .filter-panel {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            max-width: 100vw;
            height: 100vh;
            z-index: 1050;
            border-radius: 0;
            overflow-y: auto;
            transform: translateY(100%);
        }

        body.filter-open .filter-panel {
            transform: translateY(10%);
            border-radius: 14px;
        }

        body.filter-open .filter-overlay {
            display: block;
        }

        body.filter-open {
            overflow: hidden;
        }
    }
</style>
@endsection

@section('content')
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

            <div class="invitation-list"></div>

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

    function getJobOffersByStatusAndReceiverId(status) {
        $.ajax({
            url: "{{ route('jobOffers.getJobOffersByStatusAndReceiverId') }}",
            type: "GET",
            data: { status },
            success: function (response) {
                debug.log("getJobOffersByStatusAndReceiverId", response.data)

                $(".invitation-list").empty()
                response.data.forEach(offer => {
                    let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + offer.position.organization.organization_logo
                    $(".invitation-list").append(xcommon.studentSideBar(offer, imageUrl))
                })
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function getJobOfferById(id) {
        let url = "{{ route('jobOffers.getJobOfferById', ['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        $.ajax({
            url,
            type: "GET",
            success: function (response) {
                debug.log("getJobOfferById", response.data)
                let offer = response.data
                console.log({offer});

                let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + offer.position.organization.organization_logo
                $("#shortlistContent").html(xjobOffer.student.mainContent(response.data, imageUrl))

            },
            error: function (response) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function handleAcceptJobOffer() {
        let id = $(this).data("id")

        let url = "{{ route('jobOffers.acceptJobOffer', ['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        let data = {
            "_method": "PUT",
            "_token": $('meta[name="csrf-token"]').attr("content"),
        }

        $.ajax({
            url,
            type: "POST",
            data,
            success: function (response) {
                xdebug.log("acceptJobOffer", response.data)
                xalert.success("Job Offer Accepted", "You have successfully accepted the job offer.")
            },
            error: function (xhr) {
                xdebug.error(xhr.responseJSON.message)
                xalert.error("Error", xhr.responseJSON.message)
            }
        });
    }

    function handleRejectJobOffer() {
        let id = $(this).data("id")

        let url = "{{ route('jobOffers.rejectJobOffer', ['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        let data = {
            "_method": "PUT",
            "_token": $('meta[name="csrf-token"]').attr("content"),
        }

        $.ajax({
            url,
            data,
            type: "POST",
            success: function (response) {
                xdebug.log("rejectJobOffer", response.data)
                xalert.success("Job Offer Rejected", "You have successfully rejected the job offer.")
            },
            error: function (xhr) {
                xdebug.error(xhr.responseJSON.message)
                xalert.error("Error", xhr.responseJSON.message)
            }
        });
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

    $(document).on("click", ".toggleFilter", function () {
        $("body").toggleClass("filter-open");
    });

    $(document).on("click", ".invitation-item ", handleJobOfferDetail)
    $(document).on("click", ".btnAcceptInvitation", handleAcceptJobOffer)
    $(document).on("click", ".btnRejectInvitation", handleRejectJobOffer)
    $(document).on("click", ".nav-item button", handleChangeStatus)
    getJobOffersByStatusAndReceiverId(currentStatus)

</script>
@endsection
