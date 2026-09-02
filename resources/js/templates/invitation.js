import { formatDate } from "../utils/format.js"

export function recruitmentInvitationList(inv) {
    return `<div role="button" data-id=${inv.id} class="invitation-item d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded"
                onclick="toggleFilter()">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary" style="width:4rem; border-radius: 50%; height:4rem; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${inv.receiver.profile_image}')"></div>
                    <div class="flex-grow-1 d-flex flex-column">
                        <p class="fw-semibold">
                            ${inv.receiver.name}
                        </p>
                        <p>${inv.position.position_title}</p>
                        <div class="badge text-success border border-success w-75">
                            ${inv.invitation_status}
                        </div>
                        <small class="text-muted">
                        expires
                            ${formatDate(inv.expires_at)}
                        </small>
                    </div>
                </div>
                <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
            </div>`
}

export function mainContent(data) {
    console.log(data);

    return `
    <div class="row g-3 ">
        <div class="h-100 border-0 p-3 position-relative">
            <div class="mb-3 d-flex flex-xl-row flex-column gap-3">
                <div class="bg-primary"
                    style="width:6rem; height:6rem; border-radius: 50%; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${data.receiver.profile_image}')">
                </div>

                <div>
                    <h3 class="fw-semibold">Dr Lorem Ipsum Dolor Sit Amit</h3>
                    <p class="fw-semibold">${data.receiver.name}</p>
                    <small class="text-muted d-block">Universiti Teknikal Malaysia Melaka(UTEM)</small>
                    <div class="badge bg-primary">See More</div>
                    <small class="text-muted d-block">
                        <i class="fa-solid fa-location-dot" style="color: rgb(0, 0, 0);"></i>
                        ${data.receiver.location}
                    </small>
                </div>
            </div>

            <div class="mb-3 border d-flex justify-content-between gap-2 p-2 flex-wrap">
                <div class="d-flex gap-2 mb-2 mb-xl-0">
                    <i class="fa-solid fa-briefcase bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                        style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                    <div>
                        <small class="text-muted">Position</small>
                        <h5 class="fw-semibold">${data.position.position_title}</h5>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-2 mb-xl-0">
                    <i class="fa-solid fa-building bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                        style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                    <div>
                        <small class="text-muted">Department</small>
                        <h5 class="fw-semibold">${data.position.department}</h5>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-2 mb-xl-0">
                    <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                        style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                    <div>
                        <small class="text-muted">Employment type</small>
                        <h5 class="fw-semibold">${data.position.employment_type}</h5>
                    </div>
                </div>
            </div>

            <div class="mb-3 border p-3">
                <h5 class="fw-semibold">Personal Message</h5>
                <p>${data.invitation_message}</p>
            </div>

            <div>
                <h6 class="fw-semibold mb-1">Actions</h6>
                <div>
                    <button class="btn btn-outline-primary">
                        <i class="fa-regular fa-message text-primary"></i>
                        Message Student
                    </button>
                    <button class="btn btn-outline-danger">
                        <i class="fa-regular fa-trash-can text-danger"></i>
                        Withdraw Invitation
                    </button>
                    <button class="btn btn-outline-primary">
                        <i class="fa-solid fa-pen text-primary"></i>
                        Edit Invitation
                    </button>
                </div>
            </div>
        </div>
    </div>`
}
