<div class="modal fade" id="invitationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="inviteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="inviteModalLabel">Invite Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 mb-3">
                        <p class="candidate_name">Candidate: </p>
                        <input type="hidden" id="candidate_id" readonly>
                    </div>

                    <div class="col-md-12 mb-3">
                        <p class="position_title">Position: </p>
                        <input type="hidden" id="position_id" readonly>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="invitation_title" class="form-label">
                            Invitation Title
                        </label>
                        <input type="text" id="invitation_title" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="expires_at" class="form-label">
                            Expires Date
                        </label>
                        <input type="date" id="expires_at" class="form-control">
                    </div>

                    <div class="col-12 mb-3">
                        <label for="invitation_message" class="form-label">
                            Message to Candidate
                        </label>
                        <textarea id="invitation_message" class="form-control" rows="5"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" id="btnAddInvitation" class="btn btn-primary">
                    Add
                </button>
                <button type="button" id="btnUpdateInvitation" class="btn btn-primary">
                    Update
                </button>
            </div>
        </div>
    </div>
</div>

@push('childScript')

<script type="module">

    window.invitationModal = {
        currentUserId: null,
        details: null,
        candidateList: null,

        init() {
            this.bindEvents();
        },

        openAdd() {
            $("#btnAddInvitation").show();
            $("#btnUpdateInvitation").hide();
            this.reset();
            xmodal.show("invitationModal");
            xmodal.hide("listInvitationModal");
        },

        openUpdate() {
            $("#btnAddInvitation").hide();
            $("#btnUpdateInvitation").show();
            xmodal.show("invitationModal");
            xmodal.hide("listInvitationModal");
        },

        reset() {
            $("#invitationModal #invite_candidate_id").val("");
            $("#invitationModal #invite_candidate").val("");
            $("#invitationModal #invite_position_id").val("");
            $("#invitationModal #invite_position_title").val("");

            $("#invitation_title").val("");

            $("#expires_at").val("");

            $("#invitation_message").val("");
        },

        async show() {
            const profileId = this.currentUserId;

            const candidate = this.candidateList.find(
                user => user.id == profileId
            );

            console.log("profileId:", profileId);
            console.log("candidateList:", this.candidateList);
            console.log("candidate:", candidate);

            if (!candidate) {
                xalert.fire("Error", "Candidate not found", "error");
                return;
            }

            if (!this.details) {
                xalert.fire("Error", "Position details not found", "error");
                return;
            }

            console.log("details:", this.details);

            $("#invitationModal #candidate_id").val(candidate.id);
            $("#invitationModal .candidate_name")
                .text(`Candidate: ${candidate.name}`);

            $("#invitationModal #position_id").val(this.details.id);
            $("#invitationModal .position_title")
                .text(`Position: ${this.details.position_title}`);

            this.openAdd();
        },

        async storeInvitations(candidate_id,position_id,expires_at,invitation_message,title) {
            const data = {
                _token: $('meta[name="csrf-token"]').attr("content"),
                receiver_profile_id: candidate_id,
                invitation_message: invitation_message,
                expires_at: expires_at,
                position_id: position_id,
                title: title
            };

            try {
                const response = await xApiInvite.store("{{ route('invitations.store') }}", data);

                if (!response) {
                    return null;
                }

                const $row = $("#tableDetail").find(`tr[data-id="${candidate_id}"]`);

                if ($row.length) {
                    $row.find(".text-status").text("Invited");
                    $row.find(".list-item-add-invite").hide();
                    $row.find(".list-item-cancel-invite").hide();
                }

                xmodal.hide("invitationModal");

                this.reset();

                xalert.success("Success",response.message);
                return response;

            } catch (error) {
                console.error("Error storing invitation:",error);
                throw error;
            }
        },

        async store() {

            const candidate_id = $("#invitationModal #candidate_id").val();
            const position_id = $("#invitationModal #position_id").val();
            const expires_at = $("#invitationModal #expires_at").val();
            const title = $("#invitationModal #invitation_title").val().trim();
            const invitation_message = $("#invitationModal #invitation_message").val().trim();
            const today = new Date().toISOString().split("T")[0];

            if (!candidate_id) {
                xalert.fire("Validation Error","Candidate ID is missing","warning");
                return;
            }
            if (!position_id) {
                xalert.fire("Validation Error","Position ID is missing","warning");
                return;
            }
            if (!title) {
                xalert.fire("Validation Error","Please enter invitation title","warning");
                return;
            }
            if (!expires_at) {
                xalert.fire("Validation Error","Please select expiration date","warning");
                return;
            }
            if (expires_at < today) {
                xalert.fire("Validation Error","Expiration date cannot be in the past","warning");
                return;
            }
            if (!invitation_message) {
                xalert.fire("Validation Error","Please enter a message","warning");
                return;
            }

            try {
                await this.storeInvitations(candidate_id,position_id,expires_at,invitation_message,title);

            } catch (error) {
                console.error(error);
                const message = error?.responseJSON?.message || error?.response?.data?.message || "Something went wrong";
                xalert.fire("Error",message,"error");
            }
        },

        async update() {
            console.log("Update invitation");
        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail",function (event,details,candidateList) {
                self.details = details;
                self.candidateList = candidateList;
            });

            $(document).on("click", ".btnShowModalAddInvitee", function () {
                self.currentUserId = $(this).data("id");
                self.show();
            });

            $(document).on("click", "#invitationModal #btnAddInvitation", function () {
                self.store();
            });

            $(document).on("click","#invitationModal #btnUpdateInvitation",function () {
                self.update();
            });
        }
    };
</script>

@endpush
