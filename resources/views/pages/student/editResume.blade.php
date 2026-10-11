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
    let originalEducation = [];
    let originalSkills = [];

    let data = {
        education: {
            name: "education",
            data: {
                in: [],
                out: []
            }
        },
        skill: {
            name: "skill",
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

    const sectionConfig = {
        education: {
            title: "Education",
            data: data.education.data,
            getId: getEducationId,
            renderItem: temInn,
            renderAvailableItem: temOutt,
            update: updateResumeEducation
        },

        skill: {
            title: "Skills",
            data: data.skill.data,
            getId: getSkillId,
            renderItem: temSkillIn,
            renderAvailableItem: temSkillOut,
            update: updateResumeSkill
        }
    };


    $(document).on("click", "#editor-content [data-action]", function () {
        const $item = $(this).closest("[data-id]");
        const sectionKey = $item.attr("data-section");
        const itemId = String($item.attr("data-id"));
        const action = $(this).attr("data-action");

        const config = sectionConfig[sectionKey];

        if (!config) {
            console.error("Unknown section:", sectionKey);
            return;
        }

        if (action !== "add" && action !== "remove") {
            return;
        }

        const sourceList = action === "add" ? config.data.out : config.data.in;
        const targetList = action === "add" ? config.data.in : config.data.out;

        const index = sourceList.findIndex(
            item => config.getId(item) === itemId
        );

        if (index === -1) {
            console.error("Item not found:", {sectionKey,itemId,action});
            return;
        }

        const [item] = sourceList.splice(index, 1);
        targetList.push(item);

        renderSection(sectionKey);
        config.update();

        console.log(`${config.title} ${action}ed:`, item);
    });

    function renderSection(sectionKey) {
        const config = sectionConfig[sectionKey];

        if (!config) {
            console.error("Section not found:", sectionKey);
            return;
        }

        const dataIn = config.data.in;
        const dataOut = config.data.out;

        const temIn = dataIn.map(item => config.renderItem(item)).join("");
        const temOut = dataOut.map(item => config.renderAvailableItem(item)).join("");

        $("#editor-content").html(`
            <h2>${config.title}</h2>
            <div class="border-bottom border-top py-3 inResume">
                <h4>In Resume (${dataIn.length})</h4>
                ${temIn || `<p class="text-muted">No items added to your resume.</p>`}
            </div>

            <div class="mt-3 outResume">
                <h4>Available ${config.title} (${dataOut.length})</h4>
                ${temOut || `<p class="text-muted">No more items available.</p>`}
            </div>`);
        }

    function updateResume(id, payload) {
        const url = "{{ route('resumes.update', ['id' => '__ID__']) }}"
            .replace('__ID__', encodeURIComponent(id));

        return $.ajax({
            url,
            type: "PUT",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(payload),
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
    }

    function getResumeContentId(item) {
        return (
            item.resume_content_id ??
            item.resume_content?.id ??
            item.resumeContent?.id ??
            item.pivot?.id ??
            null
        );
    }

    function prepareResumePayload() {
        const educationChanges = getChanges(
            originalEducation,
            data.education.data.in,
            "education"
        );

        const skillChanges = getChanges(
            originalSkills,
            data.skill.data.in,
            "skill"
        );

        const contentToAdd = [
            ...educationChanges.toAdd.map(item => ({
                source_type: "education",
                source_id: Number(item.education_id ?? item.id ?? item.programme_id)
            })),

            ...skillChanges.toAdd.map(item => ({
                source_type: "user_skill",
                source_id: Number(item.pivot.id)
            }))
        ];

        const removedItems = [
            ...educationChanges.toRemove,
            ...skillChanges.toRemove
        ];

        const contentIdsToDelete = removedItems
            .map(item => getResumeContentId(item))
            .filter(id => id !== null && id !== undefined)
            .map(Number);

        console.log("Education removed:", educationChanges.toRemove);
        console.log("Skills removed:", skillChanges.toRemove);

        console.table(
            removedItems.map(item => ({
                source_id: item.id,
                resume_content_id: item.resume_content_id,
                pivot_id: item.pivot?.id,
                delete_id: getResumeContentId(item)
            }))
        );

        if (removedItems.length > 0 && contentIdsToDelete.length !== removedItems.length) {
            console.error("Missing resume content IDs:", removedItems);

            throw new Error(
                "Missing resume content IDs. Check the API response."
            );
        }

        const payload = {};

        if (contentToAdd.length > 0) {
            payload.content_to_add = contentToAdd;
        }

        if (contentIdsToDelete.length > 0) {
            payload.content_ids_to_delete = contentIdsToDelete;
        }

        return payload;
    }

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
        return item.programme?.organization?.company_name ?? "Institution";
    }

    function getProgrammeName(item) {
        return item.programme?.programme_name ?? "Programme not specified";
    }

    function temInn(item) {
        return `
        <div class="card d-flex flex-column flex-md-row
                    justify-content-between p-3 mb-2"
             data-id="${getEducationId(item)}"
             data-section="education">

            <div>
                <h6>${getEducationName(item)}</h6>
                <p class="text-muted mb-0">
                    ${getProgrammeName(item)}
                </p>
            </div>

            <button type="button"
                    class="btn btn-danger"
                    data-action="remove">
                −
            </button>
        </div>
    `;
    }

    function temOutt(item) {
        return `
        <div class="card d-flex flex-column flex-md-row
                    justify-content-between p-3 mb-2"
             data-id="${getEducationId(item)}"
             data-section="education">

            <div>
                <h6>${getEducationName(item)}</h6>
                <p class="text-muted mb-0">
                    ${getProgrammeName(item)}
                </p>
            </div>

            <button type="button"
                    class="btn btn-primary"
                    data-action="add">
                +
            </button>
        </div>
    `;
    }

    function renderEducation() {
        let dataIn = data.education.data.in
        let dataOut = data.education.data.out

        const temIn = dataIn.map(item => temInn(item)).join("");
        const temOut = dataOut.map(item => temOutt(item)).join("");

        $("#editor-content").html(`
            <h2>Education</h2>
            <div class="border-bottom border-top py-3 inResume">
                <h4>In Resume (${dataIn.length})</h4>
                ${temIn || `<p class="text-muted">No education added to your resume.</p>`}
            </div>

            <div class="mt-3 outResume">
                <h4>Available Education (${dataOut.length})</h4>
                ${temOut || `<p class="text-muted">No more education available.</p>`}
            </div>`);
    }

    function temSkillIn(item) {
        const name = item.skill?.skill_name ?? item.skill_name ?? "";
        return `
            <div class="card d-flex flex-row
                        justify-content-between p-3 mb-2"
                data-id="${getSkillId(item)}"
                data-section="skill">

                <h6 class="mb-0">${name}</h6>

                <button type="button"
                        class="btn btn-danger"
                        data-action="remove">
                    −
                </button>
            </div>`;
    }

    function temSkillOut(item) {
        const name = item.skill?.skill_name ?? item.skill_name ?? "";

        return `
            <div class="card d-flex flex-row
                        justify-content-between p-3 mb-2"
                data-id="${getSkillId(item)}"
                data-section="skill">

                <h6 class="mb-0">${name}</h6>

                <button type="button"
                        class="btn btn-primary"
                        data-action="add">
                    +
                </button>
            </div>`;
    }

    function renderSkill() {
        const dataIn = data.skill.data.in;
        const dataOut = data.skill.data.out;

        $("#editor-content").html(`
            <h2>Skills</h2>

            <div class="border-bottom border-top py-3 inResume">
                <h4>In Resume (${dataIn.length})</h4>

                ${dataIn.map(temSkillIn).join("") ||
                '<p class="text-muted">No skills added to your resume.</p>'}
            </div>

            <div class="mt-3 outResume">
                <h4>Available Skills (${dataOut.length})</h4>

                ${dataOut.map(temSkillOut).join("") ||
                '<p class="text-muted">No more skills available.</p>'}
            </div>`);
    }

    $(document).on("click","#editor-content .add-skill", function () {
        const id = String($(this).closest("[data-id]").attr("data-id"));
        const list = data.skill.data.out;
        const index = list.findIndex(
            item => getSkillId(item) === id
        );

        if (index === -1) return;

        const [skill] = list.splice(index, 1);
        data.skill.data.in.push(skill);

        renderSkill();
        updateResumeSkill();
    });

    $(document).on("click","#editor-content .remove-skill",function () {
        const id = String(
            $(this).closest("[data-id]").attr("data-id")
        );

        const list = data.skill.data.in;
        const index = list.findIndex(
            item => getSkillId(item) === id
        );

        if (index === -1) return;

        const [skill] = list.splice(index, 1);
        data.skill.data.out.push(skill);

        renderSkill();
        updateResumeSkill();
    });

    function updateResumeSkill() {
        if (!dataResume) return;

        dataResume.skills = [...data.skill.data.in];

        if (resumeData) {
            resumeData = {
                ...resumeData,
                skills: [...data.skill.data.in]
            };

            renderResume();
            applyZoom();
        }
    }

    $(document).on("click","#editor-content .outResume .add",function () {
        const id = String($(this).closest("[data-id]").attr("data-id"));
        const educationOut = data.education.data.out;
        const index = educationOut.findIndex(item => getEducationId(item) === id);

        if (index === -1) return;

        const [education] = educationOut.splice(index, 1);

        data.education.data.in.push(education);

        renderEducation();
        updateResumeEducation();

        console.log("Education added:", education);
        console.log("Current data:", data);
    });

    $(document).on("click","#editor-content .inResume .remove", function () {
        const id = String($(this).closest("[data-id]").attr("data-id"));
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
    });

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

    function initializeEducation(allEducation, selectedEducation = []) {
        const selectedIds = new Set(
            selectedEducation.map(getEducationId)
        );

        data.education.data.in = [...selectedEducation];
        originalEducation = structuredClone(data.education.data.in);

        data.education.data.out = allEducation.filter(
            item => !selectedIds.has(getEducationId(item))
        );
    }

    function getSkillId(item) {
        return String(
            item.skill_id ??
            item.skill?.id ??
            item.id ??
            item.name ??
            item
        );
    }

    function initializeSkill(allSkill = [], selectedSkill = []) {
        const selectedIds = new Set(
            selectedSkill.map(getSkillId)
        );

        data.skill.data.in = [...selectedSkill];
        originalSkills = structuredClone(data.skill.data.in);

        data.skill.data.out = allSkill.filter(
            item => !selectedIds.has(getSkillId(item))
        );
    }

    function getSourceId(item, type) {
        if (type === "education") {
            return String(
                item.education_id ??
                item.id ??
                item.programme_id ??
                item.programme?.id ??
                ""
            );
        }

        return String(
            item.skill_id ??
            item.id ??
            item.name ??
            item
        );
    }

    function getChanges(original, current, type) {
        const originalMap = new Map(
            original.map(item => [getSourceId(item, type), item])
        );

        const currentMap = new Map(
            current.map(item => [getSourceId(item, type), item])
        );

        return {
            toAdd: current.filter(
                item => !originalMap.has(getSourceId(item, type))
            ),

            toRemove: original.filter(
                item => !currentMap.has(getSourceId(item, type))
            ),

            unchanged: current.filter(
                item => originalMap.has(getSourceId(item, type))
            )
        };
    }

    function prepareResumeChanges() {
        const educationChanges = getChanges(
            originalEducation,
            data.education.data.in
        );

        const skillChanges = getChanges(
            originalSkills,
            data.skill.data.in
        );

        console.log("Education:", educationChanges);
        console.log("Skills:", skillChanges);

        return {
            educationToAdd: educationChanges.toAdd,
            skillsToAdd: skillChanges.toAdd,
            educationToRemove: educationChanges.toRemove,
            skillsToRemove: skillChanges.toRemove,
            unchangedEducation: educationChanges.unchanged,
            unchangedSkills: skillChanges.unchanged
        };
    }

    function createResume(payload) {
        const url = "{{ route('resumes.store') }}";

        return $.ajax({
            url: url,
            type: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(payload),
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
    }

    function prepareCreateResumePayload() {
        const contentToAdd = [];

        for (const [sectionKey, config] of Object.entries(sectionConfig)) {
            for (const item of config.data.in) {
                let sourceId;

                if (sectionKey === "education") {
                    sourceId =
                        item.education_id ??
                        item.programme_id ??
                        item.programme?.id ??
                        item.id;
                } else if (sectionKey === "skill") {
                    sourceId = item.pivot?.id;
                } else {
                    console.warn(
                        "Source ID mapping not configured:",
                        sectionKey
                    );
                    continue;
                }

                if (sourceId == null || !Number.isInteger(Number(sourceId))) {
                    throw new Error(
                        `Invalid source ID for ${sectionKey}: ${sourceId}`
                    );
                }

                contentToAdd.push({
                    source_type:
                        sectionKey === "education"
                            ? "education"
                            : "user_skill",
                    source_id: Number(sourceId)
                });
            }
        }

        // Nilai ini mesti ID sebenar dalam jadual resume_templates.
        const selectedOption = $("#templateSelect option:selected");
        const templateId = Number(selectedOption.attr("data-template-id"));

        if (!Number.isInteger(templateId) || templateId <= 0) {
            throw new Error(
                "Please select a valid resume template database ID."
            );
        }

        if (contentToAdd.length === 0) {
            throw new Error(
                "Please add at least one item to your resume."
            );
        }

        return {
            resume_template_id: templateId,
            content_to_add: contentToAdd
        };
    }

    $(document).on("click", "#saveResume", async function () {
        const $button = $(this);
        const resumeId = new URLSearchParams(window.location.search).get("id");
        let payload;

        try {
            payload = resumeId ? prepareResumePayload() : prepareCreateResumePayload();

            if (Object.keys(payload).length === 0) {
                console.log("No changes to save.");
                return;
            }

        } catch (error) {
            console.error("Invalid resume payload:", error);
            alert(error.message);
            return;
        }

        console.log("Resume payload:", payload);

        $button.prop("disabled", true);

        try {
            let response;

            if (resumeId) {
                // Resume sedia ada
                response = await updateResume(resumeId, payload);

                originalEducation = structuredClone(
                    data.education.data.in
                );

                originalSkills = structuredClone(
                    data.skill.data.in
                );

                alert("Resume updated successfully.");

            } else {
                // Resume baharu
                response = await createResume(payload);

                console.log("Resume created:", response);

                alert("Resume created successfully.");

                // Jika API memulangkan ID resume, gunakan ID itu
                // untuk membuka resume yang baru dicipta.
                const createdResume =
                    response.data?.data ?? response.data;

                const newResumeId =
                    createdResume?.id ?? createdResume?.resume?.id;

                if (newResumeId != null) {
                    window.location.href =
                        window.location.pathname +
                        "?id=" +
                        encodeURIComponent(newResumeId);
                }
            }
        } catch (error) {
            console.error(
                "Failed to save resume:",
                error.responseJSON ?? error
            );

            const message =
                error.responseJSON?.message ??
                "Failed to save resume. Check the console.";

            alert(message);
        } finally {
            $button.prop("disabled", false);
        }
    });

    $(document).ready(async function () {
        const id = new URLSearchParams(window.location.search).get("id");
        const profileId = "{{ session('user_profile_id') }}";

        try {
            if (id) {
                $("#template_id").val(id);

                const [
                    resumeResponse,
                    educationResponse,
                    profileResponse
                ] = await Promise.all([
                    getResumeById(id),
                    getEducationByUserProfileId(profileId),
                    getProfileDataByProfileId(profileId)
                ]);

                dataResume = resumeResponse.data;
                educationByUser = educationResponse.data ?? [];
                profileData = profileResponse.data;

                const selectedEducation = Array.isArray(dataResume.education) ? dataResume.education : [];
                const selectedSkills = Array.isArray(dataResume.user_skills) ? dataResume.user_skills : [];
                const allSkills = Array.isArray(profileData.skills) ? profileData.skills : [];

                initializeEducation(educationByUser, selectedEducation);
                initializeSkill(allSkills, selectedSkills);

                dataResume = {
                    ...dataResume,
                    profile: dataResume.profile ?? profileData,
                    education: [...data.education.data.in],
                    skills: [...data.skill.data.in]
                };

            } else {
                const [profileResponse, educationResponse] = await Promise.all([
                    getProfileDataByProfileId(profileId),
                    getEducationByUserProfileId(profileId)
                ]);

                profileData = profileResponse.data;
                educationByUser = educationResponse.data ?? [];

                const allSkills = Array.isArray(profileData.skills)
                    ? profileData.skills
                    : [];

                initializeEducation(educationByUser, []);
                initializeSkill(allSkills, []);

                dataResume = {
                    profile: profileData,
                    education: [],
                    skills: []
                };
            }

            renderSection("education");
            loadResumeToApp(dataResume);


        } catch (error) {
            console.error("Failed to load resume data:",error);
        }
    });

    function renderResume() {
        const template = templates[currentTemplate]?.default;

        if (!template || !resumeData) {
            console.error("Template or resume data not found");
            return;
        }

        const templateData = {
            ...resumeData,
            education: data.education.data.in,
            skills: data.skill.data.in
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

    $(document).on("change", "#templateSelect", function () {
        currentTemplate = this.value;
        renderResume();
        applyZoom();
    });

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

    $(document).on("click", ".page-link", function () {
        const sectionKey = $(this).data("content");

        if (sectionConfig[sectionKey]) {
            renderSection(sectionKey);
        }
    });

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
