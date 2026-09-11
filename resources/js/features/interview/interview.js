import { formatDate, formatDateShort, formatDateTime } from "../../shared/utils/format.js"

function renderStatus(status) {
    let class_name = ""
    if (status == "Scheduled" || status == "Pending") class_name = "text-warning bg-warning-subtle border-warning"
    if (status == "Completed" || status == "Passed") class_name = "text-success bg-success-subtle border-success"
    if (status == "Cancelled" || status == "Failed") class_name = "text-danger bg-danger-subtle border-danger"
    if (status == "Exprired") class_name = "text-secondary bg-secondary-subtle border-secondary"
    if (status == "Withdrawn") class_name = "text-secondary bg-secondary-subtle border-secondary"

    return `<div class="badge text-success border-2 ${class_name} w-75">
                ${status}
            </div>`
}

export let student = {
    sideList(invite, imageUrl){
        return `<div data-id="${invite.id}" role="button" class="invitation-item list-item row border p-2 rounded-2">
                <div class="col-3 d-flex align-items-center">
                    <div class="thum-image" style="background-image: url('${imageUrl}'); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>
                </div>
                <div class="col-9 d-flex flex-column">
                    <h5 class="fw-semibold student-name">${invite.position.organization.company_name}</h5>
                    <h6 class="student-position">${invite.position.position_title}</h6>
                    ${renderStatus(invite.interview_status)}
                    <small class="text-muted">expires ${formatDate(invite.expires_at)}</small>
                </div>
            </div>`
    },

    mainContent(invitation, imageUrl) {
        console.log({ invitation });

        const statusClass = {
            Pending: "text-warning text-warning bg-warning-subtle",
            Accepted: "text-success text-success bg-success-subtle",
            Rejected: "text-danger text-danger bg-danger-subtle",
            Withdrawn: "text-secondary text-secondary bg-secondary-subtle",
        }[invitation.interview_status] ?? "text-secondary";

        return `<div class="results-panel flex-grow-1">
                <div class="bg-body p-4">
                    <div class="d-flex flex-column flex-lg-row gap-3 bg-light p-3 rounded rounded-2 border align-items-center">
                        <div class=" d-flex align-items-center">
                            <div class="thum-image-lg" style="background-image: url('${imageUrl}');background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
                        </div>
                        <div class="">
                            <h4 class="fw-semibold">${invitation.position.organization.company_name}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12 card-body p-3">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="col-3">
                                        <small class="text-muted d-block">Company</small>
                                    </td>
                                    <td class="col-9">
                                        <div>${invitation.position.organization.company_name}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-3">
                                        <small class="text-muted d-block">Position</small>
                                    </td>
                                    <td class="col-9">
                                        <span>${invitation.position.position_title}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-3">
                                        <small class="text-muted d-block">Department</small>
                                    </td>
                                    <td class="col-9">
                                        <span>${invitation.position.department}</span>
                                    </td>
                                </tr>
                                <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Interview mode</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${invitation.interview_mode}</span>
                                        </td>
                                    </tr>

                                    ${invitation.interview_mode === "Online" ? `
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Interview Link</small>
                                            </td>
                                            <td class="col-9">
                                                <span>${invitation.meeting_url}</span>
                                            </td>
                                        </tr>` : ""}

                                    ${invitation.interview_mode === "On-site" ? `
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Interview location</small>
                                            </td>
                                            <td class="col-9">
                                                <span>${invitation.location}</span>
                                            </td>
                                        </tr>` : ""}

                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6 col-12 card-body p-3">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Ceheduled</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${formatDateTime(invitation.scheduled_at)}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Interview result</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${renderStatus(invitation.interview_result)}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Interview status</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${renderStatus(invitation.interview_status)}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Recruiter comment</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${invitation.recruiter_comment}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Create at</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${formatDate(invitation.created_at)}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Create by</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${invitation.interviewee.name}</span>
                                        </td>
                                    </tr>
                                </body>
                            </table>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="fw-bold mb-3">
                            Actions
                        </h5>

                        <div class="d-flex flex-column flex-lg-row gap-2">
                            <button class="btn btn-primary btn-message-student">
                                <i class="fa-solid fa-message me-2"></i>
                                Message Student
                            </button>
                        </div>
                    </div>
                </div>
            </div>`
    }
}

export let recruiter = {
    mainContent(invitation, imageUrl, educatios) {
        const statusClass = {
            Pending: "text-warning text-warning bg-warning-subtle",
            Accepted: "text-success text-success bg-success-subtle",
            Rejected: "text-danger text-danger bg-danger-subtle",
            Withdrawn: "text-secondary text-secondary bg-secondary-subtle",
        }[invitation.invitation_status] ?? "text-secondary";

        return `<div class="results-panel flex-grow-1">
                <div class="bg-body p-4">
                    <div class="d-flex flex-column flex-lg-row gap-3 bg-light p-3 rounded rounded-2 border">
                        <div class=" d-flex align-items-center">
                            <div class="thum-image-lg" style="background-image: url('${imageUrl}');background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
                        </div>
                        <div class="">
                            <h4 class="fw-semibold">${invitation.interviewee.name}</h4>
                            <p>${invitation.interviewee.location ?? "Location not specified"}</p>
                            ${educatios[0]?.programme?.organization?.company_name ? `<p class="fw-semibold">${educatios[0].programme.organization.company_name}</p>` : ""}
                            ${educatios[0]?.programme?.programme_name ? `<p>${educatios[0].programme.programme_name}</p>` : ""}
                            ${educatios[0]?.programme?.programme_name ? `<div role="button" class="badge bg-primary btnSeeMore">See More</div>` : ""}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12 card-body p-3">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="col-3">
                                        <small class="text-muted d-block">Company</small>
                                    </td>
                                    <td class="col-9">
                                        <div>${invitation.position.organization.company_name}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-3">
                                        <small class="text-muted d-block">Position</small>
                                    </td>
                                    <td class="col-9">
                                        <span>${invitation.position.position_title}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-3">
                                        <small class="text-muted d-block">Department</small>
                                    </td>
                                    <td class="col-9">
                                        <span>${invitation.position.department}</span>
                                    </td>
                                </tr>
                                <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Interview mode</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${invitation.interview_mode}</span>
                                        </td>
                                    </tr>

                                    ${invitation.interview_mode === "Online" ? `
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Interview Link</small>
                                            </td>
                                            <td class="col-9">
                                                <span>${invitation.meeting_url}</span>
                                            </td>
                                        </tr>` : ""}

                                    ${invitation.interview_mode === "On-site" ? `
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Interview location</small>
                                            </td>
                                            <td class="col-9">
                                                <span>${invitation.location}</span>
                                            </td>
                                        </tr>` : ""}

                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6 col-12 card-body p-3">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Ceheduled</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${formatDateTime(invitation.scheduled_at)}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Interview result</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${invitation.interview_result}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Interview status</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${invitation.interview_status}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Recruiter comment</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${invitation.recruiter_comment}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Create at</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${formatDate(invitation.created_at)}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Create by</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${invitation.interviewee.name}</span>
                                        </td>
                                    </tr>
                                </body>
                            </table>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="fw-bold mb-3">
                            Actions
                        </h5>

                        <div class="d-flex flex-column flex-lg-row gap-2">
                            <button class="btn btn-primary btn-message-student">
                                Message Student
                            </button>

                            <button id="btnCencelInterview" data-id="${invitation.id}" ${invitation.interview_status === "Scheduled" ? "" : "disabled"}
                                class="btn-withdraw-invitation btn btn-outline-danger">
                                Cencel
                            </button>

                            <button id="btnCompletedInterview" data-id="${invitation.id}" ${invitation.interview_status === "Scheduled" ? "" : "disabled"}
                                class="btn-withdraw-invitation btn btn-outline-success">
                                Completed
                            </button>

                            <button id="btnUpdateInterview" data-id="${invitation.id}"
                                class="btn-edit-invitation btn btn-outline-primary">
                                Edit Invitation
                            </button>

                        </div>
                    </div>
                </div>
            </div>`
    }
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
