export function getProfileDataByProfileId(url,id) {
    url = url.replace("__ID__", id);
    return $.ajax({
        type: "GET",
        url,
    });
}
