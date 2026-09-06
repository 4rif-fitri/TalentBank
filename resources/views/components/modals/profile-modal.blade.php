<div class="modal fade" id="editProfileModal" data-bs-backdrop="static" tabindex="-1" >
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Personal Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div id="profileFields">
                    <div class="mb-3">
                        <label for="profileNameInput" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" id="profileNameInput" required>
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
                <button type="button" class="btn btn-outline-secondary" id="btnCencelEditProfile">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveProfile">Save</button>
            </div>
        </div>
    </div>
</div>

@push('childScript')
<script type="module">

    function handleEditDetil(){
        xmodal.show("editProfileModal")
        $("#profileNameInput").val(stateProfile.name)
        $("#locationInput").val(stateProfile.location)
        $("#profileHeadlineInput").val(stateProfile.headline)
    }

    function handleCencelEditProfile(){
        xmodal.hide("editProfileModal")
        $("#profileNameInput").val(stateProfile.name)
        $("#locationInput").val(stateProfile.location)
        $("#profileHeadlineInput").val(stateProfile.headline)
    }

    function handleSaveProfile(){

         let data = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            _method: "PUT",
            name: $("#profileNameInput").val(),
            email: stateProfile.email,
            headline: $("#profileHeadlineInput").val(),
            location: $("#locationInput").val(),
            phone_no: stateProfile.phoneNo,
        }

        $.ajax({
            url: "{{ route('profile.update') }}",
            type: "POST",
            data,
            success: function (response) {
                xmodal.hide("editProfileModal")
                xdebug.line(response.data)
                xalert.fire("Success", response.message, "success")
                $(document).trigger("profile:stateProfile:updated", [response.data])
            },
            error: xhr => {
                xdebug.line(xhr.responseJSON.message)
            }
        });
    }

    $(document).on("click","#btnEditProfile", handleEditDetil)
    $(document).on("click", "#btnCencelEditProfile", handleCencelEditProfile)
    $(document).on("click", "#btnSaveProfile", handleSaveProfile)

</script>
@endpush
