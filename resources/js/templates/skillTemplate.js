export function templateRowSkillList(skill) {
    console.error(skill);

    return `
        <div class="badge rounded-pill bg-light text-dark border d-flex justify-content-between align-items-center gap-2 px-3 py-2">
            <i class="${skill.icon_class_name ?? "fa-solid fa-code"} fa-xl"></i>

            <span class="h6 d-flex justify-content-center align-items-center mb-0 fw-bold">
                ${skill.skill_name}
            </span>
        </div>
    `;
}

export function templateRowSkillModal(skill) {
    return `<div class="skill-item border rounded p-3 d-flex justify-content-between align-items-center gap-3"
            data-skill-id="${skill.id}" data-user-skill-id="${skill.pivot.id}">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <div class="bg-light rounded d-flex justify-content-center align-items-center flex-shrink-0"
                        style="width: 40px; height: 40px;">
                    <i class="${skill.icon_class_name ?? "fa-solid fa-code"}"></i>
                </div>

                <div>
                    <div class="fw-semibold">
                        ${skill.skill_name}
                    </div>

                    <small class="text-secondary">
                        ${skill.skill_category}
                    </small>
                </div>

            </div>

            <div class="d-flex gap-2 flex-shrink-0">

                <button type="button"data-skill-id="${skillId}" data-user-skill-id="${userSkillId ?? ""}"
                    class="btn btn-outline-primary btn-sm btn-edit-skill">
                    <i class="fa-solid fa-pencil"></i>
                </button>

                <button type="button" data-skill-id="${skillId}"
                    data-user-skill-id="${userSkillId ?? ""}"
                    class="btn btn-outline-danger btn-sm btn-remove-skill">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    `
}

export function templateRowSkillAdd(skill) {

}

export function templateRowSkillUpdate(skill) {

}
