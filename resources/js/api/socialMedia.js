// get profile data
export function getAllSocialMedia(profileId) {

    return $.ajax({
        url: `${window.appConfig.routes.socialMedia.getAllSocialMedia}`,
        type: 'GET',
    });
}

export function store(formData) {

    return $.ajax({
        url: `${window.appConfig.routes.socialMedia.store}`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}

export function remove(id){
    let url = `${window.appConfig.routes.socialMedia.delete}`
    url = url.replace("__ID__", id);

    return $.ajax({
        url: url,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}

export function update(id,formData) {
    let url = `${window.appConfig.routes.socialMedia.update}`
    url = url.replace("__ID__", id);

    return $.ajax({
        url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}
