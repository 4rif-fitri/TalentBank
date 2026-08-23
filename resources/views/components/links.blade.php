<article class="links">
    <div id="linksList" class="d-flex flex-wrap gap-2"></div>

    <button class="btn badge text-bg-primary" id="btnSocialMediaLink" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-custom-class="custom-tooltip" data-bs-title="See More Social Media">
        <i class="fa-solid fa-pencil"></i>
        Edit
    </button>
</article>

@push('scripts')
<script>
    let datalink = [];
    let allSocialMedia = [];

    function getAllSocialMedia() {
        return $.ajax({
            url: "{{ route('social-media.getAllSocialMedia') }}",
            type: "GET",
            dataType: "json"
        }).done(function ({ data }) {
            allSocialMedia = data;
        }).fail(function (xhr) {
            console.error(xhr);
        });
    }

    function renderSocialMediaLinks(socialMediaLinks) {
        $("#linksList").empty();

        datalink = socialMediaLinks ?? [];

        datalink.forEach(dt => {
            const $a = $("<a>", {
                class: "badge text-bg-light d-flex align-items-center gap-1",
                href: dt.link,
                target: "_blank",
                rel: "noopener noreferrer"
            });

            const $icon = $("<i>", {
                class: dt.social_media.icon_class_name
            });

            $a.append(
                $icon,
                dt.social_media.name
            );

            $("#linksList").append($a);
        });
    }

    $("#btnSocialMediaLink").on("click", function () {
        renderLinkModal(datalink);

        const modalElement = document.getElementById("socialMediaModal");
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

        modal.show();
    });

    // $(document).ready(async function () {
    //     await getAllSocialMedia();
    //     templateRenderSocialMedia();
    // });
</script>
@endpush
