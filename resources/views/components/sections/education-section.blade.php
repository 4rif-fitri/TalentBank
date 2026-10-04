@section('css')
<style>
    .image {
        background-position: center;
        background-size: cover;
    }

    #education,
    #semesterResults {
        overflow: hidden;
    }

    #educationsContainer,
    #semesterResultList {
        width: 100%;
        overflow: hidden;
    }

    #educationsContainer .carousel-track,
    #semesterResultList .carousel-track {
        display: flex;
        transition: transform 0.4s ease;
    }

    #educationsContainer .carousel-track .item,
    #semesterResultList .carousel-track .item {
        min-width: 50%;
        flex: 0 0 50%;
        padding: 0 8px;
        box-sizing: border-box;
    }

    @media (max-width: 1200px) {
        #educationsContainer .carousel-track .item,
        #semesterResultList .carousel-track .item {
            min-width: 100%;
            flex: 0 0 100%;
        }
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
    <div id="educationsContainer">

        <div class="carousel-wrapper">

            <div class="carousel-track">
            </div>

            <div class="carousel-controls">

                <button type="button" class="carousel-prev">
                    <i class="fa-solid fa-chevron-left"></i>
                    Back
                </button>

                <button type="button" class="carousel-next">
                    Next
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

            </div>

        </div>

    </div>

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

    function getEducationItemsPerView() {

        return window.innerWidth <= 1200 ? 1 : 2;
    }


    function updateEducationCarousel() {
        const $track = $("#educationsContainer .carousel-track");

        const totalItems = $track.find(".item").length;
        const itemsPerView = window.innerWidth <= 1200 ? 1 : 2;

        const maxIndex = Math.max(0, totalItems - itemsPerView);

        if (currentEducation > maxIndex) {
            currentEducation = maxIndex;
        }

        $track.css(
            "transform",
            `translateX(-${currentEducation * (100 / itemsPerView)}%)`
        );

        $track.data("current", currentEducation);
    }

    function renderEducationList(educations) {

        const $container = $("#educationsContainer");
        const $track = $container.find(".carousel-track");

        if (!educations || educations.length === 0) {

            $track.html(
                xeducation.student.emptyEducation()
            );

            return;
        }

        let htmlEducation = "";

        educations.forEach(education => {

            let htmlImage = image(
                education.media ?? [],
                education.id
            );

            htmlEducation += `
            <div class="item">
                ${xeducation.student.educationCard(
                education,
                htmlImage
            )}
            </div>
        `;
        });

        $track.html(htmlEducation);

        $track.data("current", 0);

        updateEducationCarousel();
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

    function handleScreenSize() {
        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function () {

            // Reset scroll count
            currentEducation = 0;

            // Reset carousel position
            const $track = $("#educationsContainer .carousel-track");
            $track.data("current", 0);

            updateEducationCarousel();

        }, 100);
    }

    $(window).on("resize", handleScreenSize);

    $(window).on("resize", handleScreenSize);

    $(window).on("resize", handleScreenSize);

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

    $(document).on(
        "click",
        "#educationsContainer .carousel-next",
        function () {

            const $track = $("#educationsContainer .carousel-track");

            const totalItems = $track.find(".item").length;
            const itemsPerView = window.innerWidth <= 1200 ? 1 : 2;

            const maxIndex = Math.max(0, totalItems - itemsPerView);

            if (currentEducation < maxIndex) {
                currentEducation++;
            }

            updateEducationCarousel();
        }
    );
    $(document).on(
        "click",
        "#educationsContainer .carousel-prev",
        function () {

            if (currentEducation > 0) {
                currentEducation--;
            }

            updateEducationCarousel();
        }
    );
</script>
@endpush
