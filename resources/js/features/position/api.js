export function getPositionsByOrgId(url, id) {
    url = url.replace("__ID__", id);
    return $.ajax({
        url: url,
        method: 'GET'
    });
}

export function store(url, data){
    return $.ajax({
        url: url,
        method: 'POST',
        data
    });
}

export function update(url,id,data){
    url = url.replace("__ID__", id);
    return $.ajax({
        url: url,
        method: 'GET'
    });
}

export function getShortlistedPositionIds(url, profileId, orgId){
    url = url.replace("__orgId__", orgId)
    url = url.replace("__profileId__", profileId)
    return $.ajax({
        type: "GET",
        url,
    });
}

export function getPositionById(url,id){
    url = url.replace("__ID__", id);
    return $.ajax({
        url: url,
        method: 'GET'
    });
}
