@push('scripts')
<script>

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



</script>
@endpush
