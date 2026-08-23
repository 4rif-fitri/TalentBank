<div class="modal fade" id="editContactInformationModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <!-- <div id="contactLoading" class="text-center py-4 d-none">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <span class="ms-2">Loading...</span>
                </div> -->

                <div id="contactFields">
                    <input type="hidden" name="name" id="contactNameInput">
                    <input type="hidden" name="headline" id="contactHeadlineInput">
                    <input type="hidden" name="location" id="contactLocationInput">
                    <div class="mb-3">
                        <label for="contactEmailInput" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="contactEmailInput" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactPhoneNoInput" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone_no" id="contactPhoneNoInput" maxlength="11">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btnSaveContact">Save</button>
            </div>
        </div>
    </div>
</div>


