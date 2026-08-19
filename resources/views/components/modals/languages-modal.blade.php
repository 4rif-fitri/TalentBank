<div class="modal fade" id="languageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title">
                    Languages
                </h5>
                <button type="button" class="btn btn-primary" id="btnAddLanguage">
                    Add Language
                </button>
            </div>

            <div class="modal-body">
                <div id="userLanguageList" class="d-flex flex-column gap-2">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allLanguages = [];

    function getAllLanguages() {

        return $.ajax({
            url: "{{ route('languages.getAllLanguages') }}",
            type: "GET",
            dataType: "json",

            success: function (response) {
                allLanguages = response.data ?? response;
                console.log("ALL LANGUAGES:", allLanguages);
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
            }
        });
    }

    function renderLanguageModal() {
        let $container = $("#userLanguageList");
        $container.empty();

        let userLanguages = profileData?.user_languages ?? [];

        userLanguages.forEach(item => {
            $container.append(
                createLanguageRow(item.id, item.language_id, item.proficiency_level)
            );
        });
    }

    function refreshProfileLanguages() {
        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",

            success: function ({ data }) {
                profileData = data;
                renderLanguages(data.user_languages ?? []);
                renderLanguageModal();
            },

            error: function (xhr) {
                console.error(xhr);
            }
        });
    }

    function createLanguageRow(userLanguageId = "", selectedLanguageId = "", selectedProficiency = "", editMode = false) {
        let language = allLanguages.find(
            item => String(item.id) === String(selectedLanguageId)
        );

        // EXISTING ITEM - DISPLAY MODE
        if (userLanguageId && !editMode) {

            return `
            <div class="language-row alert alert-light d-flex align-items-center gap-3"
                 data-id="${userLanguageId}"
                 data-language-id="${selectedLanguageId}"
                 data-proficiency="${selectedProficiency}">

                <div class="d-flex flex-column flex-grow-1 gap-1">

                    <p class="h6 fw-bold mb-0">
                        ${language?.language_name ?? ""}
                    </p>
                    <p class="mb-0 text-secondary">
                        ${selectedProficiency ?? ""}
                    </p>
                </div>

                <button type="button"
                        class="btn btn-link p-0 text-body btn-edit-language">
                        <i class="fa-solid fa-pencil fa-lg"></i>
                </button>
                <button type="button"
                        class="btn btn-link p-0 text-danger btn-delete-language">
                        <i class="fa-solid fa-trash fa-lg"></i>
                </button>
            </div>`;
        }


        let languageOptions = `
            <option value="" disabled
                ${!selectedLanguageId ? "selected" : ""}>
                Select Language
            </option>`;

        allLanguages.forEach(language => {

            let selected = String(language.id) === String(selectedLanguageId) ? "selected" : "";

            languageOptions += `
                <option value="${language.id}" ${selected}>
                    ${language.language_name}
                </option>`;
        });

        let proficiencies = ["Beginner", "Intermediate", "Advanced", "Fluent", "Native"];

        let proficiencyOptions = `
            <option value="" disabled
                ${!selectedProficiency ? "selected" : ""}>
                Select Proficiency
            </option>`;

        proficiencies.forEach(level => {
            let selected = level === selectedProficiency ? "selected" : "";

            proficiencyOptions += `
                <option value="${level}" ${selected}>
                    ${level}
                </option>`;
        });

        return `
        <div class="language-row alert alert-light d-flex align-items-center gap-3"
             data-id="${userLanguageId}">

            <div class="d-flex flex-column flex-grow-1 gap-2">
                <select class="form-select form-select-sm language-select">
                    ${languageOptions}
                </select>
                <select class="form-select form-select-sm language-proficiency">
                    ${proficiencyOptions}
                </select>
            </div>
            <button type="button"
                    class="btn btn-link p-0 text-success btn-save-language">
                    <i class="fa-solid fa-floppy-disk fa-xl"></i>
            </button>
            ${userLanguageId ? `
                <button type="button"
                    class="btn btn-link p-0 text-secondary btn-cancel-language">
                    <i class="fa-solid fa-xmark fa-xl"></i>
                </button>` : ""
            }
        </div>`;
    }

    function createLanguage(languageId, proficiency) {

        $.ajax({
            url: "{{ route('languages.store') }}",
            type: "POST",
            data: {
                language_id: languageId,
                proficiency_level: proficiency
            },

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {
                swalfire("Success", response.message ?? "Language added successfully.", "success");
                refreshProfileLanguages();
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
                swalfire("Create Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
            }
        });
    }

    function updateLanguage(userLanguageId, languageId, proficiency) {
        let url = "{{ route('languages.update', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", userLanguageId);

        $.ajax({
            url: url,
            type: "PUT",
            data: {
                language_id: languageId,
                proficiency_level: proficiency
            },

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {
                swalfire("Success", response.message ?? "Language updated successfully.", "success");
                refreshProfileLanguages();
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
                swalfire("Update Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
            }
        });
    }

    function renderLanguages(userLanguages = []) {
        let $container = $("#languageList");
        $container.empty();

        userLanguages.forEach(item => {
            $container.append(`
                <article class="m-1">
                    <p class="fw-bold mb-0">
                        ${item.language?.language_name ?? ""}
                    </p>
                    <p class="text-secondary mb-0">
                        ${item.proficiency_level ?? ""}
                    </p>
                </article>
            `);
        });
    }

    function deleteLanguage(id) {
        let url = "{{ route('languages.delete', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        $.ajax({
            url: url,
            type: "DELETE",

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {
                swalfire("Success", response.message ?? "Language deleted successfully.", "success");
                refreshProfileLanguages();
            },

            error: function (xhr) {
                swalfire("Delete Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
            }
        });
    }

    $(document).on("click", ".btn-edit-language", function () {
        let $row = $(this).closest(".language-row");
        let id = $row.data("id");
        let languageId = $row.data("language-id");
        let proficiency = $row.data("proficiency");

        $row.replaceWith(
            createLanguageRow(id, languageId, proficiency, true)
        );
    });

    $(document).on("click", ".btn-cancel-language", function () {
        renderLanguageModal();
    });

    $(document).on("click", "#btnAddLanguage", function () {
        $("#userLanguageList").append(createLanguageRow());
    });

    $(document).on("click", ".btn-delete-language", function () {
        let $row = $(this).closest(".language-row");
        let id = $row.data("id");

        // belum save ke database
        if (!id) {
            $row.remove();
            return;
        }

        Swal.fire({
            title: "Delete Language?",
            text: "This language will be removed.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            confirmButtonColor: "#dc3545"

        }).then(result => {

            if (!result.isConfirmed) return;
            deleteLanguage(id);
        });
    });

    $(document).on("click", "#btnLanguageLink", async function () {

        if (allLanguages.length === 0) {
            await getAllLanguages();
        }

        renderLanguageModal();

        let modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById("languageModal")
        );

        modal.show();
    });

    $(document).on("click", ".btn-save-language", function () {
        let $row = $(this).closest(".language-row");
        let userLanguageId = $row.data("id");
        let languageId = $row.find(".language-select").val();
        let proficiency = $row.find(".language-proficiency").val();

        if (!languageId) {
            swalfire("Validation Error", "Please select a language.", "error");
            return;
        }

        if (!proficiency) {
            swalfire("Validation Error", "Please select proficiency.", "error");
            return;
        }

        if (userLanguageId) {
            updateLanguage(userLanguageId, languageId, proficiency);
            return;
        }

        createLanguage(languageId, proficiency);
    });

    $(document).on("click", "#btnAddLanguage", function () {
        $("#userLanguageList").append(createLanguageRow());
    });

    $(document).ready(function () {
        getAllLanguages();
    });

</script>
@endpush
