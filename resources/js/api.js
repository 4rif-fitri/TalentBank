const apiClient = {
    // Fungsi utama yang menguruskan semua jenis request
    request: function (url, method = 'GET', data = null, customOptions = {}) {
        let ajaxOptions = {
            url: url,
            type: method,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            dataType: 'json'
        };

        // Konfigurasi khas jika data yang dihantar adalah FormData (untuk upload file/gambar)
        if (data instanceof FormData) {
            ajaxOptions.data = data;
            ajaxOptions.processData = false;
            ajaxOptions.contentType = false;
        } else if (data) {
            ajaxOptions.data = data;
        }

        // Gabungkan tetapan default dengan sebarang tetapan tambahan (jika ada)
        $.extend(ajaxOptions, customOptions);

        // Pulangkan Promise supaya kita boleh guna .then() atau async/await
        return $.ajax(ajaxOptions);
    },

    // Shortcut (Fungsi Pantas)
    get: function (url) {
        return this.request(url, 'GET');
    },
    post: function (url, data) {
        return this.request(url, 'POST', data);
    },
    put: function (url, data) {
        // Jika pakai FormData untuk PUT di Laravel, Laravel perlukan _method="PUT" dihantar melalui POST
        if (data instanceof FormData && !data.has('_method')) {
            data.append('_method', 'PUT');
            return this.request(url, 'POST', data);
        }
        return this.request(url, 'PUT', data);
    },
    delete: function (url) {
        return this.request(url, 'DELETE');
    }
};
window.apiClient = apiClient;
