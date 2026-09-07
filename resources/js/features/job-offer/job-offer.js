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
    }
}

export let common = {

}
