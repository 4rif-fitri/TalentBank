<section id="contactInformation" class="d-flex flex-column gap-1">
    <h3 class="fw-bold text-sm-center text-lg-start">Contact</h3>

    @if (array_intersect(session('roles') ?? [], ['Student']))
    <div class="icon-container">
        <button id="btnEditContactInformation" class="btn btn-secondary icon">
            <i class="fa-solid fa-pencil"></i>
        </button>
    </div>
    @endif

    <hr>

    <article class="m-1 d-flex align-items-center gap-2">
        <i class="fa-regular fa-envelope"></i>
        <p id="email"></p>
    </article>

    <article class="m-1 d-flex align-items-center gap-2">
        <i class="fa-solid fa-phone"></i>
        <p id="phoneNo"></p>
    </article>
</section>

@push('childScript')

<script>

    let state = {
        name: null,
        headline: null,
        programmes: null,
        location: null,
        email: null,
        phoneNo: null,
    }

    function renderContact(name, headline, location, programmes, email, phoneNo){
        state.name = name
        state.headline = headline
        state.location = location
        state.programmes = programmes
        state.email = email
        state.phoneNo = phoneNo

        $("#email").text(state.email)
        $("#phoneNo").text(state.phoneNo)

    }

    $(document).on("profile:loaded", function (event, data) {
        renderContact(
            data.name,
            data.headline,
            data.location,
            data.active_programmes,
            data.email,
            data.phone_no
        )
    })

    $(document).on("profile:state:updated", function (event, data) {
        renderContact(
            data.name,
            data.headline,
            data.location,
            data.active_programmes,
            data.email,
            data.phone_no
        )
    })
</script>

@endpush
