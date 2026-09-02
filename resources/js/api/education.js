export function getEducationByUserProfileId(id) {
    let url = `${window.appConfig.routes.education.getEducationByUserProfileId}`
    url = url.replace("__ID__", id);

    return $.ajax({
        url: url,
        type: 'GET',
        dataType: "json",
    });
}

export function getEducationById(id) {
    let url = `${window.appConfig.routes.education.getEducationById}`
    url = url.replace("__ID__", id);

    return $.ajax({
        url: url,
        type: 'GET',
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}

export function educationStore(formData) {

    return $.ajax({
        url: `${window.appConfig.routes.education.educationStore}`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}

export function educationUpdate(id, formData) {
    let url = `${window.appConfig.routes.education.educationUpdate}`
    url = url.replace("__ID__", id);

    return $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}

export function educationDelete(id) {
    let url = `${window.appConfig.routes.education.educationDelete}`
    url = url.replace("__ID__", id);

    return $.ajax({
        url: url,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}

export function getAllFieldOfStudies() {

    return $.ajax({
        url: `${window.appConfig.routes.education.getAllFieldOfStudies}`,
        type: 'GET',
        dataType: "json",
    });
}

export function getAllQualifications() {

    return $.ajax({
        url: `${window.appConfig.routes.education.getAllQualifications}`,
        type: 'GET',
        dataType: "json",
    });
}
