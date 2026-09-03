@extends('layouts.internship-layouts')

@section('css')
<style>
    .shortlist-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .shortlist-sidebar {
        width: 420px;
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
            width: 450px !important;
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
        <h3 class="m-0 fw-bold">Invitations</h3>
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
                    <li class="nav-item">
                        <a class="nav-link active text-primary" aria-current="page" href="#">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="#">Appected</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="#">Rejected</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="#">Expired</a>
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
    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }

    $(document).on("click", ".toggleFilter", toggleFilter)
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

    function getInvitationById(id) {
        url = "{{ route('invitations.getInvitationById', ['id' => '__ID__' ]) }}"
        url = url.replace("__ID__", id)

        return $.ajax({
            url,
            type: "GET",
        });
    }

    async function handleSelectedInvitation() {
        let id = $(this).data('id');

        try {
            let inv = await getInvitationById(id)
            console.log(inv);

            $("#shortlistContent").empty()
            $("#shortlistContent").append(invitation.mainContent(inv.data))

        } catch (xhr) {
            console.error(xhr);
        }
    }

    $(document).on("click", ".invitation-item", handleSelectedInvitation)

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
