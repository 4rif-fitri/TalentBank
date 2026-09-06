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
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnHideLanguageModal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('childScript')
<script type="module">
    $(document).ready(function (){

        function showLanguageMediaModal(){
            $("#userLanguageList").empty()
            stateLanguage.userLanguage.forEach(language => {
                $("#userLanguageList").append(xlanguage.student.languagesRow(language))
            });

            xmodal.show("languageModal")
        }

        function hideLanguageMediaModal(){
            xmodal.hide("languageModal")
        }

        function handleAddLanguage(){
            $("#userLanguageList").prepend(
                xlanguage.student.languagesAddRow(
                    stateLanguage.optionLanguage,
                    stateLanguage.optionProficiency
            ))
        }

        function handleSaveAddLanguage(){
            let $row = $(this).closest(".language-row");
            let languageId = $row.find(".language-select").val();
            let languageName = $row.find(".language-select option:selected").text();
            let proficiency = $row.find(".language-proficiency").val();

            if (!languageId) {
                xalert.fire("Validation Error", "Please select a language.", "error");
                return;
            }

            if (!proficiency) {
                xalert.fire("Validation Error", "Please select proficiency.", "error");
                return;
            }

            let data = {
                _token: $('meta[name="csrf-token"]').attr("content"),
                language_id: languageId,
                proficiency_level: proficiency,
            }

            $.ajax({
                url: "{{ route('languages.store') }}",
                type: "POST",
                data,
                success: response => {
                    console.log(response);

                    response.data.language = {
                        language_name: languageName
                    };

                    $("#languageList").append(xlanguage.student.template(response.data))
                    $row.replaceWith(xlanguage.student.languagesRow(response.data));


                    xalert.fire("Success", response.message ?? "Language added successfully.", "success");

                },
                error: xhr => {
                    console.log(xhr);
                    xalert.fire("Create Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
                }
            });

        }

        function handleCencelupdateLanguage() {
            let $row = $(this).closest(".language-row");

            let id = $row.data('id');
            let languageId = $row.data('language-id');
            let proficiency = $row.data('proficiency');

            let language = stateLanguage.allLanguage.find(
                language => Number(language.id) === Number(languageId)
            );

            if (!language) {
                xdebug.line("language not found:", languageId);
                return;
            }

            let languageData = {
                id: id,
                language_id: language.id,
                proficiency_level: proficiency,

                language: {
                    language_name: language.language_name
                }
            };

            console.log(languageData);

            $row.replaceWith(
                xlanguage.student.languagesRow(languageData)
            );
        }

        function handleCencelAddLanguage(){
            $(this).parent().remove()
        }

        function handleDeleteLanguage(){
            let $row = $(this).closest(".language-row");
            let id = $row.data("id");

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

            }).then(async result => {

                if (!result.isConfirmed) return;

                let url = "{{ route('languages.delete', ['id' => '__ID__']) }}"
                 url = url.replace("__ID__", id)

                $.ajax({
                    url,
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: response => {
                        let theBadge = $("#languageList").find(`.language-item[data-id='${id}']`);
                        theBadge.remove();
                        $row.remove();
                        xalert.fire("Success", response.message ?? "Language deleted successfully.", "success");

                        xdebug.line(response)
                    },
                    error: xhr => {
                        xdebug.line(xhr)
                        xalert.fire("Delete Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");

                    }
                });

            });
        }

        function handleEditLanguage(){
            let $row = $(this).closest(".language-row");
            let id = $row.data("id");
            let languageId = $row.data("language-id");
            let theProficiency = $row.data("proficiency");

            let languagesOptions = ""
            stateLanguage.allLanguage.forEach(language => languagesOptions += xlanguage.student.languageOptions(language, languageId))

            let proficiencyOptions = ""
            let proficiencies = stateLanguage.allProficiency
            proficiencies.forEach(proficiency => proficiencyOptions += xlanguage.student.proficiencyOptions(proficiency, theProficiency))

            $row.replaceWith(
                xlanguage.student.languagesUpdateRow(
                    languagesOptions,
                    proficiencyOptions,
                    id,
                    languageId,
                    theProficiency
                )
            );
        }

        function handleUpdateLanguage(){
            let $row = $(this).closest(".language-row");
            let id = $row.data("id");
            let languageId = $row.find(".language-select").val();
            let proficiency = $row.find(".language-proficiency").val();

            console.log({ id, languageId, proficiency });

            if (!languageId) {
                xalert.fire("Validation Error", "Please select a language.", "error");
                return;
            }

            if (!proficiency) {
                xalert.fire("Validation Error", "Please select proficiency.", "error");
                return;
            }

            let url = "{{ route('languages.update', ['id' => '__ID__']) }}"
            url = url.replace("__ID__",id)

            let data = {
                _method: "PUT",
                _token: $('meta[name="csrf-token"]').attr("content"),
                language_id: languageId,
                proficiency_level: proficiency
            }

            $.ajax({
                url,
                type: "POST",
                data,
                success: response => {
                    xdebug.line(response);

                    $row.replaceWith(xlanguage.student.languagesRow(response.data));

                    let languageRow = $("#languageList").find(`.language-item[data-id="${response.data.id}"]`);
                    languageRow.find(".languageName").text(response.data.language.language_name)
                    languageRow.find(".languageProficiency").text(response.data.proficiency_level)

                    xalert.fire("Success", response.message ?? "Language updated successfully.", "success");
                },
                error: xhr => {
                    xdebug.line(xhr)

                    xalert.fire("Update Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");

                }
            });

        }

        $(document).on('click', '#btnLanguageLink', showLanguageMediaModal);
        $(document).on('click', '#btnHideLanguageModal', hideLanguageMediaModal);
        $(document).on('click', '#btnAddLanguage', handleAddLanguage);
        $(document).on('click', '.btn-save-language', handleSaveAddLanguage);
        $(document).on('click', '.btn-delete-language', handleDeleteLanguage);
        $(document).on('click', '.btn-edit-language', handleEditLanguage);
        $(document).on('click', '.btn-update-language', handleUpdateLanguage);
        $(document).on('click', '.btnCencelUpdateLanguage', handleCencelupdateLanguage);
        $(document).on('click', '.btnCencelAddLanguage', handleCencelAddLanguage);

    })

</script>
@endpush
