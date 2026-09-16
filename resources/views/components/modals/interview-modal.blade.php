<div class="modal fade" id="interviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="interviewModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="interviewModalLabel">
                    Schedule Interview
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form id="interviewForm">

                <div class="modal-body">
                    <div class="row">
                        <input type="number" class="form-control invitation_id" hidden readonly>

                        <div class="col-md-12 mb-3">
                            <label for="invite_candidate_id" class="form-label">
                                Candidate
                            </label>
                            <input type="text" id="invite_candidate_id" hidden>
                            <input type="text" class="form-control candidate_name" readonly>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="invite_title" class="form-label">
                                Title
                            </label>
                            <input type="text" id="invite_title" required>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="expires_at" class="form-label">
                                Interview Date
                            </label>

                            <input type="date" id="interview_date" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="expires_at" class="form-label">
                                Start Time
                            </label>

                            <input type="time" id="start_time" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label d-block">
                                Interview Mode <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-4 justify-content-evenly px-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="interview_mode" id="mode_online"
                                        value="Online" required>
                                    <label class="form-check-label" for="mode_online">
                                        Online
                                        <i class="fa-solid fa-video" style="color: rgb(0, 0, 0);"></i>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="interview_mode" id="mode_onsite"
                                        value="On-site">
                                    <label class="form-check-label" for="mode_onsite">
                                        On-site
                                        <i class="fa-solid fa-building" style="color: rgb(0, 0, 0);"></i>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="interview_mode" id="mode_phone"
                                        value="Phone">
                                    <label class="form-check-label" for="mode_phone">
                                        Phone
                                        <i class="fa-solid fa-phone" style="color: rgb(0, 0, 0);"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3" id="div_meeting_url">
                            <label for="meeting_url" class="form-label">
                                Meeting Link / URL <span class="text-danger">*</span>
                            </label>
                            <input type="url" id="meeting_url" class="form-control"
                                placeholder="https://meet.google.com/xyz or https://zoom.us/j/xyz">
                        </div>

                        <div class="col-md-12 mb-3" id="div_location">
                            <label for="location" class="form-label">
                                Physical Location <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="location" class="form-control"
                                placeholder="Bilik Mesyuarat 1, Tingkat 2 / Alamat Pejabat">
                        </div>

                        <div class="col-12 mb-3">
                            <label for="recruiter_comment" class="form-label">
                                Recruiter Comment
                            </label>

                            <textarea id="recruiter_comment" class="form-control" rows="5" placeholder=""
                                required></textarea>
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

                </div>

            </form>

        </div>
    </div>
</div>

<script type="module">
    window.interviewModal = {
        currentUserId: null,
        details: null,
        candidateList: null,

        init() {
            const self = this;
            self.bindEvents()
        },

        open() {
            $("#interviewModalLabel").text("Schedule Interview");
            $("#btnAddInterview").show();
            $("#btnUpdateInterview").hide();

            xmodal.show("interviewModal")
            xmodal.hide("listInterviewModal")
            $("#btnUpdateInterview").hide()
        },
        async storeInterview(position_id, scheduled_at, interview_mode, location, meeting_url, recruiter_comment, interviewee_profile_id, title){
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
            let response = await xApiInterview.store("{{ route('interviews.store') }}", data)
            if (!response) return

            xalert.success("Success", response.message)
            xmodal.hide("interviewModal")
        } catch (error) {
            console.error(error);
            xalert.error("Faild", error.responseJSON.message)
        }
    },
        showModalAddintervieww() {
            let profileId = this.currentUserId
            let candidte = this.candidateList.find(user => user.id == profileId)

            $("#inviteForm")[0].reset();
            $("#invitation_id").val("");

            $(".invitation_id").val(this.details.id)
            $(".candidate_name").val(candidte.name)

            let today = new Date().toISOString().split("T")[0];
            $("#interview_date").attr("min", today).val(today);
            $("#start_time").val("10:00");

            let candidateName = $(this).data("candidate-name") || "";
            let candidateId = $(this).data("candidate-id") || "";
            $("#invite_candidate").val(candidateName);
            $("#invite_candidate_id").val(candidateId);

            this.open()
        },

        changeinterviewMode(){
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
        },
        handleAddInterview(){
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
            let title = $("#invite_title").val();

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
        },
        toggleInterviewMode(mode) {
            $("#div_meeting_url, #div_location").addClass("d-none");
            $("#meeting_url, #location").prop("required", false);

            if (mode === "Online") {
                $("#div_meeting_url").removeClass("d-none");
                $("#meeting_url").prop("required", true);
            } else if (mode === "On-site") {
                $("#div_location").removeClass("d-none");
                $("#location").prop("required", true);
            }
        },
        showModalAddinterview() {
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
        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail", function (event, details, candidateList) {
                self.details = details
                self.candidateList = candidateList
            })

            $(document).on("click", ".btnShowModalAddIntervieww", function(){
                self.currentUserId = $(this).data("id")
                self.showModalAddintervieww()
            });

            $(document).on("change", 'input[name="interview_mode"]', function () {
                self.changeinterviewMode()
            });

            $(document).on("change", "input[name='interview_mode']", function () {
                toggleInterviewMode($(this).val());
            });

            $(document).on("click", "#btnAddInterview", function () {
                self.handleAddInterview()
            });

            $(document).on("click", ".btnShowModalAddInterview", function (){
                self.showModalAddinterview()
            });

        }
    }
</script>
