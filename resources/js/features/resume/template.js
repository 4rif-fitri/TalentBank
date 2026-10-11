
export let language = {
    inResume(item, getLanguageId) {
        const id = getLanguageId(item);
        const name = item.language?.language_name
            ?? item.language_name
            ?? "Unknown language";

        return `
            <div class="card mb-2" data-id="${id}" data-section="language">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${name}</h6>
                        <small class="text-muted">
                            ${item.proficiency_level ?? ""}
                        </small>
                    </div>

                    <button type="button"
                        class="btn btn-outline-danger btn-sm"
                        data-action="remove">
                        Remove
                    </button>
                </div>
            </div>
        `;
    },

    outResume(item, getLanguageId) {
        const id = getLanguageId(item);
        const name = item.language?.language_name
            ?? item.language_name
            ?? "Unknown language";

        return `
            <div class="card mb-2" data-id="${id}" data-section="language">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${name}</h6>
                        <small class="text-muted">
                            ${item.proficiency_level ?? ""}
                        </small>
                    </div>

                    <button type="button"
                        class="btn btn-outline-primary btn-sm"
                        data-action="add">
                        Add
                    </button>
                </div>
            </div>
        `;
    }
};



export let skill = {
    inResume(item, getSkillId) {
        const name = item.skill?.skill_name ?? item.skill_name ?? "";
        return `
            <div class="card d-flex flex-row
                        justify-content-between p-3 mb-2"
                data-id="${getSkillId(item)}"
                data-section="skill">

                <h6 class="mb-0">${name}</h6>

                <button type="button"
                        class="btn btn-danger"
                        data-action="remove">
                    −
                </button>
            </div>`;
    },
    outResume(item, getSkillId) {
        const name = item.skill?.skill_name ?? item.skill_name ?? "";

        return `
            <div class="card d-flex flex-row
                        justify-content-between p-3 mb-2"
                data-id="${getSkillId(item)}"
                data-section="skill">

                <h6 class="mb-0">${name}</h6>

                <button type="button"
                        class="btn btn-primary"
                        data-action="add">
                    +
                </button>
            </div>`;
    },
}

export let education = {
    inResume(item, getEducationId) {
        return `
            <div class="card d-flex flex-column flex-md-row justify-content-between p-3 mb-2"
                    data-id="${getEducationId(item)}"
                    data-section="education">

                <div>
                    <h6>${item.programme?.organization?.company_name ?? "Institution"}</h6>
                    <p class="text-muted mb-0">
                        ${item.programme?.programme_name ?? ""}
                    </p>
                </div>

                <button type="button"
                        class="btn btn-danger"
                        data-action="remove">
                    −
                </button>
            </div>`;
    },
    outResume(item,getEducationId) {
        return `
            <div class="card d-flex flex-column flex-md-row justify-content-between p-3 mb-2"
                    data-id="${getEducationId(item)}"
                    data-section="education">

                <div>
                    <h6>${item.programme?.organization?.company_name ?? "Institution"}</h6>
                    <p class="text-muted mb-0">
                        ${item.programme?.programme_name ?? ""}
                    </p>
                </div>

                <button type="button"
                        class="btn btn-primary"
                        data-action="add">
                    +
                </button>
            </div>`;
    },
}

export let socialMedia = {
    inResume() {

    },
    outResume() {

    },
}
