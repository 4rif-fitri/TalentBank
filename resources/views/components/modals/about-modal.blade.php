<div class="modal fade" id="editAboutModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit About</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <div id="aboutLoading" class="text-center py-4 d-none">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <span class="ms-2">Loading...</span>
                </div>

                <div id="aboutFields">
                    <div class="mb-3">
                        <label for="aboutInput" class="form-label">About</label>
                        <textarea class="form-control" name="about" id="aboutInput" rows="6" maxlength="255"></textarea>
                        <div class="text-end text-muted small mt-1">
                            <span id="aboutCount">0</span>/255
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btnSaveAbout">Save</button>
            </div>

        </div>
    </div>
</div>
