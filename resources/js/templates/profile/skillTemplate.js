function getUserSkillId(skill) {
    return skill.user_skill_id ??
        skill.pivot?.id ??
        "";
}

export function templateRowSkillList(skill) {

    const userSkillId = getUserSkillId(skill);

    return `
        <div
            class="badge rounded-pill bg-light text-dark border d-flex justify-content-between align-items-center gap-2 px-3 py-2"
            data-skill-id="${skill.id}"
            data-user-skill-id="${userSkillId}"
        >
            <i class="${skill.icon_class_name ?? "fa-solid fa-code"} fa-xl"></i>

            <span class="h6 d-flex justify-content-center align-items-center mb-0 fw-bold">
                ${skill.skill_name ?? ""}
            </span>
        </div>
    `;
}

export function templateRowSkillModal(skill) {

    const userSkillId = getUserSkillId(skill);

    return `
        <div
            class="skill-item border rounded p-3 d-flex justify-content-between align-items-center gap-3"
            data-skill-id="${skill.id}"
            data-user-skill-id="${userSkillId}"
        >

            <div class="d-flex align-items-center gap-3 flex-grow-1">

                <div
                    class="bg-light rounded d-flex justify-content-center align-items-center flex-shrink-0"
                    style="width: 40px; height: 40px;"
                >
                    <i class="${skill.icon_class_name ?? "fa-solid fa-code"}"></i>
                </div>

                <div>

                    <div class="fw-semibold">
                        ${skill.skill_name ?? ""}
                    </div>

                    <small class="text-secondary">
                        ${skill.skill_category ?? ""}
                    </small>

                </div>

            </div>

            <div class="d-flex gap-2 flex-shrink-0">

                <button
                    type="button"
                    data-skill-id="${skill.id}"
                    data-user-skill-id="${userSkillId}"
                    class="btn btn-outline-primary btn-sm btn-edit-skill"
                >
                    <i class="fa-solid fa-pencil"></i>
                </button>

                <button
                    type="button"
                    data-skill-id="${skill.id}"
                    data-user-skill-id="${userSkillId}"
                    class="btn btn-outline-danger btn-sm btn-remove-skill"
                >
                    <i class="fa-solid fa-trash"></i>
                </button>

            </div>

        </div>
    `;
}
export function templateSkillOption(skill, selectedSkillId = "") {

    return `
        <option
            value="${skill.id}"
            ${Number(skill.id) === Number(selectedSkillId) ? "selected" : ""}
        >
            ${skill.skill_name ?? skill.name ?? ""}
        </option>
    `;
}
export function templateRowSkillAdd(skillOptions) {
    return `
        <div class="skill-input-row border rounded p-3 d-flex align-items-center gap-2">
            <select class="form-select skill-select">
                <option value="">Select Skill</option>
                    ${skillOptions}
                </select>
                <button type="button" class="btn btn-primary btn-save-new-skill">
                    <i class="fa-solid fa-check"></i>
                </button>
            <button type="button" class="btn btn-outline-danger btn-cencel-addSkill">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `
}

export function templateRowSkillUpdate(skillOptions,skillId,userSkillId) {
    return `
        <div
            class="skill-input-row border rounded p-3 d-flex align-items-center gap-2"
            data-skill-id="${skillId}"
            data-user-skill-id="${userSkillId}"
        >

            <select class="form-select skill-select">
                <option value="">
                    Select Skill
                </option>

                ${skillOptions}
            </select>

            <button
                type="button"
                title="Save Skill"
                class="btn btn-primary btn-update-skill"
            >
                <i class="fa-solid fa-check"></i>
            </button>

            <button
                type="button"
                title="Cancel"
                class="btn btn-outline-danger btn-cancel-update-skill"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>
    `;
}
