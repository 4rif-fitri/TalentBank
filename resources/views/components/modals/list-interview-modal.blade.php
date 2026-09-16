<div class="modal fade" id="listInterviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="listInterviewModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header d-flex justify-content-between">

                <h5 class="modal-title" id="listInterviewModalLabel">
                    List Interview
                </h5>

                <button type="button" class="btn btn-primary btnShowModalAddInterview">
                    Add Interview
                </button>

            </div>

            <div class="modal-body">

                <div class="listInterviewContainer">
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

@push('childScript')
<script type="module">

    window.listInterviewModal = {

        details: null,
        candidateList: null,
        currentUserId: null,

        init() {
            this.bindEvents();
        },

        renderItem(interview) {
            const editUrl = "{{ route('recruiter.interview.id', ['id' => '__ID__']) }}"
                                .replace('__ID__', interview.id);

            return `<div data-id="${interview.id}"
                        class="alert alert-light d-flex justify-content-between align-items-center">

                        <div>${interview.title}</div>

                        <div class="d-flex gap-2">
                            <a href="${editUrl}" class="btn btn-primary" data-id="${interview.id}">
                                Edit
                            </a>
                        </div>
                    </div>`;
        },

        renderList(listOfInterview) {
            const $container = $("#listInterviewModal .listInterviewContainer");
            $container.empty();

            if (!listOfInterview || !listOfInterview.length) {

                $container.html(`<div class="text-center text-muted py-4">
                                    No interview found.
                                </div>`);
                return;
            }

            const html = listOfInterview.map(interview => this.renderItem(interview)).join("");

            $container.html(html);
        },

        async handleShowModalListInterview(userId) {

            if (!this.details) {
                xalert.fire("Error","Position details not found","error");
                return;
            }
            if (!userId) {
                xalert.fire("Error","Candidate ID is missing","error");
                return;
            }

            this.currentUserId = userId;

            const url = "{{ route('interviews.getInterviewsByPositionIdAndIntervieweeId', ['intervieweeId' => '__INTERVIEWEE_ID__','positionId' => '__POSITION_ID__'])}}"
                            .replace("__INTERVIEWEE_ID__",userId)
                            .replace("__POSITION_ID__",this.details.id);

            try {
                const response = await $.ajax({
                    url: url,
                    type: "GET"
                });

                const listOfInterview = response.data ?? [];
                this.renderList(listOfInterview);
                xmodal.show("listInterviewModal");

            } catch (error) {
                console.error("Error loading interviews:",error);
                const message = error?.responseJSON?.message || "Failed to load interviews.";
                xalert.fire("Error",message,"error");
            }
        },

        openAddInterview() {
            if (!this.currentUserId) {
                xalert.fire("Error","Candidate ID is missing","error");
                return;
            }

            interviewModal.currentUserId = this.currentUserId;
            xmodal.hide("listInterviewModal");
            interviewModal.showModalAddInterview();
        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail",function (event, details, candidateList) {
                self.details = details;
                self.candidateList = candidateList;
            });

            $(document).on("click",".btnShowModalListInterview",function () {
                const userId = $(this).data("id");
                self.handleShowModalListInterview(userId);
            });

            $(document).on("click", "#listInterviewModal .btnShowModalAddInterview", function () {
                self.openAddInterview();
            });
        }
    };
</script>
@endpush
