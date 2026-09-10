import { formatDate } from "../utils/format.js"

function renderStatus(status) {
    let class_name = ""
    if (status.invitation_status == "Pending") class_name = "bg-warning text-white"
    if (status.invitation_status == "Accepted") class_name = "bg-success text-white"
    if (status.invitation_status == "Rejected") class_name = "bg-danger text-white"
    if (status.invitation_status == "Exprired") class_name = "bg-danger text-white"
    if (status.invitation_status == "Withdrawn") class_name = "bg-danger text-white"

    return `<div class="badge text-success border-2 ${class_name} w-75">
                ${status.invitation_status}
            </div>`
}

export function recruitmentInvitationList(inv) {
    return `<div role="button" data-id=${inv.id} class="invitation-item d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary" style="width:4rem; border-radius: 50%; height:4rem; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${inv.receiver.profile_image}')"></div>
                    <div class="flex-grow-1 d-flex flex-column">
                        <p class="fw-semibold">
                            ${inv.receiver.name}
                        </p>
                        <p>${inv.position.position_title}</p>
                        ${renderStatus(inv)}
                        <small class="text-muted">
                        expires
                            ${formatDate(inv.expires_at)}
                        </small>
                    </div>
                </div>
                <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
            </div>`
}

export function mainContent(invitation, imageUrl, educatios) {
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
                            <h4 class="fw-semibold">${invitation.receiver.name}</h4>
                            <p>${invitation.receiver.location ?? "Location not specified"}</p>
                            ${educatios[0]?.programme?.organization?.company_name ? `<p class="fw-semibold">${educatios[0].programme.organization.company_name}</p>` : ""}
                            ${educatios[0]?.programme?.programme_name ? `<p>${educatios[0].programme.programme_name}</p>` : ""}
                            ${educatios[0]?.programme?.programme_name ? `<div role="button" class="badge bg-primary btnSeeMore">See More</div>` : ""}
                        </div>
                    </div>
                    <div class="border-0 mb-4">
                        <div class="row g-3">
                            <div class="h-100">
                                <div class="row">
                                    <div class="col-lg-6 col-12 card-body p-3">
                                        <small class="text-primary fw-semibold text-uppercase">Job Opportunity</small>
                                        <small class="text-muted d-block">Company</small>
                                        <div class="fw-semibold">${invitation.position.organization.company_name}</div>
                                        <small class="text-muted d-block">Position</small>
                                        <span class="fw-semibold">${invitation.position.position_title}</span>
                                    </div>
                                    <div class="col-lg-6 col-12 p-3">
                                        <div class=" bg-body">
                                            <small class="text-muted d-block">Message</small>
                                            <span class="fw-semibold">${invitation.invitation_message}</span>
                                            <hr>
                                            <small class="text-muted d-block">Sent By</small>
                                            <span class="fw-semibold">${invitation.sender.name}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-1 ">
                        <h6 class="fw-bold ">Invitation Details</h6>

                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Status</span>
                            <div class="badge ${statusClass}">${invitation.invitation_status}</div>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Sent</span>
                            <span class="fw-semibold">${(invitation.created_at).split(" ")[0]}</>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Expires</span>
                            <span class="fw-semibold">${invitation.expires_at}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body p-3">
                        <h5 class="fw-bold mb-3">
                            Actions
                        </h5>

                        <div class="d-flex flex-column flex-lg-row gap-2">
                            <button class="btn btn-primary btn-message-student">
                                <i class="fa-solid fa-message me-2"></i>
                                Message Student
                            </button>

                            <button data-id="${invitation.id}" ${invitation.invitation_status === "Pending" ? "" : "disabled"}
                                class="btn-withdraw-invitation btn btn-outline-danger">
                                <i class="fa-regular fa-trash-can me-2"></i>
                                Withdraw Invitation

                            </button>

                            <button data-id="${invitation.id}" ${invitation.invitation_status === "Pending" ? "" : "disabled" }
                                class="btn-edit-invitation btn btn-outline-primary">
                                <i class="fa-solid fa-pen me-2"></i>
                                Edit Invitation
                            </button>

                        </div>
                    </div>
                </div>
            </div>`
}

export function reciverInvitationList(inv) {
    return `<div role="button" data-id=${inv.id} class="invitation-item btn-toggle-filter d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded" >
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
}

export function sennderInvitatinNoSelected(){
    return `<div class="border-0 p-3 d-flex flex-column align-items-center">
                <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                <h4 class="mt-2">No Interview Selected Yet</h4>
                <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                    <i class="fa-solid fa-filter"></i>
                    Interview
                </button>
            </div>`
}

export function reciverInvitatinMainContent(inv) {
    console.log(inv);

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
                        <p>${inv.sender.name}</p>

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

