<article class="links">
    <div id="linksList" class="d-flex flex-wrap gap-2"></div>

    @if (array_intersect(session('roles') ?? [], ['Student']))
    <button class="btn badge text-bg-primary" id="btnSocialMediaLink" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-custom-class="custom-tooltip" data-bs-title="See More Social Media">
        <i class="fa-solid fa-pencil"></i>
        Edit
    </button>
    @endif

</article>
@push('childScript')
<script>
    let stateSocialMedia = {
        userLinks: null,
        allLinks: null
    }
</script>

<script type="module">

    function getAllSocialMedia(){
        $.ajax({
            url: "{{ route('social-media.getAllSocialMedia') }}",
            type: "GET",
            success: response => {
                stateSocialMedia.allLinks = response.data
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }

    function renderSocialMedia(links){
        stateSocialMedia.userLinks = links

        $("#linksList").empty();
        $("#socialMediaList").empty()

        stateSocialMedia.userLinks.forEach(link => {
            $("#linksList").append(xlink.student.badgeSocialMedia(link))
        })

        stateSocialMedia.userLinks.forEach(link => {
            $("#socialMediaList").append(xlink.student.socialMediaRow(link.id, link.social_media.id, link.social_media.name, link.social_media.icon_class_name, link.link))
        })

    }

    $(document).on("profile:loaded", function (event, data) {
        getAllSocialMedia()
        renderSocialMedia(data.social_media_links)
    })

</script>

@endpush
