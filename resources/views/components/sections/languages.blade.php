<section id="languages" class="d-flex flex-column gap-1">
    <h3 class="fw-bold text-sm-center text-lg-start">Languages</h3>

    @if (array_intersect(session('roles') ?? [], ['Student']))
    <div class="icon-container">
        <button id="btnLanguageLink" class="btn btn-secondary icon">
            <i class="fa-solid fa-pencil"></i>
        </button>
    </div>
    @endif

    <hr>

    <div id="languageList"></div>
</section>