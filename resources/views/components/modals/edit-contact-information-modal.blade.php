<div class="modal fade" id="editContactInformationModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">

            <form id="contactInformationForm" action="{{ route('profile.update') }}" method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Contact</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div id="contactLoading" class="text-center py-4 d-none">

                        <div class="spinner-border spinner-border-sm" role="status"></div>

                        <span class="ms-2">Loading...</span>
                    </div>

                    <div id="contactFields">

                        {{-- Required oleh profile.update --}}
                        <input type="hidden" name="name" id="contactNameInput">

                        <input type="hidden" name="headline" id="contactHeadlineInput">

                        <input type="hidden" name="location" id="contactLocationInput">

                        <div class="mb-3">
                            <label for="contactEmailInput" class="form-label">
                                Email
                            </label>

                            <input type="email" class="form-control" name="email" id="contactEmailInput" required>
                        </div>

                        <div class="mb-3">
                            <label for="contactPhoneNoInput" class="form-label">
                                Phone Number
                            </label>

                            <input type="text" class="form-control" name="phone_no" id="contactPhoneNoInput"
                                maxlength="11">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary" id="btnSaveContact">
                        Save
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let contactModalEl =
            document.getElementById("editContactInformationModal");

        let contactModal =
            bootstrap.Modal.getOrCreateInstance(contactModalEl);

        let $contactLoading = $("#contactLoading");
        let $contactFields = $("#contactFields");
        let $btnSaveContact = $("#btnSaveContact");

        $("#btnEditContactInformation").on("click", function () {
            console.log("asd");

            $("#contactNameInput")
                .val($("#name").text().trim());

            $("#contactHeadlineInput")
                .val($("#headline").text().trim());

            $("#contactLocationInput")
                .val($("#profileLocation").text().trim());

            $("#contactEmailInput")
                .val($("#email").text().trim());

            $("#contactPhoneNoInput")
                .val($("#phoneNo").text().trim());

            contactModal.show();
        });


        $("#contactInformationForm").on("submit", function (e) {
            e.preventDefault();

            let $form = $(this);
            let formData = new FormData(this);

            $btnSaveContact
                .prop("disabled", true)
                .text("Saving...");

            $.ajax({
                url: $form.attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function (response) {

                    $("#email").text(
                        $("#contactEmailInput").val()
                    );

                    $("#phoneNo").text(
                        $("#contactPhoneNoInput").val()
                    );

                    contactModal.hide();

                    Swal.fire({
                        title: "Success",
                        text: response.message ??
                            "Contact updated successfully.",
                        icon: "success"
                    });
                },

                error: function (xhr) {
                    console.error(xhr.responseJSON);

                    Swal.fire({
                        title: "Unable to edit contact",
                        text: xhr.responseJSON?.message ??
                            "Unable to update contact.",
                        icon: "error"
                    });
                },

                complete: function () {
                    $btnSaveContact
                        .prop("disabled", false)
                        .text("Save");
                }
            });
        });


        contactModalEl.addEventListener(
            "hidden.bs.modal",
            function () {

                $("#contactInformationForm")[0].reset();

                $btnSaveContact
                    .prop("disabled", false)
                    .text("Save");
            }
        );
    })
</script>
@endpush