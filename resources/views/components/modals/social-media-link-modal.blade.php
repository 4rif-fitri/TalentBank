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
                xdebug.line(response)
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
                xdebug.line(xhr)
                xalert.fire("Add Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
            }
        });


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

            $.ajax({
                url: "url",
                type: "method",
                data: "data",
                dataType: "dataType",
                success: function (response) {

                }
            });

        });
    }

    function handleEditLink(){

    }

    function handleUpdateLink(){

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
    $(document).on('click', '.social-media-option', handleSelectSocialMedia);
</script>
@endpush
