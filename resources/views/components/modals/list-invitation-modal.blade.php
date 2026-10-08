<div class="modal fade" id="listInvitationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="listInvitationModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="listInvitationModalLabel">
                    Invitations
                </h5>
                <button type="button" class="btn btn-primary btnShowModalAddInvitee">
                    Add Invite
                </button>
            </div>

            <div class="modal-body">
                <div class="listItemsContainer"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btnClose">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('childScript')
<script type="module">

    window.listInvitationModal = {
        details: null,
        candidateList: null,
        currentUserId: null,

        init() {
            this.bindEvents();
        },

        renderItem(invite) {
            const withdrawUrl = "{{ route('recruiter.invitation.id', ['id' => '__ID__']) }}"
                                    .replace('__ID__', invite.id);

            return `<div data-id="${invite.id}" class="alert alert-light d-flex justify-content-between align-items-center">
                        <div>${invite.title}</div>
                        <div class="d-flex gap-2">
                            <a href="${withdrawUrl}" class="btn btn-primary" data-id="${invite.id}">Edit</a>
                        </div>
                    </div>`;
        },

        renderList(listOfInvite) {

            const $container = $("#listInvitationModal .listItemsContainer");
            $container.empty();

            if (!listOfInvite || !listOfInvite.length) {

                $container.html(`<div class="text-center text-muted py-4">
                                    No invitation found.
                                </div>`);
                return;
            }

            const html = listOfInvite.map(invite => this.renderItem(invite)).join("");
            $container.html(html);
        },

        async handleShowModalListInvite(userId) {
            if (!this.details) {
                xalert.fire("Error","Position details not found","error");
                return;
            }
            if (!userId) {
                xalert.fire("Error","Candidate ID is missing","error");
                return;
            }

            this.currentUserId = userId;
            const url = "{{ route('invitations.getInvitationsByPositionIdAndReceiverId', ['receiverId' => '__RECEIVER_ID__', 'positionId' => '__POSITION_ID__'])}}"
                            .replace("__RECEIVER_ID__", userId)
                            .replace("__POSITION_ID__", this.details.id);

            try {
                const response = await $.ajax({
                    url: url,
                    type: "GET"
                });

                const listOfInvite = response.data ?? [];
                this.renderList(listOfInvite);
                xmodal.show("listInvitationModal");

            } catch (error) {
                console.error("Error loading invitations:",error);
                const message = error?.responseJSON?.message || "Failed to load invitations.";
                xalert.fire("Error",message,"error");
            }
        },

        openAddInvitation() {
            if (!this.currentUserId) {
                xalert.fire("Error","Candidate ID is missing","error");
                return;
            }

            $(".btnShowModalAddInvitee").attr("data-id", this.currentUserId);

            xmodal.hide("listInvitationModal");
            invitationModal.show();
        },

        close() {
            xmodal.hide("listInvitationModal");
        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail",function (event, details, candidateList) {
                self.details = details;
                self.candidateList = candidateList;
            });

            $(document).on("click",".btnShowModalListInvite",function () {
                const userId = $(this).data("id");
                $(".btnShowModalAddInvitee").attr("data-id", userId)
                self.handleShowModalListInvite(userId);
            });

            $(document).on("click","#listInvitationModal .btnClose",function () {
                self.close();
            });
        }
    };
</script>
@endpush
