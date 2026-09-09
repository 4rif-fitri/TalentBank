<section id="semesterResults">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-0">
            Semester Results
        </h3>
        <div>
            @if (array_intersect(session('roles') ?? [], ['Student']))
            <button class="btn btn-primary" id="addSemester" type="button">
                <i class="fa-solid fa-plus me-1"></i>
                Add Semester
            </button>
            <button class="btn btn-primary" id="addResult" type="button">
                <i class="fa-solid fa-plus me-1"></i>
                Add Result
            </button>
            @endif

        </div>
    </div>
    <hr>
    <div id="semesterResultList">
        <div class="text-center py-4 text-muted">
            <p class="mb-0">
                Select Result tab to load semester results.
            </p>
        </div>
    </div>
</section>
@push('childScript')

<script>
    function getProgrammesByUserProfileId(){
        let url = "{{ route('programme.getProgrammesByUserProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");

        $.ajax({
            url,
            type: "GET",
            success: response => {
                console.log("getProgrammesByUserProfileId",response);

            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }
    getProgrammesByUserProfileId()

</script>
@endpush
