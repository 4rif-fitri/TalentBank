export let student = {

    template: (language) => {
        return `<article class="language-item mb-2" data-id="${language.id}">
                    <p class="h5 fw-bold mb-0 languageName">
                        ${language.language?.language_name ?? ""}
                    </p>
                    <p class="text-secondary mb-0 languageProficiency">
                        ${language.proficiency_level ?? ""}
                    </p>
                </article>`
        },

    templateLanguagesRow: (language) => {
        return `<div class="language-row alert alert-light d-flex align-items-center gap-3"
                        data-id="${language.id}"
                        data-language-id="${language.language_id}"
                        data-proficiency="${language.proficiency_level}">

                    <div class="d-flex flex-column flex-grow-1 gap-1">

                        <p class="h6 fw-bold mb-0 languageName">
                            ${language.language.language_name}
                        </p>
                        <p class="mb-0 text-secondary languageProficiency">
                            ${language.proficiency_level}
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
                </div>`
    },

    templatelanguageOptions: (language, languageId = "") => {
        return `<option value="${language.id}" ${languageId == language.id ? "selected" : ""}>${language.language_name}</option>`
    },

    templateProficiencyOptions: (proficiency, theProficiency) => {
        return `<option value="${proficiency}" ${proficiency == theProficiency ? "selected" : ""}>${proficiency}</option>`
    },

    templateLanguagesAddRow: (languageOptions, proficiencyOptions) => {
        return `
        <div class="language-row alert alert-light d-flex align-items-center gap-3">

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
        `
    },

    templateLanguagesUpdateRow: (languageOptions, proficiencyOptions, id, languageId = "", proficiency = "") => {
        return `<div data-id="${id}" data-language-id="${languageId}" data-proficiency="${proficiency}"
                    class="language-row alert alert-light d-flex align-items-center gap-3">

                    <div class="d-flex flex-column flex-grow-1 gap-2">

                        <select class="form-select form-select-sm language-select">
                            ${languageOptions}
                        </select>

                        <select class="form-select form-select-sm language-proficiency">
                            ${proficiencyOptions}
                        </select>

                    </div>

                    <button type="button"
                        class="btn btn-link p-0 text-success btn-update-language">
                        <i class="fa-solid fa-floppy-disk fa-xl"></i>
                    </button>
                </div>`;
    }
}
export let recruiter = {

}

export let common = {

}
