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
        <!-- <button class="btn btn-primary d-md-none btn-toggle-filter">
            <i class="fa-solid fa-filter"></i>
            Shortlists
        </button> -->

    </div>

    <div class="talent-layout">

        <aside class="results-panel" id="filterPanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button class="nav-link active text-primary">All</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Sent</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Accepted</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Dealined</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Exprired</button>
                    </li>
                </ul>
            </div>

            <div class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded"
                onclick="toggleFilter()">
                <div role="button" class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-circle-user fa-xl" style="color: rgb(0, 0, 0);"></i>
                    <div class="flex-grow-1 d-flex flex-column">
                        <p class="fw-semibold">
                            Software Engineering Interns
                        </p>
                        <p>FrontEnd intern </p>
                        <div class="badge text-primary border border-primary w-75">
                            Awaiting Response
                        </div>
                        <small class="text-muted">
                            Expires 22 May 2025
                        </small>
                    </div>
                </div>
                <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
            </div>

            <div class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded"
                onclick="toggleFilter()">
                <div role="button" class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-circle-user fa-xl" style="color: rgb(0, 0, 0);"></i>
                    <div class="flex-grow-1 d-flex flex-column">
                        <p class="fw-semibold">
                            Software Engineering Interns
                        </p>
                        <p>FrontEnd intern </p>
                        <div class="badge text-danger border border-danger w-75">
                            Awaiting Response
                        </div>
                        <small class="text-muted">
                            Expires 22 May 2025
                        </small>
                    </div>
                </div>
                <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
            </div>

            <div class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded"
                onclick="toggleFilter()">
                <div role="button" class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-circle-user fa-xl" style="color: rgb(0, 0, 0);"></i>
                    <div class="flex-grow-1 d-flex flex-column">
                        <p class="fw-semibold">
                            Software Engineering Interns
                        </p>
                        <p>FrontEnd intern </p>
                        <div class="badge text-success border border-success w-75">
                            Acceped
                        </div>
                        <small class="text-muted">
                            Expires 22 May 2025
                        </small>
                    </div>
                </div>
                <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
            </div>

        </aside>

        <div class=" filter-panel flex-grow-1">
            <div class="row g-3 ">
                <div class="card h-100 shadow-sm border-0 p-3 position-relative">

                    <div class="dropdown position-absolute top-0 end-0 m-3">
                        <button type="button" class="btn btn-sm p-1" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li>
                                <button class="dropdown-item">
                                    <i class="fa-regular fa-copy me-2"></i>
                                    Duplicate
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item">
                                    <i class="fa-regular fa-folder me-2"></i>
                                    Archive
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger">
                                    <i class="fa-regular fa-trash-can me-2"></i>
                                    Delete
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="d-flex gap-3">
                        <i class="fa-solid fa-circle-user" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                        <div>
                            <h3 class="fw-semibold">Dr Lorem Ipsum Dolor Sit Amit</h3>
                            <p class="fw-semibold">Sofware inginer with Honor</p>
                            <small class="text-muted d-block">Universiti Teknikal Malaysia Melaka(UTEM)</small>
                            <small class="text-muted d-block">
                                <i class="fa-solid fa-location-dot" style="color: rgb(0, 0, 0);"></i>
                                Durian Tunggal, Malaka, Malaysia
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between my-3">
                        <div class="border p-1">
                            <small class="text-muted">Position</small>
                            <h5 class="fw-semibold">FrontEnd Intern</h5>
                        </div>
                        <div>
                            <small class="text-muted">Department</small>
                            <h5 class="fw-semibold">Department IT and DEV</h5>
                        </div>
                        <div>
                            <small class="text-muted">employment_type</small>
                            <h5 class="fw-semibold">employment_type</h5>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-semibold">Personal Message</h5>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste reprehenderit veniam sunt dicta
                            iusto, temporibus ex consequatur perferendis quasi. Corrupti, mollitia aperiam! Dolorem,
                            ipsum! Quo expedita reiciendis quae libero accusamus!</p>
                    </div>

                    <div class="mt-2">
                        <h6 class="fw-semibold mb-1">Actions</h6>
                        <div>
                            <button class="btn btn-outline-primary">
                                <i class="fa-regular fa-message text-primary"></i>
                                Message Student
                            </button>
                            <button class="btn btn-outline-danger">
                                <i class="fa-regular fa-trash-can text-danger"></i>
                                Withdraw Invitation
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fa-solid fa-pen text-primary"></i>
                                Edit Invitation
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay Gelap untuk Mobile -->
<div class="filter-overlay" onclick="toggleFilter()"></div>
@endsection

@section('script')
<script>
    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }
</script>
<script>
    let url = "{{ route('invitations.getInvitationsBySenderId', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 1)

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            console.log("getInvitationsBySenderId", response)
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

    url = "{{ route('invitations.getInvitationById', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 4)

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            console.log("getInvitationById", response)
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

    // url = "{{ route('invitations.getInvitationsByStatusAndSenderId', ['status' => '__status__' ]) }}"
    // url = url.replace("__status__", "ACCEPTED")

    // $.ajax({
    //     url,
    //     type: "GET",
    //     success: function (response) {
    //         console.log("getInvitationsByStatus", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr)
    //     }
    // });

    // // can create intervwe many times
    // url = "{{ route('interviews.store') }}"
    // let formData = new FormData()
    // formData.append("invitation_id", 6)
    // formData.append("scheduled_at", "2026-9-30 05:07:17")
    // formData.append("interview_mode", "Online")
    // formData.append("location", "")
    // formData.append("meeting_url", "http://127.0.0.1:8000/recruiter/invitations")
    // formData.append("recruiter_comment", "recruiter_comment recruiter_comment")

    // $.ajax({
    //     url,
    //     data: formData,
    //     type: "POST",
    //     processData: false,
    //     contentType: false,
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    //     },
    //     success: function (response) {
    //         console.log("interviews.store", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr)

    //     }
    // });

    // url = "{{ route('invitations.update',['id' => '__ID__' ]) }}"
    // url = url.replace("__ID__", 10)

    // let formData = new FormData()
    // formData.append("expires_at", "2026-9-30 05:07:17")
    // formData.append("invitation_message", "NOBB")
    // formData.append("_method", "PUT")

    // $.ajax({
    //     url,
    //     data: formData,
    //     type: "POST",
    //     processData: false,
    //     contentType: false,
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    //     },
    //     success: function (response) {
    //         console.log("interviews.update", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr.responseJSON.message)

    //     }
    // });

    url = "{{ route('invitations.withdrawInvitation',['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 14)

    let formData = new FormData()
    formData.append("_method", "PUT")

    $.ajax({
        url,
        data: formData,
        type: "POST",
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function (response) {
            console.log("interviews.update", response)
        },
        error: function (xhr) {
            console.error(xhr.responseJSON.message)

        }
    });
</script>
@endsection