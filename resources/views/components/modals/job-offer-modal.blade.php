<div class="modal fade" id="jobOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="jobOfferModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-titlem fw-semibold" id="jobOfferModalLabel">
                    Create JobOffer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <input type="number" class="form-control invitation_id" hidden readonly>

                    <div class="col-md-12 mb-3">
                        <label class="form-label offer-candidate-name">
                            Candidate
                        </label>
                        <input type="text" id="job-offer-candicate-id" hidden>
                    </div>

                <div class="col-md-12 mb-3">
                    <label id="jobOfferPositionName" class="form-label">
                        Position
                    </label>
                    <input type="text" id="jobOfferPositionId" hidden>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="salary_amount" class="form-label">
                        title
                    </label>
                    <input type="text" name="title" id="job-offer-title" class="form-control" required>
                </div>

                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">
                            Start Date
                        </label>
                        <input type="date" id="start_date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">
                            End Date
                        </label>
                        <input type="date" id="end_date" class="form-control" required>
                    </div>


                    <div class="col-md-12 mb-3">
                        <label for="salary_amount" class="form-label">
                            Salary Amount
                        </label>
                        <input type="number" min="0" id="salary_amount" class="form-control" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="salary_period" class="form-label">
                            Salary Period
                        </label>

                        <select class="form-select" id="salary_period">
                            <option value="Hourly">Hourly</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Yearly">Yearly</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="expires_at" class="form-label">
                            expires Date
                        </label>
                        <input  min="{{ date('Y-m-d') }}" type="date" id="expires_at" class="form-control" required>
                    </div>

                    <div class="col-6 mb-2">
                        <label for="benefits" class="form-label">
                            Benefits
                        </label>

                        <textarea id="benefits" class="form-control" rows="4" placeholder="" required></textarea>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="recruiter_comment" class="form-label">
                            Terms and Conditions
                        </label>

                        <textarea id="terms_and_conditions" class="form-control" rows="4" placeholder="" required></textarea>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btnCencelAddJobOffer" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btnCencelupdateJobOffer" class="d-none btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btnAddJobOffer" class="btn btn-primary">Save</button>
                <button type="button" id="btnUpdateJobOffer" class="btn btn-primary">Update</button>
            </div>

        </div>
    </div>
</div>

<script type="module">
    window.jobOfferModal = {
        currentUserId: null,
        details: null,
        candidateList: null,

        init() {
            const self = this;
            this.bindEvents()
        },
        open() {
            xmodal.show("jobOfferModal");
            xmodal.hide("listJobOfferModal");
        },

        save(){

        },

        async storeJobOffer($form){
            let data = {
                _token: $('meta[name="csrf-token"]').attr("content"),
                salary_amount: $form.find("#salary_amount").val(),
                salary_period: $form.find("#salary_period").val(),
                start_date: $form.find("#start_date").val(),
                end_date: $form.find("#end_date").val(),
                terms_and_conditions: $form.find("#terms_and_conditions").val(),
                benefits: $form.find("#benefits").val(),
                expires_at: $form.find("#expires_at").val(),
                position_id: $form.find("#jobOfferPositionId").val(),
                receiver_profile_id: $form.find("#job-offer-candicate-id").val(),
                title: $form.find("#job-offer-title").val()
            }

            try {
                let response = await xApiJobOffer.store("{{ route('jobOffers.store') }}", data)
                if (!response) return

                xalert.fire("Success", response.message, "success")
                xmodal.hide("jobOfferModal")

            } catch (error) {
                console.error(error);

            }
        },

        showModalAddJobOffer(){
            let profileId = this.currentUserId
            let candidte = this.candidateList.find(user => user.id == profileId)

            console.log("profileId", profileId);
            console.log("candidte", candidte);
            console.log("position", this.details);


            $("#jobOfferModal .offer-candidate-name").text(`Candidate: ${candidte.name}`)
            $("#jobOfferModal #job-offer-candicate-id").text(candidte.id)

            $("#jobOfferModal #jobOfferPositionName").text(`Position: ${this.details.position_title}`)
            $("#jobOfferModal #jobOfferPositionId").text(`Position: ${this.details.id}`)

            $("#btnAddJobOffer").removeClass("d-none");
            $("#btnUpdateJobOffer").addClass("d-none");

            this.open()
        },

        handleAddJobOffer() {

            const $form = $("#jobOfferForm");

            const salary_amount =
                $form.find("#salary_amount").val();

            const salary_period =
                $form.find("#salary_period").val();

            const start_date =
                $form.find("#start_date").val();

            const end_date =
                $form.find("#end_date").val();

            const terms_and_conditions =
                $form.find("#terms_and_conditions").val();

            const benefits =
                $form.find("#benefits").val();

            const expires_at =
                $form.find("#expires_at").val();

            const position_id =
                $form.find("#jobOfferPositionId").val();

            const receiver_profile_id =
                $form.find("#job-offer-candicate-id").val();

            const title =
                $form.find("#job-offer-title").val();

            if (!salary_amount || Number(salary_amount) <= 0) {
                xalert.fire(
                    "Warning",
                    "Please enter a valid salary amount.",
                    "warning"
                );
                return;
            }

            if (!salary_period) {
                xalert.fire(
                    "Warning",
                    "Please select salary period.",
                    "warning"
                );
                return;
            }

            if (!start_date) {
                xalert.fire(
                    "Warning",
                    "Please select start date.",
                    "warning"
                );
                return;
            }

            if (!end_date) {
                xalert.fire(
                    "Warning",
                    "Please select end date.",
                    "warning"
                );
                return;
            }

            if (end_date < start_date) {
                xalert.fire(
                    "Warning",
                    "End date cannot be before start date.",
                    "warning"
                );
                return;
            }

            if (!expires_at) {
                xalert.fire(
                    "Warning",
                    "Please enter expiration date.",
                    "warning"
                );
                return;
            }

            if (!position_id) {
                xalert.fire(
                    "Warning",
                    "Position ID not found.",
                    "warning"
                );
                return;
            }

            if (!receiver_profile_id) {
                xalert.fire(
                    "Warning",
                    "Receiver profile ID not found.",
                    "warning"
                );
                return;
            }

            storeJobOffer($form);
        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail", function (event, details, candidateList) {
                self.details = details
                self.candidateList = candidateList
            })

            $(document).on("click", ".btnShowModalAddOffer", function (){
                self.currentUserId = $(this).data("id")
                self.showModalAddJobOffer()
            })

            $(document).on("click", "#btnAddJobOffer", function(){
                this.handleAddJobOffer()
            })

        }
    }
</script>
