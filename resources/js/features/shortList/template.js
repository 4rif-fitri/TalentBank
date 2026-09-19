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


function renderSidebar(position) {

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
}


function renderCandidate(user) {

    const status = getStatus(
        user.interviews_count ?? 0,
        user.invitations_count ?? 0,
        user.job_offers_count ?? 0
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

                        <li class="list-item-view-profile">
                            <a
                                target="_blank"
                                href="${window.appConfig.baseURL}/profile/student/${user.id}"
                                class="dropdown-item">

                                View Profile

                            </a>
                        </li>

                        <li class="list-item-add-invite">
                            <button
                                type="button"
                                data-id="${user.id}"
                                class="dropdown-item btnShowModalListInvite">

                                List Invite

                            </button>
                        </li>

                        <li class="${user.invitations_count > 0
            ? ""
            : "d-none"} list-item-add-interview">

                            <button
                                type="button"
                                data-id="${user.id}"
                                class="dropdown-item btnShowModalListInterview">

                                List Interview

                            </button>

                        </li>

                        <li class="${user.interviews_count > 0
            ? ""
            : "d-none"} list-item-add-jobOffer">

                            <button
                                type="button"
                                data-id="${user.id}"
                                class="dropdown-item btnShowModalListJobOffer">

                                List Job Offer

                            </button>

                        </li>

                    </ul>

                </div>

            </td>

        </tr>
    `;
}


function renderDetail(data) {

    return `
        <article
            data-id="${data.id}"
            class="content-card results-panel"
            id="positionDetails">

            <header class="content-card__header">

    <div class="company-heading">
        <h2 class="ui_position_title">
            ${data.position_title ?? "-"}
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


            <div class="offer-actions">

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
}


export function sideBar(position) {

    return renderSidebar(position);
}


export function detail(data) {

    $("#shortlistContent").html(renderDetail(data));

    const $tbody = $("#tableDetail tbody");

    if (!data.shortlist_users?.length) {
        $tbody.html(`
            <tr>
                <td colspan="4" class="text-center text-muted">
                    No candidates found
                </td>
            </tr>
        `);

        return;
    }

    const rows = data.shortlist_users
        .map(user => renderCandidate(user))
        .join("");

    $tbody.html(rows);
}


export function appendNew(data) {

    $("#shortlistList").append(
        renderSidebar(data)
    );
}


export {
    renderSkills,
    getStatus,
    renderStatus,
    renderCandidate
};
