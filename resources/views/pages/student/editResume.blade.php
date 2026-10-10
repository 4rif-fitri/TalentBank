@extends('layouts.internship-layouts')
    <style>
        .asd{
            width: 600px;
        }
        @media screen and (max-width:1300px) {
            .asd{
                width: 400px;
            }
        }
        @media screen and (max-width:1000px) {
            .asd{
                width: 300px;
            }
        }
        @media screen and (max-width:760px) {
            .asd{
                width: 100%;
            }
        }
    </style>
@section('css')
@endsection

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/base.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/modern.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/professional.css') }}">

<div class="">

    <!-- <section class="page-header">
        <div class="page-heading">
            <h1>Edit Resume</h1>
        </div>
    </section> -->

    <input type="hidden" id="template_id">

    <div class="d-flex flex-md-row flex-column bg-body m-2" style="display: flex; flex-grow: 1;">
        <div class="border border-1 asd" style="height: 92vh;">
            <x-sections.resume-editor />
        </div>

        <div class="workspace w-100 " style="height: 92vh !important;">
            <x-sections.resume-setting />

            <div id="resumePages"></div>
        </div>
    </div>

</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script type="module">

    let dataResume, educationByUser, profileData

    function getEducationByUserProfileId(id) {

        let url = "{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}";

        url = url.replace('__ID__', id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }
    function getProfileDataByProfileId(id) {

        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";

        url = url.replace('__ID__', id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }
    function getResumeById(id) {

        let url = "{{ route('resumes.getResumeById', ['id' => '__ID__']) }}";

        url = url.replace('__ID__', id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }


    function templateContent(dataIn = [], dataOut = []) {
        let temIn = dataIn.map(data => `
            <div class="card d-flex flex-column flex-md-row justify-content-between p-3 mb-2">
                <div>
                    ${data.institution ?? "Lorem ipsum dolor sit amet"}
                </div>

                <div class="btn-group border border-1">
                    <button class="btn btn-danger">-</button>
                    <button class="btn btn-primary">+</button>
                </div>
            </div>`).join("");

            return `
                <h2>Education</h2>
                <div class="border-bottom border-top py-3">
                    <h4>In Resume</h4>
                    ${temIn}
                </div>
                <div class="mt-3">
                    <h4>Available Education</h4>

                    ${dataOut.map(data => `
                        <div class="card d-flex flex-column flex-md-row justify-content-between p-3 mb-2">
                            <div>
                                ${data.institution ?? "Lorem ipsum dolor sit amet"}
                            </div>

                            <div class="btn-group border border-1">
                                <button class="btn btn-danger">-</button>
                                <button class="btn btn-primary">+</button>
                            </div>
                        </div>`).join("")}
                </div>`;
        }


    function renderEducation(educations){
        $("#editor-content").html(templateContent(educations))
    }

    $(document).ready(async function () {
        const templateId = new URLSearchParams(window.location.search).get('template_id');
        const profileId = "{{ session('user_profile_id') }}";

        if (templateId) {
            $('#template_id').val(templateId);

            try {
                const response = await getResumeById(templateId);
                dataResume = response.data;

                loadResumeToApp(dataResume);

            } catch (error) {
                console.error("Failed to load resume:",error);
            }

        }

        else {

            try {
                const [profileResponse,educationResponse] = await Promise.all([
                    getProfileDataByProfileId(profileId),
                    getEducationByUserProfileId(profileId)
                ]);

                profileData = profileResponse.data;
                educationByUser = educationResponse.data;

                dataResume = {
                    profile: profileData,
                    education: educationByUser
                };

                // console.log(dataResume);
                renderEducation(educationByUser)
                loadResumeToApp(dataResume);

            } catch (error) {
                console.error("Failed to load resume data:",error);
            }
        }
    });

    const templates = { modern, professional };
    let currentTemplate = "modern";
    let resumeData = null;
    let zoom = getDefaultZoom();

    function getDefaultZoom() {
        return window.innerWidth < 992 ? 0.5 : 0.8;
    }

    function renderResume() {

        const template = templates[currentTemplate]?.default;

        if (!template) {
            console.error("Template not found:", currentTemplate);
            return;
        }

        let pages;

        if (template.type === "single") {

            const blocks = template.render(resumeData);
            const measured = measureBlocks.measureBlocks(blocks);
            const columnPages = paginateColumn.paginateColumn(measured);

            pages = columnPages.map(blocks => ({
                left: blocks,
                right: []
            }));
        }

        else if (template.type === "columns") {

            const layout = template.render(resumeData);
            const left = measureBlocks.measureBlocks(layout.left);
            const right = measureBlocks.measureBlocks(layout.right);
            pages = paginateTwoColumns.paginateTwoColumns(left, right);
        }

        // console.log(pages);
        renderPages.renderPages(pages, template);
    }

    document.addEventListener("DOMContentLoaded", function () {

        $("#zoomIn").on("click", function () {
            zoom += 0.1;
            if (zoom > 2) zoom = 2;
            applyZoom();
        });

        $("#zoomOut").on("click", function () {
            zoom -= 0.1;
            if (zoom < 0.5) zoom = 0.5;
            applyZoom();
        });

        $("#templateSelect").on("change", function () {
            currentTemplate = this.value;
            renderResume();
        });

        $("#downloadResume").on("click", async function () {

            const resumePages = document.querySelector("#resumePages");

            const originalZoom = zoom;

            resumePages.style.scale = "1";
            resumePages.style.transformOrigin = "top center";

            try {

                await html2pdf()
                    .set({
                        margin: 0,
                        filename: "resume.pdf",
                        image: {
                            type: "jpeg",
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            useCORS: true
                        },
                        jsPDF: {
                            unit: "mm",
                            format: "a4",
                            orientation: "portrait"
                        }
                    })
                    .from(resumePages)
                    .save();

            } finally {
                resumePages.style.scale = originalZoom;
                resumePages.style.transformOrigin = "top center";
            }
        });
    });

    function loadResumeToApp(data) {
        window.dispatchEvent(new CustomEvent('resumeDataLoaded', { detail: data }));
    }

    function applyZoom() {
        const resumePages = document.querySelector("#resumePages");
        if (!resumePages) return;

        resumePages.style.scale = zoom;
        resumePages.style.transformOrigin = "top center";
    }

    $(window).on("resize", function () {
        zoom = getDefaultZoom();
        applyZoom();
    });

    window.addEventListener("resumeDataLoaded", function (event) {
        resumeData = event.detail;
        renderResume();
        applyZoom();
    });
</script>

<!-- @vite('resources/js/features/resume/app.js') -->

@endsection
