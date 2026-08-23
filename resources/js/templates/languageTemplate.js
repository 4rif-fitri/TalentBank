export function templateLanguages(language){
    return `
        <article class="language-item mb-2"
                data-id="${language.id}">

            <p class="h5 fw-bold mb-0">
                ${language.language?.language_name ?? ""}
            </p>

            <p class="text-secondary mb-0">
                ${language.proficiency_level ?? ""}
            </p>

        </article>
        `
}
export function templateLanguagesRow(language) {
    return `
        <div class="language-row alert alert-light d-flex align-items-center gap-3"
                data-id="${language.id}"
                data-language-id="${language.language_id}"
                data-proficiency="${language.proficiency_level}">

            <div class="d-flex flex-column flex-grow-1 gap-1">

                <p class="h6 fw-bold mb-0">
                    ${language.language.language_name}
                </p>
                <p class="mb-0 text-secondary">
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
        </div>
    `
}
