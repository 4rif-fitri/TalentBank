export function getAllLanguages(profileId) {

    return $.ajax({
        url: `${window.appConfig.routes.languages.getAllLanguages}`,
        type: 'GET',
    });
}

export function languagesStore(formData) {

    return $.ajax({
        url: `${window.appConfig.routes.languages.languagesStore}`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}

export function languagesUpdate(id, formData) {
    let url = `${window.appConfig.routes.languages.languagesUpdate}`
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

export function languagesDelete (id) {
    let url = `${window.appConfig.routes.languages.languagesDelete}`
    url = url.replace("__ID__", id);

    return $.ajax({
        url: url,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}
