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

    function getInterviewsByStatusAndInterviewerId(status) {
        let url = "{{ route('interviews.getInterviewsByStatusAndInterviewerId') }}"

        $.ajax({
            url,
            data:{ status },
            type: "GET",
            success: function (response) {
                debug.log("getInterviewsByStatusAndInterviewerId", response.data);
                let interviews = response.data
                $("#shortlistList").empty()
                interviews.forEach(interview => {
                    $("#shortlistList").append(intervieww.sidebar(interview))
                });

            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    async function getPositionsByOrgId(id) {
        let url = "{{ route('positions.getPositionsByOrgId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        return await $.ajax({
            url: url,
            method: 'GET'
        });
    }

    async function getProfileDataByProfileId() {
        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");
        let response = await $.ajax({
            url: url,
            method: 'GET'
        });

        return response.data;
    }

    function getPositionById(id) {
        let url = "{{ route('positions.getPositionById', ['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)
        $.ajax({
            url,
            type: "GET",
            success: function (response) {
                debug.log("getPositionById", response.data)
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function getInterviewById(id) {
        let url = "{{ route('interviews.getInterviewById', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }

    function getEducationByUserProfileId(id) {
        let url = "{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }

    // ===== GET ======

    // ===== Handle ======

    async function handleSelectedInterview() {
        let id = $(this).data('id');

        try {
            let interviewDetail = await getInterviewById(id);
            let receiverId = interviewDetail.data.interviewee.id

            let listEducationReceiver = await getEducationByUserProfileId(receiverId);

            curreEducations = listEducationReceiver.data
            currentInterview = interviewDetail.data

            console.log("curreEducations", curreEducations);
            console.log("currentInterview", currentInterview);

            $(".shortlist-content").empty();
            $(".shortlist-content").append(intervieww.mainContent(currentInterview, curreEducations));

        } catch (xhr) {
            xalert.fire("Error", xhr.responseJSON.message, "error")
        }
    }

    function completeInterview() {
        let id = $(this).data("id")

        let url = "{{ route('interviews.completeInterview',['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            '_method': "PUT"
        }

        $.ajax({
            type: "POST",
            url,
            data,
            success: function (response) {
                xalert.fire("Success", "Interview Completed", "success")
            },
            error: function (xhr) {
                xalert.fire("Error", xhr.responseJSON.message, "error")
            }
        });
    }

    function cancelInterview() {
        let id = $(this).data("id")

        let url = "{{ route('interviews.cancelInterview',['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            '_method': "PUT"
        }

        $.ajax({
            type: "POST",
            url,
            data,
            success: function (response) {
                xalert.fire("Success", "Interview Cancelled", "success")
            },
            error: function (xhr) {
                xalert.fire("Error", xhr.responseJSON.message, "error")
            }
        });
    }

    function handleUpdateInterview(e) {
        e.preventDefault();

        let url = "{{ route('interviews.update',['id' => '__ID__' ]) }}"
        url = url.replace("__ID__", currentInterview.id)

        let data = {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "_method": "PUT",
            "scheduled_at": $("#interviewModal #interview_date").val() + " " + $("#interviewModal #start_time").val(),
            "interview_mode": $("input[name='interview_mode']:checked").val(),
            "location": $("#location").val(),
            "meeting_url": $("#meeting_url").val(),
            "recruiter_comment": $("#recruiter_comment").val(),
            "interview_result": currentInterview.interview_result
        }

        $.ajax({
            url,
            data,
            method: "POST",
            success: function (response) {
                xalert.fire("Success", "Interview Updated", "success")
                $("#inviteForm")[0].reset();
                xmodal.hide("interviewModal")
            },
            error: function (xhr) {
                xalert.fire("Error", xhr.responseJSON.message, "error")
            }
        });
    }

    function store(id) {
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

        $.ajax({
            url: "{{ route('interviews.store') }}",
            data,
            method: "POST",
            success: function (response) {
                debug.log("store", response.data);
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
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
        $("#div_meeting_url, #div_location").addClass("d-none");
        $("#meeting_url, #location").prop("required", false);

        if (mode === "Online") {
            $("#div_meeting_url").removeClass("d-none");
            $("#meeting_url").prop("required", true);
        } else if (mode === "On-site") {
            $("#div_location").removeClass("d-none");
            $("#location").prop("required", true);
        }
    }

    function showUpdateInterviewModal() {
        let id = $(this).data("id")
        console.log("currentinterview", currentInterview)
        $("#invite_candidate_id").val(currentInterview.interviewee.id)
        $(".candidate_name").val(currentInterview.interviewee.name)

        $("#interview_date").val(currentInterview.scheduled_at.split(" ")[0])
        $("#interview_time").val(currentInterview.scheduled_at.split(" ")[1])

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
                console.log("education", education)
                let educationHtml = xeducation.student.template(education.programme);
                modalBody.append(educationHtml);
            });
        }

        xmodal.show("activeEducationsModal");
    }

    // ===== Handle ======

    async function loadData() {
        try {
            let profile = await getProfileDataByProfileId();
            let organizations = profile.organization_users;

            let results = await Promise.all(
                organizations.map(organization => getPositionsByOrgId(organization.organization_id))
            );

            getInterviewsByStatusAndInterviewerId("Scheduled")

        } catch (error) {
            console.error("Ralat semasa loadData:", error);
        }
    }

    loadData()
    $(document).on("click", ".btnSeeMoreEducation", handleSeeMoreEducation);
    $(document).on("click", ".shortlist-item", handleSelectedInterview)
    $(document).on("click", ".nav-item button", handleChnageStatus);
    $(document).on("click", "#btnMessageStudent", handleMessageStudent)
    $(document).on("click", "#btnCencelInterview", cancelInterview)
    $(document).on("click", "#btnCompletedInterview", completeInterview)
    $(document).on("click", "#btnReschedule", handleRescheduleInterview)
    $(document).on("click","#btnUpdateInterview", showUpdateInterviewModal)
    $(document).on("submit", "#inviteForm", handleUpdateInterview)
    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay', function () {
        document.body.classList.toggle('filter-open');
    });
    $(document).on("change", "input[name='interview_mode']", function () {
        toggleInterviewMode($(this).val());
    });
</script>
@endsection
