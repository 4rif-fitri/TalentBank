<div class="modal fade" id="semesterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Semester</h1>
            </div>

            <form id="semesterForm">

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="education_ID" class="form-label">
                            Education
                        </label>

                        <select class="form-select" id="education_ID" name="education_id" required disabled>
                            <option value="" selected disabled>
                                Select Education
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cgpaInput" class="form-label">
                            GPA
                        </label>

                        <input type="number" class="form-control gpa" id="cgpaInput" name="gpa" placeholder="e.g. 3.85"
                            step="0.01" min="0" max="4.00" required>
                    </div>

                    <div class="mb-3">
                        <label for="sessionInput" class="form-label">
                            Session
                        </label>

                        <input type="text" class="form-control" id="sessionInput" name="session"
                            placeholder="2023/2024 - 1" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Add
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on("click", "#addSemester", function () {

        let $form = $("#semesterForm");
        let $education = $("#education_ID");

        $form[0].reset();

        $form
            .data("mode", "add")
            .removeData("semester-id")
            .removeData("education-id");

        $("#semesterModal .modal-title").text("Add Semester");
        $form.find("button[type='submit']").text("Add");

        let url = "{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",

            success: function ({ data }) {

                console.table(data);

                $education.empty();

                if (!data || data.length === 0) {

                    $education.append(`
                        <option value="" selected disabled>
                            No Education Available
                        </option>
                    `);

                    return;
                }

                $education.append(`
        <option value="" selected disabled>
            Select Education
        </option>
        `);

                data.forEach(dt => {

                    $education.append(`
                        <option value="${dt.id}">
                            ${dt.programme?.programme_name ?? "Unknown Programme"}
                        </option>
                    `);

                });

                $education.prop("disabled", false);
            },

            error: function (xhr) {
                console.error(xhr);

                $education.html(`
        <option value="" selected disabled>
            Failed to load education
        </option>
                `);
            }
        });


        let semesterModal = bootstrap.Modal .getOrCreateInstance(
            $("#semesterModal")[0]
        );

        semesterModal.show();
    });

    $(document).on("submit", "#semesterForm", function (e) {
        e.preventDefault();

        let $form = $(this);

        let mode = $form.data("mode") ?? "add";
        let semesterId = $form.data("semester-id");
        let educationId = $form.data("education-id");

        let formData = new FormData(this);

        let url = "{{ route('semester.store') }}";

        if (mode === "edit") {

            // sebab education select disabled,
            // FormData tak akan ambil education_id
            formData.set("education_id", educationId);

            url = "{{ route('semester.update', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", semesterId);

            formData.append("_method", "PUT");
        }

        $.ajax({
            url: url,
            type: "POST",

            data: formData,
            processData: false,
            contentType: false,

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {

                swalfire(
                    "Success",
                    response.message ??
                    (mode === "edit"
                        ? "Semester updated successfully"
                        : "Semester added successfully"),
                    "success"
                );

                let semesterModal = bootstrap.Modal .getInstance(
                    $("#semesterModal")[0]
                );

                semesterModal?.hide();

                $form[0].reset();

                refreshSemesterResults();
            },

            error: function (xhr) {

                console.error(xhr);

                let message =
                    xhr.responseJSON?.message ??
                    (mode === "edit"
                        ? "Failed to update semester"
                        : "Failed to add semester");

                swalfire(
                    mode === "edit" ? "Update Failed" : "Add Failed",
                    message,
                    "error"
                );
            }
        });
    });
    $(document).on("click", ".btnEditSemester", function () {

        let item = $(this).closest(".semester-result-item");

        let semesterId = $(this).data("id");
        let educationId = $(this).data("education-id");
        let programmeName = $(this).data("programme-name");

        let session = item.find(".session").text().trim();
        let gpa = item.find(".gpa").text().trim();

        let $education = $("#education_ID");

        $education
            .html(`
            <option value="${educationId}" selected>
                ${programmeName}
            </option>
        `)
            .prop("disabled", true);

        $("#semesterModal #sessionInput").val(session);
        $("#semesterModal #cgpaInput").val(gpa);

        // edit data
        $("#semesterForm")
            .data("mode", "edit")
            .data("semester-id", semesterId)
            .data("education-id", educationId);

        $("#semesterModal .modal-title").text("Edit Semester");
        $("#semesterForm button[type='submit']").text("Update");

        let semesterModal = bootstrap.Modal .getOrCreateInstance(
            $("#semesterModal")[0]
        );

        semesterModal.show();
    });


</script>
@endpush.
