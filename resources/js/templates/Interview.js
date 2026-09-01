import { formatDateFull, formatDate, formatTime } from "../utils/format.js"

export function sidebar(interview) {
    return `<div data-id=${interview.id} class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                    <div role="button" class="d-flex align-items-center gap-3">
                    <div class="bg-primary" style="width:4rem; border-radius: 50%; height:4rem; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${interview.invitation.receiver.profile_image}')"></div>
                        <div class="flex-grow-1 d-flex flex-column">
                            <p class="fw-semibold">${interview.invitation.receiver.name}</p>
                            <p>${interview.invitation.position.position_title}</p>
                            <smoll>${formatDateFull(interview.scheduled_at)}</smoll>
                        </div>
                    </div>
                </div>`
}

function renderEducation(educations) {

}

function conditionRendering(mode, data) {
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
}

export function mainContent(data, educations) {
    return `<div class="row g-3 ">
                <div class="h-100 border-0 p-3 position-relative">

                    <div class="mb-3 d-flex flex-xl-row flex-column gap-3">
                        <div class="bg-primary"
                            style="width:6rem; height:6rem; border-radius: 50%; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${data.invitation.receiver.profile_image}')">
                        </div>

                        <div>
                            <h3 class="fw-semibold">${data.invitation.receiver.name}</h3>
                            <p class="fw-semibold">Sofware inginer with Honor</p>
                            <small class="text-muted d-block">Universiti Teknikal Malaysia Melaka(UTEM)</small>
                            <div class="badge bg-primary">See More</div>
                            <small class="text-muted d-block">
                                <i class="fa-solid fa-location-dot" style="color: rgb(0, 0, 0);"></i>
                                Durian Tunggal, Malaka, Malaysia
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 border d-block d-xl-flex justify-content-between p-2">
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-briefcase bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Position</small>
                                <h5 class="fw-semibold">${data.invitation.position.position_title}</h5>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-building bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Department</small>
                                <h5 class="fw-semibold">${data.invitation.position.department}</h5>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Employment type</small>
                                <h5 class="fw-semibold">${data.invitation.position.employment_type}</h5>
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
                            <button
                                class="btn btn-outline-primary d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-regular fa-message text-primary"></i>
                                <p>Message Student</p>
                            </button>
                            <button
                                class="btn btn-outline-primary d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-primary"></i>
                                <p>Reschedule</p>
                            </button>
                            <button
                                class="btn btn-outline-danger d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-solid fa-trash-can text-danger"></i>
                                <p>Cencel Interview</p>
                            </button>
                            <button
                                class="btn btn-outline-success d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-solid fa-check-circle text-success"></i>
                                <p class="text-success">Mark as Completed</p>
                            </button>
                        </div>
                    </div>

                </div>
            </div>`
}

