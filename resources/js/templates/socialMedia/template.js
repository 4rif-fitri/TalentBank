import { socialMedia } from "../../student/profile/state.js"

export function templateBadgeSocialMedia(link){
    console.log(link);

    return `
        <a class="badge text-bg-light d-flex align-items-center gap-1"
            href=${link.link} target="_blank" rel="noopener noreferrer">
            <i class="${link.social_media.icon_class_name}"></i>
            ${link.social_media.name}
        </a>
    `
}

export function templateSocialMediaRow(linkId, socialMediaId, name, icon, link) {
    return `
        <div class="alert alert-light d-flex align-items-center gap-3 p-2 social-media-row"
            data-id="${linkId}"
            data-social-media-id="${socialMediaId}">

            <div class="d-flex flex-column flex-grow-1 gap-2">
                <div class="w-100 text-start p-1">
                    <i class="${icon} fa-lg me-1"></i>
                    <span class="social-media-name">
                        ${name}
                    </span>
                </div>
                <a href="${link}" target="_blank" class="social-media-link">
                    ${link}
                </a>
            </div>

            <div class="d-flex ratio-1x1 btnEditLink">
                <i class="fa-solid fa-pencil fa-lg text-secondary" style="cursor:pointer;"></i>
            </div>

            <div class="d-flex ratio-1x1 btnDeleteLink">
                <i class="fa-solid fa-trash fa-lg text-danger" style="cursor:pointer;"></i>
            </div>
        </div>`;
}

export function templateRenderSocialMedia() {

    return socialMedia.data.map(socialMedia => {
        return `
            <li class="dropdown-item">
                <button class="dropdown-item social-media-option"
                        type="button"
                        data-id="${socialMedia.id}"
                        data-name="${socialMedia.name}"
                        data-icon="${socialMedia.icon_class_name}">
                    <i class="${socialMedia.icon_class_name} me-2"></i>
                    ${socialMedia.name}
                </button>
            </li>`;
    }).join("");
}

export function templatAddLink(){
    return `
        <div class="alert alert-light d-flex align-items-center gap-4 social-media-row" role="alert">

            <div class="input-group flex-grow-1">

                <button
                    class="btn btn-outline-secondary dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="fa-brands me-1"></i>
                    Select Here
                </button>

                <ul class="dropdown-menu">
                    ${templateRenderSocialMedia()}
                </ul>

                <input
                    type="url"
                    class="form-control"
                    placeholder="https://"
                >

            </div>

            <!-- SAVE -->
            <div class="d-flex ratio-1x1 btnAddSave">
                <i
                    class="fa-solid fa-floppy-disk fa-lg text-success"
                    style="cursor:pointer;">
                </i>
            </div>

            <!-- CANCEL -->
            <div class="d-flex ratio-1x1 btnCancelAdd">
                <i
                    class="fa-solid fa-trash fa-lg text-danger"
                    style="cursor:pointer;">
                </i>
            </div>

        </div>
    `
}
