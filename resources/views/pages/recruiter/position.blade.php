@extends('layouts.internship-layouts')

@section('css')
<style>
    .shortlist-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .shortlist-sidebar {
        width: 350px;
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
        <h3 class="m-0 fw-bold">Positions</h3>
        <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            Positions
        </button>
    </div>

    <div class="shortlist-layout">

        <aside class="shortlist-sidebar" id="listContainer">

            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                <h5 class="m-0 fw-bold">Your Positions</h5>
                <button type="button"
                    class="btnShowModalAddShortlist btn btn-outline-primary d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-plus fs-5"></i>
                </button>
            </div>

            <div id="shortlistList"></div>

        </aside>

        <div class="shortlist-content flex-grow-1">

            <div class="row g-3" id="shortlistContent">
                <div class="card shadow-sm border-0 p-3 d-flex justify-content-center align-items-center ">
                    <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                    <h4 class="mt-2">No Position Selected Yet</h4>
                    <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                        <i class="fa-solid fa-filter"></i>
                        Positions
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="shortlist-overlay toggleFilter"></div>

<x-modals.position-modal />
<x-modals.invitation-modal />
<x-modals.interview-modal />

<x-modals.list-interview-modal />
<x-modals.list-invitation-modal />
<x-modals.list-jobOffer-modal />

@endsection

@section('script')
<script type="module">

    let myData, positionId, curruntPosition, candidateList, curruntCandidate

    // < ---- STORE ------ >

    async function storePosition(position_title, employment_type, vacancies, department, work_location, description) {

        let data = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            organization_id: myData.organization_users[0].organization_id,
            position_title: position_title,
            employment_type: employment_type,
            department: department,
            work_location: work_location,
            vacancies: vacancies,
            description: description
        }

        try {
            let response = await xApiPosition.store("{{ route('positions.store') }}", data)
            if(!response) return

            xalert.success("Success", response.message)
            handleCloseModalPosition()

            loadData()

        } catch (error) {
            console.error(error);
        }
    }

    async function storeInvitations(candidate_id, position_id, expires_at, invitation_message, title) {

        let data = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            receiver_profile_id: candidate_id,
            invitation_message: invitation_message,
            expires_at: expires_at,
            position_id: position_id,
            title
        }

        try {
            let response = await xApiInvite.store("{{ route('invitations.store') }}", data)
            if(!response) return

            let $row = $("#tableDetail").find(`tr[data-id=${response.data.receiver.id}]`);
            $row.find(".text-status").text("Invited")
            $row.find(".list-item-add-invite").hide()
            $row.find(".list-item-cancel-invite").hide()

            xalert.success("Success", response.message)
            xmodal.hide("invitationModal")
            $("#invitationModal").find("form")[0].reset()
            console.log(response.data);

        } catch (error) {
            console.error(error);
        }
    }

    async function storeInterview(position_id, scheduled_at, interview_mode, location, meeting_url, recruiter_comment, interviewee_profile_id, title){
        let data = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            position_id,
            scheduled_at,
            interview_mode,
            location,
            meeting_url,
            recruiter_comment,
            interviewee_profile_id,
            title
        }

        console.table(data);


        try {
            let response = await xApiInterview.store("{{ route('interviews.store') }}",data)
            if(!response) return

            xalert.success("Success", response.message)
            xmodal.hide("interviewModal")
        } catch (error) {
            console.error(error);
            xalert.error("Faild", error.responseJSON.message)
        }
    }

    async function storeJobOffer($form){

        let data = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            salary_amount: $form.find("#salary_amount").val(),
            salary_period: $form.find("#salary_period").val(),
            start_date: $form.find("#start_date").val(),
            end_date: $form.find("#end_date").val(),
            terms_and_conditions: $form.find("#terms_and_conditions").val(),
            benefits: $form.find("#benefits").val(),
            expires_at: $form.find("#expires_at").val(),
            position_id: $form.find("#jobOfferPositionId").val(),
            receiver_profile_id: $form.find("#job-offer-candicate-id").val()
        }

        try {
            let response = await xApiJobOffer.store("{{ route('jobOffers.store') }}", data)
            if(!response) return

            xalert.fire("Success", response.message, "success")
            xmodal.hide("jobOfferModal")

        } catch (error) {
            console.error(error);

        }
    }

    // < ---- STORE ------ >

    // < ---- UPDATE ------ >

    async function updatePositions(id, position_title, employment_type, vacancies, department, work_location, description) {
        let data = {
            organization_id:myData.organization_users[0].organization_id,
            position_title: position_title,
            employment_type: employment_type,
            department: department,
            work_location: work_location,
            vacancies: vacancies,
            description: description,
            _method: "PUT",
            _token: $('meta[name="csrf-token"]').attr("content")
        }

        try {
            let response = await xApiPosition.update("{{ route('positions.update', ['id' => '__ID__']) }}", id, data)
            if(!response) return
            console.log("response", response);
            $(".ui_position_title").text(response.data.position_title)
            $(".ui_description").text(response.data.description)
            $(".ui_work_location").text(response.data.work_location)
            $(".ui_employment_type").text(response.data.employment_type)
            $(".ui_department").text(response.data.department)
            $(".ui_vacancies").text(response.data.vacancies)

            xalert.success("Success", response.message)

            handleCloseModalPosition()

        } catch (error) {
            console.error(error);
        }
    }

    // < ---- UPDATE ------ >

    async function handleClickShortlist() {
        let newPositionId = $(this).data('id');
        if (newPositionId === positionId) return
        positionId = newPositionId

        try {
            $(".shortlist-item").each(function () {
                $(this).removeClass("active");
            });

            $(this).addClass("active")

            let response = await xApiPosition.getPositionById("{{ route('positions.getPositionById', ['id' => '__ID__']) }}",positionId)
            if (!response) return
            curruntPosition = response.data
            console.log(curruntPosition);

            candidateList = response.data.shortlist_users

            xshortList.detail(response.data)

        } catch (error) {
            console.error(error);
        }

    }

    async function handleAddShortlist(e) {
        e.preventDefault();

        let form = $(this).closest("form");

        let position_title = form.find("#position_title").val()
        let employment_type = form.find("#employment_type").val()
        let vacancies = form.find("#vacancies").val()
        let department = form.find("#department").val()
        let work_location = form.find("#work_location").val()
        let description = form.find("#description").val()

        if (position_title == "") {
            xalert.fire("Validation Error", "Please enter a position title", "warning");
            return
        }

        if (employment_type == "") {
            xalert.fire("Validation Error", "Please select employment type", "warning");
            return
        }

        if (vacancies == "") {
            xalert.fire("Validation Error", "Please enter a vacancies", "warning");
            return
        }

        if (department == "") {
            xalert.fire("Validation Error", "Please enter a department", "warning");
            return
        }

        if (work_location == "") {
            xalert.fire("Validation Error", "Please enter a work location", "warning");
            return
        }

        try {

            let response = await storePosition(position_title, employment_type, vacancies, department, work_location, description)

            if (!response) return

            xalert.fire('Success', response.message, 'success');
            form[0].reset();
            bootstrap.Modal .getOrCreateInstance($("#shortlistModal")).hide();

            xshortList.appendNew(response.data)

        } catch (xhr) {
            console.error(xhr);
            xalert.fire("Error", xhr, "error")
        }

    }

    async function handleUpdateShortlist(e) {
        e.preventDefault();

        let form = $(this).closest("form");

        let position_id = form.find("#position_id").val()
        let position_title = form.find("#position_title").val()
        let employment_type = form.find("#employment_type").val()
        let vacancies = form.find("#vacancies").val()
        let department = form.find("#department").val()
        let work_location = form.find("#work_location").val()
        let description = form.find("#description").val()

        if (position_id == "") {
            xalert.fire("Validation Error", "Position id is NULL", "warning");
            return
        }

        if (position_title == "") {
            xalert.fire("Validation Error", "Please enter a position title", "warning");
            return
        }

        if (employment_type == "") {
            xalert.fire("Validation Error", "Please select employment type", "warning");
            return
        }

        if (vacancies == "") {
            xalert.fire("Validation Error", "Please enter a vacancies", "warning");
            return
        }

        if (department == "") {
            xalert.fire("Validation Error", "Please enter a department", "warning");
            return
        }

        if (work_location == "") {
            xalert.fire("Validation Error", "Please enter a work location", "warning");
            return
        }

        try {

            let response = await updatePositions(parseInt(position_id), position_title, employment_type, vacancies, department, work_location, description)
            if (!response) return

            xalert.fire('Success', response.message, 'success');
            form[0].reset();
            bootstrap.Modal .getOrCreateInstance($("#shortlistModal")).hide();

            shortListRender.detail(response.data)
            let $row = $("#shortlistList").find(`[data-id="${response.data.id}"]`)
            $row.find(".title").text(response.data.position_title)

        } catch (xhr) {
            console.error(xhr);
            xalert.fire("Error", xhr, "error")
        }
    }

    async function handleAddInvitation(e) {
        e.preventDefault()

        let today = new Date().toISOString().split('T')[0];
        let form = $(this).closest("form");

        let candidate_id = form.find("#invite_candidate_id").val()
        let position_id = form.find("#invite_position_id").val()
        let expires_at = form.find("#expires_at").val()
        let title = form.find("#invitation_title").val()
        let invitation_message = form.find("#invitation_message").val()

        if (title == "") {
            xalert.fire("Validation Error", "Title id is NULL", "warning");
            return
        }

        if (candidate_id == "") {
            xalert.fire("Validation Error", "Candidte id is NULL", "warning");
            return
        }

        if (position_id == "") {
            xalert.fire("Validation Error", "Position id is NULL", "warning");
            return
        }

        if (expires_at == "") {
            xalert.fire("Validation Error", "Please select expires date ", "warning");
            return
        }

        if (expires_at < today) {
            xalert.fire("Validation Error", "Expiration date cannot be in the past", "warning");
            return;
        }

        if (invitation_message == "") {
            xalert.fire("Validation Error", "Please enter a vacancies", "warning");
            return
        }

        try {
            let response = await storeInvitations(candidate_id, position_id, expires_at, invitation_message,title)
            if (!response) return

            xalert.fire('Success', response.message, 'success');


        } catch (xhr) {
            console.error(xhr);
            xalert.fire("Error", xhr.responseJSON.message, "error")
        }
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

    async function handleInviteForm(e){
        e.preventDefault();

        let interviewMode = $('input[name="interview_mode"]:checked').val();

        let interviewDate = $("#interview_date").val();
        let startTime = $("#start_time").val();

        let data = {
            position_id: curruntPosition.id,
            interviewee_profile_id: curruntCandidate.id,
            title: $("#invitation_title").val(),
            scheduled_at: `${interviewDate} ${startTime}`,
            interview_mode: interviewMode,
            location: $("#location").val(),
            meeting_url: $("#meeting_url").val(),
            recruiter_comment: $("#recruiter_comment").val(),
            _token: $('meta[name="csrf-token"]').attr("content")
        };

        if (!data.interview_mode) {
            xalert.fire("Validation Error", "Please select an interview mode", "warning");
            return;
        }

        try {
            let response = await xApiInterview.store("{{ route('interviews.store') }}", data)
            if(!response) return

            bootstrap.Modal .getInstance($("#interviewModal")).hide();
            xalert.fire("Success", response.message, "success");
            // Reload table/datatable jika perlu:
            // table.ajax.reload();

        } catch (error) {
            console.error(error);
        }
    }


    function showModalAddShortlist(){
        $("#listInvitationModal").hide()
        $("#btnUpdateShortlist").hide()
        xmodal.show("shortlistModal")
    }

    function showModalUpdateShortlist(){
        $("#shortlistModal #position_id").val(curruntPosition.id)
        $("#shortlistModal #position_title").val(curruntPosition.position_title)
        $("#shortlistModal #employment_type").val(curruntPosition.employment_type)
        $("#shortlistModal #vacancies").val(curruntPosition.vacancies)
        $("#shortlistModal #department").val(curruntPosition.department)
        $("#shortlistModal #work_location").val(curruntPosition.work_location)
        $("#shortlistModal #description").val(curruntPosition.description)

        $("#btnAddShortlist").hide()
        $("#btnUpdateShortlist").show()
        xmodal.show("shortlistModal")
    }

    function showModalAddInvite(){
        $("#btnAddInvitation").show()
        $("#btnUpdateInvitation").hide()

        let profileId = $(this).attr("data-id")
        let candidte = candidateList.find(user => user.id == profileId)
        $("#invite_candidate_id").val(profileId)
        $("#invite_candidate").val(candidte.name)
        $("#invite_position_title").val(curruntPosition.position_title)
        $("#invite_position_id").val(curruntPosition.id)
        xmodal.show("invitationModal")
    }

    let currentUserId
    function showModalAddInviteId() {
        $("#btnAddInvitation").show()
        $("#btnUpdateInvitation").hide()

        let profileId = currentUserId

        let candidte = candidateList.find(user => user.id == profileId)
        console.error(candidte);

        $("#invite_candidate_id").val(profileId)
        $("#invite_candidate").val(candidte.name)
        $("#invite_position_title").val(curruntPosition.position_title)
        $("#invite_position_id").val(curruntPosition.id)
        xmodal.show("invitationModal")
        xmodal.hide("listInvitationModal")

    }

    function showModalAddinterview(){
        $("#inviteForm")[0].reset();
        $("#invitation_id").val("");

        $(".invitation_id").val(curruntPosition.id)

        let profileId = $(this).attr("data-id")
        let candidte = candidateList.find(user => user.id == profileId)
        curruntCandidate = candidte
        $(".candidate_name").val(candidte.name)


        let today = new Date().toISOString().split("T")[0];
        $("#interview_date").attr("min", today).val(today);
        $("#start_time").val("10:00");

        let candidateName = $(this).data("candidate-name") || "";
        let candidateId = $(this).data("candidate-id") || "";
        $("#invite_candidate").val(candidateName);
        $("#invite_candidate_id").val(candidateId);

        $("#interviewModalLabel").text("Schedule Interview");
        $("#btnAddInterview").show();
        $("#btnUpdateInterview").hide();

        xmodal.show("interviewModal")
    }

        function showModalAddintervieww() {
                $("#inviteForm")[0].reset();
                $("#invitation_id").val("");

                $(".invitation_id").val(curruntPosition.id)

                let profileId = currentUserId
                let candidte = candidateList.find(user => user.id == profileId)
                curruntCandidate = candidte
                console.log(profileId);
                console.log(candidateList);

                $(".candidate_name").val(candidte.name)


                let today = new Date().toISOString().split("T")[0];
                $("#interview_date").attr("min", today).val(today);
                $("#start_time").val("10:00");

                let candidateName = $(this).data("candidate-name") || "";
                let candidateId = $(this).data("candidate-id") || "";
                $("#invite_candidate").val(candidateName);
                $("#invite_candidate_id").val(candidateId);

                $("#interviewModalLabel").text("Schedule Interview");
                $("#btnAddInterview").show();
                $("#btnUpdateInterview").hide();

                xmodal.show("interviewModal")
                xmodal.hide("listInterviewModal")
                $("#btnUpdateInterview").hide()
            }

    function handleAddInterview(){


        let interviewMode = $('input[name="interview_mode"]:checked').val();

        let interviewDate = $("#interview_date").val();
        let startTime = $("#start_time").val();

        let data = {
            position_id: curruntPosition.id,
            interviewee_profile_id: $("#invite_candidate_id").val(),
            scheduled_at: `${interviewDate} ${startTime}`,
            interview_mode: interviewMode,
            location: $("#location").val(),
            meeting_url: $("#meeting_url").val(),
            recruiter_comment: $("#recruiter_comment").val()
        };


        // $.ajax({
        //     url: "{{ route('interviews.store') }}",
        //     type: "POST",
        //     data,
        //     success: function (response) {

        //     },
        //     error: xhr => {
        //     }
        // });
    }

    async function getShortlistedPositionIds(profileId, orgId) {

        try {
            let response = await xApiPosition.getShortlistedPositionIds("{{ route('shortlists.getShortlistedPositionIds',['profileId' => '__profileId__','orgId' => '__orgId__' ]) }}")
            if(!response) return


        } catch (error) {
            console.error(error);
        }
    }

    function _store(user_profile_id, position_id) {
        let data = {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "scheduled_at": "2026-9-30 05:07:17",
            "user_profile_id": user_profile_id,
            "position_id": position_id,
        }

        $.ajax({
            url: "{{ route('shortlists.store') }}",
            data,
            method: "POST",
            success: function (response) {

            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    function _delete(id) {
        let url = "{{ route('shortlists.delete', ['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        let data = {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "_method": "DELETE"
        }

        $.ajax({
            url,
            data,
            type: "POST",
            success: function (response) {

            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    async function loadData() {
        try {

            let profile = await xApiProfile.getProfileDataByProfileId(
                "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}",
                "{{ session('user_profile_id') }}"
            );

            let organizations = profile.data.organization_users;
            myData = profile.data

            let results = await Promise.all(
                organizations.map(organization => {

                    let orgId = organization.organization_id;

                    return xApiPosition.getPositionsByOrgId(
                        "{{ route('positions.getPositionsByOrgId', ['id' => '__ID__']) }}",
                        orgId
                    );

                })
            );

            xshortList.sideBar(results);

        } catch (error) {

            console.error("Ralat semasa loadData:", error);
        }
    }

    function showJobOfferModal() {
        let profileId = $(this).data("id");

        let candidate = candidateList.find(user => user.id == profileId);

        if (!candidate) {
            console.error("Candidate not found:", profileId);
            return;
        }

        curruntCandidate = candidate;

        console.info("curruntPosition",curruntPosition);
        console.info("curruntCandidate",curruntCandidate);

        let $form = $("#jobOfferForm");

        $(".offer-candidate-name").text(`Candidate: ${curruntCandidate.name}`);
        $("#job-offer-candicate-id").val(curruntCandidate.id);

        $("#jobOfferPositionName").text(`Position: ${curruntPosition.position_title}`);
        $("#jobOfferPositionId").val(curruntPosition.id);

        $("#btnUpdateJobOffer").hide()
        $("#btnAddJobOffer").show()

        xmodal.show("jobOfferModal");
    }

    function handleAddJobOffer(){

        let $form = $(this).parent().parent()

        let salary_amount = $form.find("#salary_amount").val()
        let salary_period = $form.find("#salary_period").val()
        let start_date = $form.find("#start_date").val()
        let end_date = $form.find("#end_date").val()
        let terms_and_conditions = $form.find("#terms_and_conditions").val()
        let benefits = $form.find("#benefits").val()
        let expires_at = $form.find("#expires_at").val()
        let position_id = $form.find("#jobOfferPositionId").val()
        let receiver_profile_id = $form.find("#job-offer-candicate-id").val()

        if(!salary_amount || salary_amount == 0){
            xalert.fire("Warning", "Please enter salary amount", "warning")
            return
        }

        if(salary_period == ""){
            xalert.fire("Warning", "Please select salary period", "warning")
            return
        }

        if(!expires_at){
            xalert.fire("Warning", "Please enter expires date", "warning")
            return
        }

        if(!position_id){
            xalert.fire("Warning", "Position id not found", "warning")
            return
        }

        if(!receiver_profile_id){
            xalert.fire("Warning", "Receiver profile id not found", "warning")
            return
        }

        storeJobOffer($form)
    }

    async function handleEditPosition (){
        console.log(curruntPosition);

        xmodal.show("shortlistModal")
        let $form = $("#shortlistModal")
        $form.find("#position_id").val(curruntPosition.id)
        $form.find("#position_title").val(curruntPosition.position_title)
        $form.find("#employment_type").val(curruntPosition.employment_type)
        $form.find("#vacancies").val(curruntPosition.vacancies)
        $form.find("#department").val(curruntPosition.department)
        $form.find("#work_location").val(curruntPosition.work_location)
        $form.find("#description").val(curruntPosition.description)
        $("#btnAddShortlist").hide()
        $("#btnUpdateShortlist").show()
    }

    function handleCloseModalPosition(){
        xmodal.hide("shortlistModal")

        $("#shortlistModal").find("form")[0].reset();
    }

    let listInvite

function tem(invite) {
    let withdrawUrl = "{{ route('recruiter.invitation.id', ['id' => '__ID__']) }}"
        .replace('__ID__', invite.id);

    return `<div data - id="${invite.id}" class="alert alert-light d-flex justify-content-between" >
                ${ invite.title }
                 <div>
                    <!--
                    <button class="btn btn-danger btnWithdrawInvite btn-withdraw-invitation" data-id="${invite.id}">
                        Withdraw
                    </button>
                    -->
                    <a href="${withdrawUrl}" class="btn btn-primary" data-id="${invite.id}">
                        Edit
                    </a>
                </div>
            </div>`;
}

    function temm(interview) {
        let withdrawUrl = "{{ route('recruiter.interview.id', ['id' => '__ID__']) }}"
            .replace('__ID__', interview.id);

        return `<div data - id="${interview.id}" class="alert alert-light d-flex justify-content-between" >
                ${interview.title}
                 <div>
                    <!--
                    <button class="btn btn-danger btnWithdrawInvite btn-withdraw-invitation" data-id="${interview.id}">
                        Withdraw
                    </button>
                    -->
                    <a href="${withdrawUrl}" class="btn btn-primary" data-id="${interview.id}">
                        Edit
                    </a>
                </div>
            </div>`;
    }


    function handleShowModalListInvite(){
        let userId = $(this).data("id");
        let positionId = curruntPosition.id
        currentUserId = userId

        xmodal.show("listInvitationModal")

        let url = "{{ route('invitations.getInvitationsByPositionIdAndReceiverId', ['receiverId' => '__RECEIVER_ID__','positionId' => '__POSITION_ID__'])}}";
        url = url
            .replace("__RECEIVER_ID__", userId)
            .replace("__POSITION_ID__", positionId);

        return $.ajax({
            url,
            type: "GET",
            success: response => {
                let listOfInvite = response.data
                let html = ""
                listOfInvite.forEach(invite => {
                    console.log(invite);
                    html += tem(invite)
                });
                $(".listItemsContainer").html(html)
                $("listInterviewModal").show()
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }

    function handleShowModalListInterview(){
        let userId = $(this).data("id");
        let positionId = curruntPosition.id
        currentUserId = userId

        let url = "{{ route('interviews.getInterviewsByPositionIdAndIntervieweeId', ['intervieweeId' => '__INTERVIEWEE_ID__','positionId' => '__POSITION_ID__'])}}";

        url = url
            .replace("__INTERVIEWEE_ID__", userId)
            .replace("__POSITION_ID__", positionId);

        $.ajax({
            url,
            type: "GET",
            success: response => {
                console.error(response);
                let listOfInvite = response.data
                let html = ""
                listOfInvite.forEach(invite => {
                    console.log(invite);
                    html += temm(invite)
                });
                $(".listInterviewaContainer").html(html)
                xmodal.show("listInterviewModal")
            },
            error: xhr => {
                console.log(xhr);
            }
        });

    }

    function handleShowModalListJobOffer(){
        xmodal.show("listJobOfferModal")

    }

    function handleWithdrawInvitation() {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Withdraw it!"

        }).then(async (result) => {

            if (!result.isConfirmed) return;

            let id = $(this).data('id');

            let data = {
                _method: "PUT",
                _token: $('meta[name="csrf-token"]').attr("content")
            }

            try {
                let response = await xApiInvite.withdrawInvitation("{{ route('invitations.withdrawInvitation',['id' => '__ID__' ]) }}", id, data)
                if (!response) return

                xalert.salert('Success', response.message, 'success');
                xmodal.hide("listInvitationModal")

            } catch (error) {
                xalert.error('Error', error.responseJSON.message);
            }
        });
    }

    $(document).on("click", ".btn-withdraw-invitation", handleWithdrawInvitation)

    $(document).on("click", ".btnShowModalAddShortlist", showModalAddShortlist);
    $(document).on("click", ".showModalUpdateShortlist", showModalUpdateShortlist);
    $(document).on("click", ".btnShowModalAddInvite", showModalAddInvite)
    $(document).on("click", ".btnShowModalAddInterview", showModalAddinterview);
    $(document).on("click", ".btnShowModalAddIntervieww", showModalAddintervieww);
    $(document).on("click", "#btnAddInvitation", handleAddInvitation)
    $(document).on("submit", "#inviteForm", handleInviteForm);
    $(document).on("click", ".shortlist-item", handleClickShortlist)
    $(document).on("click", ".toggleFilter", toggle)
    $(document).on("click", "#btnAddShortlist", handleAddShortlist)
    $(document).on("click", "#btnUpdateShortlist", handleUpdateShortlist)
    $(document).on("click", ".btnShowModalAddJobOffer", showJobOfferModal)
    $(document).on("click", "#btnAddJobOffer", handleAddJobOffer)
    $(document).on("click", ".btnShowModalAddInvitee", showModalAddInviteId)
    $(document).on("click", ".btnShowModalUpdateShortlist", handleEditPosition)

    $(document).on("click", "#btnCloseModalPosition", handleCloseModalPosition)

    $(document).on("click", ".btnShowModalListInvite", handleShowModalListInvite)
    $(document).on("click", ".btnShowModalListInterview", handleShowModalListInterview)
    $(document).on("click", ".btnShowModalListJobOffer", handleShowModalListJobOffer)

    $(document).on("change", 'input[name="interview_mode"]', function () {

        const mode = $(this).val();

        $("#div_meeting_url, #div_location").hide();

        $("#meeting_url, #location").prop("required", false);

        // Clear unused fields
        $("#meeting_url").val("");
        $("#location").val("");

        if (mode === "Online") {

            $("#div_meeting_url").show();
            $("#meeting_url").prop("required", true);

        } else if (mode === "On-site") {

            $("#div_location").show();
            $("#location").prop("required", true);
        }
    });

    $(document).on("click", "#btnAddInterview", function () {

        const form = $("#interviewForm")[0];

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const scheduled_at =
            $("#interview_date").val() + " " + $("#start_time").val();

        const interview_mode =
            $('input[name="interview_mode"]:checked').val();

        const meeting_url = $("#meeting_url").val();
        const location = $("#location").val();
        const recruiter_comment = $("#recruiter_comment").val();
        let title =  $("#invite_title").val();

            console.error(title);

        storeInterview(
            curruntPosition.id,
            scheduled_at,
            interview_mode,
            location,
            meeting_url,
            recruiter_comment,
            currentUserId,
            title
        );
    });

    $(document).ready(loadData);
    $(document).on("change", "input[name='interview_mode']", function () {
        toggleInterviewMode($(this).val());
    });

    function getInvitationsByPositionIdAndReceiverId(receiverId, positionId) {
        let url = "{{ route('invitations.getInvitationsByPositionIdAndReceiverId', ['receiverId' => '__RECEIVER_ID__','positionId' => '__POSITION_ID__'])}}";
        url = url
            .replace("__RECEIVER_ID__", receiverId)
            .replace("__POSITION_ID__", positionId);

        return $.ajax({
            url,
            type: "GET",
            success: response => {
                console.log(response);
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }

    function getInterviewsByPositionIdAndIntervieweeId(intervieweeId, positionId) {
        let url = "{{ route('interviews.getInterviewsByPositionIdAndIntervieweeId', ['intervieweeId' => '__INTERVIEWEE_ID__','positionId' => '__POSITION_ID__'])}}";

        url = url
            .replace("__INTERVIEWEE_ID__", intervieweeId)
            .replace("__POSITION_ID__", positionId);

        $.ajax({
            url,
            type: "GET",
            success: response => {
                console.log(response);
            },
            error: xhr => {
                console.log(xhr);
            }
        });

    }

    function getJobOffersByPositionIdAndReceiverId(receiverId, positionId) {
        let url = "{{ route('interviews.getJobOffersByPositionIdAndReceiverId', ['receiverId' => '__RECEIVER_ID__','positionId' => '__POSITION_ID__'])}}";
        url = url
            .replace("__RECEIVER_ID__", receiverId)
            .replace("__POSITION_ID__", positionId);

        return $.ajax({
            url,
            type: "GET",
            success: response => {
                console.log(response);
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }

    getInvitationsByPositionIdAndReceiverId(2, 11);
    getInvitationsByPositionIdAndReceiverId(2, 11);
    getJobOffersByPositionIdAndReceiverId(2, 11);

</script>
@endsection
