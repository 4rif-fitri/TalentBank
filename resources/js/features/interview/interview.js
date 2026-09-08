import { formatDate } from "../../utils/format.js"

export let student = {
    sideList: inv =>  {
    return `<div role="button" data-id=${inv.id} class="invitation-item d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                <div class="d-flex align-items-center gap-3">
                    <div class="flex-grow-1 d-flex flex-column">
                        <h4 class="fw-semibold">${inv.position.organization.company_name}</h4>
                        <p class="text-primary fw-semibold">${inv.position.position_title}</p>
                        <small class="text-primary">
                            Expires: ${formatDate(inv.expires_at)}
                        </small>
                    </div>
                </div>
                <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
            </div>`
    },

    mainContent: inv => {
        return `<div class="h-100 border-0 p-3 position-relative">
                <div class="d-flex gap-3">
                    <div class=" bg-danger border-2"  style="width:4rem; height:4rem; background-image: url('${window.appConfig.organizationLogoUrl}/${inv.position.organization.organization_logo}'); background-size: cover;"></div>
                    <div class="d-flex align-items-center">
                        <h3 class="fw-semibold">${inv.position.organization.company_name}</h3>
                    </div>
                </div>

                <div class="row p-1">
                    <div class="col-sm-6 col-12 d-flex flex-column gap-2 p-1">
                        <div>
                            <h6 class="fw-semibold">Position</h6>
                            <h4 class="text-muted">${inv.position.position_title}</h4>
                        </div>
                        <div>
                            <h6 class="fw-semibold">Department</h6>
                            <h4 class="text-muted">${inv.position.department}</h4>
                        </div>
                        <div>
                            <h6 class="fw-semibold">Work location</h6>
                            <h4 class="text-muted">${inv.position.work_location}</h4>
                        </div>
                        <div>
                            <h6 class="fw-semibold">Employment type</h6>
                            <h4 class="text-muted">${inv.position.employment_type}</h4>
                        </div>
                    </div>

                    <div class="col-sm-6 col-12 p-1">
                        <h6 class=" fw-semibold">Recruiter Message</h6>
                        <p>${inv.invitation_message}</p>
                        <p>From<p>
                        <p>${inv.interviewer.name}</p>

                    </div>
                </div>

                <div class="mt-2">
                    <h6 class="fw-semibold mb-1">Actions</h6>
                    <div class=" d-flex gap-1">
                        <button class="btn btn-outline-primary">
                            <i class="fa-regular fa-message text-primary"></i>
                            Message Company
                        </button>
                        <div class="btnContainer">
                            <button ${inv.invitation_status == "Pending" ? "" : "disabled"}  data-id=${inv.id} class="btn btn-outline-danger btnRejectInvitation">
                                <i class="fa-regular fa-trash-can text-danger"></i>
                                Decline
                            </button>
                            <button ${inv.invitation_status == "Pending" ? "" : "disabled"} data-id=${inv.id} class="btn btn-outline-primary btnAcceptInvitation">
                                <i class="fa-solid fa-pen text-primary"></i>
                                Accept Invitation
                            </button>
                        </div>

                    </div>
                </div>

            </div>`
    }
}

export let recruiter = {

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
                                <small class="text-muted">nterview mode</small>
                                <h5 class="fw-semibold">${data.interview_mode}</h5>
                            </div>
                        </div>

                        ${conditionRendering(data.interview_mode, data)}

                    </div>

                    <div class="mb-3 border d-flex flex-column g-1 p-2">
                        <h4 class="mb-0 fw-semibold">Schedule</h4>
                        <div class="d-flex gap-4">
                            <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                                <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                    style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                                <small class="text-muted">${formatDate(data.scheduled_at)}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                                <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                    style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                                <small class="text-muted">${formatTime(data.scheduled_at)}</small>
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
