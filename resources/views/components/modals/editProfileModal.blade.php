<div class="modal fade" id="editProfileModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Intro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- <div id="profileLoading" class="text-center py-4 d-none">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span class="ms-2">Loading...</span>
                    </div> -->

                    <div id="profileFields">
                        <div class="mb-3">
                            <label for="profileNameInput" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" id="profileNameInput" required>
                        </div>

                        <div class="mb-3 d-none">
                            <label for="profileEmailInput" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="profileEmailInput" required>
                        </div>

                        <div class="mb-3 d-none">
                            <label for="profileEmailInput" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone_no" id="profilePhoneNoInput" required>
                        </div>

                        <div class="mb-3">
                            <label for="locationInput" class="form-label">Location</label>
                            <input type="text" name="location" id="locationInput" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="profileHeadlineInput" class="form-label">Headline</label>
                            <textarea class="form-control" name="headline" id="profileHeadlineInput" rows="3"
                                maxlength="255"></textarea>
                            <div class="text-end text-muted small mt-1">
                                <span id="headlineCount">0</span>/255
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="btnSaveProfile">Save</button>
                </div>
        </div>
    </div>
</div>


