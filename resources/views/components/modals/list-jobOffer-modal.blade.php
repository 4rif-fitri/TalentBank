<div class="modal fade" id="listJobOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="listJobOfferModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header d-flex justify-content-between">

                <h5 class="modal-title" id="listJobOfferModalLabel">
                    List Offers
                </h5>

                <button type="button" class="btn btn-primary btnShowModalAddOffer">
                    Add Offer
                </button>

            </div>

            <div class="modal-body">

                <div class="listOffersContainer">
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

    window.listJobOfferModal = {

        details: null,
        candidateList: null,
        currentUserId: null,

        init() {
            this.bindEvents();
        },

        renderItem(offer) {

            const editUrl = "{{ route('recruiter.jobOffer.id', ['id' => '__ID__']) }}"
                                .replace('__ID__', offer.id);

            return `<div data-id="${offer.id}" class="alert alert-light d-flex justify-content-between align-items-center">
                        <div>${offer.title}</div>
                            <div class="d-flex gap-2">
                            <a href="${editUrl}" class="btn btn-primary" data-id="${offer.id}">
                                Edit
                            </a>
                        </div>
                    </div>`;
        },

        renderList(listOfOffer) {
            const $container = $("#listJobOfferModal .listOffersContainer");
            $container.empty();

            if (!listOfOffer || !listOfOffer.length) {
                $container.html(`<div class="text-center text-muted py-4">No job offer found.</div>`);
                return;
            }

            const html = listOfOffer.map(offer => this.renderItem(offer)).join("");
            $container.html(html);
        },

        async handleShowModalListJobOffer(userId) {
            console.log("userId", userId);

            if (!this.details) {
                xalert.fire("Error","Position details not found","error");
                return;
            }
            if (!userId) {
                xalert.fire("Error","Candidate ID is missing","error");
                return;
            }

            this.currentUserId = userId;

            const url = "{{ route('interviews.getJobOffersByPositionIdAndReceiverId', ['receiverId' => '__RECEIVER_ID__','positionId' => '__POSITION_ID__'])}}"
                            .replace("__RECEIVER_ID__",userId)
                            .replace("__POSITION_ID__",this.details.id);

            try {
                const response = await $.ajax({
                    url: url,
                    type: "GET"
                });

                const listOfOffer = response.data ?? [];
                this.renderList(listOfOffer);
                xmodal.show("listJobOfferModal");

            } catch (error) {
                console.error("Error loading job offers:",error);
                const message = error?.responseJSON?.message || "Failed to load job offers.";
                xalert.fire("Error",message,"error");
            }
        },

        openAddOffer() {
            if (!this.currentUserId) {
                xalert.fire("Error","Candidate ID is missing","error");
                return;
            }

            jobOfferModal.currentUserId = this.currentUserId;

            xmodal.hide("listJobOfferModal");
            this.open()
        },

        open(){
            xmodal.hide("listJobOfferModal")
            xmodal.show("listJobOfferModal")
        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail",function (event, details, candidateList) {
                self.details = details;
                self.candidateList = candidateList;
            });

            $(document).on("click",".btnShowModalListJobOffer",function () {
                const userId = $(this).data("id");
                $(".btnShowModalAddOffer").attr("data-id", userId)

                self.handleShowModalListJobOffer(userId);
            });

            $(document).on("click", "#listJobOfferModal .btnShowModalAddOffer",function () {
                self.openAddOffer();
            });
        }
    };
</script>
@endpush
