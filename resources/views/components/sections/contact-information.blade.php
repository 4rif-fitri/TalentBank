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