import { formatDate } from "../../utils/format"

export function sideBar(position) {
    return `<div data-id=${position.id} class="shortlist-item d-flex justify-content-start align-items-center gap-3 mb-2 p-3 border rounded" role="button">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-regular fa-folder fa-xl"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold title">
                            ${position.position_title}
                        </div>
                        <small class="text-muted">
                            Created ${formatDate(position.created_at)}
                        </small>
                    </div>
                </div>
            </div>`
}

export function detail(data) {
    return `<div data-id=${data.id} class="card h-100 shadow-sm border-0 p-3 position-relative">
                <div class="dropdown position-absolute top-0 end-0 m-3">
                    <button type="button" class="btn btn-sm p-1" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <button data-id=${data.id} class="dropdown-item btnShowModalUpdateShortlist">
                                    <i class="fa-regular fa-copy me-2"></i>
                                    Edit
                            </button>
                        </li>
                        <li>
                            <button data-id=${data.id} class="dropdown-item text-danger" id="btnDeleteShortlist">
                                <i class="fa-regular fa-trash-can me-2"></i>
                                Delete
                            </button>
                        </li>
                    </ul>
                </div>
                <h1 class="fw-bolder">${data.position_title}</h1>
                <p class="mt-1">${data.description}</p>
                <small class="text-muted">Location: ${data.work_location}</small>
                <div class="d-flex mt-2 gap-2">
                    <div class="badge text-dark border">${data.employment_type}</div>
                    <div class="badge text-dark border">${data.department}</div>
                    <div class="badge text-dark border">${data.vacancies} person</div>
                </div>
                <hr>

                    <table id="tableDetail" class="table table-bordered text-center">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Candidate</th>
                                <th scope="col" class="d-none d-md-block border-0">Skills</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>`
}

function renderSkills(skills) {
    let html = ""

    skills.forEach(skill => {
        html += `<div class="badge border text-black">
                        <i class="fa-brands fa-laravel fa-lg"></i>
                        ${skill.skill_name}
                    </div>`
    });

    return html
}

function getStatus(interviews_count, invitations_count) {
    if (invitations_count === 0 && interviews_count === 0) {
        return "Added";
    }

    if (invitations_count === 1 && interviews_count === 0) {
        return "Invited";
    }

    if (invitations_count === 1 && interviews_count === 1) {
        return "Interview";
    }

    return "Unknown";
}

export function candidate(user) {
    console.log(user);

    let interviewsCount = user.interviews_count ?? 0;
    let invitationsCount = user.invitations_count ?? 0;

    // Handle interview record that exists without an invitation
    if (interviewsCount === 1 && invitationsCount === 0) {
        interviewsCount = 0;
    }

    const status = getStatus(
        interviewsCount,
        invitationsCount
    );

    return `
        <tr data-id="${user.id}">
            <td>
                <div class="d-flex gap-2">
                    <h6 class="text-start mb-0">
                        ${user.name}
                    </h6>
                </div>
            </td>

            <td class="d-none d-md-table-cell">
                ${renderSkills(user.skills)}
            </td>

            <td>
                <div class="badge bg-primary text-white">
                    ${status}
                </div>
            </td>

            <td>
                <div class="dropdown">
                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                        <!-- View Profile -->
                        <li>
                            <a
                                target="_blank"
                                href="${window.appConfig.baseURL}/profile/student/${user.id}"
                                class="dropdown-item"
                            >
                                <i class="fa-solid fa-address-book me-2"></i>
                                View Profile
                            </a>
                        </li>

                        <!-- Cancel Invite -->
                        <li class="${status === "Invited" ? "" : "d-none"}">
                            <button
                                type="button"
                                data-id="${user.id}"
                                class="dropdown-item text-danger btnCancelInvite"
                            >
                                <i class="fa-solid fa-circle-minus me-2"></i>
                                Cancel Invite
                            </button>
                        </li>

                        <!-- Invite -->
                        <li class="${status === "Added" ? "" : "d-none"}">
                            <button
                                type="button"
                                data-id="${user.id}"
                                class="dropdown-item btnShowModalAddInvite"
                            >
                                <i class="fa-regular fa-envelope me-2"></i>
                                Invite
                            </button>
                        </li>

                        <!-- Set Interview -->
                        <li class="${status === "Invited" ? "" : "d-none"}">
                            <button
                                type="button"
                                data-id="${user.id}"
                                class="dropdown-item btnShowModalAddInterview"
                            >
                                <i class="fa-regular fa-calendar me-2"></i>
                                Set Interview
                            </button>
                        </li>

                        <!-- Delete -->
                        <!-- <li>
                            <button type="button" data-id="${user.id}" class="dropdown-item text-danger btnDeleteTalentRow">
                                <i class="fa-regular fa-trash-can me-2"></i>
                                Delete
                            </button>
                        </li> -->

                        </ul>
                </div>
            </td>
        </tr>
    `;
}
