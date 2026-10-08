<div class="modal fade" id="socialMediaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable ">
        <div class="modal-content h-75">
            <div class="modal-header d-flex justify-content-between">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Social Media Links</h1>
                <button type="button" class="btn btn-primary" id="addlink">Add Link</button>
            </div>

            <div class="modal-body" id="socialMediaList"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="btnHideSocialMediaModal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('childScript')
<script type="module">

    function showLinksSocialMediaModal(){
        xmodal.show("socialMediaModal")
    }

    function hideLinksSocialMediaModal(){
        xmodal.hide("socialMediaModal")
    }

    function handleAddRowSocialMediaLink(){
        let socialMediaOption = ""
        stateSocialMedia.allLinks.forEach(socialMedia => {
            socialMediaOption += xlink.student.socialMediaOption(socialMedia)
        });
         $("#socialMediaList").prepend(xlink.student.addLink(socialMediaOption))
    }

    function handleCencelAddLink(){
        $(this).parent().remove()
    }

    function handleAddLink(){
        let $row = $(this).closest(".social-media-row");
        let $dropdown = $row.find(".dropdown-toggle");

        let socialMediaId = $row.find(".dropdown-toggle").attr("data-id");
        let link = $row.find('input[type="url"]').val().trim();

        if (!socialMediaId) {
            xalert.fire("Validation Error", "Please select a social media platform.", "warning");
            return;
        }

        if (!xvalidate.isValidUrl(link)) {
            xalert.fire("Validation Error", "Please enter a valid URL.", "warning");
            return;
        }

        let data = {
            social_media_id: socialMediaId,
            link: link,
            _token: $('meta[name="csrf-token"]').attr("content")
        }

        let platformName = $dropdown.text().trim();
        let platformIcon = $dropdown.find("i").attr("class");

        $.ajax({
            url: "{{ route('social-media.store') }}",
            type: "POST",
            data,
            success: response => {
                let linkId = response.data?.id;

                $row.replaceWith(xlink.student.socialMediaRow(linkId, socialMediaId, platformName, platformIcon, link));

                let theLink = {
                    link,
                    social_media: {
                        icon_class_name: platformIcon,
                        name: platformName
                    }
                }

                $("#linksList").append(xlink.student.badgeSocialMedia(theLink))
                xalert.fire("Success", response.message ?? "Social media link added successfully.", "success");
            },

            error: xhr => {
                xalert.fire("Add Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
            }
        });


    }

    function handleCencelUpdateLink() {

        let $row = $(this).closest(".alert");
        let linkId = $row.data("id");

        let $button = $row.find("button[data-id]");

        let socialMediaId = $button.data("social-media-id");

        let socialMediaName = $button.text().trim();

        let socialMediaLink = $row
            .find('input[type="url"]')
            .val();

        let socialMedia = stateSocialMedia.allSocialMedia.find(
            item => Number(item.id) === Number(socialMediaId)
        );

        if (!socialMedia) {
            console.error("Social media not found:", socialMediaId);
            return;
        }

        $row.replaceWith(
            xlink.student.socialMediaRow(
                linkId,
                socialMediaId,
                socialMediaName,
                socialMedia.icon_class_name,
                socialMediaLink
            )
        );
    }

    function handleDeleteLink(){
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

        }).then(async result => {

            let url = "{{ route('social-media.delete', ['id' => '__ID__']) }}"
            url = url.replace('__ID__', id)

            $.ajax({
                url,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: response => {

                    let theBadge = $("#linksList").find(`.badge[data-id='${id}']`);
                    theBadge.remove();
                    $row.remove();

                    xalert.fire('Success', 'contact updated successfully', 'success');

                },
                error: xhr => {
                    xalert.fire("Delete Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");

                }
            });

        });
    }

    function handleEditLink(){
        let $row = $(this).closest(".social-media-row");
        let id = $row.data("id");
        let socialMediaId = $row.data("social-media-id");

        let currentName = $row.find(".social-media-name").text().trim();
        let currentLink = $row.find(".social-media-link").attr("href");

        let socialMediaOption = ""
        stateSocialMedia.allLinks.forEach(socialMedia => {
            socialMediaOption += xlink.student.socialMediaOption(socialMedia)
        });

        $row.html(xlink.student.editSocialMedia(socialMediaId, currentName, currentLink, socialMediaOption))
    }

    function handleUpdateLink(){
        let $row = $(this).closest(".social-media-row");
        let id = $row.data("id");

        let socialMediaId = $row.find(".dropdown-toggle").attr("data-id");

        let link = $row.find('input[type="url"]').val().trim();
        $row.attr("data-social-media-id", socialMediaId)
            .data("social-media-id", Number(socialMediaId));

        if (!socialMediaId) {
            xalert.fire("Validation Error", "Please select social media.", "warning");
            return;
        }
        if (!xvalidate.isValidUrl(link)) {
            xalert.fire("Validation Error", "Please enter a valid URL.", "warning");
            return;
        }

        let url = "{{ route('social-media.update', ['id' => '__ID__']) }}"
        url = url.replace('__ID__', id)

        let data = {
            _method: "PUT",
            _token: $('meta[name="csrf-token"]').attr("content"),
            social_media_id: socialMediaId,
            link: link,
        }

        $.ajax({
            url,
            type: "POST",
            data,
            success: response => {
                let data = response.data;

                let theBadge = $("#linksList").find(`.badge[data-id='${data.id}']`);
                theBadge.html(`<i class="${data.social_media.icon_class_name}"></i> ${data.social_media.name}`);

                $row.parent().prepend(xlink.student.socialMediaRow(data.id, data.social_media.id, data.social_media.name, data.social_media.icon_class_name, data.link));
                $row.remove();

                let badge = $("#linksList").find(`.badge[data-id="${data.id}"]`);
                badge.find(".badge i").attr("class", data.social_media.icon_class_name);
                badge.find(".badge").text(data.social_media.name);
                badge.attr("href", data.link);

                xalert.fire("Success", response.message ?? "Social media link updated successfully.","success");
            },
            error: xhr => {
                xalert.fire("Update Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
            }
        });

    }

    function handleSelectSocialMedia(){
        const id = $(this).data("id");
        const name = $(this).data("name");
        const icon = $(this).data("icon");

        const $inputGroup = $(this).closest(".input-group");
        const $dropdownButton = $inputGroup.find(".dropdown-toggle");

        $dropdownButton.attr("data-id", id)
            .html(`<i class="${icon} me-1"></i>${name}`);
    }

    $(document).on('click', '#btnSocialMediaLink', showLinksSocialMediaModal);
    $(document).on('click', '#btnHideSocialMediaModal', hideLinksSocialMediaModal);
    $(document).on('click', '#addlink', handleAddRowSocialMediaLink);
    $(document).on('click', '.btnCancelAdd', handleCencelAddLink);
    $(document).on('click', '.btnAddSave', handleAddLink);
    $(document).on('click', '.btnDeleteLink', handleDeleteLink);
    $(document).on('click', '.btnEditLink', handleEditLink);
    $(document).on('click', '.btnUpdateLink', handleUpdateLink);
    $(document).on('click', '.btnCencelUpdateLink', handleCencelUpdateLink);
    $(document).on('click', '.social-media-option', handleSelectSocialMedia);

</script>
@endpush
