@extends('layouts.internship-layouts')
@php
$list = [
['status' => 'Pending', 'class' => 'active'],
['status' => 'Accepted', 'class' => ''],
['status' => 'Rejected', 'class' => ''],
['status' => 'Expired', 'class' => ''],
['status' => 'Withdrawn', 'class' => ''],
];
@endphp
@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">

<div class="content p-4 page-container">

    <x-atom.page-header title="Invitations" />

    <div class="talent-layout">

        <aside class="results-panel" id="filterPanel">

            <x-molecule.nav-tabs :list="$list" />

            <div class="invitation-list d-flex flex-column gap-2 p-2"></div>

        </aside>

        <div class="filter-panel flex-grow-1">
            <div class="row g-3" id="shortlistContent"></div>
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
                $(".invitation-list").append(xinvitation.student.sideList(inv, imageUrl));
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
        $(".offer-tab").removeClass("active")
        $(this).addClass("active");
        getInvitationsByStatusAndReceiverId(currentStatus)
    }

    getInvitationsByStatusAndReceiverId(currentStatus)

    $(document).on("click", ".invitation-item", handleSelectedInvitation)
    $(document).on("click", "#btnAcceptInvitation", handleAcceptInvitation)
    $(document).on("click", "#btnRejectInvitation", handleRejectInvitation)
    $(document).on("click", ".nav-link", handleChangeStatus)
    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay, .filter-overlay, .list-item', function () {
        document.body.classList.toggle('filter-open');
    });

</script>
@endsection
