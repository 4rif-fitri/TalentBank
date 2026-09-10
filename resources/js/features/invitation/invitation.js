import { formatDate } from "../../shared/utils/format.js"
function renderStatus(status) {
    let class_name = ""
    if (status.invitation_status == "Pending") class_name = "text-warning bg-warning-subtle border-warning"
    if (status.invitation_status == "Accepted") class_name = "text-success bg-success-subtle border-success"
    if (status.invitation_status == "Rejected") class_name = "text-danger bg-danger-subtle border-danger"
    if (status.invitation_status == "Exprired") class_name = "text-secondary bg-secondary-subtle border-secondary"
    if (status.invitation_status == "Withdrawn") class_name = "text-secondary bg-secondary-subtle border-secondary"

    return `<div class="badge border-2 ${class_name} w-75">
                ${status.invitation_status}
            </div>`
}
export let student = {
    mainContent(invitation, imageUrl) {
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

                            <button data-id="${invitation.id}" ${invitation.invitation_status === "Pending" ? "" : "disabled"}
                                class="btn-edit-invitation btn btn-outline-primary">
                                <i class="fa-solid fa-pen me-2"></i>
                                Edit Invitation
                            </button>

                        </div>
                    </div>
                </div>
            </div>`
        }
}

export let recruiter = {
    notInvitationSelected(){
        return `<div class="bg-body border-0 p-3 d-flex flex-column align-items-center">
                <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                <h4 class="mt-2">No Invitation Selected Yet</h4>
                <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                    <i class="fa-solid fa-filter"></i>
                    Invitations
                </button>
            </div>`
    },
    sennderInvitatinNoSelected() {
        return `<div class="border-0 p-3 d-flex flex-column align-items-center">
                <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                <h4 class="mt-2">No Interview Selected Yet</h4>
                <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                    <i class="fa-solid fa-filter"></i>
                    Interview
                </button>
            </div>`
    },
    recruitmentInvitationList(invite,imageUrl){
        return `<div data-id="${invite.id}" role="button" class="invitation-item list-item row border p-2 rounded-2">
                    <div class="col-3 d-flex align-items-center">
                        <div class="thum-image" style="background-image: url('${imageUrl}'); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>
                    </div>
                    <div class="col-9 d-flex flex-column">
                        <h5 class="fw-semibold student-name">${invite.receiver.name}</h5>
                        <h6 class="student-position">${invite.position.position_title}</h6>
                        ${renderStatus(invite)}
                        <small class="text-muted">expires ${formatDate(invite.expires_at)}</small>
                    </div>
                </div>`
    },
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

                            <button data-id="${invitation.id}" ${invitation.invitation_status === "Pending" ? "" : "disabled"}
                                class="btn-edit-invitation btn btn-outline-primary">
                                <i class="fa-solid fa-pen me-2"></i>
                                Edit Invitation
                            </button>

                        </div>
                    </div>
                </div>
            </div>`
    }


}

export let common = {

}
