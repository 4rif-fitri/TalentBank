<div class="modal fade" id="listInvitationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="listInvitationModal" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="inviteModalLabel">
                    Invite Candidate
                </h5>

                <button class="btn btn-primary btnShowModalAddInvitee">
                    Add invite
                </button>

            </div>
            <div class="modal-body">

                <div class="listItemsContainer">


                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script type="module">
    function tem(invite) {
        let withdrawUrl = "{{ route('recruiter.invitation.id', ['id' => '__ID__']) }}"
            .replace('__ID__', invite.id);

        return `<div data - id="${invite.id}" class="alert alert-light d-flex justify-content-between" >
        ${invite.title}
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

    window.listInvitationModal = {
        details: null,
        candidateList: null,

        init() {
            const self = this;

            self.bindEvents()

        },

        handleShowModalListInvite(userId) {
            xmodal.show("listInvitationModal")

            let url = "{{ route('invitations.getInvitationsByPositionIdAndReceiverId', ['receiverId' => '__RECEIVER_ID__','positionId' => '__POSITION_ID__'])}}";
            url = url
                .replace("__RECEIVER_ID__", userId)
                .replace("__POSITION_ID__", this.details.id);

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
        },

        open() {

        },
        bindEvents() {
            const self = this;

            $(document).on("position:detail", function (event, details, candidateList) {
                self.details = details
                self.candidateList = candidateList
            })

            $(document).on("click", ".btnShowModalListInvite", function () {
                let userId = $(this).data("id");
                $(".btnShowModalAddInvitee").attr("data-id", userId);
                self.handleShowModalListInvite(userId)
            })
        }
    }
</script>
