<!-- @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let profileModalEl = document.getElementById("editProfileModal");
        let profileModal = bootstrap.Modal .getOrCreateInstance(profileModalEl);

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
                    // console.log(response);

                    profileModal.hide();
                    getProfileData()
                    swalfire("Success", "Profile updated successfully", "success")
                },

                error: function (xhr) {
                    console.log(xhr);

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
@endpush -->
