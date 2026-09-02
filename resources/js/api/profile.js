// get profile data
export function getProfile(profileId) {
    let url = `${window.appConfig.routes.profile.show}`
    url = url.replace("__ID__", profileId)
    return $.ajax({
        url,
        type: 'GET'
    });
}

// update profile
export function updateProfile(data) {
    return $.ajax({
        url: window.appConfig.routes.profile.update,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
}

// update about
export function updateAbout(data) {

    return $.ajax({
        url: window.appConfig.routes.profile.updateAbout,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
}

// upload photo profile image
export function uploadProfileImage(formData) {

    return $.ajax({
        url: window.appConfig.routes.profile.uploadProfileImage,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
}

// upload cover image
export function uploadCoverImage(formData) {

    return $.ajax({
        url: window.appConfig.routes.profile.uploadCoverImage,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
}
