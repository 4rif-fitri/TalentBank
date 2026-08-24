export function getAllSkills() {

    return $.ajax({
        url: `${window.appConfig.routes.skills.getAllSkills}`,
        type: 'GET',
    });
}

export function skillsStore(formData) {

    return $.ajax({
        url: `${window.appConfig.routes.skills.skillsStore}`,
        type: "POST",
        data: formData,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}

export function skillsUpdate(id, formData) {
    let url = `${window.appConfig.routes.skills.skillsUpdate}`
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

export function skillsDelete(id) {
    let url = `${window.appConfig.routes.skills.skillsDelete}`
    url = url.replace("__ID__", id);

    return $.ajax({
        url: url,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
    });
}
