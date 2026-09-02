<div class="modal fade" id="invitationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="inviteModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="inviteModalLabel">
                    Invite Candidate
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form id="inviteForm">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="invite_position_title" class="form-label">
                                Candidate
                            </label>

                            <input type="text" id="invite_candidate" class="form-control" readonly>
                            <input type="number" id="invite_candidate_id" class="form-control" hidden readonly>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="invite_position_title" class="form-label">
                                Position Title
                            </label>

                            <input type="text" id="invite_position_title" class="form-control" readonly>
                            <input type="number" id="invite_position_id" class="form-control" hidden readonly>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="expires_at" class="form-label">
                                Expires Date
                            </label>

                            <input type="date" id="expires_at" class="form-control" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="invitation_message" class="form-label">
                                Message to Candidate
                            </label>

                            <textarea id="invitation_message" class="form-control" rows="5" placeholder=""
                                required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" id="btnAddInvitation" class="btn btn-primary">
                        Add
                    </button>
                    <button type="submit" id="btnUpdateInvitation" class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>