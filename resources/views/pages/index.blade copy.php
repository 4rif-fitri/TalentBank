@push('scripts')
<script>
    function templateSocialMediaRow(linkId, socialMediaId, name, icon, link) {
        return `
        <div
            class="alert alert-light d-flex align-items-center gap-3 p-2 social-media-row"
            data-id="${linkId}"
            data-social-media-id="${socialMediaId}"
        >
            <div class="d-flex flex-column flex-grow-1 gap-2">

                <div class="w-100 text-start p-1">
                    <i class="${icon} fa-lg me-1"></i>

                    <span class="social-media-name">
                        ${name}
                    </span>
                </div>

                <a
                    href="${link}"
                    target="_blank"
                    class="social-media-link"
                >
                    ${link}
                </a>

            </div>

            <div class="d-flex ratio-1x1 btnEditLink">
                <i
                    class="fa-solid fa-pencil fa-lg text-secondary"
                    style="cursor:pointer;">
                </i>
            </div>

            <div class="d-flex ratio-1x1 btnDeleteLink">
                <i
                    class="fa-solid fa-trash fa-lg text-danger"
                    style="cursor:pointer;">
                </i>
            </div>
        </div>
    `;
    }

    function addLink(id, link, $row) {

        let formData = new FormData();

        formData.append("social_media_id", id);
        formData.append("link", link);

        // Ambil info platform daripada dropdown yang user pilih
        let $dropdown = $row.find(".dropdown-toggle");

        let platformName = $dropdown.text().trim();
        let platformIcon = $dropdown.find("i").attr("class");

        $.ajax({
            url: "{{ route('social-media.store') }}",
            type: "POST",

            data: formData,
            processData: false,
            contentType: false,

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function(response) {

                let linkId = response.data?.id;

                $row.replaceWith(
                    templateSocialMediaRow(
                        linkId,
                        id,
                        platformName,
                        platformIcon,
                        link
                    )
                );

                getProfileData();
                swalfire(
                    "Success",
                    response.message ?? "Social media link added successfully.",
                    "success"
                );
            },

            error: function(xhr) {

                console.error(xhr.responseJSON);

                swalfire(
                    "Add Failed",
                    xhr.responseJSON?.message ?? "Something went wrong.",
                    "error"
                );
            }
        });
    }

    function isValidUrl(link) {
        try {
            const url = new URL(link);

            return url.protocol === "http:" || url.protocol === "https:";
        } catch {
            return false;
        }
    }

    function templateRenderSocialMedia() {
        return allSocialMedia.map(socialMedia => {
            return `
            <li class="dropdown-item">
                <button
                    class="dropdown-item social-media-option"
                    type="button"
                    data-id="${socialMedia.id}"
                    data-name="${socialMedia.name}"
                    data-icon="${socialMedia.icon_class_name}"
                >
                    <i class="${socialMedia.icon_class_name} me-2"></i>
                    ${socialMedia.name}
                </button>
            </li>
        `;
        }).join("");
    }

    function renderAddLinkForm() {
        $("#socialMediaList").prepend(`
        <div class="alert alert-light d-flex align-items-center gap-4 social-media-row" role="alert">

            <div class="input-group flex-grow-1">

                <button
                    class="btn btn-outline-secondary dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="fa-brands me-1"></i>
                    Select Here
                </button>

                <ul class="dropdown-menu">
                    ${templateRenderSocialMedia()}
                </ul>

                <input
                    type="url"
                    class="form-control"
                    placeholder="https://"
                >

            </div>

            <!-- SAVE -->
            <div class="d-flex ratio-1x1 btnAddSave">
                <i
                    class="fa-solid fa-floppy-disk fa-lg text-success"
                    style="cursor:pointer;">
                </i>
            </div>

            <!-- CANCEL -->
            <div class="d-flex ratio-1x1 btnCancelAdd">
                <i
                    class="fa-solid fa-trash fa-lg text-danger"
                    style="cursor:pointer;">
                </i>
            </div>

        </div>
    `);
    }

    function renderLinkModal(datalink) {

        $("#socialMediaList").empty();

        datalink.forEach(link => {

            $("#socialMediaList").append(`
            <div
                class="alert alert-light d-flex align-items-center gap-3 p-2 social-media-row"
                data-id="${link.id}"
                data-social-media-id="${link.social_media_id}"
            >

                <div class="d-flex flex-column flex-grow-1 gap-2">

                    <div class="w-100 text-start p-1">

                        <i class="${link.social_media.icon_class_name} fa-lg me-1"></i>

                        <span class="social-media-name">
                            ${link.social_media.name}
                        </span>

                    </div>

                    <a
                        href="${link.link}"
                        target="_blank"
                        class="social-media-link"
                    >
                        ${link.link}
                    </a>

                </div>

                <div class="d-flex ratio-1x1 btnEditLink">
                    <i
                        class="fa-solid fa-pencil fa-lg text-secondary"
                        style="cursor:pointer;">
                    </i>
                </div>

                <div class="d-flex ratio-1x1 btnDeleteLink">
                    <i
                        class="fa-solid fa-trash fa-lg text-danger"
                        style="cursor:pointer;">
                    </i>
                </div>

            </div>
        `);
        });
    }

    function updateLink(id, socialMediaId, link, $row) {

        let url =
            "{{ route('social-media.update', ['id' => '__ID__']) }}";

        url = url.replace("__ID__", id);

        let formData = new FormData();

        formData.append("social_media_id", socialMediaId);
        formData.append("link", link);
        formData.append("_method", "PUT");

        $.ajax({
            url: url,
            type: "POST",

            data: formData,
            processData: false,
            contentType: false,

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function(response) {

                // cari info social media daripada master list
                let socialMedia = allSocialMedia.find(
                    item => Number(item.id) === Number(socialMediaId)
                );

                let name = socialMedia?.name ?? "Social Media";
                let icon = socialMedia?.icon_class_name ?? "fa-solid fa-link";

                // update data pada row
                $row
                    .attr("data-social-media-id", socialMediaId)
                    .data("social-media-id", Number(socialMediaId));

                // tukar inline editor balik kepada display
                $row.html(`
                <div class="d-flex flex-column flex-grow-1 gap-2">

                    <div class="w-100 text-start p-1">

                        <i class="${icon} fa-lg me-1"></i>

                        <span class="social-media-name">
                            ${name}
                        </span>

                    </div>

                    <a
                        href="${link}"
                        target="_blank"
                        class="social-media-link"
                    >
                        ${link}
                    </a>

                </div>

                <div class="d-flex ratio-1x1 btnEditLink">
                    <i
                        class="fa-solid fa-pencil fa-lg text-secondary"
                        style="cursor:pointer;">
                    </i>
                </div>

                <div class="d-flex ratio-1x1 btnDeleteLink">
                    <i
                        class="fa-solid fa-trash fa-lg text-danger"
                        style="cursor:pointer;">
                    </i>
                </div>
            `);
                getProfileData();

                swalfire(
                    "Success",
                    response.message ??
                    "Social media link updated successfully.",
                    "success"
                );
            },

            error: function(xhr) {

                console.error(xhr.responseJSON);

                swalfire(
                    "Update Failed",
                    xhr.responseJSON?.message ??
                    "Something went wrong.",
                    "error"
                );
            }
        });
    }

    $(document).on("click", ".btnUpdateLink", function() {

        let $row = $(this).closest(".social-media-row");

        let id = $row.data("id");

        let socialMediaId = $row
            .find(".dropdown-toggle")
            .attr("data-id");

        let link = $row
            .find('input[type="url"]')
            .val()
            .trim();

        if (!socialMediaId) {
            swalfire(
                "Validation Error",
                "Please select social media.",
                "warning"
            );
            return;
        }

        if (!isValidUrl(link)) {
            swalfire(
                "Validation Error",
                "Please enter a valid URL.",
                "warning"
            );
            return;
        }

        updateLink(
            id,
            socialMediaId,
            link,
            $row
        );
    });

    $(document).on("click", ".btnEditLink", function() {

        let $row = $(this).closest(".social-media-row");

        let id = $row.data("id");
        let socialMediaId = $row.data("social-media-id");

        let currentName = $row
            .find(".social-media-name")
            .text()
            .trim();

        let currentLink = $row
            .find(".social-media-link")
            .attr("href");

        $row.html(`
        <div class="input-group flex-grow-1">

            <button
                class="btn btn-outline-secondary dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                data-id="${socialMediaId}"
            >
                ${currentName}
            </button>

            <ul class="dropdown-menu">
                ${templateRenderSocialMedia()}
            </ul>

            <input
                type="url"
                class="form-control"
                value="${currentLink}"
            >

        </div>

        <div class="btnUpdateLink">
            <i
                class="fa-solid fa-floppy-disk fa-lg text-success"
                style="cursor:pointer;">
            </i>
        </div>`);
    });

    function deleteLink(id, $row) {

        let url =
            "{{ route('social-media.delete', ['id' => '__ID__']) }}";

        url = url.replace("__ID__", id);

        $.ajax({
            url: url,
            type: "DELETE",

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function(response) {

                $row.remove();
                getProfileData();

                swalfire(
                    "Success",
                    response.message ?? "Social media link deleted successfully.",
                    "success"
                );
            },

            error: function(xhr) {

                console.error(xhr.responseJSON);

                swalfire(
                    "Delete Failed",
                    xhr.responseJSON?.message ?? "Something went wrong.",
                    "error"
                );
            }
        });
    }

    $(document).on("click", ".btnDeleteLink", function() {

        let $row = $(this).closest(".social-media-row");
        let id = $row.data("id");

        Swal.fire({
            title: "Delete Link?",
            text: "This social media link will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#dc3545"

        }).then(result => {

            if (!result.isConfirmed) {
                return;
            }

            deleteLink(id, $row);
        });
    });

    $(document).on("click", "#addlink", function(e) {
        renderAddLinkForm()
    })

    $(document).on("click", ".social-media-option", function() {
        const id = $(this).data("id");
        const name = $(this).data("name");
        const icon = $(this).data("icon");

        const $inputGroup = $(this).closest(".input-group");
        const $dropdownButton = $inputGroup.find(".dropdown-toggle");

        $dropdownButton.attr("data-id", id)
            .html(`<i class="${icon} me-1"></i>
            ${name}
        `);

        // console.log(id, name, icon);
    });

    $(document).on("click", ".btnAddSave", function() {

        let $row = $(this).closest(".social-media-row");

        let socialMediaId = $row
            .find(".dropdown-toggle")
            .attr("data-id");

        let link = $row
            .find('input[type="url"]')
            .val()
            .trim();

        if (!socialMediaId) {

            swalfire(
                "Validation Error",
                "Please select a social media platform.",
                "warning"
            );

            return;
        }

        if (!isValidUrl(link)) {

            swalfire(
                "Validation Error",
                "Please enter a valid URL.",
                "warning"
            );

            return;
        }

        addLink(socialMediaId, link, $row);
    });

    $(document).on("click", ".btnCancelAdd", function() {
        $(this).closest(".social-media-row").remove();
    });
</script>
@endpush
