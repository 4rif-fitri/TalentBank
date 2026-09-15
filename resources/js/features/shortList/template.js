import { formatDate } from "../../shared/utils/format"

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

export function sideBar(results) {
    $("#shortlistList").empty();

    if (results.length != 0) {
        results.forEach(response => {
            response.data.forEach(position => {
                $("#shortlistList").append(

                    `<div data-id=${position.id} class="shortlist-item d-flex justify-content-start align-items-center gap-3 mb-2 p-3 border rounded" role="button">
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

                );
            });
        });

    } else {
        $("#shortlistList").append(`<small class="text-muted">No Data yet</small>`);
    }
}

export function detail(data) {
    $("#shortlistContent").empty()

    $("#shortlistContent").append(

        `<div data-id=${data.id} class="card h-100 shadow-sm border-0 p-3 position-relative">
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
            <h1 class="fw-bolder ui_position_title">${data.position_title}</h1>
            <p class="mt-1 ui_description">${data.description}</p>
            <small class="text-muted ui_work_location">Location: ${data.work_location}</small>
            <div class="d-flex mt-2 gap-2">
                <div class="badge text-dark border ui_employment_type">${data.employment_type}</div>
                <div class="badge text-dark border ui_department">${data.department}</div>
                <div class="badge text-dark border ui_vacancies">${data.vacancies} person</div>
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
    )

    if (!data.shortlist_users) return

    data.shortlist_users.forEach(user => {
        console.log({ user });

        let interviewsCount = user.interviews_count ?? 0;
        let invitationsCount = user.invitations_count ?? 0;
        let jobOfferCount = user.job_offers_count ?? 0;
        let status = getStatus(interviewsCount, invitationsCount, jobOfferCount);

        $("#tableDetail").append(

                `<tr data-id="${user.id}">
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
                        <div class="badge bg-primary text-white text-status">
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
    <li class="list-item-view-profile">
        <a
            target="_blank"
            href="${window.appConfig.baseURL}/profile/student/${user.id}"
            class="dropdown-item"
        >
            View Profile
        </a>
    </li>

    <!-- List Invitation -->
    <li class="list-item-add-invite">
        <button
            type="button"
            data-id="${user.id}"
            class="dropdown-item btnShowModalListInvite"
        >
            List Invite
        </button>
    </li>

    <!-- List Interview -->
    <li class="${user.invitations_count > 0 ? "" : "d-none"} list-item-add-interview">
        <button
            type="button"
            data-id="${user.id}"
            class="dropdown-item btnShowModalListInterview"
        >
            List Interview
        </button>
    </li>

    <!-- List Job Offer -->
    <li class="${user.interviews_count > 0 ? "" : "d-none"} list-item-add-jobOffer">
        <button
            type="button"
            data-id="${user.id}"
            class="dropdown-item btnShowModalListJobOffer"
        >
            List Job Offer
        </button>
    </li>

</ul>
                        </div>
                    </td>
                </tr>`

        )
    })

}

export function appendNew(data) {
    $("#shortlistList").append(

        `<div data-id=${position.id} class="shortlist-item d-flex justify-content-start align-items-center gap-3 mb-2 p-3 border rounded" role="button">
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

    );
}
