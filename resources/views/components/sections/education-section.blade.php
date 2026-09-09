@section('css')
<style>
    .image {
        background-position: center;
        background-size: cover;
    }
    #education{
        overflow: hidden;
    }

    #educationsContainer .card {
        height: 100%;
    }

    #educationsContainer .owl-stage {
        display: flex;
    }

    #educationsContainer .owl-item {
        display: flex;
    }

    #educationsContainer .owl-item>div {
        width: 100%;
    }
</style>
@endsection

<section id="education" class="d-flex flex-column gap-1">

    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-bold text-sm-center text-lg-start">Education</h3>

        @if (array_intersect(session('roles') ?? [], ['Student']))
        <button class="btn btn-primary" id="addEducation" type="button">
            <i class="fa-solid fa-plus me-1"></i>
            Add Education
        </button>
        @endif

    </div>
    <hr>
    <div id="educationsContainer" class="owl-carousel"></div>

</section>

<x-modals.education-modal />

@push('childScript')
<script>
    let currentEducation;
    let profileId;
</script>

<script type="module">

    function getEducationByUserProfileId(id) {
        let url = "{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        $.ajax({
            url: url,
            type: "GET",
            success: response => {
                console.log("getEducationByUserProfileId",response);
                renderEducationList(response.data ?? []);
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }


    function renderSkills(skills) {
        let html = "";
        skills.forEach(data => {
            html += `<div class="badge text-bg-secondary m-1">${data.skill_name}</div>`;
        });
        return html;
    }


    function image(medias, educationId) {
        let html = "";
        let limit = 4;

        medias.forEach((media, index) => {
            if (index >= limit) return;
            let imageUrl = `{{ asset('storage/' . env('EDUCATION_FILE_URL')) }}/${media.file_name}`;

            if (index === 3 && medias.length > 4) {
                let remaining = medias.length - 4;

                html += `
                    <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center education-preview-image"
                        style="width: 80px; height: 80px; background-image: url('${imageUrl}'); filter: brightness(.5); cursor: pointer;"
                        data-education-id="${educationId}" data-slide-index="${index}">
                        <h4 class="text-white m-0">
                            +${remaining}
                        </h4>
                    </div>
                `;

            } else {

                html += `
                    <div
                        class="image rounded-1 m-1 education-preview-image"
                        style="
                            width: 80px;
                            height: 80px;
                            background-image: url('${imageUrl}');
                            cursor: pointer;
                        "
                        data-education-id="${educationId}"
                        data-slide-index="${index}"
                    ></div>
                `;
            }
        });

        return html;
    }

    function renderEducationList(educations) {

        let $carousel = $("#educationsContainer");

        if ($carousel.hasClass("owl-loaded")) {
            $carousel.trigger("destroy.owl.carousel");
            $carousel.removeClass("owl-loaded");
            $carousel.removeAttr("style");
        }

        $carousel.empty();

        if (!educations || educations.length === 0) {

            $carousel.html(`
            <div class="text-center py-4">
                <p class="text-muted mb-0">
                    No education records found.
                </p>
            </div>
        `);

            return;
        }

        let htmlEducation = "";

        educations.forEach(education => {
            let htmlImage = image(education.media ?? [], education.id);

            htmlEducation += `
            <div class="h-100">
                <div class="card p-3 h-100">

                    <h5 class="card-title">
                        ${education.programme?.organization?.company_name ?? ""}
                    </h5>
                    <p class="card-text mb-1">
                        ${education.programme?.programme_name ?? ""}
                    </p>
                    <p class="card-text mb-1">
                        ${education.start_date ?? ""} - ${education.end_date ?? ""}
                    </p>
                    <p class="card-text mb-1">
                        ${education.description ?? ""}
                    </p>
                    <div class="skills d-flex flex-wrap align-items-start">
                        ${renderSkills(education.skills ?? [])}
                    </div>
                    <div class="images d-flex flex-wrap mt-2">
                        ${htmlImage}
                    </div>
                </div>

            </div>`;
        });

        $carousel.html(htmlEducation);

        $carousel.owlCarousel({
            margin: 15,
            nav: true,
            dots: educations.length > 1,
            navText: [
                '<i class="fa-solid fa-chevron-left"></i>',
                '<i class="fa-solid fa-chevron-right"></i>'
            ],
            responsive: {
                0: {
                    items: 1
                },
                992: {
                    items: 2
                },
            }
        });
    }

    function initEducationCarousel() {
        let $carousel = $("#educationsContainer");

        if ($carousel.hasClass("owl-loaded")) {
            $carousel.trigger("destroy.owl.carousel");
        }

        $carousel.owlCarousel({
            margin: 15,
            nav: true,
            dots: true,

            navText: [
                '<i class="fa-solid fa-chevron-left"></i>',
                '<i class="fa-solid fa-chevron-right"></i>'
            ],

            responsive: {
                0: {
                    items: 1
                },
                992: {
                    items: 2
                },
            }
        });
    }
    let resizeTimer;

    function handleScreenSize(){
        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function () {

            let $carousel = $("#educationsContainer");

            if ($carousel.hasClass("owl-loaded")) {
                $carousel.trigger("refresh.owl.carousel");
            }

        }, 100);
    }

    $(window).on("resize", handleScreenSize);
    function getEducationById(id) {

        let url = "{{ route('education.getEducationById', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        $.ajax({
            url,
            type: "GET",
            success: response => {
                console.log(response);
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }

    function showEducationModal() {
        xmodal.show("educationModal");
    }


    $(document).on("click","#menuToggle", handleScreenSize)
    $(document).on("click","#addEducation",showEducationModal);
    $(document).on("profile:loaded",function (event, data) {
        profileId = data.id;
        getEducationByUserProfileId(profileId);
    });

</script>
@endpush
