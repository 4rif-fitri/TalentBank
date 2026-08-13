<div class="modal fade" id="editAboutModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">
            <form id="aboutForm" action="/profile/about" method="POST">
                <!-- @csrf -->
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title">Edit About</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- LOADING -->
                    <div id="aboutLoading" class="text-center py-4 d-none">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span class="ms-2">Loading...</span>
                    </div>

                    <!-- FORM -->
                    <div id="aboutFields">
                        <div class="mb-3">
                            <label for="aboutInput" class="form-label">About</label>
                            <textarea class="form-control" name="about" id="aboutInput" rows="6" maxlength="1000" required></textarea>
                            <div class="text-end text-muted small mt-1">
                                <span id="aboutCount">0</span>/1000
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveAbout">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let aboutModalEl = document.getElementById("editAboutModal");
        let aboutModal = bootstrap.Modal.getOrCreateInstance(aboutModalEl);

        let $aboutLoading = $("#aboutLoading");
        let $aboutFields = $("#aboutFields");
        let $btnSaveAbout = $("#btnSaveAbout");

        function showAboutLoading() {
            $aboutLoading.removeClass("d-none");
            $aboutFields.addClass("d-none");
            $btnSaveAbout.prop("disabled", true);
        }

        function hideAboutLoading() {
            $aboutLoading.addClass("d-none");
            $aboutFields.removeClass("d-none");
            $btnSaveAbout.prop("disabled", false);
        }

        $("#btnEditAbout").on("click", function() {
            showAboutLoading();

            $.ajax({
                url: "../data/about.json",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    /*
                    |----------------------------------------------
                    | Expected:
                    |
                    | {
                    |     "about": "Motivated..."
                    | }
                    |----------------------------------------------
                    */
                    $("#aboutInput").val(response.about ?? "");
                    $("#aboutCount").text((response.about ?? "").length);
                    hideAboutLoading();
                    aboutModal.show();
                },

                error: function(xhr) {
                    console.error(xhr);
                    hideAboutLoading();
                    Swal.fire({
                        title: "Unable to load about",
                        text: "About information could not be loaded.",
                        icon: "error"
                    });
                }
            });
        });

        $("#aboutForm").on("submit", function() {
            $btnSaveAbout.prop("disabled", true).text("Saving...");
        });

        $("#aboutInput").on("input", function() {
            $("#aboutCount").text(this.value.length);
        });

        aboutModalEl.addEventListener("hidden.bs.modal", function() {
            $("#aboutForm")[0].reset();
            $("#aboutCount").text("0");

            $btnSaveAbout.prop("disabled", false).text("Save");
        });
    </script>
@endpush
