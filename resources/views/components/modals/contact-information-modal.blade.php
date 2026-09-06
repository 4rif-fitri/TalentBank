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
                        <input type="email" class="form-control" id="contactEmailInput" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactPhoneNoInput" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="contactPhoneNoInput" maxlength="11" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="btnCancelContact">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveContact">Save</button>
            </div>
        </div>
    </div>
</div>

@push('childScript')
<script>

    function handleEditContact(){
        xmodal.show("editContactInformationModal")
        $("#contactEmailInput").val(state.email)
        $("#contactPhoneNoInput").val(state.phoneNo)
    }

    function handleCencelSaveAbout() {
        $("#contactEmailInput").val(state.email)
        $("#contactPhoneNoInput").val(state.phoneNo)
        xmodal.hide("editContactInformationModal")
    }

    function handleUpdateContact(){

        let data = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            _method: "PUT",
            name: state.name,
            email: $("#contactEmailInput").val(),
            headline: state.headline,
            location: state.location,
            phone_no: $("#contactPhoneNoInput").val(),
        }

        $.ajax({
            url: "{{ route('profile.update') }}",
            type: "POST",
            data,
            success: function (response) {
                xmodal.hide("editContactInformationModal")
                xdebug.line(response.data)
                xalert.fire("Success", response.message, "success")
                $(document).trigger("profile:state:updated", [response.data])
            },
            error: xhr => {
                xdebug.line(xhr.responseJSON.message)
            }
        });

    }

    $("#btnEditContactInformation").on("click", handleEditContact)
    $("#btnCancelContact").on("click", handleCencelSaveAbout)
    $("#btnSaveContact").on("click", handleUpdateContact)

</script>
@endpush
