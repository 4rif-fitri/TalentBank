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
        padding: 20px;
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
        <h3 class="m-0 fw-bold">Recruitment Invitation</h3>
    </div>

    <div class="talent-layout">

        <aside class="results-panel" id="filterPanel">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button class="nav-link active text-primary">Awaiting</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Accepted</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Declined</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Expired</button>
                    </li>
                </ul>
            </div>

            <div class="invitation-list"></div>

        </aside>

        <div class="filter-panel flex-grow-1">
            <div class="row g-3 " id="shortlistContent">

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
</div>

<div class="filter-overlay" onclick="toggleFilter()"></div>
@endsection

@section('script')
<script type="module">
    function getJobOffersByReceiverId() {
        $.ajax({
            url: "{{ route('jobOffers.getJobOffersByReceiverId') }}",
            type: "GET",
            success: function (response) {
                debug.log("getJobOffersByReceiverId", response.data)
            },
            error: function (response) {
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
            },
            error: function (response) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function getJobOffersByStatus(status) {
        let url = "{{ route('jobOffers.getJobOffersByStatus', ['status' => '__STATUS__']) }}"
        url = url.replace("__STATUS__", status)

        $.ajax({
            type: "GET",
            url,
            success: function (response) {
                debug.log("getJobOffersByStatus", response.data)
            },
            error: function (response) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function acceptJobOffer(id) {
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
                debug.log("acceptJobOffer", response.data)
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function rejectJobOffer(id) {
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
                debug.log("acceptJobOffer", response.data)
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    // getJobOffersByReceiverId()
    // getJobOfferById(4)
    // getJobOffersByStatus("Pending")
    // acceptJobOffer(4)
    // rejectJobOffer(4)
</script>
@endsection
