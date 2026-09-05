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
                <!-- <div class="d-flex justify-content-between w-100">
                    <h5 class="m-0 fw-bold">Your invitation</h5>
                    <button type="button"
                        class="btnShowModalAddShortlist btn btn-outline-primary d-flex justify-content-center align-items-center">
                        <i class="fa-solid fa-plus fs-5"></i>
                    </button>
                </div> -->

                <ul class="nav nav-tabs">
                    <li data-status="Pending" class="nav-item">
                        <button class="nav-link text-primary active">Pending</button>
                    </li>
                    <li data-status="Accepted" class="nav-item">
                        <button class="nav-link text-black">Accepted</button>
                    </li>
                    <li data-status="Declined" class="nav-item">
                        <button class="nav-link text-black">Declined</button>
                    </li>
                    <li data-status="Expired" class="nav-item">
                        <button class="nav-link text-black">Expired</button>
                    </li>
                </ul>
            </div>

            <div id="recruitment-invitation-list"></div>
        </aside>

        <div class="shortlist-content bg-body flex-grow-1 p-2 rounded card">
            <div class="row g-3" id="shortlistContent">
                <div class="border-0 p-3 d-flex flex-column align-items-center">
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
<div class="shortlist-overlay toggleFilter"></div>

@endsection

@section('script')
<script>

    let currentStatus = "Pending"

    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }

    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay', function () {
        toggleFilter();
    });

    function getJobOffersBySenderId() {

        $.ajax({
            url: "{{ route('jobOffers.getJobOffersBySenderId') }}",
            type: "GET",
            success: function (response) {
                debug.log("getJobOffersBySenderId", response.data);

                let jobOffers = response.data
                $("#recruitment-invitation-list").empty()
                jobOffers.forEach(offer => {
                    $("#recruitment-invitation-list").append(jobOffer.recruiter.sidebar(offer))
                });

            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }


    function getJobOfferById(id) {
        let url = "{{ route('jobOffers.getJobOfferById', ['id' => '__ID__']) }}"
        url = url.replace('__ID__', id)
        $.ajax({
            url,
            type: "GET",
            success: function (response) {
                debug.log("getJobOfferById", response.data);
                $(".shortlist-content").empty();
                $(".shortlist-content").append(jobOffer.recruiter.mainContent(response.data))

            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    function getJobOffersByStatus(status) {
        let url = "{{ route('jobOffers.getJobOffersByStatus', ['status' => '__STATUS__']) }}"
        url = url.replace('__STATUS__', status)
        $.ajax({
            url,
            type: "GET",
            success: function (response) {
                debug.log("getJobOffersByStatus", response.data);

                let jobOffers = response.data
                $("#recruitment-invitation-list").empty()
                jobOffers.forEach(offer => {
                    $("#recruitment-invitation-list").append(jobOffer.recruiter.sidebar(offer))
                });
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    function withdrawJobOffer(id) {
        let url = "{{ route('jobOffers.withdrawJobOffer', ['id' => '__ID__']) }}"
        url = url.replace('__ID__', id)

        let data = {
            '_method': "PUT"
        }

        $.ajax({
            url,
            data,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function (response) {
                debug.log("getJobOffersByStatus", response.data);
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    function update(id) {
        let url = "{{ route('jobOffers.update', ['id' => '__ID__']) }}"
        url = url.replace('__ID__', id)

        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            'salary_amount': 67,
            'salary_period': "a",
            'start_date': "2000-1-10",
            'end_date': "2000-1-11",
            'terms_and_conditions': "Kerja Lembur",
            'benefits': "Percutian Di Homestay",
            'expires_at': "2027-1-1 00:00:00",
            '_method': "PUT",
        }

        $.ajax({
            url,
            data,
            type: "POST",
            success: function (response) {
                debug.log("update", response.data);
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    function store(inv_id) {

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

        $.ajax({
            url: "{{ route('jobOffers.store') }}",
            data,
            type: "POST",
            success: function (response) {
                debug.log("update", response.data);
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    // getJobOffersBySenderId()
    // getJobOfferById(2)
    // getJobOffersByStatus(currentStatus)
    // withdrawJobOffer(2)
    // update(2)
    // store(8)

    $(document).on("click", ".nav-item", function () {
        $(".nav-item button").removeClass("active text-primary").addClass("text-black");
        $(this).find("button").removeClass("text-black").addClass("active text-primary");
        let status = $(this).data("status")
        $(".shortlist-content").html(`<div class="border-0 p-3 d-flex flex-column align-items-center">
                                            <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                                            <h4 class="mt-2">No Interview Selected Yet</h4>
                                            <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                                                <i class="fa-solid fa-filter"></i>
                                                Interview
                                            </button>
                                        </div>`)

        if (currentStatus == status) return
        currentStatus = status
        getJobOffersByStatus(currentStatus)
    })

    $(document).on("click", ".invitation-item", function () {
        let id = $(this).data("id")
        console.log(id);

        getJobOfferById(id)
    })

    $(document).on("click", "#btnMessageStudent", function () {
        let id = $(this).data("id")
    })

    $(document).on("click", "#btnWithdrawJobOffer", function () {
        let id = $(this).data("id")
        withdrawJobOffer(id)
    })

    $(document).on("click", "#btnEditJobOffer", function () {
        let id = $(this).data("id")

    })


</script>
@endsection
