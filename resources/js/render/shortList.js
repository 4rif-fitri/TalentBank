export function sideBar(results) {
    $("#shortlistList").empty();

    if (results != []) {
        results.forEach(response => {
            response.data.forEach(position => {
                $("#shortlistList").append(shortListTemplate.sideBar(position));
            });
        });

    } else {
        $("#shortlistList").append(`<small class="text-muted">No Data yet</small>`);
    }
}

export function detail(data) {
    $("#shortlistContent").empty()

    $("#shortlistContent").append(shortListTemplate.detail(data))

    if (!data.shortlist_users) return

    data.shortlist_users.forEach(user => {
        $("#tableDetail").append(shortListTemplate.candidate(user))
    })

}

export function appendNew(data) {
    $("#shortlistList").append(shortListTemplate.sideBar(data));
}
