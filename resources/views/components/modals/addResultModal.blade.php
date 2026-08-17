<div class="modal fade" id="addResultModal" data-bs-backdrop="static" tabindex="-1"
    aria-labelledby="addResultModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">

            <form id="addResultForm" enctype="multipart/form-data">

                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="addResultModalLabel">
                            Add Semester Result
                        </h5>

                        <small class="text-muted">
                            Select your programme and semester, then upload your result.
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>


                <div class="modal-body">

                    <div id="resultFormLoading" class="text-center py-5">

                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">
                                Loading...
                            </span>
                        </div>

                        <p class="text-muted mt-2 mb-0">
                            Loading programmes...
                        </p>

                    </div>


                    <div id="resultFormContent" class="d-none">

                        <div class="mb-3">

                            <label for="resultProgramme" class="form-label fw-semibold">

                                Programme
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" id="resultProgramme" required>

                                <option value="" selected disabled>
                                    Select Programme
                                </option>

                            </select>

                        </div>


                        {{-- Semester --}}
                        <div class="mb-3">

                            <label for="resultSemester" class="form-label fw-semibold">

                                Semester
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" id="resultSemester" required disabled>

                                <option value="" selected disabled>
                                    Select Programme First
                                </option>

                            </select>

                        </div>


                        {{-- Selected semester information --}}
                        <div id="semesterInfo" class="alert alert-light border d-none">

                            <div class="row g-2">

                                <div class="col-md-6">
                                    <small class="text-muted d-block">
                                        Session
                                    </small>

                                    <span class="fw-semibold" id="semesterSession">
                                        -
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block">
                                        GPA
                                    </small>

                                    <span class="fw-semibold" id="semesterGpa">
                                        -
                                    </span>
                                </div>

                            </div>

                        </div>


                        {{-- Title --}}
                        <div class="mb-3">

                            <label for="resultTitle" class="form-label fw-semibold">

                                Title
                            </label>

                            <input type="text" class="form-control" id="resultTitle" name="title" maxlength="255"
                                value="Semester Result" placeholder="Semester Result">

                        </div>


                        {{-- Description --}}
                        <div class="mb-3">

                            <label for="resultDescription" class="form-label fw-semibold">

                                Description
                            </label>

                            <textarea class="form-control" id="resultDescription" name="description" rows="3"
                                placeholder="Optional description"></textarea>

                        </div>


                        {{-- PDF --}}
                        <div class="mb-3">

                            <label for="resultFile" class="form-label fw-semibold">

                                Result File
                                <span class="text-danger">*</span>
                            </label>


                            <label for="resultFile" id="resultDropZone"
                                class="border border-2 rounded-3 w-100 text-center p-5" style="
                                    cursor: pointer;
                                    border-style: dashed !important;
                                ">

                                {{-- No file --}}
                                <div id="resultUploadEmpty">

                                    <i class="fa-solid fa-cloud-arrow-up text-primary fs-1 mb-3"></i>

                                    <p class="fw-semibold mb-1">
                                        Click to choose your PDF result
                                    </p>

                                    <small class="text-muted">
                                        PDF only - Maximum 2MB
                                    </small>

                                </div>


                                {{-- File selected --}}
                                <div id="resultUploadSelected" class="d-none">

                                    <i class="fa-solid fa-file-pdf text-danger fs-1 mb-3"></i>

                                    <p class="fw-semibold mb-1" id="selectedResultFileName">
                                    </p>

                                    <small class="text-muted" id="selectedResultFileSize">
                                    </small>

                                </div>

                            </label>


                            <input type="file" class="d-none" id="resultFile" name="result_file"
                                accept="application/pdf" required>

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">

                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary" id="btnUploadResult" disabled>

                        <span id="uploadResultButtonText">
                            <i class="fa-solid fa-upload me-1"></i>
                            Upload Result
                        </span>

                        <span id="uploadResultSpinner" class="d-none">

                            <span class="spinner-border spinner-border-sm me-1">
                            </span>

                            Uploading...
                        </span>

                    </button>

                </div>

            </form>

        </div>
    </div>
</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {


        let programmesData = [];
        let addResultModalEl = document.getElementById('addResultModal');
        if (!addResultModalEl) return;

        let addResultModal = bootstrap.Modal.getOrCreateInstance(addResultModalEl);

        $("#addResult").on("click", function () {
            resetResultForm();
            addResultModal.show();
            loadProgrammesForResult();
        });

        function loadProgrammesForResult() {
            $("#resultFormLoading")
                .removeClass("d-none")
                .html(`<div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">
                            Loading...
                        </span>
                    </div>

                    <p class="text-muted mt-2 mb-0">
                        Loading programmes...
                    </p>`);

            $("#resultFormContent").addClass("d-none");

            $.ajax({
                url: "{{ route('programme.getProgrammesByUserIdJson',['userId' => auth() -> id()])}}",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    console.log("Programme response:", response);

                    programmesData = response.data ?? [];

                    if (programmesData.length === 0) {
                        $("#resultFormLoading")
                            .html(`<div class="alert alert-info mb-0">
                                        No programme found.
                                    </div>`);
                        return;
                    }

                    let options = `<option value="" selected disabled>
                                        Select Programme
                                    </option>`;

                    programmesData.forEach(programme => {
                        options += `
                            <option value="${programme.id}">
                                ${programme.programme_name ?? "-"}
                                (${programme.programme_level ?? "-"})
                            </option>`;
                    });

                    $("#resultProgramme").html(options);
                    $("#resultFormLoading").addClass("d-none");
                    $("#resultFormContent").removeClass("d-none");
                },
                error: function (xhr) {
                    console.error(xhr);
                    $("#resultFormLoading")
                        .html(`
                            <div class="alert alert-danger mb-0">
                                Unable to load programmes.
                            </div>`);
                }
            });
        }

        $("#resultProgramme").on("change", function () {
            let programmeId = Number($(this).val());
            $("#semesterInfo").addClass("d-none");
            $("#btnUploadResult").prop("disabled", true);

            if (!programmeId) {
                $("#resultSemester")
                    .prop("disabled", true)
                    .html(`
                            <option value="">
                                Select Programme First
                            </option>`);
                return;
            }

            let programme = programmesData.find(item => Number(item.id) === programmeId);

            if (!programme) {
                $("#resultSemester")
                    .prop("disabled", true)
                    .html(` <option value="">
                                Programme Not Found
                            </option>`);
                return;
            }

            let semesters = [];
            (programme.education ?? []).forEach(education => {
                (education.semesters ?? []).forEach((semester, index) => {
                    semesters.push({
                        ...semester,
                        educationId: education.id,
                        cgpa: education.cgpa,
                        enrollmentStatus: education.enrollment_status,
                        semesterNumber: index + 1
                    });

                });
            });

            console.log("Selected programme:", programme);
            console.log("Available semesters:", semesters);

            if (semesters.length === 0) {
                $("#resultSemester")
                    .prop("disabled", true)
                    .html(` <option value="">
                                No Semester Available
                            </option>`);
                return;
            }

            let options = ` <option value="" selected disabled>
                                Select Semester
                            </option>`;

            semesters.forEach(semester => {
                let session = semester.session ?? "-";
                let gpa = semester.gpa ?? "-";

                options += `
                            <option
                                value="${semester.id}"
                                data-session="${session}"
                                data-gpa="${gpa}"
                                data-education-id="${semester.educationId}">

                                Semester ${semester.semesterNumber}
                                - ${session}
                            </option>`;
            });

            $("#resultSemester").html(options)
                .prop("disabled", false);
            checkResultForm();
        });

        $("#resultSemester").on("change", function () {
            let selectedOption = $(this).find(":selected");
            let session = selectedOption.data("session");
            let gpa = selectedOption.data("gpa");
            $("#semesterSession").text(session || "-");
            $("#semesterGpa").text(gpa || "-");
            $("#semesterInfo").removeClass("d-none");
            checkResultForm();
        });

        $("#resultFile").on("change", function () {
            let file = this.files[0];
            if (!file) {
                resetFilePreview();
                checkResultForm();
                return;
            }

            if (file.type !== "application/pdf") {
                Swal.fire({
                    title: "Invalid File",
                    text: "Please upload PDF only.",
                    icon: "error"
                });
                $(this).val("");
                resetFilePreview();
                checkResultForm();
                return;
            }

            let maxSize = 2 * 1024 * 1024;

            if (file.size > maxSize) {
                Swal.fire({
                    title: "File Too Large",
                    text: "Maximum file size is 2MB.",
                    icon: "error"
                });
                $(this).val("");
                resetFilePreview();
                checkResultForm();
                return;
            }


            $("#selectedResultFileName").text(file.name);
            $("#selectedResultFileSize").text(formatFileSize(file.size));
            $("#resultUploadEmpty").addClass("d-none");
            $("#resultUploadSelected").removeClass("d-none");
            checkResultForm();
        });

        function checkResultForm() {
            let semesterId = $("#resultSemester").val();
            let fileInput = $("#resultFile")[0];
            let file = fileInput?.files?.[0];

            $("#btnUploadResult").prop("disabled", !(semesterId && file));
        }

        $("#addResultForm").on("submit", function (event) {
            event.preventDefault();
            let semesterId = $("#resultSemester").val();
            let file = $("#resultFile")[0]?.files?.[0];

            if (!semesterId) {
                Swal.fire({
                    title: "Semester Required",
                    text: "Please select a semester.",
                    icon: "warning"
                });
                return;
            }

            if (!file) {
                Swal.fire({
                    title: "File Required",
                    text: "Please select a PDF result.",
                    icon: "warning"
                });
                return;
            }

            let formData = new FormData(this);

            let uploadUrl = "{{ route('semester.uploadResults',['id' => '__SEMESTER_ID__']) }}".replace("__SEMESTER_ID__", semesterId);
            setUploading(true);

            $.ajax({
                url: uploadUrl,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {
                    console.log("Upload result:", response);

                    addResultModal.hide();
                    resetResultForm();
                    Swal.fire({
                        title: "Success",
                        text: response.message ?? "File uploaded successfully.",
                        icon: "success"
                    });
                    // Kalau nanti ada function
                    // untuk refresh result list:
                    //
                    // loadResultSemester();
                },

                error: function (xhr) {
                    console.error(xhr);

                    let message =
                        xhr.responseJSON?.message ??
                        "Something went wrong.";
                    if (xhr.responseJSON?.errors) {

                        let errors = xhr.responseJSON.errors;
                        let firstKey = Object.keys(errors)[0];
                        if (firstKey && errors[firstKey]?.length) {
                            message = errors[firstKey][0];
                        }
                    }

                    Swal.fire({
                        title: "Upload Failed",
                        text: message,
                        icon: "error"
                    });
                },


                complete: function () {
                    setUploading(false);
                }
            });
        });

        function resetResultForm() {
            let form = $("#addResultForm")[0];
            if (form) form.reset()

            $("#resultProgramme")
                .html(` <option value="" selected disabled>
                            Select Programme
                        </option>`);

            $("#resultSemester")
                .prop("disabled", true)
                .html(` <option value="" selected disabled>
                            Select Programme First
                        </option>`);

            $("#semesterInfo").addClass("d-none");
            $("#semesterSession").text("-");
            $("#semesterGpa").text("-");

            resetFilePreview();

            $("#btnUploadResult").prop("disabled", true);
            setUploading(false);
        }

        function resetFilePreview() {
            $("#resultUploadEmpty").removeClass("d-none");
            $("#resultUploadSelected").addClass("d-none");
            $("#selectedResultFileName").text("");
            $("#selectedResultFileSize").text("");
        }

        function setUploading(uploading) {
            $("#btnUploadResult").prop("disabled", uploading);

            if (uploading) {
                $("#uploadResultButtonText").addClass("d-none");
                $("#uploadResultSpinner").removeClass("d-none");

            } else {
                $("#uploadResultButtonText").removeClass("d-none");
                $("#uploadResultSpinner").addClass("d-none");
                checkResultForm();
            }
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + " B";

            if (bytes < 1024 * 1024) {
                return (bytes / 1024).toFixed(2) + " KB";
            }

            return (bytes / 1024 / 1024).toFixed(2) + " MB";
        }

        addResultModalEl.addEventListener("hidden.bs.modal", function () {
            resetResultForm();
        });
    });
</script>
@endpush
