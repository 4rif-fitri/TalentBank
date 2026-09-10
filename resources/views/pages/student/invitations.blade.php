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
        .results-panel {
            padding: 0rem;
        }

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
            transform: translateY(0%);
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

    async function getInvitationsByStatusAndReceiverId(status) {
        try {
            let response = await xApiInvite.getInvitationsByStatusAndReceiverId("{{ route('invitations.getInvitationsByStatusAndReceiverId') }}",status)
            if(!response) return

            console.log("getInvitationsByStatusAndReceiverId", response)
            $(".invitation-list").empty()
            invitations = response.data

            invitations.forEach(inv =>{
                let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + inv.position.organization.organization_logo
                $(".invitation-list").append(xcommon.studentSideBar(inv, imageUrl));
            })

        } catch (error) {
            console.error(error)
        }
    }

    async function handleSelectedInvitation() {
        let id = $(this).data('id');

        try {
            let invitationDetail = await xApiInvite.getInvitationById("{{ route('invitations.getInvitationById', ['id' => '__ID__' ]) }}",id);
            $("#shortlistContent").empty()

            let invitation = invitationDetail.data
            let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + invitation.position.organization.organization_logo
            $("#shortlistContent").append(xinvitation.student.mainContent(invitation, imageUrl))

        } catch (error) {
            console.error(error);
        }
    }

    async function handleAcceptInvitation () {
        let id = $(this).data("id")

        let data = {
            _method: "PUT",
            _token: $('meta[name="csrf-token"]').attr("content")
        }

        try {
            let response = await xApiInvite.acceptInvitation("{{ route('invitations.acceptInvitation', ['id' => '__ID__' ]) }}", id, data)
            if(!response) return

            xalert.fire('Success', response.message, 'success');

            $(`.invitation-item[data-id="${id}"]`).remove()
            $("#shortlistContent").empty()

        } catch (error) {
            console.error(error);
        }
    }

    async function handleRejectInvitation () {
        let id = $(this).data("id")

        let data = {
            _method: "PUT",
            _token: $('meta[name="csrf-token"]').attr("content")
        }

        try {
            let response = await xApiInvite.rejectInvitation("{{ route('invitations.rejectInvitation', ['id' => '__ID__' ]) }}", id, data)
            if(!response) return

            xalert.fire('Success', response.message, 'success');

            $(`.invitation-item[data-id="${id}"]`).remove()
            $("#shortlistContent").empty()

        } catch (error) {
            console.error(error);
        }
    }

    function handleChangeStatus () {
        let currentStatus = $(this).data("status")
        $(".nav-item button").removeClass("active text-primary").addClass("text-body");
        $(this).find("button").removeClass("text-body").addClass("active text-primary");
        getInvitationsByStatusAndReceiverId(currentStatus)
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
