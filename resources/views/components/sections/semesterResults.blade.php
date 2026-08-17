<section id="semesterResults">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-0">
            Semester Results
        </h3>
        <button class="btn btn-primary" id="addResult" type="button">
            <i class="fa-solid fa-plus me-1"></i>
            Add Result
        </button>
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
            return  `
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
                            <p class="text-muted mb-1">
                                Session
                                ${escapeHtml(semester.session ?? "-")}
                            </p>
                            <div class="d-flex flex-wrap gap-3 small ">
                                <span>
                                    <strong>GPA:</strong>
                                    ${escapeHtml(semester.gpa ?? "-")}
                                </span>
                                <span>
                                    <strong>CGPA:</strong>
                                    ${escapeHtml(semester.cgpa ?? "-")}
                                </span>
                            </div>
                        </div>
                        <div>${resultButton}</div>
                    </div>
                </article>
                `
        }

        function templateButtonViewResult(fileUrl, semester){
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

        function templateBadgeResultUploaded(){
            return `<span class="badge text-bg-success">Result Uploaded</span>`;
        }

        function templateBadgeNoResultUploaded() {
            return `<span class="badge text-bg-secondary">No Result</span>`;
        }

        function loadSemesterResults(forceReload = false) {
            if (semesterResultsLoaded && !forceReload) return

            $("#semesterResultList").html(templateLoading());

            $.ajax({
                url: "{{ route('programme.getProgrammesByUserIdJson',['userId' => auth() -> id()])}}",
                type: "GET",
                dataType: "json",

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
                            semesterNumber: index + 1,
                            session: semester.session,
                            gpa: semester.gpa,
                            media: semester.media
                        });
                    });
                });
            });

            console.log("Flatten semester results:", results);

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
            if (!media?.file_url) return null

            // Kalau backend dah bagi full URL
            if (media.file_url.startsWith("http://") || media.file_url.startsWith("https://")) {
                return media.file_url;
            }

            let baseUrl = @json(url('/SEMESTER_RESULTS_FILE_URL/'));
            let path = String(media.file_path ?? "").replace(/^\/+|\/+$/g, "");
            let file = String(media.file_url).replace(/^\/+/g, "");
            return path ? `${baseUrl}/${path}/${file}` : `${baseUrl}/${file}`;
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
