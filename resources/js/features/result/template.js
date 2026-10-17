function escapeHtml(value){
    if (value === null || value === undefined) return "";

    return String(value)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

export let student = {

}

export let recruiter = {

}

export let common = {
    loadSemesterResults() {
        return `
        <div class="w-100 text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">
                    Loading...
                </span>
            </div>
            <p class="text-muted mt-2 mb-0">
                Loading semester results...
            </p>
        </div>`
    },

    buttonViewResult(fileUrl, semester){
        return `<button type="button" class="btn btn-outline-primary btn-sm btn-view-result"
                    data-file-url="${escapeHtml(fileUrl)}"
                    data-session="${escapeHtml(semester.session ?? "")}">
                    <i class="fa-regular fa-file-pdf me-1"></i>
                    View Result
                </button>`
    },

    semesterResultItem(semester, hasResult, resultButton){
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
                        ${hasResult ? `<span class="badge text-bg-success">Uploaded</span>` : ''}
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
                    <div>${resultButton}</div>
                    <button type="button" class="btn text-secondary icon border-1 btnEditSemester"
                        data-id="${semester.semesterId}"
                        data-education-id="${semester.educationId}"
                        data-programme-name="${escapeHtml(semester.programmeName ?? "-")}">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                </div>
            </div>
        </article>`;
    },

    semesterResults(){
        return `
            <div class="w-100 text-center py-5">
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

}
