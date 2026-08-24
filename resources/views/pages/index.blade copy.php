@push('scripts')
<script>
    let allLanguages = [];

    $(document).on("click", ".btn-edit-language", function() {
        let $row = $(this).closest(".language-row");
        let id = $row.data("id");
        let languageId = $row.data("language-id");
        let proficiency = $row.data("proficiency");

        $row.replaceWith(
            createLanguageRow(id, languageId, proficiency, true)
        );
    });

    $(document).on("click", ".btn-cancel-language", function() {
        renderLanguageModal();
    });

    $(document).on("click", ".btn-delete-language", function() {
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

    $(document).on("click", "#btnLanguageLink", async function() {

        if (allLanguages.length === 0) {
            await getAllLanguages();
        }

        renderLanguageModal();

        let modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById("languageModal")
        );

        modal.show();
    });

    $(document).on("click", ".btn-save-language", function() {
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

    $(document).on("click", "#btnAddLanguage", function() {
        $("#userLanguageList").append(createLanguageRow());
    });

    $(document).ready(function() {
        getAllLanguages();
    });
</script>
@endpush
