import { formatDate, formatDateShort } from "../../shared/utils/format.js"

function renderStatus(status) {
    console.log({ status });

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
    sideList: (invite, imageUrl) =>  {

        return `<button data-id="${invite.id}" role="button" type="button" class="list-item invitation-item list-item" data-offer-id="2">
                    <div class="company-logo">
                        <div class="thum-image" style="background-image: url('${imageUrl}'); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>
                    </div>
                    <div class="list-item__content">
                        <h3 class="student-name">${invite.position.organization.company_name}</h3>
                        <p class="student-position">${invite.position.position_title}</p>
                        <small class="offer-expiry">
                            ${formatDate(invite.expires_at)}
                        </small>
                        ${renderStatus(invite.offer_status)}
                    </div>
                    <i class="fa-solid fa-arrow-right offer-arrow"></i>
                </button>`
    },

    mainContent(invitation, imageUrl) {
            const statusClass = {
                Pending: "text-warning text-warning bg-warning-subtle",
                Accepted: "text-success text-success bg-success-subtle",
                Rejected: "text-danger text-danger bg-danger-subtle",
                Withdrawn: "text-secondary text-secondary bg-secondary-subtle",
            }[invitation.invitation_status] ?? "text-secondary";

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
                                <span>Period</span>
                                <strong>${formatDateShort(invitation.start_date)} - ${formatDateShort(invitation.end_date)}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Salary</span>
                                <strong>${invitation.salary_amount} / ${invitation.salary_period}</strong>
                            </div>
                        </div>

                        <div class="offer-info-column">
                            <div class="offer-info-row">
                                <span>Benefits</span>
                                <strong>${invitation.benefits ?? ""}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Terms and Conditions</span>
                                <strong>${invitation.terms_and_conditions ?? ""}</strong>
                            </div>
                            <div class="offer-info-row">
                                <span>Status</span>
                                <strong>
                                    ${renderStatus(invitation.offer_status)}
                                </strong>
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
                                Decline
                            </button>
                            <button id="btnAcceptInvitation" data-id="${invitation.id}" ${invitation.offer_status === "Pending" ? "" : "disabled"} type="button" class="btn-tb btn-tb-outline" id="acceptOfferBtn">
                                <i class="fa-solid fa-pen"></i>
                                Accept Offer
                            </button>
                        </div>
                    </footer>
                </article>`
    }
}

export let recruiter = {
    noInterviewSelected: () => {
        return `<div class="border-0 p-3 d-flex flex-column align-items-center">
                    <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                    <h4 class="mt-2">No Interview Selected Yet</h4>
                    <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                        <i class="fa-solid fa-filter"></i>
                        Interview
                    </button>
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
                        <div class="col-12">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Company</small>
                                        </td>
                                        <td class="detail-value">
                                            ${invitation.position.organization.company_name}
                                            - ${invitation.position.work_location}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Position</small>
                                        </td>
                                        <td class="detail-value">
                                            ${invitation.position.position_title}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Department</small>
                                        </td>
                                        <td class="detail-value">
                                            ${invitation.position.department}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Period</small>
                                        </td>
                                        <td class="detail-value">
                                            ${formatDateShort(invitation.start_date)}
                                            - ${formatDateShort(invitation.end_date)}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Salary</small>
                                        </td>
                                        <td class="detail-value">
                                            ${invitation.salary_amount}
                                            / ${invitation.salary_period}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card-body p-3">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Benefits</small>
                                        </td>
                                        <td class="detail-value">
                                            ${invitation.benefits}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">
                                                Terms and conditions
                                            </small>
                                        </td>
                                        <td class="detail-value">
                                            ${invitation.terms_and_conditions}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Status</small>
                                        </td>
                                        <td class="detail-value">
                                            ${invitation.offer_status}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Sent</small>
                                        </td>
                                        <td class="detail-value">
                                            ${formatDate(invitation.created_at)}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Expires</small>
                                        </td>
                                        <td class="detail-value">
                                            ${formatDate(invitation.expires_at)}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">
                                            <small class="text-muted">Created by</small>
                                        </td>
                                        <td class="detail-value">
                                            ${invitation.sender.name}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="fw-bold mb-3">
                            Actions
                        </h5>

                        <div class="d-flex flex-column flex-lg-row gap-2">
                            <button id="btnMessageStudent" class="btn btn-primary btn-message-student">
                                <i class="fa-solid fa-message me-2"></i>
                                Message Student
                            </button>

                            <button id="btnWithdrawJobOffer" data-id="${invitation.id}" ${invitation.offer_status === "Pending" ? "" : "disabled"}
                                class="btn-withdraw-invitation btn btn-outline-danger">
                                <i class="fa-regular fa-trash-can me-2"></i>
                                Withdraw Invitation

                            </button>

                            <button id="btnEditJobOffer" data-id="${invitation.id}" ${invitation.offer_status === "Pending" ? "" : "disabled"}
                                class="btn-edit-invitation btn btn-outline-primary">
                                <i class="fa-solid fa-pen me-2"></i>
                                Edit Invitation
                            </button>

                        </div>
                    </div>
                </div>
            </div>`
    },

    sideBarItem(offer, imageUrl){
        return `<div data-id="${offer.id}" role="button" class="list-item row border p-2 rounded-2">
                    <div class="col-3 d-flex align-items-center">
                        <div class="thum-image" style="background-image: url('${imageUrl}');background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
                    </div>
                    <div class="col-9 d-flex flex-column">
                        <h6 class="fw-semibold student-name">${offer.receiver.name}</h6>
                        <h6 class="student-position">${offer.position.position_title}</h6>
                        ${renderStatus(offer.offer_status)}
                        <small class="text-muted">expires ${formatDate(offer.expires_at)}</small>
                    </div>
                </div>`
    },
}

export let common = {

}
