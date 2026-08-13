<div class="modal fade" id="addResultModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">

            <form id="addResultForm" action="{{ route('semester.uploadResults', ['id' => '__ID__']) }}" method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold">Add Semester Result</h5>
                        <small class="text-muted">
                            Select your programme and semester, then upload your result.
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    {{-- Loading --}}
                    <div id="resultFormLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
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

                                <option value="">
                                    Select Programme
                                </option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label for="resultSemester" class="form-label fw-semibold">
                                Semester
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" id="resultSemester" required disabled>

                                <option value="">
                                    Select Programme First
                                </option>

                            </select>

                            <div class="form-text">
                                The semester ID selected here will be used when uploading the result.
                            </div>

                        </div>


                        {{-- Semester Information --}}
                        <!-- <div id="semesterInfo" class="border rounded-3 p-3 mb-4 d-none">

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Session
                                    </small>

                                    <strong id="semesterInfoSession">
                                        -
                                    </strong>

                                </div>

                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        GPA
                                    </small>

                                    <strong id="semesterInfoGpa">
                                        -
                                    </strong>

                                </div>

                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Semester ID
                                    </small>

                                    <strong id="semesterInfoId">
                                        -
                                    </strong>

                                </div>

                            </div>

                        </div> -->


                        {{-- File Upload --}}
                        <div class="mb-3">

                            <label for="resultFile" class="form-label fw-semibold">
                                Result File
                                <span class="text-danger">*</span>
                            </label>


                            <label for="resultFile" id="resultDropZone"
                                class="border border-2 border-dashed rounded-3
                                       w-100 text-center p-5"
                                style="cursor:pointer;">

                                <div id="resultUploadEmpty">

                                    <i
                                        class="fa-solid fa-cloud-arrow-up
                                              text-primary fs-1 mb-3">
                                    </i>

                                    <p class="fw-semibold mb-1">
                                        Click to choose your PDF result
                                    </p>

                                    <small class="text-muted">
                                        PDF only • Maximum 10MB
                                    </small>

                                </div>


                                <div id="resultUploadSelected" class="d-none">

                                    <i
                                        class="fa-solid fa-file-pdf
                                              text-danger fs-1 mb-3">
                                    </i>

                                    <p class="fw-semibold mb-1" id="selectedResultFileName">
                                    </p>

                                    <small class="text-muted" id="selectedResultFileSize">
                                    </small>

                                </div>

                            </label>
                            <input type="file" class="d-none" id="resultFile" name="result_file"
                                accept="application/pdf" required>
                            <input type="text" class="d-none" id="resultFile" name="title" value="asd">
                            <input type="text" class="d-none" id="resultFile" name="description" value="asd">
                        </div>

                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnUploadResult" disabled>
                        Upload Result
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        let programmesData = [];
        let addResultModalEl = document.getElementById("addResultModal");
        let addResultModal = bootstrap.Modal.getOrCreateInstance(addResultModalEl);

        let resultLoaded = false;

        function loadProgrammesForResult() {

            $("#resultFormLoading").removeClass("d-none");
            $("#resultFormContent").addClass("d-none");

            $.ajax({
                url: "{{ route('programme.getProgrammesByUserIdJson', ['userId' => auth()->id()]) }}",
                type: "GET",
                dataType: "json",

                success: function(response) {

                    console.log("Programme response:", response);

                    programmesData = response.data ?? [];

                    let options = `
                <option value="">
                    Select Programme
                </option>
            `;

                    programmesData.forEach(function(programme) {

                        options += `
                    <option value="${programme.id}">
                        ${programme.programme_name}
                        (${programme.programme_level})
                    </option>
                `;

                    });

                    $("#resultProgramme").html(options);

                    $("#resultFormLoading").addClass("d-none");
                    $("#resultFormContent").removeClass("d-none");
                },

                error: function(xhr) {

                    console.error(xhr);

                    $("#resultFormLoading").html(`
                <div class="alert alert-danger mb-0">
                    Unable to load programmes.
                </div>
            `);

                }
            });
        }
        $("#resultProgramme").on("change", function() {

            const programmeId = Number($(this).val());

            $("#semesterInfo").addClass("d-none");
            $("#btnUploadResult").prop("disabled", true);

            if (!programmeId) {

                $("#programmeInfo").addClass("d-none");

                $("#resultSemester")
                    .prop("disabled", true)
                    .html(`
                <option value="">Select Programme First</option>
            `);

                return;
            }


            const programme = programmesData.find(function(item) {
                return Number(item.id) === programmeId;
            });


            if (!programme) {
                return;
            }


            // Programme info
            $("#programmeInfoName").text(programme.programme_name ?? "-");
            $("#programmeInfoLevel").text(programme.programme_level ?? "-");
            $("#programmeInfoCode").text(programme.programme_code ?? "-");

            $("#programmeInfo").removeClass("d-none");


            // Collect semester
            let semesters = [];

            (programme.enrollments ?? []).forEach(function(enrollment) {

                (enrollment.semesters ?? []).forEach(function(semester, index) {

                    semesters.push({
                        ...semester,
                        semesterNumber: index + 1
                    });

                });

            });


            if (semesters.length === 0) {

                $("#resultSemester")
                    .prop("disabled", true)
                    .html(`
                <option value="">No Semester Available</option>
            `);

                return;
            }


            let options = `
            <option value="">Select Semester</option>`;


            semesters.forEach(function(semester) {

                options += `
            <option
                value="${semester.id}"
                data-session="${semester.session ?? ""}"
                data-gpa="${semester.gpa ?? ""}">

                Semester ${semester.semesterNumber}
                - ${semester.session ?? ""}
            </option>
        `;

            });


            $("#resultSemester")
                .html(options)
                .prop("disabled", false);

        });


        $("#resultSemester").on("change", function() {

            const semesterId = $(this).val();

            const form = $("#addResultForm");

            const baseAction = "{{ route('semester.uploadResults', ['id' => '__ID__']) }}";

            if (!semesterId) {
                form.attr("action", baseAction);
                return;
            }

            const newAction = baseAction.replace("__ID__", semesterId);

            form.attr("action", newAction);

            console.log("Form action:", newAction);
        });
        $("#resultFile").on("change", function() {

            const file = this.files[0];


            if (!file) {

                $("#resultUploadEmpty").removeClass("d-none");
                $("#resultUploadSelected").addClass("d-none");

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

                return;
            }


            const maxSize = 10 * 1024 * 1024;


            if (file.size > maxSize) {

                Swal.fire({
                    title: "File Too Large",
                    text: "Maximum file size is 10MB.",
                    icon: "error"
                });

                $(this).val("");

                return;
            }


            $("#selectedResultFileName").text(file.name);

            $("#selectedResultFileSize").text(
                (file.size / 1024 / 1024).toFixed(2) + " MB"
            );


            $("#resultUploadEmpty").addClass("d-none");
            $("#resultUploadSelected").removeClass("d-none");


            checkResultForm();

        });

        function checkResultForm() {

            const semesterId = $("#resultSemester").val();

            const file = $("#resultFile")[0].files[0];


            $("#btnUploadResult").prop(
                "disabled",
                !(semesterId && file)
            );
        }



        function loadSemesterResults() {
            $("#semesterResultList").html(`
					<div class="text-center py-4">
						<div class="spinner-border text-primary"></div>
						<p class="mt-2">Loading results...</p>
					</div>
				`);

            $.ajax({
                url: "../data/semester-results.json",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    let html = "";
                    response.forEach(function(result) {
                        html += `
								<article class="semester-result-item py-3">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<p class="fw-bold mb-1">
												${result.semester}
											</p>
											<p class="text-muted mb-0">
												Session ${result.session}
											</p>
										</div>
										<a href="${result.file}"
											target="_blank"
											class="btn btn-outline-primary">
											<i class="fa-regular fa-file-pdf me-1"></i>
											View Result
										</a>
									</div>
								</article>
								<hr>`;
                    });

                    $("#semesterResultList").html(html);

                    resultLoaded = true;
                },

                error: function(xhr, status, error) {

                    console.error(error);

                    $("#semesterResultList").html(`
							<div class="alert alert-danger">
							Failed to load semester results.
							</div>
						`);
                }
            });
        }

        function resetResultForm() {

            const form = $("#addResultForm")[0];

            if (form) form.reset();

            $("#resultProgramme").html(`<option value="">Select Programme</option>`);

            $("#resultSemester").prop("disabled", true).html(`<option value="">Select Programme First</option>`);

            $("#programmeInfo").addClass("d-none");
            $("#semesterInfo").addClass("d-none");

            $("#resultUploadEmpty").removeClass("d-none");
            $("#resultUploadSelected").addClass("d-none");

            $("#btnUploadResult").prop("disabled", true);
        }

        $("#resultProgramme").on("change", function() {

            const programmeId = Number($(this).val());

            $("#semesterInfo").addClass("d-none");
            $("#btnUploadResult").prop("disabled", true);

            if (!programmeId) {

                $("#programmeInfo").addClass("d-none");

                $("#resultSemester")
                    .prop("disabled", true)
                    .html(`<option value="">Select Programme First</option>`);
                return;
            }


            const programme = programmesData.find(function(item) {
                return Number(item.id) === programmeId;
            });


            if (!programme) return

            let semesters = [];

            (programme.enrollments ?? []).forEach(function(enrollment) {

                (enrollment.semesters ?? []).forEach(function(semester, index) {

                    semesters.push({
                        ...semester,
                        semesterNumber: index + 1
                    });

                });

            });


            if (semesters.length === 0) {

                $("#resultSemester")
                    .prop("disabled", true)
                    .html(`
                <option value="">No Semester Available</option>
            `);

                return;
            }


            let options = `<option value="">Select Semester</option>`;

            semesters.forEach(function(semester) {
                options += `
                    <option
                        value="${semester.id}"
                        data-session="${semester.session ?? ""}"
                        data-gpa="${semester.gpa ?? ""}">
                        Semester ${semester.semesterNumber} -
                        ${semester.session ?? ""}
                    </option>`;
            });


            $("#resultSemester").html(options).prop("disabled", false);
        });

        $("#addResult").on("click", function() {
            resetResultForm();
            addResultModal.show();
            loadProgrammesForResult();
        });
    </script>
@endpush
