export function getInterviewsByStatusAndIntervieweeId(url, status){
    return $.ajax({
        url,
        type: "GET",
        data: {
            status: status
        },
    });
}
export function getInterviewById(url,id){
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "GET",
    });
}

export function getInterviewsByStatusAndInterviewerId(url, status){
    return $.ajax({
        url,
        type: "GET",
        data: {
            status: status
        },
    });
}

export function completeInterview(url, id, data){
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "POST",
        data
    });
}

export function cancelInterview(url, id, data) {
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "POST",
        data
    });
}

export function store(url, data) {
    return $.ajax({
        url,
        type: "POST",
        data
    });
}
