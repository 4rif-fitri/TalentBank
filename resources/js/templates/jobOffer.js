export const student = {
    sidebar: (data) => {
        return `<div role="button" data-id=${inv.id} class="invitation-item d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded" onclick="toggleFilter()">
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
    mainContent: (data) => {

    }
}

export const recruiter = {
    sidebar: (offer) => {
        return `<div role="button" data-id=${offer.id} class="invitation-item d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                        <div class="d-flex align-items-center gap-3">
                            <div class="flex-grow-1 d-flex flex-column">
                                <h4 class="fw-semibold">${offer.invitation.receiver.name}</h4>
                                <p class="text-primary fw-semibold">${offer.invitation.position.position_title}</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
                    </div>`
    },
    mainContent: (data) => {
        console.log(data);

        return `<div class="row g-3 ">
        <div class="h-100 border-0 p-3 position-relative">
            <div class="mb-3 d-flex flex-xl-row flex-column gap-3">
                <div class="bg-primary"
                    style="width:6rem; height:6rem; border-radius: 50%; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${data.invitation.receiver.profile_image}')">
                </div>

                <div>
                    <h3 class="fw-semibold" id="receiverName">${data.invitation.receiver.name}</h3>
                    <p class="fw-semibold"></p>
                    <small class="text-muted d-block">Universiti Teknikal Malaysia Melaka(UTEM)</small>
                    <div class="badge bg-primary">See More</div>
                    <small class="text-muted d-block">
                        <i class="fa-solid fa-location-dot" style="color: rgb(0, 0, 0);"></i>
                        ${data.invitation.receiver.location}
                    </small>
                </div>
            </div>

            <div class="mb-3 border d-flex justify-content-between gap-2 p-2 flex-wrap">
                <div class="d-flex gap-2 mb-2 mb-xl-0">
                    <i class="fa-solid fa-briefcase bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                        style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                    <div>
                        <small class="text-muted">Position</small>
                        <h5 class="fw-semibold" id="position">${data.invitation.position.position_title}</h5>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-2 mb-xl-0">
                    <i class="fa-solid fa-building bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                        style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                    <div>
                        <small class="text-muted">Department</small>
                        <h5 class="fw-semibold">${data.invitation.position.department}</h5>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-2 mb-xl-0">
                    <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                        style="width: 3rem; height: 3rem; color: rgb(0, 0, 0); font-size: 1.5rem; border-radius: 50%;"></i>
                    <div>
                        <small class="text-muted">Employment type</small>
                        <h5 class="fw-semibold">${data.invitation.position.employment_type}</h5>
                    </div>
                </div>
            </div>

            <div class="mb-3 border p-3">
                <h5 class="fw-semibold">Personal Message</h5>
                <p>${data.invitation.invitation_message}</p>
            </div>

            <div>
                <h6 class="fw-semibold mb-1">Actions</h6>
                <div>
                   <button id="btnMessageStudent" class="btn btn-outline-primary btn-message-student">
                        Message Student
                    </button>
                    <button id="btnWithdrawJobOffer" data-id=${data.id} ${data.invitation_status === "Pending" ? "disabled" : ""} class="btn-withdraw-invitation btn btn-outline-danger ${data.invitation_status === "Pending" ? "disabled" : ""}">
                        <i class="fa-regular fa-trash-can text-danger"></i>
                        Withdraw JobOffer
                    </button>
                    <button id="btnEditJobOffer" data-id=${data.id} ${data.invitation_status === "Pending" ? "disabled" : ""} class="${data.invitation_status === "Pending" ? "disabled" : ""} btn btn-outline-info text-black btn-edit-invitation">
                        <i class="fa-solid fa-pen text-primary"></i>
                        Edit JobOffer
                    </button>
                </div>
            </div>
        </div>
    </div>`
    }
}
