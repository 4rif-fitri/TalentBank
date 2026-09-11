@section('css')
<style>
    .image {
        background-position: center;
        background-size: cover;
    }
    #education, #semesterResults{
        overflow: hidden;
    }

    #educationsContainer .card,
    #semesterResultList .card{
        height: 100%;
    }

    #educationsContainer .owl-stage,
    #semesterResultList .owl-stage{
        display: flex;
    }

    #educationsContainer .owl-item,
    #semesterResultList .owl-item{
        display: flex;
    }

    #educationsContainer .owl-item>div,
    #semesterResultList .owl-item>div
    {
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
<x-modals.imagePreviewModal />

@push('childScript')
<script>
    let listEducation = [];
    let currentEducation;
    let profileId;
    let resizeTimer;

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
                listEducation = response.data ?? [];
                renderEducationList(listEducation);
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    }

    function image(medias, educationId) {
        let html = "";
        let limit = 4;

        medias.forEach((media, index) => {
            if (index >= limit) return;
            let imageUrl = `{{ asset('storage/' . env('EDUCATION_FILE_URL')) }}/${media.file_name}`;

            if (index === 3 && medias.length > 4) {
                let remaining = medias.length - 4;
                html += xeducation.student.educationCardImageLast(index,remaining, educationId, imageUrl)
            } else {
                html += xeducation.student.educationCardImage(index,educationId, imageUrl)
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

        $carousel.empty(xeducation.common.noRecords());

        if (!educations || educations.length === 0) {
            $carousel.html(xeducation.student.emptyEducation());
            return;
        }

        let htmlEducation = "";

        educations.forEach(education => {
            let htmlImage = image(education.media ?? [], education.id);
            htmlEducation += xeducation.student.educationCard(education, htmlImage);
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


    function handleEditEducation() {
        let educationId = $(this).data("educationId");
        let currentEducation = listEducation.find(education => education.id === educationId);
        xmodal.show("educationModal");
    }

    function handlePreviewImage() {
        let imageUrl = $(this).attr("src");
        openImagePreview(imageUrl)

        xmodal.show("imagePreviewModal");
    }

    $(document).on("click", ".btnEditEducation", handleEditEducation);
    $(document).on("click","#menuToggle", handleScreenSize)
    $(document).on("click","#addEducation",showEducationModal);
    $(document).on("profile:loaded",function (event, data) {
        profileId = data.id;
        getEducationByUserProfileId(profileId);
    });

</script>
@endpush
