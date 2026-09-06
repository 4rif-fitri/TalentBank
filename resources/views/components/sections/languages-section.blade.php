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
@push('childScript')

<script>

    let stateLanguage = {
        userLanguage: null,
        allLanguage: null,
        allProficiency: @json(\App\Constants\AppConstants:: PROFICIENCY_LEVELS),
        optionLanguage: "",
        optionProficiency: "",
    }

</script>

<script type="module">

    function getAllLanguages(){
        $.ajax({
            url: "{{ route('languages.getAllLanguages') }}",
            type: "GET",
            success: function (response) {
                stateLanguage.allLanguage = response.data

                stateLanguage.optionLanguage += "<option value='' selected disable>Select Language</option>"
                stateLanguage.allLanguage.forEach(language => {
                    stateLanguage.optionLanguage += xlanguage.student.languageOptions(language)
                })

                stateLanguage.optionProficiency += "<option value='' selected disable>Select Proficiency</option>"
                stateLanguage.allProficiency.forEach(proficiency => {
                    stateLanguage.optionProficiency += xlanguage.student.proficiencyOptions(proficiency)
                })
            }
        });
    }

    function renderLanguage(languages){
        stateLanguage.userLanguage = languages


        stateLanguage.userLanguage.forEach(language => {
            $("#languageList").append(xlanguage.student.template(language))
        });
    }

    $(document).on("profile:loaded", function (event, data) {
        renderLanguage(data.user_languages)
        getAllLanguages()
    })

</script>
@endpush
