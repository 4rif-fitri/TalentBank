<style>
    #semesterResultList .owl-stage {
        display: flex;
    }

    #semesterResultList .owl-item {
        display: flex;
    }

    #semesterResultList .item {
        width: 100%;
    }

    #semesterResultList .semester-programme-group {
        height: 100%;
    }

     #semesterResultList {
        position: relative;
    }

    #semesterResultList .owl-nav {
        display: flex;
        align-items: center;
        position: absolute;
        width: 100%;
        gap: .5em;
        justify-content: center;
        transform: translateY(-50%);
        pointer-events: none;
    }

    #semesterResultList .owl-nav button.owl-prev,
    #semesterResultList .owl-nav button.owl-next {
        pointer-events: auto;

        width: 40px;
        height: 40px;

        border-radius: 50%;
        border: 1px solid #dee2e6 !important;

        background: #fff !important;
        color: #0d6efd !important;

        display: flex;
        align-items: center;
        justify-content: center;

        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);

        transition: all 0.2s ease;
    }

    #semesterResultList .owl-nav button.owl-prev:hover,
    #semesterResultList .owl-nav button.owl-next:hover {
        background: #0d6efd !important;
        color: #fff !important;
    }

    #semesterResultList .owl-nav button span {
        display: none;
    }

    #semesterResultList .owl-nav button i {
        font-size: 16px;
    }

    #semesterResultList .owl-dots {
        text-align: center;
        margin-top: 15px;
    }

    #semesterResultList .owl-dot {
        width: 8px;
        height: 8px;
        margin: 0 4px;
        border-radius: 50%;
        background: #dee2e6 !important;
    }

    #semesterResultList .owl-dot.active {
        background: #0d6efd !important;
    }

</style>
<section id="semesterResults">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-0">
            Semester Results
        </h3>
        <div>
            @if (array_intersect(session('roles') ?? [], ['Student']))
            <button class="btn btn-primary" id="addSemester" type="button">
                <i class="fa-solid fa-plus me-1"></i>
                Add Semester
            </button>
            <button class="btn btn-primary" id="addResult" type="button">
                <i class="fa-solid fa-plus me-1"></i>
                Add Result
            </button>
            @endif

        </div>
    </div>
    <hr>
    <div id="semesterResultList" class="owl-carousel">
        <div class="text-center py-4 text-muted">
            <p class="mb-0">
                Select Result tab to load semester results.
            </p>
        </div>
    </div>
</section>

@push('childScript')

<script>

        let semesterResultsLoaded = false;

        function templateLoading() {
            return `
                <div class="text-center py-4">
                    <div
                        class="spinner-border text-primary"
                        role="status">
                        <span class="visually-hidden">
                            Loading...
                        </span>
                    </div>
                    <p class="text-muted mt-2 mb-0">
                        Loading semester results...
                    </p>
                </div>
            `
        }

        function templateNoGroupResult() {
            return `
                <div class="text-center py-5">
                    <i class="fa-regular fa-file-lines fs-1 text-muted mb-3"></i>
                    <h5 class="fw-semibold">
                        No Semester Results
                    </h5>
                    <p class="text-muted mb-0">
                        No semester information is currently available.
                    </p>
                </div>
            `
        }

        function renderGroupResult(results) {
            let groupedResults = {};

            results.forEach(result => {
                let key = result.programmeId;

                if (!groupedResults[key]) {
                    groupedResults[key] = {
                        programmeName: result.programmeName,
                        programmeCode: result.programmeCode,
                        programmeLevel: result.programmeLevel,
                        semesters: []
                    };
                }

                groupedResults[key].semesters.push(result);
            });

            let html = "";
            Object.values(groupedResults).forEach(programme => {
                html += `
                <div class="item">
                    <div class="semester-programme-group mb-4">
                        <div class="mb-3">
                            <h5 class="fw-bold mb-1">
                                ${escapeHtml(programme.programmeName ?? "-")}
                            </h5>
                            <p class="text-muted small mb-0">
                                ${escapeHtml(programme.programmeLevel ?? "-")}
                                ${programme.programmeCode ? " • " + escapeHtml(programme.programmeCode) : ""}
                            </p>
                        </div>
                        <div class="semester-items">`;

                programme.semesters.forEach(semester => {
                    html += createSemesterItem(semester);
                });

                html += `</div></div></div>`;
            });
            return html
        }
    function initializeSemesterCarousel() {
        let $carousel = $("#semesterResultList");

        if ($carousel.hasClass("owl-loaded")) {
            $carousel.trigger("destroy.owl.carousel");
            $carousel.removeClass("owl-loaded");
            $carousel.find(".owl-stage-outer").children().unwrap();
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
        function templateResultArticle(semester, hasResult, resultButton) {
            return `
                <article class="semester-result-item border rounded-3 p-3 mb-2">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <div
                                class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <span class="fw-bold mb-0 mb-1 d-flex gap-2">
                                    <p>Semester</p>
                                    <p class="session">${escapeHtml(semester.session ?? "-")}</p>
                                </span>
                                ${hasResult ? ` <span class="badge text-bg-success">Uploaded</span>` : ""}
                            </div>
                            <div class="d-flex flex-wrap gap-3 small text-muted">
                                <span class="d-flex gap-2">
                                    <strong>GPA:</strong>
                                    <p class="gpa">${escapeHtml(semester.gpa ?? "-")}</p>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div>${resultButton}</div>
                            <button
                                class="btn text-secondary icon border-1 btnEditSemester"
                                data-id="${semester.semesterId}"
                                data-education-id="${semester.educationId}"
                                data-programme-name="${escapeHtml(semester.programmeName ?? "-")}">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                        </div>
                    </div>
                </article>
                `
        }

        function templateButtonViewResult(fileUrl, semester) {
            return `
                <button type="button" class="btn btn-outline-primary btn-sm btn-view-result"
                    data-file-url="${escapeHtml(fileUrl)}"
                    data-session="${escapeHtml(semester.session ?? "")}">
                    <i class="fa-regular fa-file-pdf me-1"></i>
                    View Result
                </button>`;
        }

        function templateBadgeResultUploaded() {
            return `<span class="badge text-bg-success">Result Uploaded</span>`;
        }

        function templateBadgeNoResultUploaded() {
            return `<span class="badge text-bg-secondary">No Result</span>`;
        }

        function loadSemesterResults() {
            $("#semesterResultList").html(templateLoading());

            let url = "{{ route('programme.getProgrammesByUserProfileId', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", "{{ session('user_profile_id') }}");

            $.ajax({
                url: url,
                type: "GET",

                success: function (response) {
                    console.log("Semester result response:", response);
                    let programmes = response.data ?? [];
                    renderSemesterResults(programmes);
                    semesterResultsLoaded = true;
                },

                error: function (xhr) {
                    console.error(xhr);
                    $("#semesterResultList").html(`
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            ${escapeHtml(xhr.responseJSON?.message ?? "Failed to load semester results.")}
                        </div>`);
                }
            });
        }

        function renderSemesterResults(programmes) {
            let results = [];
            programmes.forEach(function (programme) {

                (programme.education ?? []).forEach(function (education) {

                    (education.semesters ?? []).forEach(function (semester, index) {

                        results.push({
                            programmeId: programme.id,
                            programmeName: programme.programme_name,
                            programmeCode: programme.programme_code,
                            programmeLevel: programme.programme_level,
                            educationId: education.id,
                            cgpa: education.cgpa,
                            enrollmentStatus: education.enrollment_status,
                            semesterId: semester.id,
                            session: semester.session,
                            gpa: semester.gpa,
                            media: semester.media
                        });
                    });
                });
            });


            // Empty
            if (results.length === 0) {
                $("#semesterResultList").html(templateNoGroupResult());
                return;
            }

            $("#semesterResultList").html(renderGroupResult(results));

            initializeSemesterCarousel()
        }

        function createSemesterItem(semester) {
            let media = getSemesterMedia(semester.media);
            let hasResult = media !== null;
            let resultButton;

            if (hasResult) {
                let fileUrl = getMediaUrl(media);

                if (fileUrl) resultButton = templateButtonViewResult(fileUrl, semester)
                else resultButton = templateBadgeResultUploaded()

            } else {
                resultButton = templateBadgeNoResultUploaded()
            }
            return templateResultArticle(semester, hasResult, resultButton);
        }

        function getSemesterMedia(media) {
            if (!media) return null;
            if (Array.isArray(media)) {
                return media.length > 0 ? media[0] : null;
            }
            return media;
        }

        function getMediaUrl(media) {
            if (!media?.file_name) return null;
            console.log(media);

            let imageUrl = `{{ asset('storage/' . env('SEMESTER_RESULTS_FILE_URL')) }}/${media.file_name}`
            return imageUrl
        }

        function escapeHtml(value) {
            if (value === null || value === undefined) return ""

            return String(value)
                .replaceAll("&", "&amp;")
                .replaceAll("<", "&lt;")
                .replaceAll(">", "&gt;")
                .replaceAll('"', "&quot;")
                .replaceAll("'", "&#039;");
        }

        $(".profile-tab[data-target='result']").on("click", function () {
            loadSemesterResults();
        });

        $(document).on("education:updated", function () {
        })

        $(document).on("profile:loaded", function () {
        })

        function handleAddSemester(){
            xmodal.show("semesterModal")
        }

        function handleAddResult(){
            xmodal.show("addResultModal")
        }

        $(document).on("click", "#addSemester", handleAddSemester)
        $(document).on("click","#addResult", handleAddResult)
        loadSemesterResults();

</script>

@endpush
