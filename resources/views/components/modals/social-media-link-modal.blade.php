<div class="modal fade" id="socialMediaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable ">
        <div class="modal-content h-75">
            <div class="modal-header d-flex justify-content-between">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Social Media Links</h1>
                <button type="button" class="btn btn-primary" id="addlink">Add Link</button>
            </div>
            <div class="modal-body" id="socialMediaList">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

    function renderAfterAdd(id, link, $row) {
        $row.children().remove();
    }

    function deleteLink(id) {
        let url = "{{ route('social-media.delete', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        $.ajax({
            url: url,
            type: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (response) {
                // console.log(response);
                swalfire("Success", response.message ?? "Education deleted successfully.", "success")
            },

            error: function (xhr) {
                console.error(xhr.responseJSON);
                swalfire("Delete Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error")
            }
        });

    }

    function addLink(id, link, $row) {
        let formData = new FormData()
        formData.append("social_media_id", id);
        formData.append("link", link);

        $.ajax({
            url: "{{ route('social-media.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function (response) {
                // renderLinkModal(id, link, $row)
                // console.log(response);
                swalfire("Success", response.message ?? "Uploaded successfully", "success")
            },

            error: function (xhr) {
                console.error(xhr);
                swalfire("Upload Failed", xhr.message, "error")
            },

            complete: function () {

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
            <div class="alert alert-light d-flex align-items-center gap-4" role="alert">
                <div class="input-group flex-grow-1">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-brands me-1"></i>
                        Select Here
                    </button>
                    <ul class="dropdown-menu">
                        ${templateRenderSocialMedia()}
                    </ul>
                    <input type="url" class="form-control" placeholder="https://">
                </div>
                <div class="d-flex flex-column ratio-1x1 btnDelete">
                    <i class="fa-solid fa-floppy-disk fa-lg text-success" style="cursor: pointer;"></i>
                </div>
                <div class="d-flex flex-column ratio-1x1 btnAddSave">
                    <i class="fa-solid fa-trash fa-lg text-danger" style="cursor: pointer;"></i>
                </div>
            </div>
        `)
    }

    function renderLinkModal(datalink) {
        $("#socialMediaList").empty()

        datalink.forEach(link => {

            $("#socialMediaList").append(`
               <div class="alert alert-light d-flex align-items-center gap-3 p-2" role="alert">
                    <div class="d-flex flex-column flex-grow-1 gap-4">
                        <div data-id=${link.id} class="w-100 text-start p-1" type="button">
                            <i class="${link.social_media.icon_class_name} fa-lg"></i>
                            ${link.social_media.name}
                        </div>
                        <a href="${link.link}">${link.link}</a>
                    </div>
                    <div class="d-flex ratio-1x1">
                        <i class="fa-solid fa-pencil fa-lg text-secondary" style="cursor: pointer;"></i>
                    </div>
                    <div class="d-flex ratio-1x1">
                        <i class="fa-solid fa-trash fa-lg text-danger" style="cursor: pointer;"></i>
                    </div>

                </div>
            `)
        });
    }

    $(document).on("click", "#addlink", function (e) {
        renderAddLinkForm()
    })

    $(document).on("click", ".social-media-option", function () {
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

    $(document).on("click", ".btnAddSave", function () {
        const $row = $(this).closest(".alert");

        const socialMediaId = $row.find(".dropdown-toggle").attr("data-id");
        const link = $row.find('input[type="url"]').val();

        if (!socialMediaId) {
            swalfire("Select social media please", "", "warning")
        }

        if (!isValidUrl(link)) {
            swalfire("Insert link please", "", "warning")
        }

        addLink(socialMediaId, link, $row)

        // console.log({
        //     social_media_id: socialMediaId,
        //     link: link
        // });
    });

    $(document).on("click", ".btnAddSave", function(){

    })
</script>
@endpush
