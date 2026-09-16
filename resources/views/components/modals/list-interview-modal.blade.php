<div class="modal fade" id="listInterviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="listInterviewModal" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="interviewModalLabel">
                    list Interview
                </h5>
                <button class="btn btn-primary btnShowModalAddIntervieww">
                    Add Interview
                </button>
            </div>
            <div class="modal-body">
                <div class="listInterviewaContainer"></div>
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

    window.listInterviewModal = {
        details: null,
        candidateList: null,

        init(){
            const self = this;

            self.bindEvents()
        },
        open(){

        },

        handleShowModalListInterview(userId) {
            xmodal.show("listInterviewModal")

            let url = "{{ route('interviews.getInterviewsByPositionIdAndIntervieweeId', ['intervieweeId' => '__INTERVIEWEE_ID__','positionId' => '__POSITION_ID__'])}}";

            url = url
                .replace("__INTERVIEWEE_ID__", userId)
                .replace("__POSITION_ID__", this.details.id);

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

        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail", function (event, details, candidateList) {
                self.details = details
                self.candidateList = candidateList
            })

            $(document).on("click", ".btnShowModalListInterview", function () {
                let userId = $(this).data("id");
                self.handleShowModalListInterview(userId)
            })
        }
    }
</script>
