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
<x-modals.job-offer-modal />

@endsection

@section('script')
<script type="module">

    let myData, positionId, curruntPosition, candidateList, curruntCandidate

    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }

    // < ---- GET ------ >

    async function getProfileDataByProfileId() {
        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");
        let response = await $.ajax({
            url: url,
            method: 'GET'
        });
        myData = response.data;
        return response.data;
    }

    async function getPositionById(id) {
        let url = "{{ route('positions.getPositionById', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        return await $.ajax({
            url: url,
            method: 'GET'
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

    // < ---- GET ------ >

    // < ---- STORE ------ >

    function storePosition(position_title, employment_type, vacancies, department, work_location, description) {
        let formData = new FormData()
        formData.append("organization_id", myData.organization_users[0].organization_id)
        formData.append("position_title", position_title)
        formData.append("employment_type", employment_type)
        formData.append("department", department)
        formData.append("work_location", work_location)
        formData.append("vacancies", vacancies)
        formData.append("description", description)

        return $.ajax({
            url: "{{ route('positions.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
    }

    function storeInvitations(candidate_id, position_id, expires_at, invitation_message) {
        let formData = new FormData()
        formData.append("receiver_profile_id", candidate_id)
        formData.append("invitation_message", invitation_message)
        formData.append("expires_at", expires_at)
        formData.append("position_id", position_id)

        return $.ajax({
            url: "{{ route('invitations.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
    }

    function storeInterview(position_id, scheduled_at, interview_mode, location, meeting_url, recruiter_comment, interviewee_profile_id){
        let data = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            position_id,
            scheduled_at,
            interview_mode,
            location,
            meeting_url,
            recruiter_comment,
            interviewee_profile_id
        }

        $.ajax({
            url: "{{ route('interviews.store') }}",
            type: "POST",
            data,
            success: response => {
                console.log(response);
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }

    function storeJobOffer($form){
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

        $.ajax({
            url: "{{ route('jobOffers.store') }}",
            type: "POST",
            data,
            success: response => {
                console.log(response);
                xalert.fire("Success", response.message, "success")
                xmodal.hide("jobOfferModal")
            },
            error: xhr => {
                console.log(xhr.responseJSON.message);
                xalert.fire("Error", xhr.responseJSON.message, "error")
            }
        });

    }

    // < ---- STORE ------ >

    // < ---- UPDATE ------ >

    function updatePositions(id, position_title, employment_type, vacancies, department, work_location, description) {
        let url = "{{ route('positions.update', ['id' => '__ID__']) }}"
        url = url.replace("__ID__", id);
        let formData = new FormData()
        formData.append("organization_id", myData.organization_users[0].organization_id)
        formData.append("position_title", position_title)
        formData.append("employment_type", employment_type)
        formData.append("department", department)
        formData.append("work_location", work_location)
        formData.append("vacancies", vacancies)
        formData.append("description", description)
        formData.append("_method", "PUT");

        return $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
        });
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

            let response = await getPositionById(positionId)
            if (!response) return
            console.log(response.data);
            curruntPosition = response.data
            candidateList = response.data.shortlist_users

            shortListRender.detail(response.data)

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
            salert.salert("Validation Error", "Please enter a position title", "warning");
            return
        }

        if (employment_type == "") {
            salert.salert("Validation Error", "Please select employment type", "warning");
            return
        }

        if (vacancies == "") {
            salert.salert("Validation Error", "Please enter a vacancies", "warning");
            return
        }

        if (department == "") {
            salert.salert("Validation Error", "Please enter a department", "warning");
            return
        }

        if (work_location == "") {
            salert.salert("Validation Error", "Please enter a work location", "warning");
            return
        }

        try {

            let response = await storePosition(position_title, employment_type, vacancies, department, work_location, description)
            if (!response) return

            console.log(response);
            salert.salert('Success', response.message, 'success');
            form[0].reset();
            bootstrap.Modal .getOrCreateInstance($("#shortlistModal")).hide();

            shortListRender.appendNew(response.data)

        } catch (xhr) {
            console.error(xhr);
            salert.salert("Error", xhr, "error")
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
            salert.salert("Validation Error", "position id is NULL", "warning");
            return
        }

        if (position_title == "") {
            salert.salert("Validation Error", "Please enter a position title", "warning");
            return
        }

        if (employment_type == "") {
            salert.salert("Validation Error", "Please select employment type", "warning");
            return
        }

        if (vacancies == "") {
            salert.salert("Validation Error", "Please enter a vacancies", "warning");
            return
        }

        if (department == "") {
            salert.salert("Validation Error", "Please enter a department", "warning");
            return
        }

        if (work_location == "") {
            salert.salert("Validation Error", "Please enter a work location", "warning");
            return
        }

        try {

            let response = await updatePositions(parseInt(position_id), position_title, employment_type, vacancies, department, work_location, description)
            if (!response) return

            console.log(response);
            salert.salert('Success', response.message, 'success');
            form[0].reset();
            bootstrap.Modal .getOrCreateInstance($("#shortlistModal")).hide();

            shortListRender.detail(response.data)
            let $row = $("#shortlistList").find(`[data-id="${response.data.id}"]`)
            $row.find(".title").text(response.data.position_title)

        } catch (xhr) {
            console.error(xhr);
            salert.salert("Error", xhr, "error")
        }
    }

    async function handleAddInvitation(e) {
        e.preventDefault()
        let today = new Date().toISOString().split('T')[0];
        let form = $(this).closest("form");

        let candidate_id = form.find("#invite_candidate_id").val()
        let position_id = form.find("#invite_position_id").val()
        let expires_at = form.find("#expires_at").val()
        let invitation_message = form.find("#invitation_message").val()

        if (candidate_id == "") {
            salert.salert("Validation Error", "Candidte id is NULL", "warning");
            return
        }

        if (position_id == "") {
            salert.salert("Validation Error", "Position id is NULL", "warning");
            return
        }

        if (expires_at == "") {
            salert.salert("Validation Error", "Please select employment type", "warning");
            return
        }

        if (expires_at < today) {
            salert.salert("Validation Error", "Expiration date cannot be in the past", "warning");
            return;
        }

        if (invitation_message == "") {
            salert.salert("Validation Error", "Please enter a vacancies", "warning");
            return
        }

        try {
            let response = await storeInvitations(candidate_id, position_id, expires_at, invitation_message)
            if (!response) return

            salert.salert('Success', response.message, 'success');


        } catch (xhr) {
            console.error(xhr);
            salert.salert("Error", xhr.responseJSON.message, "error")
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

    function handleInviteForm(e){
        e.preventDefault();

        let interviewMode = $('input[name="interview_mode"]:checked').val();

        let interviewDate = $("#interview_date").val();
        let startTime = $("#start_time").val();

        let data = {
            position_id: curruntPosition.id,
            interviewee_profile_id: curruntCandidate.id,
            scheduled_at: `${interviewDate} ${startTime}`,
            interview_mode: interviewMode,
            location: $("#location").val(),
            meeting_url: $("#meeting_url").val(),
            recruiter_comment: $("#recruiter_comment").val()
        };

        if (!data.interview_mode) {
            salert.salert("Validation Error", "Please select an interview mode", "warning");
            return;
        }

        $.ajax({
            url: "{{ route('interviews.store') }}",
            type: "POST",
            data,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function (response) {
                bootstrap.Modal .getInstance($("#interviewModal")).hide();
                salert.salert("Success", response.message, "success");
                // Reload table/datatable jika perlu:
                // table.ajax.reload();
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || "An error occurred while saving.";
                salert.salert("Error", message, "error");
            }
        });
    }

    function showModalAddShortlist(){
        $("#btnAddShortlist").show()
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
        console.log(candidte);
        $("#invite_candidate_id").val(profileId)
        $("#invite_candidate").val(candidte.name)
        $("#invite_position_title").val(curruntPosition.position_title)
        $("#invite_position_id").val(curruntPosition.id)
        bootstrap.Modal .getOrCreateInstance($("#invitationModal")).show()
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

    function handleAddInterview(){

        console.log("curruntPosition",curruntPosition);

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

        console.log(data);

        // $.ajax({
        //     url: "{{ route('interviews.store') }}",
        //     type: "POST",
        //     data,
        //     success: function (response) {
        //         xdebug.line(response)

        //     },
        //     error: xhr => {
        //         xdebug.line(xhr)
        //     }
        // });
    }

    function store() {
        let data = {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "organization_id": 1,
            "position_title": "New Position 2025",
            "employment_type": "Internship",
            "department": "New Department 2025",
            "work_location": "New Work Location 2025",
            "vacancies": "5",
            "description": "New Description 2025"
        }

        $.ajax({
            url: "{{ route('positions.store') }}",
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

    function getShortlistedPositionIds(profileId, orgId) {
        let url = "{{ route('shortlists.getShortlistedPositionIds',['profileId' => '__profileId__','orgId' => '__orgId__' ]) }}"
        url = url.replace("__orgId__", orgId)
        url = url.replace("__profileId__", profileId)

        $.ajax({
            url,
            type: "GET",
            success: function (response) {
                debug.log("getShortlistedPositionIds", response.data);
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
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
                debug.log("store", response.data);
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
                debug.log("store", response.data);
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    async function loadData() {
        try {
            let profile = await getProfileDataByProfileId();
            let organizations = profile.organization_users;

            let results = await Promise.all(
                organizations.map(organization => getPositionsByOrgId(organization.organization_id))
            );

            shortListRender.sideBar(results)

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

    // $(document).on("click", "#btnAddInterview", handleAddInterview)
    // store()
    // getShortlistedPositionIds(2, 1)
    // _store(2, 11)
    // _delete(11)
    $(document).ready(function(){
        loadData()
    });

    $(document).on("change", "input[name='interview_mode']", function () {
        toggleInterviewMode($(this).val());
    });
    $(document).on("click", ".btnShowModalAddShortlist", showModalAddShortlist);
    $(document).on("click", ".showModalUpdateShortlist", showModalUpdateShortlist);
    $(document).on("click", ".btnShowModalAddInvite", showModalAddInvite)
    $(document).on("click", ".btnShowModalAddInterview", showModalAddinterview);
    $(document).on("click", "#btnAddInvitation", handleAddInvitation)
    $(document).on("submit", "#inviteForm", handleInviteForm);
    $(document).on("click", ".shortlist-item", handleClickShortlist)
    $(document).on("click", ".toggleFilter", toggleFilter)
    $(document).on("click", "#btnAddShortlist", handleAddShortlist)
    $(document).on("click", "#btnUpdateShortlist", handleUpdateShortlist)
    $(document).on("click", ".btnShowModalAddJobOffer", showJobOfferModal)
    $(document).on("click", "#btnAddJobOffer", handleAddJobOffer)

</script>
@endsection
