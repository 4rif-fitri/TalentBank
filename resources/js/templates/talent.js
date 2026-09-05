function renderEducation(programmes) {

    if (!Array.isArray(programmes) || programmes.length === 0) {
        return "";
    }

    return `
        <div class=" d-flex align-items-start flex-column w-100">
            <p class="m-0 text-dark">${programmes[0].organization.company_name}</p>
            <p class="m-0 text-muted">${programmes[0].programme_name}</p>
            <div class=" badge bg-primary">See More</div>
        </div>`
}

function renderSkills(skills) {
    if (!skills) return ""
    let html = "<div class='d-flex gap-1 flex-wrap'>"

    skills.forEach(skill => {
        html += `
            <span class="badge bg-light text-dark border fw-normal">
                <i class="${skill.icon_class_name}"></i>
                ${skill.skill_name}
            </span>`
    });
    html += "</div>"
    return html
}

export function talentCard(data) {

    return `<div data-id=${data.id} class="col-12 col-md-6 col-lg-6 col-xl-4">
                <div class="card h-100 border-0 p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div></div>

                    <i role="button" data-id="${data.id}" class="${data.is_liked ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart text-muted'} talent-like"></i>
                </div>

                <div class="d-flex gap-2 student">
                    <div class="d-flex align-items-center gap-3 mb-3 mt-2">
                        <img src="${window.appConfig.baseURL}/uploads/profile-image-url/${data.profile_image}" class="rounded" width="60" alt="Profile">
                    </div>
                    <div class="mb-2 d-flex align-items-center flex-column" style="font-size: 12px;">
                        <h6 class="m-0 fw-bold profileName">${data.name}</h6>
                        ${renderEducation(data.programmes)}

                    </div>
                </div>

                ${renderSkills(data.skills)}

                <div class="mt-2 d-flex gap-2">
                    <a href="${window.appConfig.baseURL}/profile/student/${data.id}" target="_blank"
                        class="btn btn-sm btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center">
                        View Profile
                    </a>
                    <button data-name="${data.name}" data-id=${data.id} class="btn btnAddToShortlist btn-sm btn-primary w-50 fw-bold">
                        Add to Shortlist
                    </button>
                </div>
            </div>
        </div>`
}
