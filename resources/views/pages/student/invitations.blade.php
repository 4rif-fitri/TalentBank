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
                    <li>
                        <button data-status="Pending" class="nav-link text-body">Pending</button>
                    </li>
                    <li>
                        <button data-status="Accepted" class="nav-link text-body">Accepted</button>
                    </li>
                    <li>
                        <button data-status="Rejected" class="nav-link text-body">Rejected</button>
                    </li>
                    <li>
                        <button data-status="Expired" class="nav-link text-body">Expired</button>
                    </li>
                    <li>
                        <button data-status="Withdrawn" class="nav-link text-body">Withdrawn</button>
                    </li>
                </ul>
            </div>

            <div class="invitation-list d-flex flex-column gap-2 p"></div>

        </aside>

        <div class="filter-panel flex-grow-1">
            <div class="row g-3" id="shortlistContent">

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

<div class="filter-overlay"></div>
@endsection

@section('script')
<script type="module">
    let invitations
    let url
    let currentStatus = "Pending"
    const statusOrder = {
        Pending: 1,
        Accepted: 2,
        Rejected: 3,
        Expired: 4,
        Withdrawn: 5
    };

    function getInvitationById(id) {
        url = "{{ route('invitations.getInvitationById', ['id' => '__ID__' ]) }}"
        url = url.replace("__ID__", id)
        return $.ajax({
            url,
            type: "GET"
        });
    }

    function getInvitationsByStatusAndReceiverId(status) {
        $.ajax({
            url: "{{ route('invitations.getInvitationsByStatusAndReceiverId') }}",
            type: "GET",
            data: { status },
            success: function (response) {
                console.log("getInvitationsByStatusAndReceiverId", response)
                $(".invitation-list").empty()
                invitations = response.data

                invitations.forEach(inv =>{
                    let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + inv.position.organization.organization_logo
                    $(".invitation-list").append(xcommon.studentSideBar(inv, imageUrl));
                })

            },
            error: function (xhr) {
                console.error(xhr)
            }
        });
    }

    async function handleSelectedInvitation() {
        let id = $(this).data('id');

        try {
            let invitationDetail = await getInvitationById(id);
            console.log(invitationDetail);

            $("#shortlistContent").empty()
            let invitation = invitationDetail.data
            let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + invitation.position.organization.organization_logo
            $("#shortlistContent").append(xinvitation.student.mainContent(invitation, imageUrl))

        } catch (error) {
            console.error(error);
        }
    }

    function disabledButton(id) {
        $(".btnContainer")
            .html(`<button disabled data-id=${id} class="btn btn-outline-danger btnRejectInvitation">
                    <i class="fa-regular fa-trash-can text-danger"></i>
                    Decline
                </button>
                <button disabled data-id=${id} class="btn btn-outline-primary btnAcceptInvitation">
                    <i class="fa-solid fa-pen text-primary"></i>
                    Accept Invitation
                </button>`)
    }

    function handleAcceptInvitation () {
        let id = $(this).data("id")
        let $btn = $(this)
        url = "{{ route('invitations.acceptInvitation', ['id' => '__ID__' ]) }}"
        url = url.replace("__ID__", id)
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
                console.log("acceptInvitation", response)
                disabledButton(id)
                let target = invitations.find(inv => inv.id == id);
                if (target) {
                    target.invitation_status = "Accepted";
                }

                salert.salert('Success', response.message, 'success');
                $(`.invitation-item[data-id="${id}"]`).remove()
                $("#shortlistContent").html(`<div class="d-flex flex-column border-0 p-3 d-flex justify-content-center align-items-center ">
                    <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                    <h4 class="mt-2">Select Interview</h4>
                    <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                        <i class="fa-solid fa-filter"></i>
                        Interview
                    </button>
                </div>`)

            },
            error: function (xhr) {
                console.error(xhr)
                salert.salert('Error', xhr.responseJSON?.message, 'error');
            }
        });
    }

    function handleRejectInvitation () {
        let id = $(this).data("id")

        url = "{{ route('invitations.rejectInvitation', ['id' => '__ID__' ]) }}"
        url = url.replace("__ID__", id)

        let data = {
            _method: "PUT",
            _token: $('meta[name="csrf-token"]').attr("content")
        }

        $.ajax({
            url,
            type: "POST",
            data: data,
            success: function (response) {
                console.log("rejectInvitation", response)
                salert.salert('Success', response.message, 'success');
                disabledButton(id)
                let target = invitations.find(inv => inv.id == id);
                if (target) {
                    target.invitation_status = "Rejected";
                }

                salert.salert('Success', response.message, 'success');
                $(`.invitation-item[data-id="${id}"]`).remove()
                $("#shortlistContent").html(`<div class="d-flex flex-column border-0 p-3 d-flex justify-content-center align-items-center ">
                    <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                    <h4 class="mt-2">Select Interview</h4>
                    <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                        <i class="fa-solid fa-filter"></i>
                        Interview
                    </button>
                </div>`)
            },
            error: function (xhr) {
                console.error(xhr)
                salert.salert('Error', xhr.responseJSON?.message, 'error');
            }
        });
    }

    function dataFilter(status) {
        $(".invitation-list").empty()

        invitations.sort((a, b) => statusOrder[a.invitation_status] - statusOrder[b.invitation_status]);

        if (status == "") {
            invitations.forEach(inv => $(".invitation-list").append(invitation.reciverInvitationList(inv)));
            return;
        }

        let filted = invitations.filter(inv => inv.invitation_status == status)
        filted.forEach(inv => $(".invitation-list").append(invitation.reciverInvitationList(inv)));
    }

    function handleChangeStatus () {
        let currentStatus = $(this).data("status")
        getInvitationsByStatusAndReceiverId(currentStatus)
        $(".nav-item button").removeClass("active text-primary").addClass("text-body");
        $(this).find("button").removeClass("text-body").addClass("active text-primary");
    }

    getInvitationsByStatusAndReceiverId(currentStatus)

    $(document).on("click", ".invitation-item", handleSelectedInvitation)
    $(document).on("click", ".btnAcceptInvitation", handleAcceptInvitation)
    $(document).on("click", ".nav-link", handleChangeStatus)
    $(document).on("click", ".btnRejectInvitation", handleRejectInvitation)
    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay, .filter-overlay, .list-item', function () {
        document.body.classList.toggle('filter-open');
    });

</script>
@endsection
