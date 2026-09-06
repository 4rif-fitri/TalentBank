<section id="skills" class="d-flex flex-column gap-1">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-bold text-sm-center text-lg-start mb-0">
            Skills
        </h3>

        @if (array_intersect(session('roles') ?? [], ['Student']))
        <div class="icon-container">
            <button id="btnSkill" type="button" class="btn btn-secondary icon" title="Edit Skills">
                <i class="fa-solid fa-pencil"></i>
            </button>
        </div>
        @endif
    </div>

    <hr>
    <div id="skillList" class="d-flex flex-wrap gap-2"></div>
</section>
@push('childScript')
<script>
    let stateSkills = {
        skills: null,
        allSkill: null,
        skillOptions: ""
    }
</script>

<script type="module">

    function getAllSkills() {
        $.ajax({
            url: "{{ route('skills.getAllSkills') }}",
            type: "GET",
            success: response => {
                xdebug.line(response)
                stateSkills.allSkill = response.data

                stateSkills.allSkill.forEach(skill => {
                   stateSkills.skillOptions += xskill.student.skillOption(skill)
                });

            },
            error: xhr => {
                xdebug.line(response)
            }
        });
    }

    function renderSkill(skills){
        stateSkills.skills = skills

        stateSkills.skills.forEach(skill => {
            $("#skillList").append(xskill.student.rowSkill(skill))
        });

    }

    $(document).on("profile:loaded", function (event, data) {
        renderSkill(data.skills,)
        getAllSkills()
    })


</script>
@endpush
