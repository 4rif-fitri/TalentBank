<section id="semesterResults">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-0">
            Semester Results
        </h3>
        <div>
            <button class="btn btn-primary" id="addSemester" type="button">
                <i class="fa-solid fa-plus me-1"></i>
                Add Semester
            </button>
            <button class="btn btn-primary" id="addResult" type="button">
                <i class="fa-solid fa-plus me-1"></i>
                Add Result
            </button>
        </div>
    </div>
    <hr>
    <div id="semesterResultList">
        <div class="text-center py-4 text-muted">
            <p class="mb-0">
                Select Result tab to load semester results.
            </p>
        </div>
    </div>
</section>

@push('scripts')
<script>

    document.addEventListener("DOMContentLoaded", function () {
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

                html += `</div></div>`;
            });
            return html
        }

        function templateResultArticle(semester, hasResult, resultButton) {
            console.log(semester);

            return `
                <article class="semester-result-item border rounded-3 p-3 mb-2">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <div
                                class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <p class="fw-bold mb-0">
                                    Semester ${semester.semesterNumber}
                                </p>
                                ${hasResult ? ` <span class="badge text-bg-success">Uploaded</span>` : ""}
                            </div>
                            <span class="text-muted mb-1 d-flex gap-2">
                                <p>Session</p>
                                <p class="session">${escapeHtml(semester.session ?? "-")}</p>
                            </span>
                            <div class="d-flex flex-wrap gap-3 small">
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
                <button type="button"
                    class="btn btn-outline-primary btn-sm btn-view-result"
                    data-file-url="${escapeHtml(fileUrl)}"
                    data-session="${escapeHtml(semester.session ?? "")}"
                    data-semester="${semester.semesterNumber}">
                    <i class="fa-regular fa-file-pdf me-1"></i>
                    View Result
                </button>`
        }

        function templateBadgeResultUploaded() {
            return `<span class="badge text-bg-success">Result Uploaded</span>`;
        }

        function templateBadgeNoResultUploaded() {
            return `<span class="badge text-bg-secondary">No Result</span>`;
        }

        function loadSemesterResults(forceReload = false) {
            if (semesterResultsLoaded && !forceReload) return

            $("#semesterResultList").html(templateLoading());

            let url = "{{ route('programme.getProgrammesByUserProfileId', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", "{{ session('user_profile_id') }}");

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",

                success: function (response) {
                    // console.log("Semester result response:", response);
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
            // console.log(programmes);

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
                            semesterNumber: index + 1,
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
        }

        function createSemesterItem(semester) {

            let media = getSemesterMedia(semester.media);
            let hasResult = media !== null;
            let resultButton;

            // console.log("SEMESTER:", semester);
            // console.log("MEDIA:", media);

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

            let baseUrl = @json(url('/SEMESTER_RESULTS_FILE_URL'));
            let fileName = String(media.file_name).replace(/^\/+/g, "");
            return `${baseUrl}/${fileName}`;
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

        window.refreshSemesterResults = function () {
            semesterResultsLoaded = false;
            loadSemesterResults(true);
        };

        $(document).on("education:updated", function () {
            refreshSemesterResults();
        })
    });
</script>

@endpush