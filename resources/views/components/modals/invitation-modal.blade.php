<div class="modal fade" id="invitationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="inviteModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="inviteModalLabel">
                    Invite Candidate
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form id="inviteForm">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="invite_position_title" class="form-label">
                                Candidate
                            </label>

                            <input type="text" id="invite_candidate" class="form-control" readonly>
                            <input type="number" id="invite_candidate_id" class="form-control" hidden readonly>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="invite_position_title" class="form-label">
                                Position Title
                            </label>

                            <input type="text" id="invite_position_title" class="form-control" readonly>
                            <input type="number" id="invite_position_id" class="form-control" hidden readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="invitation_title" class="form-label">
                                Invitation Title
                            </label>

                            <input type="text" name="title" id="invitation_title" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="expires_at" class="form-label">
                                Expires Date
                            </label>

                            <input type="date" id="expires_at" class="form-control" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="invitation_message" class="form-label">
                                Message to Candidate
                            </label>

                            <textarea id="invitation_message" class="form-control" rows="5" placeholder=""
                                required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" id="btnAddInvitation" class="btn btn-primary">
                        Add
                    </button>
                    <button type="submit" id="btnUpdateInvitation" class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<script type="module">
    window.invitationModal = {
        currentUserId: null,
        details: null,
        candidateList: null,

        init(){
            const self = this;
            self.bindEvents()
        },
        open(){
            $("#btnAddInvitation").show()
            $("#btnUpdateInvitation").hide()

            xmodal.show("invitationModal")
            xmodal.hide("listInvitationModal")
        },
        async storeInvitations(candidate_id, position_id, expires_at, invitation_message, title) {

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
                if (!response) return

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
        },

        showModalAddInviteId() {
            let profileId = this.currentUserId
            let candidte = this.candidateList.find(user => user.id == profileId)

            $("#invite_candidate_id").val(profileId)
            $("#invite_candidate").val(candidte.name)

            $("#invite_position_title").val(this.details.position_title)
            $("#invite_position_id").val(this.details.id)

            this.open()
        },
        async handleAddInvitation(e) {
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
                let response = await storeInvitations(candidate_id, position_id, expires_at, invitation_message, title)
                if (!response) return

                xalert.fire('Success', response.message, 'success');


            } catch (xhr) {
                console.error(xhr);
                xalert.fire("Error", xhr.responseJSON.message, "error")
            }
        },
        async handleInviteForm(e){
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
            if (!response) return

            bootstrap.Modal.getInstance($("#interviewModal")).hide();
            xalert.fire("Success", response.message, "success");
            // Reload table/datatable jika perlu:
            // table.ajax.reload();

        } catch (error) {
            console.error(error);
        }
    },

    showModalAddInvite() {
            $("#btnAddInvitation").show()
            $("#btnUpdateInvitation").hide()

            let profileId = $(this).attr("data-id")
            let candidte = candidateList.find(user => user.id == profileId)
            $("#invite_candidate_id").val(profileId)
            $("#invite_candidate").val(candidte.name)
            $("#invite_position_title").val(curruntPosition.position_title)
            $("#invite_position_id").val(curruntPosition.id)
            xmodal.show("invitationModal")
        },
        bindEvents(){
            const self = this;

            $(document).on("position:detail", function (event, details, candidateList) {
                self.details = details
                self.candidateList = candidateList
            })

            $(document).on("click", ".btnShowModalAddInvitee", function(){
                self.currentUserId = $(this).data("id")
                self.showModalAddInviteId()
            })

            $(document).on("submit", "#inviteForm", function(){
                self.handleInviteForm()
            });

            $(document).on("click", "#btnAddInvitation", function(){
                self.handleAddInvitation()
            })
            $(document).on("click", ".btnShowModalAddInvite", function (){
                self.showModalAddInvite()
            })
        }
    }
</script>
