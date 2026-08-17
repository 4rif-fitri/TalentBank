<div class="modal fade" id="editProfileModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">
            <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Intro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="profileLoading" class="text-center py-4 d-none">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span class="ms-2">Loading...</span>
                    </div>

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
                    <button type="submit" class="btn btn-primary" id="btnSaveProfile">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let profileModalEl = document.getElementById("editProfileModal");
        let profileModal = bootstrap.Modal.getOrCreateInstance(profileModalEl);

        let $profileLoading = $("#profileLoading");
        let $profileFields = $("#profileFields");
        let $btnSaveProfile = $("#btnSaveProfile")

        function showProfileLoading() {
            $profileLoading.removeClass("d-none");
            $profileFields.addClass("d-none");
            $btnSaveProfile.prop("disabled", true);
        }

        function hideProfileLoading() {
            $profileLoading.addClass("d-none");
            $profileFields.removeClass("d-none");
            $btnSaveProfile.prop("disabled", false);
        }

        $("#btnEditProfile").on("click", function () {
            profileModal.show();
            $("#profileNameInput").val($("#name").text())
            $("#profileHeadlineInput").val($("#headline").text())
            $("#locationInput").val($("#profileLocation").text())
            $("#profileEmailInput").val($("#email").text())
            $("#profilePhoneNoInput").val($("#phoneNo").text())
            $("#headlineCount").text($("#profileHeadlineInput").val().length);
        });

        $("#profileForm").on("submit", function (e) {
            e.preventDefault();

            let $form = $(this);
            let formData = new FormData(this);

            $btnSaveProfile.prop("disabled", true).text("Saving...");

            $.ajax({
                url: $form.attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function (response) {
                    console.log(response);

                    profileModal.hide();
                    getProfileData()
                    swalfire("Success", xhr.responseJSON?.message ?? response.message ?? "Profile updated successfully", "success")
                },

                error: function (xhr) {
                    console.error(xhr.responseJSON);
                    swalfire("Unable to edit profile", xhr.responseJSON?.message ?? "Unable to update profile", "error")
                },

                complete: function () {
                    $btnSaveProfile
                        .prop("disabled", false)
                        .text("Save");
                }
            });
        });

        $("#profileHeadlineInput").on("input", function () {
            $("#headlineCount").text(this.value.length);
        });

        profileModalEl.addEventListener("hidden.bs.modal", function () {
            $("#profileForm")[0].reset();
            $("#headlineCount").text("0");
            $btnSaveProfile.prop("disabled", false).text("Save");
        });
    })
</script>
@endpush
