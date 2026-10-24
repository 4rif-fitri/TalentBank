<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/base.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/Modern.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/Professional.css') }}">
<style>
    #myResume{
        height: 70rem;
    }
</style>

<section id="myResume" style="overflow: scroll;">
    <h3 class="fw-bold text-sm-center text-lg-start">My Resume</h3>
    <hr>
    <div class="workspace w-100 overflow-hidden" style="height: 92vh !important;">
        <div id="resumePages"></div>
    </div>
</section>

@push('childScript')

<script type="module">

    let templates = {
        Modern,
        Professional
    };
    let currentTemplate

    let dataResume = {
        profile: [],
    };

    function renderResume() {
        const template = templates[currentTemplate]?.default;

        if (!template) {
            console.error("Template or resume data not found");
            return;
        }

        console.error(dataResume);
        let pages;

        if (template.type === "single") {
            const blocks = template.render(dataResume);
            const measured = measureBlocks.measureBlocks(blocks);
            const columnPages = paginateColumn.paginateColumn(measured);

            pages = columnPages.map(blocks => ({
                left: blocks,
                right: []
            }));
        } else if (template.type === "columns") {
            const layout = template.render(dataResume);
            const left = measureBlocks.measureBlocks(layout.left);
            const right = measureBlocks.measureBlocks(layout.right);

            pages = paginateTwoColumns.paginateTwoColumns(left, right);
        } else {
            console.error("Unsupported template type:", template.type);
            return;
        }
        console.log({ pages });

        renderPages.renderPages(pages, template, "#");
    }

    function loadResumeData(id){
        let url = "{{ route('resumes.getResumeById', ['id' => '__ID__']) }}"
            .replace("__ID__", id)

        $.ajax({
            type: "GET",
            url,
            success: function (response) {
                currentTemplate = response.data.resume_template.template_file_name
                let data = response.data
                dataResume = {
                    ...dataResume,
                    profile: dataResume.profile,
                    ...data
                };

                console.log(dataResume);

                renderResume();
            }
        });
    }

    function loadResume(){
        let url = "{{ route('resumes.getResumesByUserProfileId', ['id' => '__ID__']) }}";

        let link = window.location.href;
        let id = link.split('/').pop();

        if (id != null && id !== "" && id != "student") {
            url = url.replace("__ID__", id);
        } else {
            url = url.replace("__ID__", "{{ session('user_profile_id') }}");
        }

        $.ajax({
            type: "GET",
            url,
            success: function (response) {
                loadResumeData(response.data[0].id)
            }
        });
    }

    $(document).on("profile:loaded", function (event, data) {
        dataResume.profile = data
    })

    $(".profile-tab[data-target='myResume']").on("click", function () {
        console.log("myResume");
        loadResume()
    });

</script>
@endpush

