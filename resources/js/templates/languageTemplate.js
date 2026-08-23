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
