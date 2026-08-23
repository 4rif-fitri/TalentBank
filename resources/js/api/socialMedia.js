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
