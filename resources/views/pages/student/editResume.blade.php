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

    // ==========================================
    // 1. GLOBAL DATA
    // ==========================================

    let data = {
        education: {
            name: "education",
            data: {
                in: [],
                out: []
            }
        }
    };

    let dataResume = null;
    let educationByUser = [];
    let profileData = null;

    const templates = { modern, professional };

    let currentTemplate = "modern";
    let resumeData = null;
    let zoom = getDefaultZoom();


    // ==========================================
    // 2. API FUNCTIONS
    // ==========================================

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


    // ==========================================
    // 3. EDUCATION HELPERS
    // ==========================================

    function getEducationId(item) {
        return String(
            item.id ??
            item.education_id ??
            item.programme_id ??
            item.programme?.id ??
            ""
        );
    }

    function getEducationName(item) {
        return item.programme?.organization?.company_name
            ?? "Institution";
    }

    function getProgrammeName(item) {
        return item.programme?.programme_name
            ?? "Programme not specified";
    }


    // ==========================================
    // 4. EDUCATION TEMPLATES
    // ==========================================

    function temInn(item) {
        return `
            <div
                class="card d-flex flex-column flex-md-row
                       justify-content-between p-3 mb-2"
                data-id="${getEducationId(item)}"
            >
                <div>
                    <h6>${getEducationName(item)}</h6>
                    <p class="text-muted mb-0">
                        ${getProgrammeName(item)}
                    </p>
                </div>

                <div class="btn-group border border-1 mt-2 mt-md-0">
                    <button
                        type="button"
                        class="btn btn-danger remove"
                        title="Remove education"
                    >−</button>
                </div>
            </div>
        `;
    }

    function temOutt(item) {
        return `
            <div
                class="card d-flex flex-column flex-md-row
                       justify-content-between p-3 mb-2"
                data-id="${getEducationId(item)}"
            >
                <div>
                    <h6>${getEducationName(item)}</h6>
                    <p class="text-muted mb-0">
                        ${getProgrammeName(item)}
                    </p>
                </div>

                <div class="btn-group border border-1 mt-2 mt-md-0">
                    <button
                        type="button"
                        class="btn btn-primary add"
                        title="Add education"
                    >+</button>
                </div>
            </div>
        `;
    }


    // ==========================================
    // 5. RENDER EDUCATION
    // ==========================================

    function templateContent(dataIn = [], dataOut = []) {
        const temIn = dataIn
            .map(item => temInn(item))
            .join("");

        const temOut = dataOut
            .map(item => temOutt(item))
            .join("");

        return `
            <h2>Education</h2>

            <div class="border-bottom border-top py-3 inResume">

                <h4>In Resume (${dataIn.length})</h4>

                ${temIn || `
                    <p class="text-muted">
                        No education added to your resume.
                    </p>
                `}

            </div>

            <div class="mt-3 outResume">

                <h4>Available Education (${dataOut.length})</h4>

                ${temOut || `
                    <p class="text-muted">
                        No more education available.
                    </p>
                `}

            </div>
        `;
    }

    function renderEducation() {
        $("#editor-content").html(
            templateContent(
                data.education.data.in,
                data.education.data.out
            )
        );
    }


    // ==========================================
    // 6. ADD EDUCATION
    // ==========================================

    $(document).on(
        "click",
        "#editor-content .outResume .add",
        function () {

            const id = String(
                $(this).closest("[data-id]").attr("data-id")
            );

            const educationOut = data.education.data.out;

            const index = educationOut.findIndex(
                item => getEducationId(item) === id
            );

            if (index === -1) return;

            const [education] = educationOut.splice(index, 1);

            data.education.data.in.push(education);

            renderEducation();
            updateResumeEducation();

            console.log("Education added:", education);
            console.log("Current data:", data);
        }
    );


    // ==========================================
    // 7. REMOVE EDUCATION
    // ==========================================

    $(document).on(
        "click",
        "#editor-content .inResume .remove",
        function () {

            const id = String(
                $(this).closest("[data-id]").attr("data-id")
            );

            const educationIn = data.education.data.in;

            const index = educationIn.findIndex(
                item => getEducationId(item) === id
            );

            if (index === -1) return;

            const [education] = educationIn.splice(index, 1);

            data.education.data.out.push(education);

            renderEducation();
            updateResumeEducation();

            console.log("Education removed:", education);
            console.log("Current data:", data);
        }
    );


    // ==========================================
    // 8. SYNC EDUCATION TO RESUME PREVIEW
    // ==========================================

    function updateResumeEducation() {
        if (!dataResume) return;

        dataResume.education = [...data.education.data.in];

        if (resumeData) {
            resumeData = {
                ...resumeData,
                education: [...data.education.data.in]
            };

            renderResume();
            applyZoom();
        }
    }


    // ==========================================
    // 9. INITIALIZE EDUCATION DATA
    // ==========================================

    function initializeEducation(allEducation, selectedEducation = []) {

        const selectedIds = new Set(
            selectedEducation.map(getEducationId)
        );

        data.education.data.in = [...selectedEducation];

        data.education.data.out = allEducation.filter(
            item => !selectedIds.has(getEducationId(item))
        );
    }


    // ==========================================
    // 10. LOAD RESUME DATA
    // ==========================================

    $(document).ready(async function () {

        const templateId = new URLSearchParams(
            window.location.search
        ).get("template_id");

        const profileId = "{{ session('user_profile_id') }}";

        try {

            if (templateId) {

                $("#template_id").val(templateId);

                const [
                    resumeResponse,
                    educationResponse
                ] = await Promise.all([
                    getResumeById(templateId),
                    getEducationByUserProfileId(profileId)
                ]);

                dataResume = resumeResponse.data;
                educationByUser = educationResponse.data ?? [];

                // Pendidikan yang telah dipilih dalam resume
                const selectedEducation = Array.isArray(
                    dataResume.education
                ) ? dataResume.education : [];

                initializeEducation(
                    educationByUser,
                    selectedEducation
                );

            } else {

                const [
                    profileResponse,
                    educationResponse
                ] = await Promise.all([
                    getProfileDataByProfileId(profileId),
                    getEducationByUserProfileId(profileId)
                ]);

                profileData = profileResponse.data;
                educationByUser = educationResponse.data ?? [];

                initializeEducation(educationByUser, []);

                dataResume = {
                    profile: profileData,
                    education: []
                };
            }

            renderEducation();

            loadResumeToApp(dataResume);

        } catch (error) {

            console.error(
                "Failed to load resume data:",
                error
            );
        }
    });


    // ==========================================
    // 11. RENDER RESUME
    // ==========================================

    function renderResume() {
        const template = templates[currentTemplate]?.default;

        if (!template || !resumeData) {
            console.error("Template or resume data not found");
            return;
        }

        const templateData = {
            ...resumeData,
            education: data.education.data.in
        };

        let pages;

        if (template.type === "single") {
            const blocks = template.render(templateData);
            const measured = measureBlocks.measureBlocks(blocks);
            const columnPages = paginateColumn.paginateColumn(measured);

            pages = columnPages.map(blocks => ({
                left: blocks,
                right: []
            }));
        } else if (template.type === "columns") {
            const layout = template.render(templateData);
            const left = measureBlocks.measureBlocks(layout.left);
            const right = measureBlocks.measureBlocks(layout.right);

            pages = paginateTwoColumns.paginateTwoColumns(left, right);
        } else {
            console.error("Unsupported template type:", template.type);
            return;
        }
        console.log({pages});

        renderPages.renderPages(pages, template);
    }

    // ==========================================
    // 12. ZOOM
    // ==========================================

    function getDefaultZoom() {
        return window.innerWidth < 992 ? 0.5 : 0.8;
    }

    function applyZoom() {

        const resumePages = document.querySelector("#resumePages");

        if (!resumePages) return;

        resumePages.style.scale = zoom;
        resumePages.style.transformOrigin = "top center";
    }

    $(document).on("click", "#zoomIn", function () {
        zoom = Math.min(zoom + 0.1, 2);
        applyZoom();
    });

    $(document).on("click", "#zoomOut", function () {
        zoom = Math.max(zoom - 0.1, 0.5);
        applyZoom();
    });

    $(window).on("resize", function () {
        zoom = getDefaultZoom();
        applyZoom();
    });


    // ==========================================
    // 13. TEMPLATE SELECTOR
    // ==========================================

    $(document).on("change", "#templateSelect", function () {
        currentTemplate = this.value;
        renderResume();
        applyZoom();
    });


    // ==========================================
    // 14. DOWNLOAD PDF
    // ==========================================

    $(document).on("click", "#downloadResume", async function () {

        const resumePages = document.querySelector("#resumePages");

        if (!resumePages) return;

        const originalScale = resumePages.style.scale;
        const originalTransformOrigin = resumePages.style.transformOrigin;

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
                }).from(resumePages).save();

        } catch (error) {

            console.error("Failed to download resume:", error);

        } finally {

            resumePages.style.scale = originalScale;
            resumePages.style.transformOrigin = originalTransformOrigin;
        }
    });


    // ==========================================
    // 15. EDITOR NAVIGATION
    // ==========================================

    $(document).on("click", ".page-link", function () {

        const content = $(this).data("content");

        if (content === "education") {

            renderEducation();

        } else if (content === "skill") {

            // TODO: Render Skills editor

        }
    });


    // ==========================================
    // 16. RESUME DATA EVENT
    // ==========================================

    function loadResumeToApp(data) {

        window.dispatchEvent(
            new CustomEvent("resumeDataLoaded", {
                detail: data
            })
        );
    }

    window.addEventListener("resumeDataLoaded", function (event) {
        resumeData = event.detail;
        renderResume();
        applyZoom();
    });

</script>

@endsection


<!-- @vite('resources/js/features/resume/app.js') -->
