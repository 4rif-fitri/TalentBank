<section id="about">
    <h3 class="fw-bold text-sm-center text-lg-start">About</h3>
    <hr>

    <p class="placeholder-glow">
        <span id="aboutText" class="placeholder">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Est, voluptas.</span>
    </p>

    @if (array_intersect(session('roles') ?? [], ['Student']))
    <div class="icon-container">
        <button type="button" class="icon btn btn-secondary" id="btnEditAbout">
            <i class="fa-solid fa-pencil"></i>
        </button>
    </div>
    @endif
</section>

<x-modals.about-modal />

@push('childScript')

<script>
    let about = null

    function renderAbout(data){
        about = data ?? ""
        $("#aboutText").text(about)
        $("#aboutText").removeClass("placeholder")
    }

    $(document).on("profile:loaded", function(event,data){
        renderAbout(data.about)
    })

    $(document).on("profile:about:updated", function (event, data) {
        renderAbout(data.about)
    })

</script>
@endpush
