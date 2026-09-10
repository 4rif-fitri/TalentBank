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

    .list-item:hover {
        border: 1px solid #6d7eca !important;
        color: #6d7eca;

        small {
            color: #6d7eca !important;
        }

    }

    .list-item.active {
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

            <div class="d-flex flex-column justify-content-between mb-1 pb-1">
                <div class="d-flex justify-content-between w-100">
                    <h5 class="m-0 fw-bold">Your invitation</h5>
                    <button type="button"
                        class="btnShowModalAddShortlist btn btn-outline-primary d-flex justify-content-center align-items-center">
                        <i class="fa-solid fa-plus fs-5"></i>
                    </button>
                </div>

                <ul class="nav nav-tabs">
                    <li data-status="Pending" class="nav-item">
                        <button class="nav-link active text-primary">Pending</button>
                    </li>
                    <li data-status="Accepted" class="nav-item">
                        <button class="nav-link text-black">Appected</button>
                    </li>
                    <li data-status="Rejected" class="nav-item">
                        <button class="nav-link text-black">Rejected</button>
                    </li>
                    <li data-status="Expired" class="nav-item">
                        <button class="nav-link text-black">Expired</button>
                    </li>
                    <li data-status="Withdrawn" class="nav-item">
                        <button class="nav-link text-black">Withdrawn</button>
                    </li>
                </ul>
            </div>

            <div id="recruitment-invitation-list" class="p-2 d-flex gap-2 flex-column"></div>
        </aside>

        <div id="shortlistContent" class="shortlist-content flex-grow-1 p-2">

            <div class="card bg-body border-0 p-3 d-flex flex-column align-items-center">
                <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                <h4 class="mt-2">No Invitation Selected Yet</h4>
                <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                    <i class="fa-solid fa-filter"></i>
                    Invitations
                </button>
            </div>

        </div>

    </div>
</div>

<div class="shortlist-overlay toggleFilter"></div>

<x-modals.invitation-modal />
<x-modals.active-educations-modal />
@endsection

@section('script')
<script type="module">
    let currentStatus = "Pending"
    let currentInv
    let currentInviteEducatios


    async function handleSelectedInvitation() {
        let id = $(this).data('id');

        try {
            let inv = await xApiInvite.getInvitationById("{{ route('invitations.getInvitationById', ['id' => '__ID__' ]) }}", id);
            if(!inv) return

            currentInv = inv.data

            currentInviteEducatios = await xApiEducation.getEducationById("{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}",currentInv.receiver.id)
            if(!currentInviteEducatios) return

            currentInviteEducatios = currentInviteEducatios.data
            let imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + currentInv.receiver.profile_image

            $("#shortlistContent").html(xinvitation.recruiter.mainContent(inv.data, imageUrl, currentInviteEducatios))

        } catch (xhr) {
            console.error(xhr);
        }
    }

    async function getInvitationsByStatusAndSenderId(status) {

        try {
            let response = await xApiInvite.getInvitationsByStatusAndSenderId("{{ route('invitations.getInvitationsByStatusAndSenderId') }}", status)

            let invitations = response.data
            $("#recruitment-invitation-list").empty()
            invitations.forEach(inv => {
                let imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + inv.receiver.profile_image
                $("#recruitment-invitation-list").append(xinvitation.recruiter.recruitmentInvitationList(inv, imageUrl))
            });

        } catch (error) {
            console.error(error);
        }
    }

    // function handleAddInvitation(
    //     invitation_id, scheduled_date,
    //     scheduled_hour, interview_mode,
    //     location, meeting_url,
    //     recruiter_comment
    // ) {



    //     // let scheduled_at =

    //     formData.append("recruiter_comment", "recruiter_comment recruiter_comment")

    //     let data = {
    //         _token: $('meta[name="csrf-token"]').attr("content"),
    //         invitation_id,
    //         scheduled_at,
    //         interview_mode,
    //         meeting_url,
    //         recruiter_comment
    //     }

    //     $.ajax({
    //         url: "{{ route('interviews.store') }}",
    //         data,
    //         type: "POST",
    //         success: function (response) {
    //             xalert.salert('Success', response.message, 'success');
    //         },
    //         error: function (xhr) {
    //             xalert.salert('Error', xhr.responseJSON.message, 'error');
    //         }
    //     });
    // }

    function handleChangeStatus(){
        $(".nav-item button").removeClass("active text-primary").addClass("text-black");
        $(this).find("button").removeClass("text-black").addClass("active text-primary");
        let status = $(this).data("status")

        $("#shortlistContent").html(xcommon.noSelected("No Invitation Selected Yet", "", "btn-toggle-filter toggleFilter", "Invitation"))

        if (currentStatus == status) return
        currentStatus = status

        getInvitationsByStatusAndSenderId(status)
    }

    function handleWithdrawInvitation(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Withdraw it!"
        }).then( async (result) => {
            if (!result) return

            let id = $(this).data('id');

            let data = {
                _method: "PUT",
                _token:  $('meta[name="csrf-token"]').attr("content")
            }

            try {
                let response =  await xApiInvite.withdrawInvitation("{{ route('invitations.withdrawInvitation',['id' => '__ID__' ]) }}", id, data)
                if(!response) return

                console.log("interviews.update", response)
                xalert.salert('Success', response.message, 'success');

                $(`#recruitment-invitation-list .invitation-item[data-id="${response.data.id}"]`).remove();

                $("#shortlistContent").html(xcommon.noSelected("No Invitation Selected Yet","","btn-toggle-filter toggleFilter","Invitation"))
                getInvitationsByStatusAndSenderId(currentStatus)

            } catch (error) {
                console.error(error);
            }
        });
    }

    function handleEditInvite() {
        let id = $(this).data('id');

        $("#invite_candidate").val(currentInv.receiver.name)
        $("#invite_position_title").val(currentInv.position.position_title)
        $("#expires_at").val(currentInv.expires_at)
        $("#invitation_message").val(currentInv.invitation_message)

        $("#inviteForm").find("#btnAddInvitation").hide()

        $("#inviteForm").find("#btnAddInvitation").hide()
        $("#inviteForm").find("#btnUpdateInvitation").show()
        xmodal.show("invitationModal")
    }

    async function handleUpdateInviteForm(e){
        e.preventDefault();

        let data = {
            _method:"PUT",
            _token: $('meta[name="csrf-token"]').attr("content"),
            invitation_message: $("#invitation_message").val(),
            expires_at: $("#expires_at").val()
        }

        try {
            let response = await xApiInvite.update("{{ route('invitations.update', ['id' => '__ID__']) }}", currentInv.id, data)
            if(!response) return

            xalert.salert('Success', response.message, 'success');
            xmodal.hide("invitationModal")
            $("#inviteForm")[0].reset()

        } catch (error) {
            console.error(error);
        }
    }

    function handleMessageStudent(){

    }

    function handleSeeMoreEdu() {
        xmodal.show("activeEducationsModal")
        $("#activeEducationList").empty()
        console.log({ currentInviteEducatios });

        if (currentInviteEducatios.length == 0) {
            $("#activeEducationList").append(xeducation.student.emptyEducation())

        } else {
            currentInviteEducatios.forEach(programme => {
                $("#activeEducationList").append(xeducation.recruiter.template(programme))
            });
        }
    }

    $(document).on("click", ".nav-item", handleChangeStatus)
    $(document).on("click", ".btn-withdraw-invitation", handleWithdrawInvitation)
    $(document).on("click", ".btn-edit-invitation", handleEditInvite)
    $(document).on("click", ".btnSeeMore", handleSeeMoreEdu)
    $(document).on("click", ".btn-message-student", handleMessageStudent)
    $(document).on("click", ".invitation-item", handleSelectedInvitation)
    $(document).on("submit", "#inviteForm", handleUpdateInviteForm)
    $(document).on("click", ".toggleFilter", function () {
        document.body.classList.toggle('filter-open');
    })
    getInvitationsByStatusAndSenderId("Pending")

</script>
@endsection
