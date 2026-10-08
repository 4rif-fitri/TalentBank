export function getInvitationById(url,id){
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "GET"
    });
}

export function getInvitationsByStatusAndReceiverId(url,status){
    return $.ajax({
        url,
        data: { status },
        type: "GET",
    });
}

export function acceptInvitation(url, id, data){
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "POST",
        data,
    });
}

export function rejectInvitation(url, id, data) {
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "POST",
        data,
    });
}

export function getInvitationsByStatusAndSenderId(url, status){
    return $.ajax({
        url,
        type: "GET",
        data: {status},
    });
}

export function withdrawInvitation(url,id, data){
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "POST",
        data,
    });
}


export function update(url, id, data){
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "POST",
        data,
    });
}

export function store(url, data){
    return $.ajax({
        url,
        type: "POST",
        data,
    });
}
