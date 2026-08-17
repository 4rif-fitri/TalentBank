<div class="modal fade" id="educationModal" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content h-75">

            <div class="modal-header">
                <h1 class="h3 modal-title fs-5">Add Education</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="educationId" name="education_id">
                <input type="hidden" id="enrollmentStatus" name="enrollment_status">

                <!-- Institution -->
                <div class="mb-3">
                    <label for="educationInstitution" class="form-label">
                        Institution
                    </label>

                    <select class="form-select" id="educationInstitution" required>
                        <option value="" selected disabled>
                            Select Institution
                        </option>
                    </select>
                </div>

                <!-- Programme -->
                <div class="mb-3">
                    <label for="educationProgramme" class="form-label">
                        Programme Name
                    </label>

                    <select class="form-select" id="educationProgramme" required disabled>
                        <option value="" selected disabled>
                            Select Programme
                        </option>
                    </select>
                </div>

                <!-- Field of Study -->
                <div class="mb-3">
                    <label for="fieldOfStudy" class="form-label fw-bold">Field of Study</label>
                    <select class="form-select" id="fieldOfStudy" name="field_of_study_id" required>
                        <option value="" disabled selected>Select Field of Study</option>
                    </select>
                </div>

                <!-- Qualification -->
                <div class="mb-3">
                    <label for="qualification" class="form-label fw-bold">Qualification</label>
                    <select class="form-select" id="qualification" name="qualification_id" required>
                        <option value="" disabled selected>Select Qualification</option>
                    </select>
                </div>

                <!-- CGPA -->
                <div class="mb-3">
                    <label for="cgpaInput" class="form-label">
                        CGPA
                    </label>

                    <input type="number" class="form-control" id="cgpaInput" name="cgpa" placeholder="e.g. 3.85"
                        step="0.01" min="0" max="4.00">
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">
                        Description
                    </label>

                    <textarea class="form-control" id="descriptionInput" name="description" rows="4"
                        placeholder="Describe your education"></textarea>
                </div>

                <!-- Start Date -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Start Date
                    </label>
                    <input type="date" class="form-control" id="dateStart">
                </div>

                <!-- End Date -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        End Date
                    </label>
                    <input type="date" class="form-control" id="dateEnd">
                </div>

                <!-- Skills -->
                <!-- <div class="mb-3">
                    <label class="form-label">Skill</label>
                    <div>
                        <button id="addSkill" type="button" class="btn btn-outline-primary btn-sm">+ Add
                            Skill</button>
                        <div class="mt-2" id="skillContainer">

                        </div>
                    </div>
                </div> -->

                <!-- Media -->
                <!-- <div class="mb-3">
                    <label class="form-label fw-semibold">Media</label>
                    <p class="text-muted small mb-2">
                        Add media like images, documents or presentations.
                    </p>

                    <input type="file" id="mediaFileInput" accept="image/*" hidden>

                    <button type="button" id="addMedia" class="btn btn-outline-primary btn-sm">
                        + Add Media
                    </button>

                    <div id="mediaContainer" class="d-flex gap-2 flex-wrap mt-3"></div>
                </div> -->

            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-danger" id="btnDeleteEducation">
                    Delete
                </button>
                <button type="button" class="btn btn-primary" id="btnSaveEducation">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

    let listOfFieldOfStudies = [];
    let listOfQualifications = [];
    let listOfOrganizations = [];

    function swalFireError() {

    }

    // Get
    function getAllOrganizations() {

        return $.ajax({
            url: "{{ route('organization.getAllOrganizationsJson') }}",
            type: "GET",
            dataType: "json",

            success: function ({ data }) {

                listOfOrganizations = data;

                let $institution = $("#educationInstitution");

                $institution.html(`
                <option value="" selected disabled>
                    Select Institution
                </option>
            `);

                data.forEach(organization => {

                    $institution.append(`
                    <option value="${organization.id}">
                        ${organization.company_name}
                    </option>
                `);
                });
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
            }
        });
    }

    function getAllFieldOfStudies() {
        $.ajax({
            url: "{{ route('education.getAllFieldOfStudies') }}",
            type: "GET",
            dataType: "json",

            success: function ({ data }) {
                listOfFieldOfStudies = data;

                $("#fieldOfStudy").html(`
                <option value="" disabled selected>
                    Select Field of Study
                </option>
            `);
                data.forEach(item => {
                    $("#fieldOfStudy").append(`
                    <option value="${item.id}">
                        ${item.name}
                    </option>
                `);
                });
            },
            error: function (xhr) {
                console.error(xhr);
            }
        });
    }

    function getAllQualifications() {
        $.ajax({
            url: "{{ route('education.getAllQualifications') }}",
            type: "GET",
            dataType: "json",

            success: function ({ data }) {
                listOfQualifications = data;

                $("#qualification").html(`
                    <option value="" disabled selected>
                        Select Qualification
                    </option>
                `);

                data.forEach(item => {
                    $("#qualification").append(`
                        <option value="${item.id}">
                            ${item.name}
                        </option>
                    `);
                });
            },

            error: function (xhr) {
                console.error(xhr);
            }
        });
    }

    function getEducationDetail(eduId) {

        let modalEl = document.getElementById("educationModal");
        let modal = bootstrap.Modal.getOrCreateInstance(modalEl);

        let url = "{{ route('education.getEducationById', ['id' => '__ID__']) }}";

        url = url.replace("__ID__", eduId);

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",

            success: function ({ data }) {
                console.log(data);

                $("#educationId").val(data.id);
                $("#qualification").val(data.qualification_id);
                $("#fieldOfStudy").val(data.field_of_study_id);
                $("#cgpaInput").val(data.cgpa);
                $("#descriptionInput").val(data.description);
                $("#dateStart").val(data.start_date);
                $("#dateEnd").val(data.end_date);
                $("#enrollmentStatus").val(data.enrollment_status ?? "Active");
                if (data.start_date) {
                    $("#dateEnd").attr("min", data.start_date);
                }

                let organizationId = data.programme?.organization?.id;
                if (organizationId) {
                    $("#educationInstitution").val(organizationId);
                    getProgrammesByOrganizationId(organizationId, data.programme_id);
                }
                $("#educationModal .modal-title").text("Edit Education");
                $("#btnSaveEducation").text("Update");
                $("#btnDeleteEducation").show();

                modal.show();
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
            }
        });
    }

    function getProgrammesByOrganizationId(organizationId, selectedProgrammeId = null) {
        let $programme = $("#educationProgramme");

        $programme
            .prop("disabled", true)
            .html(`
            <option value="">
                Loading programmes...
            </option>
        `);

        let url = "{{ route('programme.getProgrammesByOrgId', ['orgId' => '__ID__']) }}";
        url = url.replace("__ID__", organizationId);

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",

            success: function ({ data }) {

                $programme.html(`
                <option value="" selected disabled>
                    Select Programme
                </option>
            `);

                if (!data || data.length === 0) {
                    $programme.html(`
                    <option value="">
                        No programmes available
                    </option>
                `);

                    return;
                }

                data.forEach(programme => {
                    $programme.append(`
                    <option value="${programme.id}">
                        ${programme.programme_name}
                    </option>
                `);
                });

                $programme.prop("disabled", false);

                if (selectedProgrammeId) {
                    $programme.val(selectedProgrammeId);
                }
            },

            error: function (xhr) {

                console.error(xhr.responseJSON);

                $programme
                    .prop("disabled", true)
                    .html(`
                    <option value="">
                        Failed to load programmes
                    </option>
                `);
            }
        });
    }
    // Get

    // Validate
    function validateEducationDates() {

        let startDate = $("#dateStart").val();
        let endDate = $("#dateEnd").val();

        $("#dateStart, #dateEnd").removeClass("is-invalid");

        if (!startDate) {
            $("#dateStart").addClass("is-invalid");
            return false;
        }

        if (endDate && endDate < startDate) {
            $("#dateEnd").addClass("is-invalid");
            return false;
        }

        return true;
    }
    // Validate

    // Update
    function updateEducation(url, formData) {

        $.ajax({
            url: url,
            type: "PUT",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {
                bootstrap.Modal.getInstance(document.getElementById("educationModal"))?.hide();
                swalfire("Success", response.message ?? "Education updated successfully", "success")
                $(document).trigger("education:updated");
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
                swalfire("Update Failed", xhr.responseJSON?.message ?? "Something went wrong", "error")
            }
        });
    }
    // Update

    // Create
    function createEducation(formData) {

        $.ajax({
            url: "{{ route('education.store') }}",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {
                bootstrap.Modal.getInstance(document.getElementById("educationModal"))?.hide();
                swalfire("Success", response.message ?? "Education created successfully.", "success")
                $(document).trigger("education:updated");
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
                swalfire("Create Failed", xhr.responseJSON?.message ?? "Something went wrong", "error")
            }
        });
    }
    // Create

    // trigger
    $(document).on("change", "#dateStart", function () {

        let startDate = $(this).val();

        if (!startDate) {
            $("#dateEnd").removeAttr("min");
            return;
        }

        $("#dateEnd").attr("min", startDate);

        let endDate = $("#dateEnd").val();

        if (endDate && endDate < startDate) {
            $("#dateEnd").val("");
        }
    });

    $(document).on("click", ".btn-edit-education", function () {
        let eduId = $(this).data("id");
        console.log("Education ID:", eduId);
        getEducationDetail(eduId);
    });

    $(document).on("click", "#btnDeleteEducation", function () {

        let educationId = $("#educationId").val();

        if (!educationId) {
            return;
        }

        Swal.fire({
            title: "Delete Education?",
            text: "This education record will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#dc3545"
        }).then(result => {

            if (!result.isConfirmed) return;

            let url = "{{ route('education.delete', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", educationId);

            $.ajax({
                url: url,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },

                success: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById("educationModal"))?.hide();
                    swalfire("Success", response.message ?? "Education deleted successfully.", "success")
                    $(document).trigger("education:updated");
                },

                error: function (xhr) {
                    console.error(xhr.responseJSON);
                    swalfire("Delete Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error")
                }
            });
        });
    });

    $(document).on("click", "#addEducation", function () {
        let modalEl = document.getElementById("educationModal");
        let modal = bootstrap.Modal.getOrCreateInstance(modalEl);

        $("#educationId").val("");
        $("#educationInstitution").val("");
        $("#educationProgramme")
            .prop("disabled", true)
            .html(`<option value="" selected disabled>Select Programme</option>`);
        $("#qualification").val("");
        $("#fieldOfStudy").val("");
        $("#cgpaInput").val("");
        $("#descriptionInput").val("");
        $("#dateStart").val("");
        $("#dateEnd").val("").removeAttr("min");
        $("#enrollmentStatus").val("Active");
        $("#educationModal .modal-title").text("Add Education");
        $("#btnDeleteEducation").hide();
        $("#btnSaveEducation").text("Save");

        modal.show();
    });

    $(document).on("click", "#btnSaveEducation", function () {

        if (!$("#educationInstitution").val()) return;

        if (!$("#educationProgramme").val()) {
            swalfire("Validation Error", "Please select a programme", "error")
            return;
        }

        if (!$("#fieldOfStudy").val()) {
            swalfire("Validation Error", "Please select a field of study", "error")
            return;
        }

        if (!$("#qualification").val()) {
            swalfire("Validation Error", "Please select a qualification", "error")
            return;
        }

        if (!validateEducationDates()) return;


        let educationId = $("#educationId").val();

        let formData = {
            programme_id: $("#educationProgramme").val(),
            field_of_study_id: $("#fieldOfStudy").val(),
            qualification_id: $("#qualification").val(),
            cgpa: $("#cgpaInput").val() || null,
            description: $("#descriptionInput").val(),
            start_date: $("#dateStart").val(),
            end_date: $("#dateEnd").val() || null,
            enrollment_status: $("#enrollmentStatus").val() || "Active"
        };

        if (educationId) {
            let url = "{{ route('education.update', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", educationId);
            updateEducation(url, formData);
            return;
        }

        createEducation(formData);
    });

    $(document).on("change", "#educationInstitution", function () {

        let organizationId = $(this).val();
        if (!organizationId) return;
        getProgrammesByOrganizationId(organizationId);
    });

    // init Get Data
    $(document).ready(function () {
        getAllOrganizations();
        getAllFieldOfStudies();
        getAllQualifications();
    });
    // trigger

</script>
@endpush