<div class="modal fade" id="jobOfferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="jobOfferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="jobOfferModalLabel">
                    Create Job Offer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="job-offer-invitation-id">
                    <div class="col-md-12 mb-3">
                        <label class="form-label offer-candidate-name">
                            Candidate
                        </label>
                        <input type="hidden" id="job-offer-candidate-id">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label id="jobOfferPositionName" class="form-label">
                            Position
                        </label>
                        <input type="hidden" id="jobOfferPositionId">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="job-offer-title" class="form-label">
                            Title
                        </label>
                        <input type="text" id="job-offer-title" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">
                            Start Date
                        </label>
                        <input type="date" id="start_date" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">
                            End Date
                        </label>
                        <input type="date" id="end_date" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="salary_amount" class="form-label">
                            Salary Amount
                        </label>
                        <input type="number" min="0" id="salary_amount" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="salary_period" class="form-label">
                            Salary Period
                        </label>
                        <select class="form-select" id="salary_period">
                            <option value="">Select salary period</option>
                            <option value="Hourly">Hourly</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Yearly">Yearly</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="expires_at" class="form-label">
                            Expires Date
                        </label>
                        <input type="date" min="{{ date('Y-m-d') }}" id="expires_at" class="form-control">
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="benefits" class="form-label">
                            Benefits
                        </label>
                        <textarea id="benefits" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="terms_and_conditions" class="form-label">
                            Terms and Conditions
                        </label>
                        <textarea id="terms_and_conditions" class="form-control" rows="4"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btnCencelAddJobOffer" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                </button>
                <button type="button"  id="btnAddJobOffer" class="btn btn-primary">
                    Save
                </button>
                <button type="button" id="btnUpdateJobOffer" class="btn btn-primary">
                    Update
                </button>
            </div>
        </div>
    </div>
</div>


@push('childScript')

<script type="module">

    window.jobOfferModal = {
        currentUserId: null,
        details: null,
        candidateList: null,

        init() {
            this.bindEvents();
        },

        open() {
            xmodal.show("jobOfferModal");
            xmodal.hide("listJobOfferModal");
        },

        reset() {
            $("#jobOfferModal #job-offer-invitation-id").val("");
            $("#jobOfferModal #job-offer-candidate-id").val("");
            $("#jobOfferModal #jobOfferPositionId").val("");
            $("#jobOfferModal #job-offer-title").val("");
            $("#jobOfferModal #start_date").val("");
            $("#jobOfferModal #end_date").val("");
            $("#jobOfferModal #salary_amount").val("");
            $("#jobOfferModal #salary_period").val("");
            $("#jobOfferModal #expires_at").val("");
            $("#jobOfferModal #benefits").val("");
            $("#jobOfferModal #terms_and_conditions").val("");
            $("#jobOfferModal .offer-candidate-name").text("Candidate");
            $("#jobOfferModal #jobOfferPositionName").text("Position");
        },

        showModalAddJobOffer() {
            const profileId = this.currentUserId;
            const candidate = this.candidateList?.find(user => user.id == profileId);

            console.log("profileId:",profileId);
            console.log("candidate:",candidate);
            console.log("position:",this.details);

            if (!candidate) {
                xalert.fire("Error","Candidate not found","error");
                return;
            }

            if (!this.details) {
                xalert.fire("Error","Position details not found","error");
                return;
            }

            this.reset();

            $("#jobOfferModal .offer-candidate-name").text(`Candidate: ${candidate.name}`);
            $("#jobOfferModal #job-offer-candidate-id").val(candidate.id);
            $("#jobOfferModal #jobOfferPositionName").text(`Position: ${this.details.position_title}`);
            $("#jobOfferModal #jobOfferPositionId").val(this.details.id);

            $("#btnAddJobOffer").removeClass("d-none");
            $("#btnUpdateJobOffer").addClass("d-none");
            $("#btnCencelAddJobOffer").removeClass("d-none");
            $("#btnCencelupdateJobOffer").addClass("d-none");

            this.open();
        },

        async storeJobOffer(data) {

            try {
                const response = await xApiJobOffer.store("{{ route('jobOffers.store') }}",data);

                if (!response) {
                    return null;
                }

                xalert.fire("Success",response.message,"success");
                xmodal.hide("jobOfferModal");

                this.reset();
                return response;

            } catch (error) {
                console.error("Error storing job offer:",error);
                throw error;
            }
        },

        async handleAddJobOffer() {

            const salary_amount = $("#jobOfferModal #salary_amount").val();
            const salary_period = $("#jobOfferModal #salary_period").val();
            const start_date = $("#jobOfferModal #start_date").val();
            const end_date = $("#jobOfferModal #end_date").val();
            const terms_and_conditions = $("#jobOfferModal #terms_and_conditions").val().trim();
            const benefits = $("#jobOfferModal #benefits").val().trim();
            const expires_at = $("#jobOfferModal #expires_at").val();
            const position_id = $("#jobOfferModal #jobOfferPositionId").val();
            const receiver_profile_id = $("#jobOfferModal #job-offer-candidate-id").val();
            const title = $("#jobOfferModal #job-offer-title").val().trim();

            if (!title) {
                xalert.fire("Warning","Please enter job offer title.","warning");
                return;
            }
            if (!salary_amount || Number(salary_amount) <= 0) {
                xalert.fire("Warning","Please enter a valid salary amount.","warning");
                return;
            }
            if (!salary_period) {
                xalert.fire("Warning","Please select salary period.","warning");
                return;
            }
            if (!start_date) {
                xalert.fire("Warning","Please select start date.","warning");
                return;
            }
            if (!end_date) {
                xalert.fire("Warning","Please select end date.","warning");
                return;
            }
            if (end_date < start_date) {
                xalert.fire("Warning","End date cannot be before start date.","warning");
                return;
            }
            if (!expires_at) {
                xalert.fire("Warning","Please select expiration date.","warning");
                return;
            }
            if (!position_id) {
                xalert.fire("Warning","Position ID not found.","warning");
                return;
            }
            if (!receiver_profile_id) {
                xalert.fire("Warning","Receiver profile ID not found.","warning");
                return;
            }
            if (!benefits) {
                xalert.fire("Warning","Please enter benefits.","warning");
                return;
            }
            if (!terms_and_conditions) {
                xalert.fire("Warning","Please enter terms and conditions.","warning");
                return;
            }

            const data = {
                _token:$('meta[name="csrf-token"]').attr("content"),
                salary_amount:salary_amount,
                salary_period:salary_period,
                start_date:start_date,
                end_date:end_date,
                terms_and_conditions:terms_and_conditions,
                benefits:benefits,
                expires_at:expires_at,
                position_id:position_id,
                receiver_profile_id:receiver_profile_id,
                title:title
            };

            console.log("Job Offer Data:",data);

            try {
                await this.storeJobOffer(data);

            } catch (error) {
                console.error(error);
                const message = error?.responseJSON?.message || error?.response?.data?.message || "Something went wrong";
                xalert.fire("Error", message,"error");
            }
        },

        openUpdate(currentJobOffer){

            $(".offer-candidate-name").text(`Candidate: ${currentJobOffer.receiver.name}`)
            $("#jobOfferPositionName").text(`Position: ${currentJobOffer.position.position_title}`)
            $("#job-offer-title").val(currentJobOffer.title)
            $("#start_date").val(currentJobOffer.start_date)
            $("#end_date").val(currentJobOffer.end_date)

            $("#salary_amount").val(currentJobOffer.salary_amount)
            $("#salary_period").val(currentJobOffer.salary_period)
            $("#expires_at").val(currentJobOffer.expires_at.split(" ")[0])
            $("#benefits").val(currentJobOffer.benefits)
            $("#terms_and_conditions").val(currentJobOffer.terms_and_conditions)
            $("#job-offer-candicate-id").val(currentJobOffer.receiver.id)
            $("#QjobOfferPositionId").val(currentJobOffer.position.id)

            $("#jobOfferModal #btnAddJobOffer").hide()
            $("#jobOfferModal #btnUpdateJobOffer").show()
            xmodal.show("jobOfferModal")
        },

        async update(id) {

            let data = {
                '_token': $('meta[name="csrf-token"]').attr("content"),
                'title': $("#job-offer-title").val(),
                'salary_amount': $("#salary_amount").val(),
                'salary_period': $("#salary_period").val(),
                'start_date': $("#start_date").val(),
                'end_date': $("#end_date").val(),
                'terms_and_conditions': $("#terms_and_conditions").val(),
                'benefits': $("#benefits").val(),
                'expires_at': $("#expires_at").val(),
                '_method': "PUT",
            }

            try {
                let response = await xApiEducation.update("{{ route('jobOffers.update', ['id' => '__ID__']) }}", id, data)
                if (!response) return

                // getJobOffersByStatus(currentStatus)

                xalert.success(response.message)
                xmodal.hide("jobOfferModal")

            } catch (error) {
                console.error(error);
            }

        },

        async withdraw(id){
            let data = {
                _method: "PUT",
                _token: $('meta[name="csrf-token"]').attr("content")
            }

            try {
                let response = await xApiJobOffer.withdrawJobOffer("{{ route('jobOffers.withdrawJobOffer', ['id' => '__ID__']) }}", id, data)
                if (!response) return

                $(`#recruitment-invitation-list .list-item[data-id="${response.data.id}"]`).remove();
                $(".results-panel").html(xcommon.noSelected("No Job Offer Selected", "", "btn-toggle-filter toggleFilter", "Interview"))
                xalert.success(response.message)

            } catch (error) {
                console.error(error);
            }
        },

        bindEvents() {
            const self = this;

            $(document).on("position:detail", function (event,details,candidateList) {
                self.details = details;
                self.candidateList = candidateList;
            });

            $(document).on("click",".btnShowModalAddOffer",function () {
                self.currentUserId = $(this).data("id");
                self.showModalAddJobOffer();
            });

            $(document).on("click","#jobOfferModal #btnAddJobOffer",function () {
                self.handleAddJobOffer();
            });

            $(document).on("click","#jobOfferModal #btnUpdateJobOffer",function () {
                self.update();
            });
        }
    };

</script>

@endpush
