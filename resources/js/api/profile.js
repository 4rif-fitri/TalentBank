export function getProfile(profileId) {

    return $.ajax({
        url: `${window.appConfig.routes.profile.show}`,
        type: 'GET'
    });
}


export function updateProfile(data) {

    return $.ajax({
        url: window.appConfig.routes.profile.update,
        type: 'PUT',
        data: data
    });
}


export function updateAbout(about) {

    return $.ajax({
        url: window.appConfig.routes.profile.updateAbout,
        type: 'PUT',
        data: {
            about: about
        }
    });
}


export function uploadProfileImage(formData) {

    return $.ajax({
        url: window.appConfig.routes.profile.uploadProfileImage,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false
    });
}


export function uploadCoverImage(formData) {

    return $.ajax({
        url: window.appConfig.routes.profile.uploadCoverImage,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false
    });
}
