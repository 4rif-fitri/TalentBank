@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/recruiter.css') }}">

<div class="content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Invitations</h3>
        <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            invitation
        </button>
    </div>

    <div class="shortlist-layout">

        <x-molecule.invitation-list />

        <x-atom.invitation-detail />

    </div>
</div>

<div class="shortlist-overlay toggleFilter"></div>

<x-modals.invitation-modal />
<x-modals.active-educations-modal />

@endsection

@section('script')
<script type="module">

// ======================= CLEAR ==============================
    $(document).ready(function () {
        invitationList.load("Pending");
    });

    $(document).on("click", ".toggleFilter", toggle)

    $(document).on("click", ".nav-item", function () {
        const status = $(this).data("status");
        invitationList.load(status);
        invitationDetail.clear()
    });

    $(document).on("click",".invitation-item",function () {
        const id = $(this).data("id");
        invitationDetail.load(id);
    });

    $(document).on("click", ".btn-withdraw-invitation",  function () {
        const id = $(this).data("id");

        invitationModal.Withdraw(id, {
            onSuccess: async response => {
                await invitationList.refresh();

                $("#shortlistContent").html(
                    xcommon.noSelected("No Invitation Selected Yet","","btn-toggle-filter toggleFilter","Invitation")
                );
            },
            onError: response => {
                console.error(response);
            }
        });

    });

    $(document).on("click",".btn-edit-invitation", function(){
        invitationModal.openUpdate(
            invitationDetail.current, {
            onSuccess: response => {
                invitationDetail.reload(response.data)
            },
            onError: xhr => {
                console.error(xhr);
            }
        })
    })

</script>
@endsection



