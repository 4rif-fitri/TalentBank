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

.social-media-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
    overflow-wrap: anywhere;
}

.social-media-name {
    font-weight: 600;
}

.social-media-url {
    color: inherit;
    text-decoration: none;
    font-size: 0.9rem;
}

.social-media-url:hover {
    text-decoration: underline;
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
    let originalLanguage = [];
    let originalSocialMedia = [];

    let dataResume = null;
    let educationByUser = [];
    let profileData = null;

    const templates = { modern, professional };

    let currentTemplate = "professional";
    let resumeData = null;
    let zoom = getDefaultZoom();

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
        },
        language: {
            name: "language",
            data: {
                in: [],
                out: []
            }
        },
        socialMedia: {
            name: "socialMedia",
            data: {
                in: [],
                out: []
            }
        },
    };

    const sectionConfig = {
        education: {
            title: "Education",
            data: data.education.data,
            getId: getEducationId,
            renderItem: item => xresume.education.inResume(item, getEducationId),
            renderAvailableItem: item => xresume.education.outResume(item, getEducationId),
            update: updateResumeEducation
        },

        skill: {
            title: "Skills",
            data: data.skill.data,
            getId: getSkillId,
            renderItem: item => xresume.skill.inResume(item, getSkillId),
            renderAvailableItem: item =>  xresume.skill.outResume(item, getSkillId),
            update: updateResumeSkill
        },

        language: {
            title: "Language",
            data: data.language.data,
            getId: getLanguageId,
            renderItem: item => xresume.language.inResume(item, getLanguageId),
            renderAvailableItem: item => xresume.language.outResume(item, getLanguageId),
            update: updateResumeLanguage
        },

        socialMedia: {
            title: "Social Media",
            data: data.socialMedia.data,
            getId: getSocialMediaId,
            renderItem: item =>
                xresume.socialMedia.inResume(item, getSocialMediaId),
            renderAvailableItem: item =>
                xresume.socialMedia.outResume(item, getSocialMediaId),
            update: updateResumeSocialMedia
        }
    };

    // update
    function updateResumeSocialMedia() {
        if (!dataResume) return;

        dataResume.social_media_links = [
            ...data.socialMedia.data.in
        ];

        if (resumeData) {
            resumeData = {
                ...resumeData,
                social_media_links: [...data.socialMedia.data.in]
            };

            renderResume();
            applyZoom();
        }
    }

    function updateResumeLanguage() {
        if (!dataResume) return;

        dataResume.language = [...data.language.data.in];

        if (resumeData) {
            resumeData = {
                ...resumeData,
                language: [...data.language.data.in]
            };

            renderResume();
            applyZoom();
        }
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
    // update

    // Getter API
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
    // Getter API

    // initialize
    function initializeSocialMedia(allSocialMedia = [], selectedSocialMedia = []) {
        const selectedIds = new Set(
            selectedSocialMedia.map(getSocialMediaId)
        );

        data.socialMedia.data.in = [...selectedSocialMedia];

        originalSocialMedia = structuredClone(
            data.socialMedia.data.in
        );

        data.socialMedia.data.out = allSocialMedia.filter(
            item => !selectedIds.has(getSocialMediaId(item))
        );
    }

    function initializeLanguage(allLanguage = [], selectedLanguage = []) {
        const getId = getLanguageId;

        const selectedIds = new Set(
            selectedLanguage.map(getId)
        );

        data.language.data.in = [...selectedLanguage];

        originalLanguage = structuredClone(
            data.language.data.in
        );

        data.language.data.out = allLanguage.filter(
            item => !selectedIds.has(getId(item))
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
    // initialize

    // utils
    function getEducationId(item) {
        return String(
            item.id ??
            item.education_id ??
            item.programme_id ??
            item.programme?.id ??
            ""
        );
    }

    function getSocialMediaId(item) {
        return String(
            item.user_social_media_id ??
            item.id ??
            ""
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

        if (type === "language") {
            return String(
                item.user_language_id ??
                item.id ??
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

    function getSkillId(item) {
        return String(
            item.skill_id ??
            item.skill?.id ??
            item.id ??
            item.name ??
            item
        );
    }

    function getLanguageId(item) {
        return String(
            item.user_language_id ??
            item.language_id ??
            item.language?.id ??
            item.id ??
            ""
        );
    }

    function getResumeContentId(item) {
        return (
            item.resume_content_id ??
            item.pivot?.id ??
            null
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
    // utils

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

    // payload
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

        const languageChanges = getChanges(
            originalLanguage,
            data.language.data.in,
            "language"
        );

        const socialMediaChanges = getChanges(
            originalSocialMedia,
            data.socialMedia.data.in,
            "socialMedia"
        );

        const contentToAdd = [
            ...educationChanges.toAdd.map(item => ({
                source_type: "education",
                source_id: Number(
                    item.education_id ??
                    item.id ??
                    item.programme_id
                )
            })),

            ...skillChanges.toAdd.map(item => ({
                source_type: "user_skill",
                source_id: Number(item.pivot.id)
            })),

            ...languageChanges.toAdd.map(item => ({
                source_type: "user_language",
                source_id: Number(item.user_language_id ?? item.id)
            })),

            ...socialMediaChanges.toAdd.map(item => ({
                source_type: "social_media_link",
                source_id: Number(item.user_social_media_id ?? item.id)
            }))
        ];

        const removedItems = [
            ...educationChanges.toRemove,
            ...skillChanges.toRemove,
            ...languageChanges.toRemove,
            ...socialMediaChanges.toRemove
        ];

        const contentIdsToDelete = removedItems
            .map(item => getResumeContentId(item))
            .filter(id => id !== null && id !== undefined)
            .map(Number);

        console.log("Education removed:", educationChanges.toRemove);
        console.log("Skills removed:", skillChanges.toRemove);

        console.log("Removed items:", removedItems);

        console.table(
            removedItems.map(item => ({
                source_id: item.id,
                resume_id: item.pivot?.resume_id,
                pivot_source_id: item.pivot?.source_id,
                pivot_source_type: item.pivot?.source_type,
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
        console.log("Original Education:", originalEducation);
        console.log("Current Education:", data.education.data.in);

        console.log(
            "Original IDs:",
            originalEducation.map(item => getSourceId(item, "education"))
        );

        console.log("Current IDs:", data.education.data.in.map(item => getSourceId(item, "education")));
        return payload;
    }

    function prepareCreateResumePayload() {
        const contentToAdd = [];

        for (const [sectionKey, config] of Object.entries(sectionConfig)) {
            for (const item of config.data.in) {
                let sourceId;

                if (sectionKey === "education") {
                    sourceId = item.id;
                } else if (sectionKey === "skill") {
                    sourceId = item.pivot?.id;
                } else if (sectionKey === "language") {
                    sourceId =item.user_language_id ?? item.id;
                } else {
                    console.warn("Source ID mapping not configured:", sectionKey);
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
                            : sectionKey === "skill"
                                ? "user_skill"
                                : "user_language",

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
            throw new Error("Please add at least one item to your resume");
        }

        return {
            resume_template_id: templateId,
            content_to_add: contentToAdd
        };
    }
    // payload

    // render ===
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

    function renderResume() {
        const template = templates[currentTemplate]?.default;

        if (!template || !resumeData) {
            console.error("Template or resume data not found");
            return;
        }

        const templateData = {
            ...resumeData,
            education: data.education.data.in,
            skills: data.skill.data.in,
            language: data.language.data.in
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

    function renderSkill() {
        const dataIn = data.skill.data.in;
        const dataOut = data.skill.data.out;

        $("#editor-content").html(`
        <h2>Skills</h2>

        <div class="border-bottom border-top py-3 inResume">
            <h4>In Resume (${dataIn.length})</h4>

            ${dataIn.map(item => xresume.skill.inResume(item, getSkillId)).join("") ||
            '<p class="text-muted">No skills added to your resume.</p>'}
        </div>

        <div class="mt-3 outResume">
            <h4>Available Skills (${dataOut.length})</h4>

            ${dataOut.map(item => xresume.skill.inResume(item, getSkillId)).join("") ||
            '<p class="text-muted">No more skills available.</p>'}
        </div>`);
    }

    function renderEducation() {
        let dataIn = data.education.data.in
        let dataOut = data.education.data.out

        const temIn = dataIn.map(item => xresume.education.inResume(item, getEducationId)).join("");
        const temOut = dataOut.map(item => xresume.education.OutResume(item, getEducationId)(item)).join("");

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
    // render ===

    function getDefaultZoom() {
        return window.innerWidth < 992 ? 0.5 : 0.8;
    }

    function applyZoom() {

        const resumePages = document.querySelector("#resumePages");

        if (!resumePages) return;

        resumePages.style.scale = zoom;
        resumePages.style.transformOrigin = "top center";
    }

    function loadResumeToApp(data) {
        window.dispatchEvent(
            new CustomEvent("resumeDataLoaded", {
                detail: data
            })
        );
    }

    $(document).on("click", "#saveResume", async function () {
        const $button = $(this);
        const resumeId = new URLSearchParams(window.location.search).get("id");
        let payload;

        try {
            payload = resumeId ? prepareResumePayload() : prepareCreateResumePayload();

            if (Object.keys(payload).length === 0) {
                xalert.warning("warning","No changes to save",)
                return;
            }

        } catch (error) {
            xalert.warning("Warning", error.message)
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

                xalert.success("Success", "Resume updated successfully.");

            } else {
                // Resume baharu
                response = await createResume(payload);

                console.log("Resume created:", response);
                xalert.success("Success","Resume created successfully.");

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
             window.location.href = "{{ route('student.resume') }}";

        }
    });

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
                        quality: 1
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
            console.error("Item not found:", { sectionKey, itemId, action });
            return;
        }

        const [item] = sourceList.splice(index, 1);
        targetList.push(item);

        renderSection(sectionKey);
        config.update();

        console.log(`${config.title} ${action}ed:`, item);
    });

    $(document).on("click", "#editor-content .outResume .add", function () {
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

    $(document).on("click", "#editor-content .add-skill", function () {
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

    $(document).on("click", "#editor-content .remove-skill", function () {
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

    $(document).on("click", "#editor-content .inResume .remove", function () {
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

                console.log("Profile Data:", profileData);
                console.log("Resume Data:", dataResume);

                // EDUCATION
                const selectedEducation = Array.isArray(dataResume.education)
                    ? dataResume.education
                    : [];

                // SKILL
                const selectedSkills = Array.isArray(dataResume.user_skills)
                    ? dataResume.user_skills
                    : [];

                const allSkills = Array.isArray(profileData.skills)
                    ? profileData.skills
                    : [];

                // LANGUAGE
                const allLanguage = Array.isArray(profileData.user_languages)
                    ? profileData.user_languages
                    : [];

                const selectedLanguage = Array.isArray(dataResume.user_languages)
                    ? dataResume.user_languages
                    : [];

                // SOCIAL MEDIA
                const allSocialMedia = Array.isArray(profileData.social_media_links)
                    ? profileData.social_media_links
                    : [];

                // Tukar property ini jika API resume menggunakan nama lain.
                const selectedSocialMedia = Array.isArray(dataResume.social_media_links)
                    ? dataResume.social_media_links
                    : [];

                // INITIALIZE SEMUA SECTION
                initializeEducation(educationByUser, selectedEducation);
                initializeSkill(allSkills, selectedSkills);
                initializeLanguage(allLanguage, selectedLanguage);
                initializeSocialMedia(allSocialMedia, selectedSocialMedia);

                // UPDATE DATA RESUME
                dataResume = {
                    ...dataResume,
                    profile: dataResume.profile ?? profileData,
                    education: [...data.education.data.in],
                    skills: [...data.skill.data.in],
                    language: [...data.language.data.in],
                    social_media_links: [...data.socialMedia.data.in]
                };

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

                const allSkills = Array.isArray(profileData.skills)
                    ? profileData.skills
                    : [];

                const allLanguage = Array.isArray(profileData.user_languages)
                    ? profileData.user_languages
                    : [];

                const allSocialMedia = Array.isArray(profileData.social_media_links)
                    ? profileData.social_media_links
                    : [];

                initializeEducation(educationByUser, []);
                initializeSkill(allSkills, []);
                initializeLanguage(allLanguage, []);
                initializeSocialMedia(allSocialMedia, []);

                dataResume = {
                    profile: profileData,
                    education: [],
                    skills: [],
                    language: [],
                    social_media_links: []
                };
            }

            renderSection("education");
            loadResumeToApp(dataResume);

        } catch (error) {
            console.error("Failed to load resume data:", error);
        }
    });

    window.addEventListener("resumeDataLoaded", function (event) {
        resumeData = event.detail;
        renderResume();
        applyZoom();
    });

</script>

@endsection
