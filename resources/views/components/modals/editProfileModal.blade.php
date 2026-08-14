<div class="modal fade" id="editProfileModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">
            <form id="profileForm" action="/profile" method="POST">
                <!-- @csrf -->
                <input type="hidden" name="_method" value="PUT">

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

                        <div class="mb-3">
                            <label for="profileHeadlineInput" class="form-label">Headline</label>
                            <textarea class="form-control" name="headline" id="profileHeadlineInput" rows="3" maxlength="255"></textarea>
                            <div class="text-end text-muted small mt-1">
                                <span id="headlineCount">0</span>/255
                            </div>
                        </div>
                        <hr>

                        <h6 class="fw-bold mb-3">Location</h6>
                        <div class="mb-3">
                            <label for="locationInput" class="form-label">Location</label>
                            <input type="text" id="locationInput" class="form-control">
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

        $("#btnEditProfile").on("click", function() {
            showProfileLoading();

        $.ajax({
            url: "../data/profile.json",
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log(response);
                /*
                |----------------------------------------------
                | Expected:
                |
                | {
                |   "name": "Lorem bin Ipsum",
                |   "headline": "...",
                |   "location": "Durian Tunggal, Melaka, Malaysia"
                | }
                |----------------------------------------------
                */
                $("#profileNameInput").val(response.name ?? "");
                $("#profileHeadlineInput").val(response.headline ?? "");
                $("#headlineCount").text(
                    (response.headline ?? "").length
                );

                $("#locationInput").val(response.location ?? "");

                hideProfileLoading();
                profileModal.show();
            },


            error: function(xhr) {
                console.error(xhr);
                hideProfileLoading();

                Swal.fire({
                    title: "Unable to load profile",
                    text: "Profile data could not be loaded.",
                    icon: "error"
                });
            }
        });
        });

        $("#profileForm").on("submit", function() {
            $btnSaveProfile.prop("disabled", true).text("Saving...");
        });

        $("#profileHeadlineInput").on("input", function() {
            $("#headlineCount").text(this.value.length);
        });

        profileModalEl.addEventListener("hidden.bs.modal", function() {
            $("#profileForm")[0].reset();
            $("#headlineCount").text("0");
            $btnSaveProfile.prop("disabled", false).text("Save");
        });
    </script>
@endpush
