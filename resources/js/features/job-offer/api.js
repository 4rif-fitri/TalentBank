export function getJobOffersByStatusAndReceiverId(url, status){
    return $.ajax({
        type: "GET",
        url,
        data: {status},
    });
}

export function getJobOfferById(url,id){
    url = url.replace("__ID__", id)
    return $.ajax({
        type: "GET",
        url,
    });
}

export function acceptJobOffer(url, id, data){
    url = url.replace("__ID__", id)
    return $.ajax({
        type: "POST",
        url,
        data,
    });
}

export function rejectJobOffer(url, id, data){
    url = url.replace("__ID__", id)
    return $.ajax({
        type: "POST",
        url,
        data,
    });
}

export function withdrawJobOffer(url, id, data) {
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "POST",
        data
    });
}

export function getJobOffersByStatusAndSenderId(url, status){
    return $.ajax({
        url,
        type: "GET",
        data: { status }
    });
}

export function store(url,data){
    return $.ajax({
        url,
        type: "POST",
        data,
    });
}
