export let student = {
    template: (programme) => {
        return `<div class="alert alert-primary d-flex gap-2" role="button"
                    data-programme-id="${programme.id}">

                    <div class="bg-body d-flex justify-content-center align-items-center"
                        style="width: 40px; height: 40px; border-radius: 50%;">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>

                    <div>
                        <p>${programme.organization?.company_name ?? 'Unknown Institution'}</p>
                        <p>${programme.programme_name ?? ''}</p>
                        <p>${programme.programme_level ?? ''}</p>
                        <p>${programme.duration_years ?? ''} Years</p>
                    </div>
                </div>`;
    },

    loadingEducation: () => {
        return `<div class="carousel-item active">
                    <div class="py-5 text-center">
                    <div class="spinner-border" role="status"></div>
                        <p class="text-muted mt-2 mb-0">
                            Loading education...
                        </p>
                    </div>
                </div>`
    },

    emptyEducation: () => {
        return `<div class="carousel-item active">
                    <p class="text-muted text-center py-5 mb-0">
                    No education added yet</p>
                </div>`
    },

    badgeEducationSkill: (skill) => {
        return `<div class="badge text-bg-secondary m-1">
                ${skill.skill_name}
            </div>`
    },

    imageEducation: (imageUrl, educationId, index) => {
        return `<div class="image rounded-1 m-1 education-preview-image"
                style="background-image: url('${imageUrl}'); cursor: pointer;"
                    data-education-id="${educationId}"
                    data-slide-index="${index}">
            </div>`
    },

    imageEducationMore: (imageUrl, educationId, index, remaining) => {
        return `<div class="image rounded-1 m-1 d-flex justify-content-center align-items-center education-preview-image"
                style="background-image: url('${imageUrl}'); filter: brightness(.5); cursor: pointer;"
                data-education-id="${educationId}"
                data-slide-index="${index}">

                <h4 class="text-white m-0">
                    +${remaining}
                </h4>
            </div>`
    },

    cardEducation: (education) => {
        return `<article class="education-item h-100 border rounded-3 p-3 position-relative">

            <button type="button"
                class="btn btn-secondary icon btn-edit-education position-absolute top-0 end-0 m-2"
                data-id="${education.id}">
                <i class="fa-solid fa-pencil"></i>
            </button>

            <div class="pe-4">
                <p class="h5 fw-bold mb-2">
                    ${education.programme?.organization?.company_name ?? ""}
                </p>

                <p class="mb-1">
                    ${education.programme?.programme_name ?? ""}
                </p>

                <p class="text-muted mb-2">
                    ${startDate} - ${endDate}
                </p>

                <p class="mb-2">
                    Grade: ${education.cgpa ?? "-"}
                </p>

                <p class="mb-2">
                    ${education.description ?? ""}
                </p>

                <div class="skills d-flex flex-wrap align-items-center">
                    ${asd(education.skills ?? [])}
                </div>

                <div class="d-flex gap-2 flex-wrap">

                    ${education.enrollment_status ? `
                        <span class="badge text-bg-primary">
                            ${education.enrollment_status}
                        </span>
                    ` : ""}

                    ${education.verification_status ? `
                        <span class="badge text-bg-success">
                            ${education.verification_status}
                        </span>
                    ` : ""}

                </div>
            </div>
            <div class="images d-flex flex-wrap">
                ${tem(education.media, education.id)}
            </div>

        </article>`
    },
}

export let recruiter = {

}

export let common = {

}
