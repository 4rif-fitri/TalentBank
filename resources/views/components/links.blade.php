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
    function renderSocialMediaLinks(socialMediaLinks) {
        $("#linksList").empty();

        socialMediaLinks?.forEach(dt => {
            const $a = $("<a>", {
                class: "badge text-bg-light d-flex align-items-center gap-1",
                href: dt.link,
                target: "_blank",
                rel: "noopener noreferrer"
            });

            const $icon = $("<i>", {
                class: dt.social_media.icon_class_name
            });

            $a.append($icon,
                `${dt.social_media.name}`
            );
            $("#linksList").append($a);

        });
    }

    $("#btnSocialMediaLink").on("click", function () {
        const modalElement = document.getElementById("socialMediaModal");

        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

        modal.show();
    });
</script>
@endpush