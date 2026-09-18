@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/recruiter.css') }}">

<div class="content p-4 page-container">

    <x-atom.page-header title="Interviews" />

    <div class="shortlist-layout">

        <x-molecule.interview-list />
        <x-atom.interview-detail panel="rec" />

    </div>

</div>

<div class="shortlist-overlay toggleFilter"></div>

<x-interview-modal />
<x-active-educations-modal />

@endsection

@section('script')
<script type="module">

    function handleSelectedIntervieww(id) {
        if (!id) return;

        return window.interviewDetail
            .setRole("recruiter")
            .load(id);
    }

    async function loadData() {
        try {
            let id = "{{ session('user_profile_id') }}"
            let profile = await xApiProfile.getProfileDataByProfileId("{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}", id);
            let organizations = profile.data.organization_users;

            let results = await Promise.all(
                organizations.map(organization => xApiPosition.getPositionsByOrgId("{{ route('positions.getPositionsByOrgId', ['id' => '__ID__']) }}", organization.organization_id))
            );

            interviewList.load("Scheduled")
            interviewModal.init()

        } catch (error) {
            console.error("Ralat semasa loadData:", error);
        }
    }

    $(document).on("click", "#btnCencelInterview", function () {
        let id = $(this).data("id")
        interviewModal.cancelInterview(id)
    })

    $(document).on("click", "#btnCompletedInterview", function(){
        let id = $(this).data("id")
        interviewModal.completeInterview(id)
    })

    $(document).on("click", "#btnUpdateInterview", function () {
        interviewModal.openUpdate(
            window.interviewDetail.current
        );
    });

    $(document).on("click", ".nav-item", function () {
        const status = $(this).data("status");

        interviewList.load(status);
        window.interviewDetail.clear();
    });

    $(document).on("click", "#btnUpdateInterviewSave", function () {
        interviewModal.update(function (updatedInterview) {
            window.interviewDetail
                .setRole("recruiter")
                .reload(updatedInterview);
        });
    });

    $(document).on("click", ".shortlist-item", function () {
        const id = $(this).data("id");
        handleSelectedIntervieww(id)
    });

    $(document).on("click", ".btnSeeMore", function () {
        window.interviewDetail.showEducations();
    });

    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay', toggle);

    $(document).ready(function () {
        const pathParts = window.location.pathname.split("/").filter(Boolean);
        const id = pathParts[pathParts.length - 1];

        if (id && !isNaN(id)) {
            handleSelectedIntervieww(id);
        }

        loadData();
    });

</script>
@endsection
