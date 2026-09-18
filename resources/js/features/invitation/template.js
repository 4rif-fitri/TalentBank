import { formatDate } from "../../shared/utils/format.js"

function renderStatus(status) {
    let class_name = ""
    if (status == "Pending") class_name = "text-warning bg-warning-subtle border-warning"
    if (status == "Accepted") class_name = "text-success bg-success-subtle border-success"
    if (status == "Rejected") class_name = "text-danger bg-danger-subtle border-danger"
    if (status == "Exprired") class_name = "text-secondary bg-secondary-subtle border-secondary"
    if (status == "Withdrawn") class_name = "text-secondary bg-secondary-subtle border-secondary"

    return `<div class="offer-status d-block ${class_name}">
                ${status}
            </div>`
}
export let student = {
    sideList(invite, imageUrl){
        return `<button data-id="${invite.id}" role="button" type="button" class="list-item invitation-item list-item">
                    <div class="company-logo">
                        <div class="thum-image" style="background-image: url('${imageUrl}'); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>
                    </div>
                    <div class="list-item__content">
                        <h3 class="student-name">${invite.position.organization.company_name}</h3>
                        <p class="student-position">${invite.position.position_title}</p>
                         <small class="text-muted">expires ${formatDate(invite.expires_at)}</small>
                         ${renderStatus(invite.invitation_status)}
                    </div>
                    <i class="fa-solid fa-arrow-right offer-arrow"></i>
                </button>`
    },

    mainContent(invitation, imageUrl) {
                return `<article class="content-card results-panel" id="offerDetails">
                    <header class="content-card__header">
                        <div class="company-logo company-logo-large">
                            <div class="thum-image-lg" style="background-image: url('${imageUrl}');background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
                        </div>
                        <div class="company-heading">
                            <h2>${invitation.position.organization.company_name}</h2>
                            <small>${invitation.position.work_location}</small>
                            ${renderStatus(invitation.offer_status)}
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
                                <span>Employment type</span>
                                <strong>${invitation.position.employment_type}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Work location</span>
                                <strong>${invitation.position.work_location}</strong>
                            </div>
                        </div>

                        <div class="offer-info-column">
                            <div class="offer-info-row">
                                <span>Invitation status</span>
                                <strong>${renderStatus(invitation.invitation_status)}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Message</span>
                                <strong>${invitation.invitation_message}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Sent</span>
                                <strong>${formatDate(invitation.created_at)}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Expires</span>
                                <strong>${formatDate(invitation.expires_at)}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Created By</span>
                                <strong>${invitation.sender.name}</strong>
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
                            <button id="btnRejectInvitation" data-id="${invitation.id}" ${invitation.offer_status === "Pending" ? "" : "disabled"} type="button" class="btn-tb btn-tb-danger" id="declineOfferBtn">
                                <i class="fa-regular fa-trash-can"></i>
                                    Reject Invitation
                            </button>
                            <button id="btnAcceptInvitation" data-id="${invitation.id}" ${invitation.offer_status === "Pending" ? "" : "disabled"} type="button" class="btn-tb btn-tb-outline" id="acceptOfferBtn">
                                <i class="fa-solid fa-pen"></i>
                                    Accept Invitation
                            </button>
                        </div>
                    </footer>
                </article>`
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
                        ${renderStatus(invite.invitation_status)}
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
                    <div class="row">
                        <div class="col-lg-6 col-12 card-body p-3">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Company</small>
                                        </td>
                                        <td class="col-9">
                                            <span>${invitation.position.organization.company_name}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Position</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${invitation.position.position_title}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                         <td class="col-3">
                                             <small class="text-muted d-block">Department</small>
                                         </td>
                                         <td class="col-9">
                                             <div>${invitation.position.department}</div>
                                         </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Employment type</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${invitation.position.employment_type}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">
                                            <small class="text-muted d-block">Work location</small>
                                        </td>
                                        <td class="col-9">
                                            <div>${invitation.position.work_location}</div>
                                        </td>
                                    </tr>
                                    </body>
                                </table>
                            </div>
                            <div class="col-lg-6 col-12 card-body p-3">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Invitation status</small>
                                            </td>
                                            <td class="col-9">
                                                <div>${invitation.invitation_status}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Message</small>
                                            </td>
                                            <td class="col-9">
                                                <div>${invitation.invitation_message}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Sent</small>
                                            </td>
                                            <td class="col-9">
                                                <span>${formatDate(invitation.created_at)}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Expires</small>
                                            </td>
                                            <td class="col-9">
                                                <span>${formatDate(invitation.expires_at)}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3">
                                                <small class="text-muted d-block">Create by</small>
                                            </td>
                                            <td class="col-9">
                                                <span>${invitation.sender.name}</span>
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
