@extends('layouts.internship-layouts')

@section('css')
<style>
    .icon-img {
        background-size: cover;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
    }

    .shortlist-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .shortlist-sidebar {
        width: 400px;
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

    @media (max-width: 1000px) {
        .shortlist-layout {
            display: block;
        }

        .shortlist-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 380px;
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
        <h3 class="m-0 fw-bold">Interviews</h3>
        <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            Upcoming Interview
        </button>
    </div>

    <div class="shortlist-layout">

        <aside class="shortlist-sidebar" id="listContainer">

            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button data-status="Scheduled" class="nav-link active text-primary">Scheduled</button>
                    </li>
                    <li class="nav-item">
                        <button data-status="Completed" class="nav-link text-black">Completed</button>
                    </li>
                    <li class="nav-item">
                        <button data-status="Cancelled" class="nav-link text-black">Cancelled</button>
                    </li>
                    <li class="nav-item">
                        <button data-status="Rescheduled" class="nav-link text-black">Rescheduled</button>
                    </li>
                </ul>
            </div>

            <div id="shortlistList"></div>
        </aside>

        <div id="shortlistContent" class="shortlist-content flex-grow-1 p-2">

            <div class="card bg-body shadow-sm border-0 p-3 d-flex justify-content-center align-items-center ">
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

<div class="shortlist-overlay toggleFilter"></div>

<x-interview-modal />
<x-active-educations-modal />

@endsection

@section('script')
<script type="module">

    let currentStatus
    let currentInterview
    let curreEducations

    // ===== GET ======

    async function getInterviewsByStatusAndInterviewerId(status) {

        try {
            let response = await xApiInterview.getInterviewsByStatusAndInterviewerId("{{ route('interviews.getInterviewsByStatusAndInterviewerId') }}", status)
            if(!response) return

            let interviews = response.data
            $("#shortlistList").empty()
            interviews.forEach(interview => {
                $("#shortlistList").append(xinterview.recruiter.sidebar(interview))
            });

        } catch (error) {
            console.error(error);
        }
    }

    // ===== GET ======

    // ===== Handle ======

    async function handleSelectedInterview() {
        let id = $(this).data('id');

        try {
            let interviewDetail = await xApiInterview.getInterviewById("{{ route('interviews.getInterviewById', ['id' => '__ID__']) }}",id);
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

    async function completeInterview() {
        let id = $(this).data("id")

        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            '_method': "PUT"
        }

        try {
            let response = await xApiInterview.completeInterview("{{ route('interviews.completeInterview',['id' => '__ID__']) }}", id, data)
            if(!response) return

            $(`#shortlistList .shortlist-item[data-id="${response.data.id}"]`).remove();
            $(".results-panel").html(xcommon.noSelected("No Job Offer Selected", "", "btn-toggle-filter toggleFilter", "Interview"))

            xalert.fire("Success", "Interview Completed", "success")

        } catch (error) {
            console.error(error);
        }
    }

    async function cancelInterview() {
        let id = $(this).data("id")

        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            '_method': "PUT"
        }

        try {
            let response = await xApiInterview.cancelInterview("{{ route('interviews.cancelInterview',['id' => '__ID__']) }}", id, data)
            if (!response) return

            $(`#shortlistList .shortlist-item[data-id="${response.data.id}"]`).remove();
            $(".results-panel").html(xcommon.noSelected("No Job Offer Selected", "", "btn-toggle-filter toggleFilter", "Interview"))

            xalert.fire("Success", "Interview Completed", "success")

        } catch (error) {
            console.error(error);
        }
    }

    async function handleUpdateInterview(e) {
        e.preventDefault();

        let data = {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "_method": "PUT",
            "scheduled_at": $("#interviewModal #interview_date").val() + " " + $("#interviewModal #start_time").val() + ":00",
            "interview_mode": $("input[name='interview_mode']:checked").val(),
            "location": $("#location").val(),
            "meeting_url": $("#meeting_url").val(),
            "recruiter_comment": $("#recruiter_comment").val(),
            "interview_result": currentInterview.interview_result
        }
        console.log(data);

        try {
            let response = await xApiInterview.update(
                "{{ route('interviews.update',['id' => '__ID__' ]) }}",
                currentInterview.id,
                data
            )

            if(!response) return

            console.log(response);

            xalert.fire("Success", "Interview Updated", "success")
            $("#inviteForm")[0].reset();
            xmodal.hide("interviewModal")

        } catch (error) {
            console.error(error);
        }
    }

    async function handleAddInterview(id) {
        let data = {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "scheduled_at": "2026-9-30 05:07:17",
            "interview_mode": "Online",
            "location": "",
            "meeting_url": "http://127.0.0.1:8000/recruiter/invitations",
            "recruiter_comment": "!!!",
            "interview_result": "Passed",
            "invitation_id": id
        }

        try {
            let response = await xApiInterview.store("{{ route('interviews.store') }}", data)
            if(!response) return

            console.log(response);
        } catch (error) {
            console.error(error);
        }
    }

    function handleChnageStatus() {
        $(".nav-item button").removeClass("active text-primary").addClass("text-black");
        $(this).find("button").removeClass("text-black").addClass("active text-primary");
        let status = $(this).data("status")

        $(".shortlist-content").html(xjobOffer.recruiter.noInterviewSelected())

        if (currentStatus == status) return
        currentStatus = status
        getInterviewsByStatusAndInterviewerId(currentStatus)
    }

    function handleMessageStudent() {
        let id = $(this).data("id")
    }

    function handleRescheduleInterview() {
        let id = $(this).data("id")
    }

    function toggleInterviewMode(mode) {
        const isOnline = mode === "Online";
        const isOnSite = mode === "On-site";
        const isPhone  = mode === "Phone";

        $("#div_meeting_url").toggleClass("d-none", !isOnline);
        $("#div_location").toggleClass("d-none", !isOnSite);
        $("#div_phone").toggleClass("d-none", !isPhone);

        $("#mode_online").prop("checked", isOnline);
        $("#mode_onsite").prop("checked", isOnSite);
        $("#mode_phone").prop("checked", isPhone);

        $("#meeting_url").prop("required", isOnline);
        $("#location").prop("required", isOnSite);
        $("#phone_number").prop("required", isPhone);

        if (!isOnline) $("#meeting_url").val("");
        if (!isOnSite) $("#location").val("");
        if (!isPhone)  $("#phone_number").val("");
    }

    function showUpdateInterviewModal() {
        let id = $(this).data("id")
        console.log("currentinterview", currentInterview)

        console.log({currentInterview});

        $("#invite_candidate_id").val(currentInterview.interviewee.id)
        $(".candidate_name").val(currentInterview.interviewee.name)

        $("#interview_date").val(currentInterview.scheduled_at.split(" ")[0])
        $("#start_time").val(currentInterview.scheduled_at.split(" ")[1].substring(0, 5))

        $("#mode_online").prop("checked", currentInterview.interview_mode == "Online")
        $("#mode_onsite").prop("checked", currentInterview.interview_mode == "On-site")
        $("#mode_phone").prop("checked", currentInterview.interview_mode == "Phone")

        toggleInterviewMode(currentInterview.interview_mode);

        $("#recruiter_comment").val(currentInterview.recruiter_comment)
        $("#meeting_url").val(currentInterview.meeting_url)
        $("#location").val(currentInterview.location)

        $("#btnAddInterview").hide()
        $("#btnUpdateInterview").show()
        xmodal.show("interviewModal")
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

    async function loadData() {
        try {
            let id = "{{ session('user_profile_id') }}"
            let profile = await xApiProfile.getProfileDataByProfileId("{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}", id);
            let organizations = profile.data.organization_users;

            let results = await Promise.all(
                organizations.map(organization => xApiPosition.getPositionsByOrgId("{{ route('positions.getPositionsByOrgId', ['id' => '__ID__']) }}", organization.organization_id))
            );

            getInterviewsByStatusAndInterviewerId("Scheduled")

        } catch (error) {
            console.error("Ralat semasa loadData:", error);
        }
    }

    loadData()
    $(document).on("click", ".btnSeeMore", handleSeeMoreEducation);

    $(document).on("click", ".shortlist-item", handleSelectedInterview)

    $(document).on("click", ".nav-item button", handleChnageStatus);

    $(document).on("click", "#btnMessageStudent", handleMessageStudent)

    $(document).on("click", "#btnCencelInterview", cancelInterview)

    $(document).on("click", "#btnCompletedInterview", completeInterview)

    $(document).on("click", "#btnReschedule", handleRescheduleInterview)

    $(document).on("click","#btnUpdateInterview", showUpdateInterviewModal)

    $(document).on("submit", "#inviteForm", handleUpdateInterview)

    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay', toggle);

    $(document).on("change", "input[name='interview_mode']", function () {
        toggleInterviewMode($(this).val());
    });
</script>
@endsection
