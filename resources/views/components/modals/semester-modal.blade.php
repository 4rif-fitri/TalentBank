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

                        <input type="number" class="form-control" id="cgpaInput" name="gpa" placeholder="e.g. 3.85"
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

        const $education = $("#education_ID");

        // Reset
        $("#semesterForm")[0].reset();

        $education
            .prop("disabled", true)
            .html(`
        <option value="" selected disabled>
            Loading...
        </option>
        `);

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


        const semesterModal = bootstrap.Modal.getOrCreateInstance(
            $("#semesterModal")[0]
        );

        semesterModal.show();
    });

    $(document).on("submit", "#semesterForm", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('semester.store') }}",
            type: "POST",

            data: formData,
            processData: false,
            contentType: false,

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {

                console.log(response);

                swalfire(
                    "Success",
                    response.message ?? "Semester added successfully",
                    "success"
                );

                const semesterModal = bootstrap.Modal.getInstance(
                    $("#semesterModal")[0]
                );

                semesterModal?.hide();

                $("#semesterForm")[0].reset();
                refreshSemesterResults()
            },

            error: function (xhr) {

                console.error(xhr);

                const message =
                    xhr.responseJSON?.message ??
                    "Failed to add semester";

                swalfire(
                    "Add Failed",
                    message,
                    "error"
                );
            }
        });

    });
</script>
@endpush