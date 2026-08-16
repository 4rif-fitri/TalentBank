<div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="pdfPreviewModalLabel">
                        Semester Result
                    </h5>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body p-0">
                <div id="pdfPreviewLoading" class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                    <p class="text-muted mt-2 mb-0">
                        Loading PDF...
                    </p>
                </div>
                <iframe id="pdfPreviewFrame" src="" class="d-none" style="
                        width: 100%;
                        height: 75vh;
                        border: 0;">
                </iframe>
            </div>

            <!-- <div class="modal-footer">
                <a href="#" target="_blank" rel="noopener" class="btn btn-outline-primary" id="openPdfNewTab">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                    Open in New Tab
                </a>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div> -->

        </div>
    </div>
</div>


@push('scripts')

<script>

    document.addEventListener(
        "DOMContentLoaded",
        function () {

            const modalEl =
                document.getElementById(
                    "pdfPreviewModal"
                );


            if (!modalEl) {
                return;
            }



            // ==========================================
            // VIEW RESULT
            // ==========================================

            $(document)
                .on(
                    "click",
                    ".btn-view-result",
                    function () {

                        const fileUrl =
                            $(this)
                                .attr("data-file-url");


                        const session =
                            $(this)
                                .attr("data-session");


                        const semester =
                            $(this)
                                .attr("data-semester");


                        if (!fileUrl) {

                            Swal.fire({
                                title: "PDF Not Found",
                                text: "Semester result file could not be found.",
                                icon: "error"
                            });

                            return;

                        }


                        const pdfModal =
                            bootstrap.Modal
                                .getOrCreateInstance(
                                    modalEl
                                );


                        $("#pdfPreviewModalLabel")
                            .text(
                                `Semester ${semester} Result`
                            );

                        // Reset preview
                        $("#pdfPreviewFrame")
                            .attr("src", "")
                            .addClass("d-none");


                        $("#pdfPreviewLoading")
                            .removeClass("d-none");


                        $("#openPdfNewTab")
                            .attr(
                                "href",
                                fileUrl
                            );


                        // Show modal
                        pdfModal.show();


                        // Load PDF
                        $("#pdfPreviewFrame")
                            .attr(
                                "src",
                                fileUrl
                            );

                    }
                );



            // ==========================================
            // PDF LOADED
            // ==========================================

            $("#pdfPreviewFrame")
                .on(
                    "load",
                    function () {

                        const src =
                            $(this)
                                .attr("src");


                        if (!src) {
                            return;
                        }


                        $("#pdfPreviewLoading")
                            .addClass("d-none");


                        $(this)
                            .removeClass("d-none");

                    }
                );



            // ==========================================
            // MODAL CLOSED
            // ==========================================

            modalEl.addEventListener(
                "hidden.bs.modal",
                function () {

                    $("#pdfPreviewFrame")
                        .attr("src", "")
                        .addClass("d-none");


                    $("#pdfPreviewLoading")
                        .removeClass("d-none");


                    $("#openPdfNewTab")
                        .attr(
                            "href",
                            "#"
                        );


                    $("#pdfPreviewModalLabel")
                        .text(
                            "Semester Result"
                        );


                }
            );

        }
    );

</script>

@endpush
