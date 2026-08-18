<article class="links">
    <a class="badge text-bg-light d-flex align-items-center gap-1"
        href="https://instagram.com/ariffitri" target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-instagram"></i>
        Instagram
    </a>

    <a class="badge text-bg-light d-flex align-items-center gap-1"
        href="https://tiktok.com/@ariffitri" target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-tiktok"></i>
        Tiktok
    </a>

    <a class="badge text-bg-light d-flex align-items-center gap-1"
        href="https://github.com/4rif-fitri" target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-github"></i>
        Github
    </a>

    <a class="badge text-bg-light d-flex align-items-center gap-1" href="https://x.com/ariffitri"
        target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-x-twitter"></i>
        Twitter
    </a>

    <button class="btn badge text-bg-primary" id="btnSocialMediaLink"
    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="See More Social Media">
        <i class="fa-solid fa-pencil"></i>
        Edit
    </button>
</article>

@push('scripts')
    <script>
        $("#btnSocialMediaLink").on("click", function(){
            let modal = bootstrap.Modal.getOrCreateInstance($("#socialMediaModal"));
            modal.show()
        })
    </script>
@endpush
