import { formatDate, formatDateFull, formatDateTime } from "../../shared/utils/format.js"

function renderStatus(status) {
    let class_name = ""
    if (status == "Scheduled" || status == "Pending") class_name = "text-warning bg-warning-subtle border-warning"
    if (status == "Completed" || status == "Passed") class_name = "text-success bg-success-subtle border-success"
    if (status == "Cancelled" || status == "Failed") class_name = "text-danger bg-danger-subtle border-danger"
    if (status == "Exprired") class_name = "text-secondary bg-secondary-subtle border-secondary"
    if (status == "Withdrawn") class_name = "text-secondary bg-secondary-subtle border-secondary"

    return `<div class="offer-status d-block ${class_name}">
                ${status}
            </div>`
}

export let student = {
    sideList(invite, imageUrl){
        return `<div data-id="${invite.id}" role="button" class="list-item invitation-item list-item">
                <div class="company-logo">
                    <div class="thum-image" style="background-image: url('${imageUrl}'); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>
                </div>
                <div class="list-item__content">
                    <h3 class="student-name">${invite.position.organization.company_name}</h3>
                    <p class="student-position">${invite.position.position_title}</p>
                    <small class="text-muted">${formatDate(invite.scheduled_at)}</small>
                    ${renderStatus(invite.interview_result)}
                </div>
            </div>`
    },

    mainContent(invitation, imageUrl) {

        return `<article class="content-card results-panel" id="offerDetails">
                    <header class="content-card__header">
                        <div class="company-logo company-logo-large">
                            <div class="thum-image-lg" style="background-image: url('${imageUrl}');background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
                        </div>
                        <div class="company-heading">
                            <h2>${invitation.position.organization.company_name}</h2>
                            ${renderStatus(invitation.interview_status)}
                        </div>
                    </header>

                    <div class="offer-details-grid">
                        <div class="offer-info-column">
                            <div class="offer-info-row">
                                <span>Position</span>
                                <strong>${invitation.position.position_title}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Department</span>
                                <strong>${invitation.position.department}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Interview mode</span>
                                <strong>${invitation.interview_mode}</strong>
                            </div>

                            ${invitation.interview_mode === "Online" ? `
                            <div class="offer-info-row">
                                <span>Interview Link</span>
                                <strong>${invitation.meeting_url}</strong>
                            </div>` : ""}

                            ${invitation.interview_mode === "On-site" ? `
                                <div class="offer-info-row">
                                    <span>Interview location</span>
                                    <strong>${invitation.location}</strong>
                                </div>` : ""}
                        </div>

                        <div class="offer-info-column">
                            <div class="offer-info-row">
                                <span>Ceheduled</span>
                                <strong>${formatDateTime(invitation.scheduled_at)}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Interview result</span>
                                <strong>${renderStatus(invitation.interview_result)}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Status</span>
                                <strong>
                                    ${renderStatus(invitation.interview_status)}
                                </strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Recruiter comment</span>
                                <strong>${invitation.recruiter_comment}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Create at</span>
                                <strong>${formatDate(invitation.created_at) }</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Created By</span>
                                <strong>${invitation.interviewee.name}</strong>
                            </div>
                        </div>
                    </div>
                    <footer class="offer-actions">
                        <h3>Actions</h3>
                        <div class="action-buttons">
                            <button type="button" class="btn-tb btn-tb-primary" id="messageStudentBtn">
                                <i class="fa-solid fa-message"></i>
                                Message Student
                            </button>
                        </div>
                    </footer>
                </article>`
    }
}

export let recruiter = {
    mainContent(invitation, imageUrl, educatios) {
        return `
    <article class="content-card results-panel" id="interviewDetails">

        <header class="content-card__header">

            <div class="company-logo company-logo-large">
                <div
                    class="thum-image-lg"
                    style="
                        background-image: url('${imageUrl}');
                        background-position: center;
                        background-size: cover;
                        background-repeat: no-repeat;
                    ">
                </div>
            </div>

            <div class="company-heading">

                <h2>${invitation.interviewee.name}</h2>

                <small>
                    ${invitation.interviewee.location ?? "Location not specified"}
                </small>

                ${educatios[0]?.programme?.organization?.company_name
                ? `
                            <p class="mb-0 fw-semibold">
                                ${educatios[0].programme.organization.company_name}
                            </p>
                        `
                : ""
            }

                ${educatios[0]?.programme?.programme_name
                ? `
                            <p class="mb-0">
                                ${educatios[0].programme.programme_name}
                            </p>
                        `
                : ""
            }

                ${educatios[0]?.programme?.programme_name
                ? `
                            <button
                                type="button"
                                class="badge bg-primary border-0 btnSeeMore">
                                See More
                            </button>
                        `
                : ""
            }

            </div>

        </header>


        <div class="offer-details-grid">

            <div class="offer-info-column">

                <div class="offer-info-row">
                    <span>Company</span>
                    <strong>
                        ${invitation.position.organization.company_name}
                    </strong>
                </div>

                <div class="offer-info-row">
                    <span>Position</span>
                    <strong>
                        ${invitation.position.position_title}
                    </strong>
                </div>

                <div class="offer-info-row">
                    <span>Department</span>
                    <strong>
                        ${invitation.position.department}
                    </strong>
                </div>

                <div class="offer-info-row">
                    <span>Interview mode</span>
                    <strong>
                        ${invitation.interview_mode}
                    </strong>
                </div>

                ${invitation.interview_mode === "Online"
                ? `
                            <div class="offer-info-row">
                                <span>Interview Link</span>
                                <strong>
                                    ${invitation.meeting_url ?? "-"}
                                </strong>
                            </div>
                        `
                : ""
            }

                ${invitation.interview_mode === "On-site"
                ? `
                            <div class="offer-info-row">
                                <span>Interview location</span>
                                <strong>
                                    ${invitation.location ?? "-"}
                                </strong>
                            </div>
                        `
                : ""
            }

            </div>


            <div class="offer-info-column">

                <div class="offer-info-row">
                    <span>Scheduled</span>
                    <strong>
                        ${formatDateTime(invitation.scheduled_at)}
                    </strong>
                </div>

                <div class="offer-info-row">
                    <span>Interview result</span>
                    <strong>
                        ${renderStatus(invitation.interview_result)}
                    </strong>
                </div>

                <div class="offer-info-row">
                    <span>Interview status</span>
                    <strong>
                        ${renderStatus(invitation.interview_status)}
                    </strong>
                </div>

                <div class="offer-info-row">
                    <span>Recruiter comment</span>
                    <strong>
                        ${invitation.recruiter_comment ?? "-"}
                    </strong>
                </div>

                <div class="offer-info-row">
                    <span>Created At</span>
                    <strong>
                        ${formatDate(invitation.created_at)}
                    </strong>
                </div>

                <div class="offer-info-row">
                    <span>Created By</span>
                    <strong>
                        ${invitation.interviewee.name}
                    </strong>
                </div>

            </div>

        </div>


        <footer class="offer-actions">

            <h3>Actions</h3>

            <div class="action-buttons">

                <button
                    type="button"
                    class="btn-tb btn-tb-primary btn-message-student">

                    <i class="fa-solid fa-message"></i>
                    Message Student

                </button>


                <button
                    id="btnCencelInterview"
                    data-id="${invitation.id}"
                    ${invitation.interview_status === "Scheduled"
                ? ""
                : "disabled"
            }
                    type="button"
                    class="btn-tb btn-tb-danger">

                    <i class="fa-regular fa-calendar-xmark"></i>
                    Cancel

                </button>


                <button
                    id="btnCompletedInterview"
                    data-id="${invitation.id}"
                    ${invitation.interview_status === "Scheduled"
                ? ""
                : "disabled"
            }
                    type="button"
                    class="btn-tb btn-tb-success">

                    <i class="fa-solid fa-check"></i>
                    Completed

                </button>


                <button
                    id="btnUpdateInterview"
                    data-id="${invitation.id}"
                    type="button"
                    class="btn-tb btn-tb-outline">

                    <i class="fa-solid fa-pen"></i>
                    Edit Interview

                </button>

            </div>

        </footer>

    </article>
`;
    },

    sidebar: (interview) => {
        return `<div data-id="${interview.id}" role="button" class="list-item interview-item shortlist-item">
                    <div class="company-logo">
                        <div class="thum-image" style=" background-image: url('${window.appConfig.profileImageUrl}/${interview.interviewee.profile_image}'); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>
                    </div>
                    <div class="list-item__content">
                        <h3 class="student-name">
                            ${interview.interviewee.name}
                        </h3>
                        <p class="student-position">
                            ${interview.position.position_title}
                        </p>
                        <small class="text-muted">
                            ${formatDateFull(interview.scheduled_at)}
                        </small>
                    </div>
                </div>`;
    },

}

export let common = {

    sidebar: (interview) => {
        return `<div data-id=${interview.id} class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                        <div role="button" class="d-flex align-items-center gap-3">
                        <div class="bg-primary" style="width:4rem; border-radius: 50%; height:4rem; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${interview.interviewee.profile_image}')"></div>
                            <div class="flex-grow-1 d-flex flex-column">
                                <p class="fw-semibold">${interview.interviewee.name}</p>
                                <p>${interview.position.position_title}</p>
                                <smoll>${formatDateFull(interview.scheduled_at)}</smoll>
                            </div>
                        </div>
                    </div>`
    },

    conditionRendering: (mode, data) => {
        if (mode == "On-site") {
            return `<div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Location</small>
                                <h5 class="fw-semibold">${data.location}</h5>
                            </div>
                        </div>`
        }
        else if (mode == "Online") {
            return `<div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Meeting Link</small>
                                <h5 class="fw-semibold">${data.meeting_url}</h5>
                            </div>
                        </div>`
        }
        else if (mode == "Phone") {
            return ``
        }
    },

    mainContent: (data, educations) => {
        return `<div class="row g-3 ">
                    <div class="h-100 border-0 p-3 position-relative">
                    <div class="mb-3 d-flex flex-xl-row flex-column gap-3">
                        <div class="bg-primary"
                            style="width:6rem; height:6rem; border-radius: 50%; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${data.interviewee.profile_image}')">
                        </div>

                        <div>
                            <h3 class="fw-semibold">${data.interviewee.name}</h3>
                            <small class="text-muted d-block">${data.interviewee.location}</small>
                            <p class="fw-semibold">${educations[0].programme.organization.company_name}</p>
                            <small class="text-muted d-block ">${educations[0].programme.programme_name}</small>
                            <div class="badge bg-primary btnSeeMoreEducation">See More</div>
                        </div>
                    </div>
                    <div class="mb-3 border d-block d-xl-flex justify-content-between p-2">
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-briefcase bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Position</small>
                                <h5 class="fw-semibold">${data.position.position_title}</h5>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-building bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Department</small>
                                <h5 class="fw-semibold">${data.position.department}</h5>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Employment type</small>
                                <h5 class="fw-semibold">${data.position.employment_type}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 border d-flex gap-2 p-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Interview mode</small>
                                <h5 class="fw-semibold">${data.interview_mode}</h5>
                            </div>
                        </div>

                        ${this.conditionRendering(data.interview_mode, data)}

                    </div>

                    <div class="mb-3 border d-flex flex-column g-1 p-2">
                        <h4 class="mb-0 fw-semibold">Schedule</h4>
                        <div class="d-flex gap-4">
                            <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                                <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                    style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                                <small class="text-muted">${this.formatDate(data.scheduled_at)}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                                <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                    style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                                <small class="text-muted">${this.formatTime(data.scheduled_at)}</small>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-semibold mb-1">Actions</h6>
                        <div class="d-flex gap-2 flex-row flex-sm-wrap">
                            <button id="btnMessageStudent"
                                class="btn btn-outline-primary d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-regular fa-message text-primary"></i>
                                <p>Message Student</p>
                            </button>
                            <!-- <button id="btnReschedule" data-id=${data.id}
                                class="btn btn-outline-primary d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-primary"></i>
                                <p>Reschedule</p>
                            </button> -->
                            <button id="btnCencelInterview" data-id="${data.id}"
                                class="btn btn-outline-danger d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-solid fa-trash-can text-danger"></i>
                                <p>Cencel Interview</p>
                            </button>
                            <button id="btnCompletedInterview" data-id=${data.id}
                                class="btn btn-outline-success d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-solid fa-check-circle text-success"></i>
                                <p class="text-success">Mark as Completed</p>
                            </button>
                            <button id="btnUpdateInterview" data-id=${data.id}
                                class="btn btn-outline-primary d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-primary"></i>
                                <p class="text-success">Edit Interview</p>
                            </button>
                        </div>
                    </div>

                </div>
            </div>`
    }
}
