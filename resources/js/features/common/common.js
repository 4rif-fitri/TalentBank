import {formatDate} from "../../shared/utils/format.js"

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

export function noSelected(title = "", desc = "", classTarget = "", btnText = ""){
    return `<div class="bg-white border-0 p-3 d-flex flex-column align-items-center">
                <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                <h4 class="mt-2">${title}</h4>
                <p>${desc}</p>
                <button class="btn btn-primary d-block d-lg-none ${classTarget} toggleFilter">
                    <i class="fa-solid fa-filter"></i>
                    ${btnText}
                </button>
            </div>`
}

export function studentSideBar(invite,imageUrl){
    return `<div data-id="${invite.id}" role="button" class="invitation-item list-item row border p-2 rounded-2">
                <div class="col-3 d-flex align-items-center">
                    <div class="thum-image" style="background-image: url('${imageUrl}'); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>
                </div>
                <div class="col-9 d-flex flex-column">
                    <h5 class="fw-semibold student-name">${invite.position.organization.company_name}</h5>
                    <h6 class="student-position">${invite.position.position_title}</h6>
                    ${renderStatus(invite)}
                    <small class="text-muted">expires ${formatDate(invite.expires_at)}</small>
                </div>
            </div>`
}
