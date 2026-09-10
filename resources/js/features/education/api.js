export function getEducationById(url,id){
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "GET"
    });
}

export function getEducationByUserProfileId(url, id){
    url = url.replace('__ID__', id)
    return $.ajax({
        url,
        type: "GET",
    });
}


export function update(url,id,data){
    url = url.replace("__ID__", id)
    return $.ajax({
        url,
        type: "POST",
        data
    });
}

export function store(url,data){
    return $.ajax({
        url,
        type: "POST",
        data
    });
}

