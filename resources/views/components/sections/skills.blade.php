<section id="skills" class="d-flex flex-column gap-1">
    <h3 class="fw-bold text-sm-center text-lg-start">Skills</h3>
    <div class="icon-container">
        <button id="btnSkill" class="btn btn-secondary icon">
            <i class="fa-solid fa-pencil"></i>
        </button>
    </div>
    <hr>

    <article class="m-1">
        <p class="fw-bold">Database</p>
        <p>Advanced</p>
    </article>

    <article class="m-1">
        <p class="fw-bold">HTML</p>
        <p>Advanced</p>
    </article>
</section>

@push('scripts')
<script>
    $("#btnSkill").on("click", function () {
        let modal = bootstrap.Modal.getOrCreateInstance($("#skillModal"));
        modal.show()
    })
</script>
@endpush
