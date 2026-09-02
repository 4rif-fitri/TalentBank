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