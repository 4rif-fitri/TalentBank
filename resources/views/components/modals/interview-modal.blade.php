<div class="modal fade" id="interviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="interviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="interviewModalLabel">
                    Schedule Interview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 mb-3">
                        <p class="candidate_name">Candidate: </p>
                        <input type="hidden" id="invite_candidate_id">
                    </div>

                    <div class="col-md-12 mb-3">
                        <p class="position_title">Position: </p>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="invite_title" class="form-label">
                            Title
                        </label>
                        <input type="text" id="invite_title" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="interview_date" class="form-label">
                            Interview Date
                        </label>
                        <input type="date" id="interview_date" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">
                            Start Time
                        </label>
                        <input type="time" id="start_time" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label d-block">
                            Interview Mode
                            <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-4 justify-content-evenly px-4">

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="interview_mode" id="mode_online" value="Online">
                                <label class="form-check-label" for="mode_online">Online
                                    <i class="fa-solid fa-video"></i>
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="interview_mode" id="mode_onsite" value="On-site">
                                <label class="form-check-label" for="mode_onsite">On-site
                                    <i class="fa-solid fa-building"></i>
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="interview_mode" id="mode_phone" value="Phone">
                                <label class="form-check-label" for="mode_phone">Phone
                                    <i class="fa-solid fa-phone"></i>
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12 mb-3 d-none" id="div_meeting_url">
                        <label for="meeting_url" class="form-label">
                            Meeting Link / URL
                            <span class="text-danger">*</span>
                        </label>
                        <input type="url" id="meeting_url" class="form-control" placeholder="https://meet.google.com/xyz">
                    </div>

                    <div class="col-md-12 mb-3 d-none" id="div_location">
                        <label for="location" class="form-label">
                            Physical Location
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="location" class="form-control" placeholder="Bilik Mesyuarat 1, Tingkat 2 / Alamat Pejabat">
                    </div>

                    <div class="col-12 mb-3">
                        <label for="recruiter_comment" class="form-label">
                            Recruiter Comment
                        </label>
                        <textarea id="recruiter_comment" class="form-control" rows="3"></textarea>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" id="btnAddInterview" class="btn btn-primary">
                    Save
                </button>
                <button type="button" id="btnUpdateInterviewSave" class="btn btn-primary">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>


@push('childScript')

<script type="module">

    window.interviewModal = {
        currentUserId: null,
        details: null,
        candidateList: null,

        init() {
            this.bindEvents();
        },

        open() {
            $("#interviewModalLabel").text("Schedule Interview");
            $("#btnAddInterview").show();
            xmodal.show("interviewModal");
            xmodal.hide("listInterviewModal");
        },

        openUpdate(currentInterview){
            console.log("currentinterview", currentInterview)

            $("#invite_candidate_id").val(currentInterview.interviewee.id)
            $(".candidate_name").text(`Candidate: ${currentInterview.interviewee.name}`)

            $(".position_title").text(`Position: ${currentInterview.position.position_title}`)

            $("#invite_title").val(currentInterview.title)

            $("#interview_date").val(currentInterview.scheduled_at.split(" ")[0])
            $("#start_time").val(currentInterview.scheduled_at.split(" ")[1].substring(0, 5))

            $("#mode_online").prop("checked", currentInterview.interview_mode == "Online")
            $("#mode_onsite").prop("checked", currentInterview.interview_mode == "On-site")
            $("#mode_phone").prop("checked", currentInterview.interview_mode == "Phone")

            this.toggleInterviewMode(currentInterview.interview_mode);

            $("#recruiter_comment").val(currentInterview.recruiter_comment)
            $("#meeting_url").val(currentInterview.meeting_url)
            $("#location").val(currentInterview.location)

            $("#btnAddInterview").hide()
            $("#btnUpdateInterviewSave").show()
            xmodal.show("interviewModal")
        },

        async update(callback){
            let data = {
                "_token": $('meta[name="csrf-token"]').attr("content"),
                "_method": "PUT",
                "title": $("#invite_title").val(),
                "scheduled_at": $("#interviewModal #interview_date").val() + " " + $("#interviewModal #start_time").val() + ":00",
                "interview_mode": $("input[name='interview_mode']:checked").val(),
                "location": $("#location").val(),
                "meeting_url": $("#meeting_url").val(),
                "recruiter_comment": $("#recruiter_comment").val(),
                "interview_result": interviewDetail.current.interview_result
            }
            console.log(data);

            try {
                let response = await xApiInterview.update(
                    "{{ route('interviews.update',['id' => '__ID__' ]) }}",
                    interviewDetail.current.id,
                    data
                )

                if (!response) return

                console.log(response);

                xalert.fire("Success", "Interview Updated", "success")
                xmodal.hide("interviewModal")

                callback(response.data);

            } catch (error) {
                console.error(error);
            }
        },

        reset() {
            $(".invitation_id").val("");
            $("#invite_candidate_id").val("");
            $(".candidate_name").val("");
            $("#invite_title").val("");
            $("#interview_date").val("");
            $("#start_time").val("");
            $("input[name='interview_mode']").prop("checked", false);
            $("#meeting_url").val("");
            $("#location").val("");
            $("#recruiter_comment").val("");
            $("#div_meeting_url").addClass("d-none");
            $("#div_location").addClass("d-none");
        },

        showModalAddInterview() {
            const profileId = this.currentUserId;
            const candidate = this.candidateList?.find(user => user.id == profileId);

            console.log("profileId:",profileId);
            console.log("candidate:",candidate);
            console.log("position:",this.details);

            if (!candidate) {
                xalert.fire("Error","Candidate not found","error");
                return;
            }


            if (!this.details) {
                xalert.fire("Error","Position details not found","error");
                return;
            }

            this.reset();

            $("#invite_candidate_id").val(candidate.id);
            $(".candidate_name").text(`Candidate: ${candidate.name}`)
            $(".position_title").text(`Position: ${this.details.position_title}`)

            const today = new Date().toISOString().split("T")[0];

            $("#interview_date").attr("min", today).val(today);
            $("#start_time").val("10:00");
            $("#mode_online").prop("checked", true);

            this.toggleInterviewMode("Online");
            this.open();
        },

        toggleInterviewMode(mode) {

            $("#div_meeting_url").addClass("d-none");
            $("#div_location").addClass("d-none");
            $("#meeting_url").prop("required", false);
            $("#location").prop("required", false);
            $("#meeting_url").val("");
            $("#location").val("");

            if (mode === "Online") {
                $("#div_meeting_url").removeClass("d-none");
                $("#meeting_url").prop("required", true);

            }else if (mode === "On-site") {
                $("#div_location").removeClass("d-none");
                $("#location").prop("required", true);
            }
        },

        async storeInterview(position_id,scheduled_at,interview_mode,location,meeting_url,recruiter_comment,interviewee_profile_id,title) {

            const data = {
                _token: $('meta[name="csrf-token"]').attr("content"),
                position_id,
                scheduled_at,
                interview_mode,
                location,
                meeting_url,
                recruiter_comment,
                interviewee_profile_id,
                title
            };
            console.table(data);

            try {
                const response = await xApiInterview.store("{{ route('interviews.store') }}",data);

                if (!response) {
                    return null;
                }

                xalert.success("Success",response.message);
                xmodal.hide("interviewModal");

                this.reset();
                return response;

            } catch (error) {
                console.error("Error storing interview:",error);
                throw error;
            }
        },

        async handleAddInterview() {

            const position_id = this.details?.id;
            const interviewee_profile_id = $("#invite_candidate_id").val();
            const title = $("#invite_title").val().trim();
            const interview_date = $("#interview_date").val();
            const start_time = $("#start_time").val();
            const interview_mode = $("input[name='interview_mode']:checked").val();
            const meeting_url = $("#meeting_url").val().trim();
            const location = $("#location").val().trim();
            const recruiter_comment =$("#recruiter_comment").val().trim();

            if (!position_id) {
                xalert.fire("Validation Error","Position ID not found.","warning");
                return;
            }
            if (!interviewee_profile_id) {
                xalert.fire("Validation Error","Candidate ID not found.","warning");
                return;
            }
            if (!title) {
                xalert.fire("Validation Error","Please enter interview title.","warning");
                return;
            }
            if (!interview_date) {
                xalert.fire("Validation Error","Please select interview date.","warning");
                return;
            }
            if (!start_time) {
                xalert.fire("Validation Error","Please select start time.","warning");
                return;
            }
            if (!interview_mode) {
                xalert.fire("Validation Error","Please select interview mode.","warning");
                return;
            }
            if (interview_mode === "Online" && !meeting_url) {
                xalert.fire("Validation Error","Please enter meeting URL.","warning");
                return;
            }
            if (interview_mode === "On-site" && !location) {
                xalert.fire("Validation Error","Please enter interview location.","warning");
                return;
            }

            const scheduled_at = `${interview_date} ${start_time}`;

            console.log("scheduled_at:", scheduled_at);

            try {
                await this.storeInterview(
                    position_id,
                    scheduled_at,
                    interview_mode,
                    location,
                    meeting_url,
                    recruiter_comment,
                    interviewee_profile_id,
                    title
                );

            } catch (error) {
                console.error(error);
                const message = error?.responseJSON?.message || error?.response?.data?.message || "Something went wrong";
                xalert.fire("Error",message,"error");
            }
        },

        async cancelInterview(id) {

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
        },

        async completeInterview(id){
            let data = {
                '_token': $('meta[name="csrf-token"]').attr("content"),
                '_method': "PUT"
            }

            try {
                let response = await xApiInterview.completeInterview("{{ route('interviews.completeInterview',['id' => '__ID__']) }}", id, data)
                if (!response) return

                $(`#shortlistList .shortlist-item[data-id="${response.data.id}"]`).remove();
                $(".results-panel").html(xcommon.noSelected("No Job Offer Selected", "", "btn-toggle-filter toggleFilter", "Interview"))

                xalert.fire("Success", "Interview Completed", "success")

            } catch (error) {
                console.error(error);
            }
        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail", function (event,details,candidateList) {
                self.details = details;
                self.candidateList = candidateList;
            });

            $(document).on("click","#interviewModal .btnShowModalAddInterview",function () {
                self.currentUserId = $(this).data("id");
                self.showModalAddInterview();
            });

            $(document).on("change","#interviewModal input[name='interview_mode']", function () {
                self.toggleInterviewMode($(this).val());
            });

            $(document).on("click","#interviewModal #btnAddInterview",function () {
                self.handleAddInterview();
            });
        }
    };
</script>

@endpush
