@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/recruiter.css') }}">

<div class="content p-4 page-container">

    <x-atom.page-header title="Interviews" />

    <div class="shortlist-layout">

        <x-molecule.interview-list />
        <x-atom.interview-detail />

    </div>

</div>

<div class="shortlist-overlay toggleFilter"></div>

<x-interview-modal />
<x-active-educations-modal />

@endsection

@section('script')
<script type="module">

    let currentStatus
    let currentInterview
    let curreEducations

    // ===== Handle ======

    async function handleSelectedIntervieww(id) {
        try {
            let interviewDetail = await xApiInterview.getInterviewById("{{ route('interviews.getInterviewById', ['id' => '__ID__']) }}", id);
            let receiverId = interviewDetail.data.interviewee.id

            let listEducationReceiver = await xApiEducation.getEducationByUserProfileId("{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}", receiverId);

            curreEducations = listEducationReceiver.data
            currentInterview = interviewDetail.data
            let imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + currentInterview.interviewee.profile_image

            console.log("curreEducations", curreEducations);
            console.log("currentInterview", currentInterview);
            $(".shortlist-content").html(xinterview.recruiter.mainContent(currentInterview, imageUrl, curreEducations));

        } catch (xhr) {
            console.error(xhr);

        }
    }

    function handleSeeMoreEducation() {
        let educationList = curreEducations;
        let modalBody = $("#activeEducationList");
        modalBody.empty();

        if (educationList.length === 0) {
            modalBody.append("<p>No active educations found.</p>");
        } else {
            educationList.forEach(education => {
                let educationHtml = xeducation.student.template(education.programme);
                modalBody.append(educationHtml);
            });
        }

        xmodal.show("activeEducationsModal");
    }

    $(document).on("click", ".btnSeeMore", handleSeeMoreEducation);

    // ============ CLEAR ==================

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
    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay', toggle);

    $(document).ready(function () {
        const pathParts = window.location.pathname.split("/").filter(Boolean);

        const id = pathParts[pathParts.length - 1];

        if (id && !isNaN(id)) {
            handleSelectedIntervieww(id);
        }

        loadData();
    });

    $(document).on("click", "#btnCencelInterview", function () {
        let id = $(this).data("id")
        interviewModal.cancelInterview(id)
    })

    $(document).on("click", "#btnCompletedInterview", function(){
        let id = $(this).data("id")
        interviewModal.completeInterview(id)
    })

    $(document).on("click", "#btnUpdateInterview", function(){
        let id = $(this).data("id")
        interviewModal.openUpdate(interviewDetail.current)
    })

    $(document).on("click", ".nav-item", function(){
        const status = $(this).data("status");
        interviewList.load(status)
        interviewDetail.clear()
    });

    $(document).on("click", ".shortlist-item", function(){
        let id = $(this).data('id');
        interviewDetail.load(id)
    })

    $(document).on("click", "#btnUpdateInterviewSave" , function(){
        interviewModal.update()
    })

</script>
@endsection
