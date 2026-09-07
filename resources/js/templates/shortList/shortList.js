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
    let limit = 4
    skills.forEach(skill => {
        if (limit <= 0) return
        limit -= 1
        html += `<div class="badge border text-black">
                        <i class="${skill.icon_class_name} fa-lg"></i>
                        ${skill.skill_name}
                    </div>`
    });

    return html
}

function getStatus(interviewsCount, invitationsCount, jobOffersCount) {
    if (jobOffersCount > 0) {
        return "JobOffer";
    }

    if (interviewsCount > 0) {
        return "Interview";
    }

    if (invitationsCount > 0) {
        return "Invited";
    }

    return "Added";
}

export function candidate(user) {
    console.log({user});

    let interviewsCount = user.interviews_count ?? 0;
    let invitationsCount = user.invitations_count ?? 0;
    let jobOfferCount = 0;

    let status = getStatus(interviewsCount, invitationsCount, jobOfferCount);

    return `
        <tr data-id="${user.id}">
            <td>
                <div class="d-flex gap-2">
                    <h6 class="text-start mb-0">
                        ${user.name}
                    </h6>
                </div>
            </td>

            <td class="d-none d-md-table-cell d-flex gap-2">
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
                            <a target="_blank" href="${window.appConfig.baseURL}/profile/student/${user.id}" class="dropdown-item">
                                View Profile
                            </a>
                        </li>

                        <!-- Set Invite -->
                        <li class="${status === "Added" ? "" : "d-none"}">
                            <button type="button" data-id="${user.id}" class="dropdown-item btnShowModalAddInvite">
                                Invite
                            </button>
                        </li>

                        <!-- Cancel Invite -->
                        <li class="${status === "Invited" ? "" : "d-none"}">
                            <button type="button" data-id="${user.id}" class="dropdown-item text-danger btnCancelInvite">
                                Withdraw Invite
                            </button>
                        </li>

                        <!-- Set Interview -->
                        <li class="${status === "Invited" ? "" : "d-none"}">
                            <button type="button" data-id="${user.id}" class="dropdown-item btnShowModalAddInterview">
                                Set Interview
                            </button>
                        </li>

                        <!-- Cencel Interview -->
                        <li class="${status === "Interview" ? "" : "d-none"}">
                            <button type="button" data-id="${user.id}" class="dropdown-item text-danger btnCencelAddInterview">
                                Withdraw Interview
                            </button>
                        </li>

                        <!-- Set Job Offer -->
                        <li class="${status === "Interview" ? "" : "d-none"}">
                            <button type="button" data-id="${user.id}" class="dropdown-item btnShowModalAddJobOffer">
                                Create Job Offer
                            </button>
                        </li>

                        <!-- Cencel JobOffer -->
                        <li class="${status === "JobOffer" ? "" : "d-none"}">
                            <button type="button" data-id="${user.id}" class="dropdown-item text-danger btnWithdrawJobOffer"
                                Withdraw Job Offer
                            </button>
                        </li>

                    </ul>
                </div>
            </td>
        </tr>
    `;
}
