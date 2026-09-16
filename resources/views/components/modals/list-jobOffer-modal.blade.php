<div class="modal fade" id="listJobOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="listJobOfferModal" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="interviewModalLabel">
                    list Offers
                </h5>
                <button class="btn btn-primary btnShowModalAddOffer">
                    Add Offer
                </button>
            </div>
            <div class="modal-body">
                <div class="listOffersContainer"></div>
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

    function temmm(interview) {
        let withdrawUrl = "{{ route('recruiter.jobOffer.id', ['id' => '__ID__']) }}"
            .replace('__ID__', interview.id);

        return `<div data - id="${interview.id}" class="alert alert-light d-flex justify-content-between" >
            ${interview.title}
                <div>
                <a href="${withdrawUrl}" class="btn btn-primary" data-id="${interview.id}">
                    Edit
                </a>
            </div>
        </div>`;
    }

    window.listJobOfferModal = {
        details: null,
        candidateList: null,

        init() {
            const self = this;

            self.bindEvents()
        },
        open() {

        },

        handleShowModalListJobOffer(userId) {
            xmodal.show("listJobOfferModal")

            let url = "{{ route('interviews.getJobOffersByPositionIdAndReceiverId', ['receiverId' => '__RECEIVER_ID__','positionId' => '__POSITION_ID__'])}}";
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
                        html += temmm(invite)
                    });
                    $(".listOffersContainer").html(html)
                    xmodal.show("listJobOfferModal")
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

            $(document).on("click", ".btnShowModalListJobOffer", function () {
                let userId = $(this).data("id");
                self.handleShowModalListJobOffer(userId)
            })
        }
    }
</script>
