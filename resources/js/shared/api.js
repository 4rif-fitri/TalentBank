export const api = {

    get(url, data = {}) {
        return $.ajax({
            url,
            method: 'GET',
            data
        });
    },

    post(url, data = {}) {
        return $.ajax({
            url,
            method: 'POST',
            data
        });
    },

    put(url, data = {}) {
        return $.ajax({
            url,
            method: 'PUT',
            data
        });
    },

    delete(url, data = {}) {
        return $.ajax({
            url,
            method: 'DELETE',
            data
        });
    }

};
