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
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .filter-overlay {
        position: fixed;
        background-color: rgba(0, 0, 0, 0.5);
        inset: 0;
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

        <aside class="card results-panel" id="filterPanel">
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

            <div id="recruitment-invitation-list">

            </div>
        </aside>

        <div class="card filter-panel flex-grow-1">
            <div class="row g-3 ">
                <div class="h-100 border-0 p-3 position-relative">

                    <div class="mb-3 d-flex flex-xl-row flex-column gap-3">
                        <div class="bg-primary"
                            style="width:6rem; height:6rem; border-radius: 50%; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${inv.receiver.profile_image}')">
                        </div>

                        <div>
                            <h3 class="fw-semibold">Dr Lorem Ipsum Dolor Sit Amit</h3>
                            <p class="fw-semibold">Sofware inginer with Honor</p>
                            <small class="text-muted d-block">Universiti Teknikal Malaysia Melaka(UTEM)</small>
                            <div class="badge bg-primary">See More</div>
                            <small class="text-muted d-block">
                                <i class="fa-solid fa-location-dot" style="color: rgb(0, 0, 0);"></i>
                                Durian Tunggal, Malaka, Malaysia
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 border d-block d-xl-flex justify-content-between p-2">
                        <div class="d-flex gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-briefcase bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Position</small>
                                <h5 class="fw-semibold">FrontEnd Intern</h5>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-building bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Department</small>
                                <h5 class="fw-semibold">Department IT and DEV</h5>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">employment_type</small>
                                <h5 class="fw-semibold">employment_type</h5>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 border p-3">
                        <h5 class="fw-semibold">Personal Message</h5>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste reprehenderit veniam sunt dicta
                            iusto, temporibus ex consequatur perferendis quasi. Corrupti, mollitia aperiam! Dolorem,
                            ipsum! Quo expedita reiciendis quae libero accusamus!</p>
                    </div>

                    <div>
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
            $("#recruitment-invitation-list").empty()
            let invitations = response.data
            invitations.forEach(inv => {
                $("#recruitment-invitation-list").append(invitation.recruitmentInvitationList(inv))
            });
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

    // url = "{{ route('invitations.getInvitationById', ['id' => '__ID__' ]) }}"
    // url = url.replace("__ID__", 4)

    // $.ajax({
    //     url,
    //     type: "GET",
    //     success: function (response) {
    //         console.log("getInvitationById", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr)

    //     }
    // });

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

    // url = "{{ route('invitations.withdrawInvitation',['id' => '__ID__' ]) }}"
    // url = url.replace("__ID__", 14)

    // let formData = new FormData()
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
</script>
@endsection