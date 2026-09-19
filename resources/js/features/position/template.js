import { formatDate } from "../../shared/utils/format.js";


function renderSkills(skills = []) {

    return skills
        .slice(0, 4)
        .map(skill => `
            <span class="badge border text-black">
                <i class="${skill.icon_class_name} fa-lg"></i>
                ${skill.skill_name}
            </span>
        `)
        .join("");
}


function getStatus(
    interviewsCount = 0,
    invitationsCount = 0,
    jobOffersCount = 0
) {

    if (jobOffersCount > 0) return "JobOffer";
    if (interviewsCount > 0) return "Interview";
    if (invitationsCount > 0) return "Invited";

    return "Added";
}


function renderStatus(status) {

    const statusClass = {
        Added: "text-secondary bg-secondary-subtle",
        Invited: "text-warning bg-warning-subtle",
        Interview: "text-primary bg-primary-subtle",
        JobOffer: "text-success bg-success-subtle"
    }[status] ?? "text-secondary";

    return `
        <span class="offer-status ${statusClass}">
            ${status}
        </span>
    `;
}


export let student = {

    sideBar(position) {

        return `
            <button
                type="button"
                data-id="${position.id}"
                class="list-item shortlist-item">

                <div class="company-logo">
                    <i class="fa-regular fa-folder fa-xl"></i>
                </div>

                <div class="list-item__content">

                    <h3 class="student-name">
                        ${position.position_title}
                    </h3>

                    <small class="text-muted">
                        Created ${formatDate(position.created_at)}
                    </small>

                </div>

                <i class="fa-solid fa-arrow-right offer-arrow"></i>

            </button>
        `;
    },


    detail(data) {

        return `
            <article
                data-id="${data.id}"
                class="content-card results-panel"
                id="positionDetails">

                <header class="content-card__header">

                    <div class="company-logo company-logo-large">
                        <i class="fa-regular fa-folder fa-2xl"></i>
                    </div>

                    <div class="company-heading">

                        <h2 class="ui_position_title">
                            ${data.position_title}
                        </h2>

                        <small class="ui_work_location">
                            Location: ${data.work_location ?? "-"}
                        </small>

                        <div class="d-flex flex-wrap gap-2 mt-2">

                            <span class="badge border text-dark ui_employment_type">
                                ${data.employment_type ?? "-"}
                            </span>

                            <span class="badge border text-dark ui_department">
                                ${data.department ?? "-"}
                            </span>

                            <span class="badge border text-dark ui_vacancies">
                                ${data.vacancies ?? 0} person
                            </span>

                        </div>

                    </div>

                    <div class="dropdown ms-auto">

                        <button
                            type="button"
                            class="btn btn-sm p-1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="fa-solid fa-ellipsis-vertical"></i>

                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                            <li>
                                <button
                                    type="button"
                                    data-id="${data.id}"
                                    class="dropdown-item btnShowModalUpdateShortlist">

                                    <i class="fa-regular fa-copy me-2"></i>
                                    Edit

                                </button>
                            </li>

                            <li>
                                <button
                                    type="button"
                                    data-id="${data.id}"
                                    id="btnDeleteShortlist"
                                    class="dropdown-item text-danger">

                                    <i class="fa-regular fa-trash-can me-2"></i>
                                    Delete

                                </button>
                            </li>

                        </ul>

                    </div>

                </header>


                <div class="offer-details-grid">

                    <div class="offer-info-column">

                        <div class="offer-info-row">
                            <span>Position</span>
                            <strong class="ui_position_title">
                                ${data.position_title ?? "-"}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Location</span>
                            <strong class="ui_work_location">
                                ${data.work_location ?? "-"}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Employment Type</span>
                            <strong class="ui_employment_type">
                                ${data.employment_type ?? "-"}
                            </strong>
                        </div>

                    </div>


                    <div class="offer-info-column">

                        <div class="offer-info-row">
                            <span>Department</span>
                            <strong class="ui_department">
                                ${data.department ?? "-"}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Vacancies</span>
                            <strong class="ui_vacancies">
                                ${data.vacancies ?? 0} person
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Created At</span>
                            <strong>
                                ${formatDate(data.created_at)}
                            </strong>
                        </div>

                    </div>

                </div>


                <div class="offer-description">

                    <h3>Description</h3>

                    <p class="ui_description">
                        ${data.description ?? "-"}
                    </p>

                </div>


                <div class="offer-actions">

                    <h3>Candidates</h3>

                    <div class="table-responsive">

                        <table
                            id="tableDetail"
                            class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Skills</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>

                </div>

            </article>
        `;
    },


    candidate(user) {

        const interviewsCount = user.interviews_count ?? 0;
        const invitationsCount = user.invitations_count ?? 0;
        const jobOffersCount = user.job_offers_count ?? 0;

        const status = getStatus(
            interviewsCount,
            invitationsCount,
            jobOffersCount
        );

        return `
            <tr data-id="${user.id}">

                <td>
                    <span class="fw-semibold">
                        ${user.name}
                    </span>
                </td>

                <td>
                    <div class="d-flex flex-wrap gap-1">
                        ${renderSkills(user.skills ?? [])}
                    </div>
                </td>

                <td>
                    ${renderStatus(status)}
                </td>

                <td>

                    <div class="dropdown">

                        <button
                            type="button"
                            class="btn btn-light border"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="fa-solid fa-ellipsis"></i>

                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                            <li>
                                <a
                                    target="_blank"
                                    href="${window.appConfig.baseURL}/profile/student/${user.id}"
                                    class="dropdown-item">

                                    View Profile

                                </a>
                            </li>

                            <li class="${status === "Added" ? "" : "d-none"}">
                                <button
                                    type="button"
                                    data-id="${user.id}"
                                    class="dropdown-item btnShowModalAddInvite">

                                    Invite

                                </button>
                            </li>

                            <li class="${status === "Invited" ? "" : "d-none"}">
                                <button
                                    type="button"
                                    data-id="${user.id}"
                                    class="dropdown-item text-danger btnCancelInvite">

                                    Withdraw Invite

                                </button>
                            </li>

                            <li class="${status === "Invited" ? "" : "d-none"}">
                                <button
                                    type="button"
                                    data-id="${user.id}"
                                    class="dropdown-item btnShowModalAddInterview">

                                    Set Interview

                                </button>
                            </li>

                            <li class="${status === "Interview" ? "" : "d-none"}">
                                <button
                                    type="button"
                                    data-id="${user.id}"
                                    class="dropdown-item text-danger btnCencelAddInterview">

                                    Withdraw Interview

                                </button>
                            </li>

                            <li class="${status === "Interview" ? "" : "d-none"}">
                                <button
                                    type="button"
                                    data-id="${user.id}"
                                    class="dropdown-item btnShowModalAddJobOffer">

                                    Create Job Offer

                                </button>
                            </li>

                            <li class="${status === "JobOffer" ? "" : "d-none"}">
                                <button
                                    type="button"
                                    data-id="${user.id}"
                                    class="dropdown-item text-danger btnWithdrawJobOffer">

                                    Withdraw Job Offer

                                </button>
                            </li>

                        </ul>

                    </div>

                </td>

            </tr>
        `;
    }

};


export let recruiter = {

    sideBar(results = []) {

        return results
            .flatMap(response => response.data ?? [])
            .map(position => student.sideBar(position))
            .join("");
    },


    detail(data) {

        const content = student.detail(data);

        return content;
    },


    appendNew(data) {

        return student.sideBar(data);
    }

};


export let common = {

    renderSkills,

    getStatus,

    renderStatus

};
