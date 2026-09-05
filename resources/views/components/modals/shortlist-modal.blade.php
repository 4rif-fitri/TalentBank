<div class="modal fade" id="shortlistModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="shortlistModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="fw-semibold modal-title" id="shortlistModalLabel">
                    Add to Shortlist
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form id="shortlistForm">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="position_title" class="form-label">Candidte Name</label>
                            <input type="text" id="candidateId" hidden readonly>
                            <input type="text" id="candidateName" class="form-control" readonly required>
                        </div>

                        <div class="col-12">
                            <label for="selectPosition" class="form-label">Position</label>
                            <select  id="selectPosition" class="form-select" required></select>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="btnAddShortlist" class="btn btn-primary">
                        Save
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
