export function templateBadgeSocialMedia(link){
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

export function templateRenderSocialMedia(allSocialMedia) {
    return allSocialMedia.map(socialMedia => {
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
