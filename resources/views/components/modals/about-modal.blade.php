<div class="modal fade" id="editAboutModal" data-bs-backdrop="static" tabindex="-1">
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
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="btnCencelSaveAbout">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveAbout">Save</button>
            </div>

        </div>
    </div>
</div>

@push('childScript')
<script>

    function handleEditAbout() {
        xmodal.show("editAboutModal")
        $("#aboutInput").val(about ?? "")
    }

    function handleCencelSaveAbout(){
        $("#aboutInput").val(about ?? "")
        xmodal.hide("editAboutModal")
    }

    function handleUpdateAbout(){

        let data = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            _method: "PUT",
            about: $("#aboutInput").val(),
        }

        $.ajax({
            url: "{{ route('profile.updateAboutField') }}",
            type: "POST",
            data,
            success: function (response) {
                xmodal.hide("editAboutModal")
                xdebug.line(response.data)
                xalert.fire("Success",response.message, "success")
                $(document).trigger("profile:about:updated", [response.data])
            },
            error: xhr => {
                xdebug.line(xhr.responseJSON.message)
            }
        });
    }

    $(document).on("click", "#btnEditAbout", handleEditAbout)
    $(document).on("click", "#btnCencelSaveAbout", handleCencelSaveAbout)
    $(document).on("click", "#btnSaveAbout", handleUpdateAbout)

</script>
@endpush
