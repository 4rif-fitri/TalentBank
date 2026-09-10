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
