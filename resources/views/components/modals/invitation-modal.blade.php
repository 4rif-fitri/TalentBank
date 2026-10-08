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
        invitationDetail: null,

        onSuccess: null,
        onError: null,

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

        openUpdate(detail, callbacks = {}) {
            this.invitationDetail = detail
            this.onSuccess = callbacks.onSuccess;
            this.onError = callbacks.onError;

            $("#invitationModal .candidate_name").text(`Candidate: ${this.invitationDetail.receiver.name}`)
            $("#invitationModal #candidate_id").val(this.invitationDetail.receiver.id)

            $("#invitationModal .position_title").text(`Position: ${this.invitationDetail.position.position_title}`)
            $("#invitationModal #position_id").val(this.invitationDetail.position.id)

            $("#invitationModal #invitation_title").val(this.invitationDetail.title)
            $("#invitationModal #expires_at").val(this.invitationDetail.expires_at)
            $("#invitationModal #invitation_message").val(this.invitationDetail.invitation_message)

            $("#btnAddInvitation").hide();
            $("#btnUpdateInvitation").show();
            xmodal.show("invitationModal");
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

            let data = {
                _method: "PUT",
                _token: $('meta[name="csrf-token"]').attr("content"),
                title: $("#invitation_title").val(),
                expires_at: $("#expires_at").val(),
                invitation_message: $("#invitation_message").val(),
            }

            try {
                let response = await xApiInvite.update("{{ route('invitations.update', ['id' => '__ID__']) }}", this.invitationDetail.id, data)
                if (!response) return

                xalert.success(response.message, 'success');
                xmodal.hide("invitationModal")

                if (typeof this.onSuccess === "function") {
                    await this.onSuccess(response);
                }

            } catch (error) {
                if (typeof this.onError === "function") {
                    this.onError(error);
                }
            }
        },

        Withdraw(id, callbacks = {}) {

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

                const data = {
                    _method: "PUT",
                    _token: $('meta[name="csrf-token"]').attr("content")
                };

                try {

                    const response = await xApiInvite.withdrawInvitation("{{ route('invitations.withdrawInvitation',['id' => '__ID__']) }}",id,data);

                    if (!response) return;
                    xalert.salert("Success",response.message,"success");

                    if (typeof callbacks.onSuccess === "function") {
                        await callbacks.onSuccess(response);
                    }

                } catch (error) {
                    if (typeof callbacks.onError === "function") {
                        callbacks.onError(error);
                    }
                }
            });
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

            $(document).on("click","#btnUpdateInvitation",function () {
                self.update()
            });
        }
    };

    invitationModal.init()
</script>

@endpush

