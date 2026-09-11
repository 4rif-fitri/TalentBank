export let common = {
    imageCard: (media, imageUrl) => {
        return `<div class="education-media-item">
                    <div class="position-relative" style="width:100px; height:75px;">
                        <img src="${imageUrl}" class="rounded border"
                            style="width:100%; height:100%; object-fit:cover;">
                        <button type="button"
                                class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 btn-remove-existing-media"
                                data-id="${media.id}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <small class="d-block text-truncate mt-1"
                            style="width:100px;">
                        ${media.file_name}
                    </small>
                </div>`
    }
}
