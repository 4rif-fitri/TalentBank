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

                    <div class="row g-3">

                        <div class="col-6">
                            <label for="startMonth" class="form-label">
                                Month
                            </label>

                            <select class="form-select" id="startMonth" name="start_month">
                                <option selected value="">Select Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="startYear" class="form-label">Year</label>
                            <select class="form-select" id="startYear" name="start_year" required>
                                <option selected disabled value="">Select Year</option>
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label class="form-label fw-bold">End Date</label>
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="endMonth" class="form-label">Month</label>
                            <select class="form-select" id="endMonth" name="end_month">
                                <option selected value="">Select Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="endYear" class="form-label">Year</label>
                            <select class="form-select" id="endYear" name="end_year" required>
                                <option selected disabled value="">Select Year</option>
                                <option value="2030">2030</option>
                                <option value="2029">2029</option>
                                <option value="2028">2028</option>
                                <option value="2027">2027</option>
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Skills -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label fw-bold">Skill</label>
                        <button id="addSkill" type="button" class="btn btn-outline-primary btn-sm">+ Add
                            Skill</button>
                    </div>
                    <div class="mt-2" id="skillContainer">

                    </div>
                </div>

                <!-- Media -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label fw-bold">Media</label>

                        <input type="file" id="mediaFileInput" accept="image/*" multiple hidden>

                        <label for="mediaFileInput" id="addMedia" class="btn btn-outline-primary btn-sm">
                            + Add Media
                        </label>
                    </div>

                    <div id="mediaContainer" class="d-flex gap-2 flex-wrap mt-3">

                    </div>
                </div>

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

    let listOfOrganizations = [];
    let listOfSkills = [];

    let existingEducationMedia = [];
    let deletedEducationMediaIds = [];
    let newEducationMedia = [];

    function renderEducationMedia() {

        let $container = $("#mediaContainer");
        $container.empty();

        let baseUrl = "{{ env('EDUCATION_FILE_URL') }}";

        existingEducationMedia.forEach(media => {
            // Kalau user dah tekan delete, jangan render
            if (deletedEducationMediaIds.includes(media.id)) {
                return;
            }

            let imageUrl = `${baseUrl}/${media.file_name}`;

            $container.append(`
            <div class="education-media-item">

                <div class="position-relative"
                     style="width:100px; height:75px;">
                    <img src="${imageUrl}"
                         class="rounded border"
                         style="width:100%; height:100%; object-fit:cover;">
                    <button type="button"
                            class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 btn-remove-existing-media"
                            data-id="${media.id}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <small class="d-block text-truncate mt-1"
                       style="width:100px;">
                    ${media.file_name}
                </small>

            </div>`);
        });


        newEducationMedia.forEach((file, index) => {
            let preview = URL.createObjectURL(file);

            $container.append(`
            <div class="education-media-item">
                <div class="position-relative"
                     style="width:100px; height:75px;">
                    <img src="${preview}"
                         class="rounded border"
                         style="width:100%;height:100%;object-fit:cover;">
                    <button type="button"
                            class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 btn-remove-new-media"
                            data-index="${index}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <small class="d-block text-truncate mt-1"
                       style="width:100px;">
                    ${file.name}
                </small>

            </div>
        `);
        });
    }
    // Get Data from API
    function getAllOrganizations() {

        return $.ajax({
            url: "{{ route('organization.getAllOrganizations') }}",
            type: "GET",
            dataType: "json",

            success: function ({ data }) {
                listOfOrganizations = data;
                let $institution = $("#educationInstitution");
                $institution.html(`<option value="" selected disabled>Select Institution</option>`);

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

    function getEducationDetail(eduId) {

        let modalEl = document.getElementById("educationModal");
        let modal = bootstrap.Modal .getOrCreateInstance(modalEl);

        let url = "{{ route('education.getEducationById', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", eduId);

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",

            success: function ({ data }) {

                console.log(data);

                $("#educationId").val(data.id);
                $("#cgpaInput").val(data.cgpa);
                $("#descriptionInput").val(data.description);
                $("#enrollmentStatus").val(
                    data.enrollment_status ?? "Active"
                );

                // SKILLS
                $("#skillContainer").empty();

                (data.skills ?? []).forEach(skill => {
                    $("#skillContainer").append(
                        createSkillRow(skill.id)
                    );
                });

                // MEDIA
                existingEducationMedia = data.media ?? [];
                deletedEducationMediaIds = [];
                newEducationMedia = [];

                $("#mediaFileInput").val("");

                renderEducationMedia();

                setEducationDate(
                    data.start_date,
                    "#startMonth",
                    "#startYear"
                );

                setEducationDate(
                    data.end_date,
                    "#endMonth",
                    "#endYear"
                );

                let organizationId = data.programme?.organization?.id;

                if (organizationId) {
                    $("#educationInstitution").val(organizationId);

                    getProgrammesByOrganizationId(
                        organizationId,
                        data.programme_id
                    );
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

    function getAllSkills() {
        listOfSkills

        $.ajax({
            url: "{{ route('skills.getAllSkills') }}",
            type: "GET",
            dataType: "json",

            success: function ({ data }) {
                listOfSkills = data;

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


    $(document).on("change", "#mediaFileInput", function () {

        let files = Array.from(this.files);

        files.forEach(file => {
            newEducationMedia.push(file);
        });

        // clear supaya file sama pun boleh dipilih semula
        $(this).val("");

        renderEducationMedia();
    });

    $(document).on("click", ".btn-remove-existing-media", function () {

        let mediaId = Number($(this).data("id"));

        if (!deletedEducationMediaIds.includes(mediaId)) {
            deletedEducationMediaIds.push(mediaId);
        }

        renderEducationMedia();
    });

    $(document).on("click", ".btn-remove-new-media", function () {

        let index = Number($(this).data("index"));

        newEducationMedia.splice(index, 1);

        renderEducationMedia();
    });

    function getProgrammesByOrganizationId(organizationId, selectedProgrammeId = null) {
        let $programme = $("#educationProgramme");

        $programme.prop("disabled", true)
            .html(`<option value="">Loading programmes...</option>`);

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
    // Get Data from API

    function formatEducationDate(date) {
        if (!date) return "";

        let [year, month, day] = date.split("-");

        // 01-01 dianggap user hanya masukkan tahun
        if (month === "01" && day === "01") return year;

        return new Date(`${year}-${month}-${day}`)
            .toLocaleDateString("en-US", {
                month: "long",
                year: "numeric"
            });
    }

    function setEducationDate(date, monthSelector, yearSelector) {
        if (!date) {
            $(monthSelector).val("");
            $(yearSelector).val("");
            return;
        }

        let [year, month, day] = date.split("-");

        $(yearSelector).val(year);

        if (month === "01" && day === "01") {
            $(monthSelector).val("");
        } else {
            $(monthSelector).val(month);
        }
    }

    function buildValidDate(month, year) {
        if (!year) return null;
        if (!month) return `${year}-01-01`;
        return `${year}-${month}-01`;
    }

    // Validate
    function isValidDates() {
        let startMonth = $("#startMonth").val();
        let startYear = $("#startYear").val();

        let endMonth = $("#endMonth").val();
        let endYear = $("#endYear").val();

        $("#startMonth, #startYear, #endMonth, #endYear")
            .removeClass("is-invalid");

        if (!startYear) {
            $("#startYear").addClass("is-invalid");
            return false;
        }

        if (!endYear) {
            $("#endYear").addClass("is-invalid");
            return false;
        }

        let startDate = new Date(
            Number(startYear),
            Number(startMonth || 1) - 1,
            1
        );

        let endDate = new Date(
            Number(endYear),
            Number(endMonth || 1) - 1,
            1
        );

        if (endDate < startDate) {
            $("#endMonth, #endYear").addClass("is-invalid");
            return false;
        }

        return true;
    }
    // Validate

    // Update
    function updateEducation(url, formData) {

        formData.append("_method", "PUT");

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
                bootstrap.Modal
                    .getInstance(document.getElementById("educationModal"))
                    ?.hide();

                swalfire(
                    "Success",
                    response.message ?? "Education updated successfully.",
                    "success"
                );

                $(document).trigger("education:updated");
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);

                swalfire(
                    "Update Failed",
                    xhr.responseJSON?.message ?? "Something went wrong",
                    "error"
                );
            }
        });
    }
    // Update
    function validateEducationSkills() {

        let selectedSkillIds = [];
        let isValid = true;

        $(".skill-row").each(function () {

            let $select = $(this).find(".skill-select");
            let skillId = $select.val();

            $select.removeClass("is-invalid");

            if (!skillId) {
                $select.addClass("is-invalid");
                isValid = false;
                return;
            }

            if (selectedSkillIds.includes(skillId)) {
                $select.addClass("is-invalid");
                isValid = false;
                return;
            }

            selectedSkillIds.push(skillId);
        });

        return isValid;
    }
    function createSkillRow(selectedSkillId = "") {

        let skillOptions = `
        <option value="" disabled ${!selectedSkillId ? "selected" : ""}>
            Select Skill
        </option>
    `;

        listOfSkills.forEach(skill => {

            let selected =
                String(skill.id) === String(selectedSkillId)
                    ? "selected"
                    : "";

            skillOptions += `
            <option value="${skill.id}" ${selected}>
                ${skill.skill_name}
            </option>
        `;
        });

        return `
        <div class="input-group skill-row mb-2">

            <select class="form-select form-select-sm skill-select" required>
                ${skillOptions}
            </select>

            <button type="button"
                    class="btn btn-outline-danger remove-skill">
                <i class="fa-solid fa-trash"></i>
            </button>

        </div>
    `;
    }
    // Create Education
    function createEducation(formData) {

        $.ajax({
            url: "{{ route('education.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {
                bootstrap.Modal .getInstance(document.getElementById("educationModal"))?.hide();
                swalfire("Success", response.message ?? "Education created successfully.", "success")
                $(document).trigger("education:updated");
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
                swalfire("Create Failed", xhr.responseJSON?.message ?? "Something went wrong", "error")
            }
        });
    }
    // Create Education

    // trigger
    $(document).on("click", ".btn-edit-education", function () {
        let eduId = $(this).data("id");
        getEducationDetail(eduId);
    });

    $(document).on("click", "#btnDeleteEducation", function () {
        let educationId = $("#educationId").val();
        if (!educationId) return

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
                    bootstrap.Modal .getInstance(document.getElementById("educationModal"))?.hide();
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

        let modal = bootstrap.Modal .getOrCreateInstance(
            $("#educationModal")[0]
        );

        existingEducationMedia = [];
        deletedEducationMediaIds = [];
        newEducationMedia = [];

        $("#mediaFileInput").val("");
        $("#mediaContainer").empty();
        $("#skillContainer").empty();
        $("#educationId").val("");
        $("#educationInstitution").val("");

        $("#skillContainer").empty().append(createSkillRow());

        $("#educationProgramme")
            .prop("disabled", true)
            .html(`
            <option value="" selected disabled>
                Select Programme
            </option>
        `);

        $("#cgpaInput").val("");
        $("#descriptionInput").val("");

        $("#startMonth").val("");
        $("#startYear").val("");
        $("#endMonth").val("");
        $("#endYear").val("");

        $("#enrollmentStatus").val("Active");

        $("#educationModal .modal-title").text("Add Education");
        $("#btnDeleteEducation").hide();
        $("#btnSaveEducation").text("Save");

        modal.show();
    });

    $(document).on("click", "#btnSaveEducation", function () {

        let educationId = $("#educationId").val();
        let input = document.getElementById("mediaFileInput");

        if (!$("#educationInstitution").val()) {
            swalfire(
                "Validation Error",
                "Please select an institution",
                "error"
            );
            return;
        }

        if (!$("#educationProgramme").val()) {
            swalfire(
                "Validation Error",
                "Please select a programme",
                "error"
            );
            return;
        }

        // Validate start date and end date
        if (!isValidDates()) {
            swalfire(
                "Validation Error",
                "End date cannot be earlier than start date.",
                "error"
            );
            return;
        }

        if (!validateEducationSkills()) {
            swalfire(
                "Validation Error",
                "Please complete all skills and do not select duplicate skills.",
                "error"
            );
            return;
        }

        let startDate = buildValidDate(
            $("#startMonth").val(),
            $("#startYear").val()
        );

        let endDate = buildValidDate(
            $("#endMonth").val(),
            $("#endYear").val()
        );

        let formData = new FormData();

        formData.append("programme_id", $("#educationProgramme").val());
        formData.append("cgpa", $("#cgpaInput").val() || "");
        formData.append("description", $("#descriptionInput").val() || "");
        formData.append("start_date", startDate);
        formData.append("end_date", endDate);

        formData.append("enrollment_status",
            $("#enrollmentStatus").val() || "Active"
        );

        $(".skill-select").each(function (index) {
            let skillId = $(this).val();
            formData.append(`skill_ids[${index}]`, skillId);
        });

        // New media
        newEducationMedia.forEach((file, index) => {
            formData.append(`media[${index}][file]`, file);
        });

        // Deleted media
        deletedEducationMediaIds.forEach((mediaId, index) => {
            formData.append(
                `deleted_media_ids[${index}]`,
                mediaId
            );
        });

        // Debug
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

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
    // trigger

    // init Load Data from API
    $(document).ready(async function () {
        await getAllOrganizations();
        await getAllSkills();
    });
    // init Load Data from API
    $(document).on("click", "#addSkill", function () {
        $("#skillContainer").append(createSkillRow());
    });
    $(document).on("click", ".remove-skill", function () {
        $(this).closest(".skill-row").remove();
    });
</script>
@endpush
