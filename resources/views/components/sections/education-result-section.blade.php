<style>
.carousel-wrapper {
    width: 100%;
    overflow: hidden;
}

.carousel-track {
    display: flex;
    transition: transform 0.4s ease;
}

.carousel-track .item {
    min-width: 50%;
    flex: 0 0 50%;
    padding: 0 8px;
    box-sizing: border-box;
}

@media (max-width: 1200px) {
    .carousel-track .item {
        min-width: 100%;
        flex: 0 0 100%;
    }
}

.carousel-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}

.carousel-controls button {
    border: 1px solid #ddd;
    background: white;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
}

.carousel-controls button:hover {
    background: #f5f5f5;
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
                Add Semester
            </button>
            <button class="btn btn-primary" id="addResult" type="button">
                Add Result
            </button>
            @endif

        </div>
    </div>
    <hr>
    <div id="semesterResultList">

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

@push('childScript')

<script type="module">
    let semesterResultsLoaded = false;

    function getMediaUrl(media) {

        if (!media?.file_name) {
            return null;
        }

        let fileUrl = `{{ asset('storage/' . env('SEMESTER_RESULTS_FILE_URL')) }}/${media.file_name}`;

        return fileUrl;
    }
    function resultSemesterTemplate(semester) {
        let media = getSemesterMedia(semester.media);
        let hasResult = media !== null;

        let resultButton = '';

        if (hasResult) {
            let fileUrl = getMediaUrl(media);

            if (fileUrl) {
                resultButton = `
                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm btn-view-result"
                    data-file-url="${escapeHtml(fileUrl)}"
                    data-session="${escapeHtml(semester.session ?? "")}">
                    <i class="fa-regular fa-file-pdf me-1"></i>
                    View Result
                </button>
            `;
            } else {
                resultButton = `
                <span class="badge text-bg-success">
                    Result Uploaded
                </span>
            `;
            }
        } else {
            resultButton = `
            <span class="badge text-bg-secondary">
                No Result
            </span>
        `;
        }

        return `
        <article class="semester-result-item border rounded-3 p-3 mb-2">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>

                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">

                        <span class="fw-bold mb-1 d-flex gap-2">
                            <span>Semester</span>
                            <span class="session">
                                ${escapeHtml(semester.session ?? "-")}
                            </span>
                        </span>

                        ${hasResult
                ? `<span class="badge text-bg-success">Uploaded</span>`
                : ''
            }

                    </div>

                    <div class="d-flex flex-wrap gap-3 small text-muted">

                        <span class="d-flex gap-2">
                            <strong>GPA:</strong>
                            <span class="gpa">
                                ${escapeHtml(semester.gpa ?? "-")}
                            </span>
                        </span>

                    </div>

                </div>

                <div class="d-flex align-items-center gap-2">

                    <div>
                        ${resultButton}
                    </div>

                    <button
                        type="button"
                        class="btn text-secondary icon border-1 btnEditSemester"
                        data-id="${semester.semesterId}"
                        data-education-id="${semester.educationId}"
                        data-programme-name="${escapeHtml(semester.programmeName ?? "-")}">

                        <i class="fa-solid fa-pencil"></i>

                    </button>

                </div>

            </div>

        </article>
    `;
    }

    function programmeTemplate(programme) {

        return `
        <div class="item">

            <div class="semester-programme-group mb-4">

                <div class="mb-3">

                    <h5 class="fw-bold mb-1">
                        ${escapeHtml(programme.programmeName ?? "-")}
                    </h5>

                    <p class="text-muted small mb-0">
                        ${escapeHtml(programme.programmeLevel ?? "-")}
                        ${programme.programmeCode
                ? ` • ${escapeHtml(programme.programmeCode)}`
                : ''
            }
                    </p>

                </div>

                <div class="semester-items">

                    ${programme.semesters
                .map(semester => resultSemesterTemplate(semester))
                .join("")
            }

                </div>

            </div>

        </div>
    `;
    }

    function renderSemesterResults(programmes) {

        let results = [];

        programmes.forEach(function (programme) {

            (programme.education ?? []).forEach(function (education) {

                (education.semesters ?? []).forEach(function (semester) {

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


        // No result
        if (results.length === 0) {

            $('.carousel-track').html(`
            <div class="w-100 text-center py-5">

                <i class="fa-regular fa-file-lines fs-1 text-muted mb-3"></i>

                <h5 class="fw-semibold">
                    No Semester Results
                </h5>

                <p class="text-muted mb-0">
                    No semester information is currently available.
                </p>

            </div>
        `);

            return;
        }


        // Group by programme
        let groupedResults = {};

        results.forEach(function (result) {

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


        // Generate carousel items
        let html = '';

        Object.values(groupedResults).forEach(function (programme) {

            html += programmeTemplate(programme);

        });


        $('.carousel-track').html(html);


        // Reset carousel position
        $('.carousel-track').data('current', 0);

        updateCarousel();
    }

    function getSemesterMedia(media) {

        if (!media) {
            return null;
        }

        if (Array.isArray(media)) {
            return media.length > 0 ? media[0] : null;
        }

        return media;
    }

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return "";
        }

        return String(value)
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }

    function loadSemesterResults() {

        $('.carousel-track').html(`
        <div class="w-100 text-center py-4">

            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">
                    Loading...
                </span>
            </div>

            <p class="text-muted mt-2 mb-0">
                Loading semester results...
            </p>

        </div>
    `);


        let url = "{{ route('programme.getProgrammesByUserProfileId', ['id' => '__ID__']) }}";

        url = url.replace(
            "__ID__",
            "{{ session('user_profile_id') }}"
        );


        $.ajax({

            url: url,

            type: "GET",

            success: function (response) {

                console.log(
                    "Semester result response:",
                    response
                );

                let programmes = response.data ?? [];

                renderSemesterResults(programmes);

                semesterResultsLoaded = true;

            },

            error: function (xhr) {

                console.error(xhr);

                $('.carousel-track').html(`
                <div class="w-100 alert alert-danger">

                    <i class="fa-solid fa-circle-exclamation me-1"></i>

                    ${escapeHtml(
                    xhr.responseJSON?.message ??
                    "Failed to load semester results."
                )}

                </div>
            `);

            }

        });
    }

    function updateCarousel() {
        const $track = $('.carousel-track');
        const totalItems = $track.find('.item').length;

        let current = $track.data('current') || 0;

        // Ikut CSS:
        // > 1200px  = 2 item
        // <= 1200px = 1 item
        const itemsPerView = window.innerWidth <= 1200 ? 1 : 2;

        const maxIndex = Math.max(0, totalItems - itemsPerView);

        if (current > maxIndex) {
            current = maxIndex;
        }

        if (current < 0) {
            current = 0;
        }

        $track.css(
            'transform',
            `translateX(-${current * (100 / itemsPerView)}%)`
        );

        $track.data('current', current);
    }

    $(document).on("click", ".carousel-next", function () {
        const $track = $('.carousel-track');
        const totalItems = $track.find('.item').length;

        let current = $track.data('current') || 0;

        const itemsPerView = window.innerWidth <= 1200 ? 1 : 2;

        const maxIndex = Math.max(0, totalItems - itemsPerView);

        if (current < maxIndex) {
            current++;
        }

        $track.data('current', current);

        updateCarousel();
    });


    $(document).on("click", ".carousel-prev", function () {
        const $track = $('.carousel-track');
        let current = $track.data('current') || 0;

        if (current > 0) {
            current--;
        }

        $track.data('current', current);

        updateCarousel();
    });

    $(".profile-tab[data-target='result']").on("click", function () {
        loadSemesterResults();
    });

    $(window).on("resize", function () {
        updateCarousel();
    });

</script>

@endpush
