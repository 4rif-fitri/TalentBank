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
                            <option value="HOURLY">Hourly</option>
                            <option value="DAILY">Daily</option>
                            <option value="WEEKLY">Weekly</option>
                            <option value="MONTHLY">Monthly</option>
                            <option value="YEARLY">Yearly</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="expires_at" class="form-label">
                            expires Date
                        </label>
                        <input  min="{{ date('Y-m-d') }}" type="date" id="expires_at" class="form-control" required>
                    </div>

                    <div class="col-12 mb-2">
                        <label for="benefits" class="form-label">
                            Benefits
                        </label>

                        <textarea id="benefits" class="form-control" rows="3" placeholder="" required></textarea>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="recruiter_comment" class="form-label">
                            Terms and Conditions
                        </label>

                        <textarea id="terms_and_conditions" class="form-control" rows="2" placeholder="" required></textarea>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btnCencelAddJobOffer" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btnCencelupdateJobOffer" class="d-none btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" id="btnAddJobOffer" class="btn btn-primary">Save</button>
                <button type="submit" id="btnUpdateJobOffer" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>
