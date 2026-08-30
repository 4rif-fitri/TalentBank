<div class="modal fade" id="interviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="interviewModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="interviewModalLabel">
                    Schedule Interview
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form id="inviteForm">

                <div class="modal-body">
                    <div class="row">
                        <input type="number invitation_id" class="form-control" hidden readonly>

                        <div class="col-md-12 mb-3">
                            <label for="invite_position_title" class="form-label">
                                Candidate
                            </label>

                            <input type="text" class="form-control candidate_name" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="expires_at" class="form-label">
                                Interview Date
                            </label>

                            <input type="date" id="interview_date" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="expires_at" class="form-label">
                                Start Time
                            </label>

                            <input type="time" id="start_time" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label d-block">
                                Interview Mode <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-4 justify-content-evenly px-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="interview_mode" id="mode_online"
                                        value="Online" required>
                                    <label class="form-check-label" for="mode_online">
                                        Online
                                        <i class="fa-solid fa-video" style="color: rgb(0, 0, 0);"></i>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="interview_mode" id="mode_onsite"
                                        value="On-site">
                                    <label class="form-check-label" for="mode_onsite">
                                        On-site
                                        <i class="fa-solid fa-building" style="color: rgb(0, 0, 0);"></i>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="interview_mode" id="mode_phone"
                                        value="Phone">
                                    <label class="form-check-label" for="mode_phone">
                                        Phone
                                        <i class="fa-solid fa-phone" style="color: rgb(0, 0, 0);"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3 d-none" id="div_meeting_url">
                            <label for="meeting_url" class="form-label">
                                Meeting Link / URL <span class="text-danger">*</span>
                            </label>
                            <input type="url" id="meeting_url" class="form-control"
                                placeholder="https://meet.google.com/xyz or https://zoom.us/j/xyz">
                        </div>

                        <div class="col-md-12 mb-3 d-none" id="div_location">
                            <label for="location" class="form-label">
                                Physical Location <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="location" class="form-control"
                                placeholder="Bilik Mesyuarat 1, Tingkat 2 / Alamat Pejabat">
                        </div>

                        <div class="col-12 mb-3">
                            <label for="recruiter_comment" class="form-label">
                                Recruiter Comment
                            </label>

                            <textarea id="recruiter_comment" class="form-control" rows="5" placeholder=""
                                required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" id="btnAddInterview" class="btn btn-primary">
                        Save
                    </button>
                    <button type="submit" id="btnUpdateInterview" class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>