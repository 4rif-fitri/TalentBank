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

export function mainContent(data) {
    const statusClass = {
        Pending: "warning",
        Accepted: "success",
        Rejected: "danger",
        Withdrawn: "secondary",
    }[data.invitation_status] ?? "secondary";

    const profileImage = data.receiver.profile_image
        ? `${ window.appConfig.profileImageUrl }/${data.receiver.profile_image}`
        : `${window.appConfig.profileImageUrl}/default.png`;

return `
        <div class="row g-3">

            <!-- PROFILE HEADER -->
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden">

                    <!-- Cover -->
                    <div style="
                        height: 120px;
                        background: linear-gradient(135deg, #0d6efd, #6f42c1);
                    "></div>

                    <div class="card-body position-relative pt-0">

                        <div class="d-flex flex-column flex-md-row
                                    justify-content-between
                                    align-items-md-end gap-3">

                            <!-- Profile -->
                            <div class="d-flex flex-column flex-sm-row
                                        align-items-center align-items-sm-end
                                        gap-3">

                                <div style="
                                    width: 110px;
                                    height: 110px;
                                    margin-top: -55px;
                                    border-radius: 50%;
                                    border: 5px solid white;
                                    background-image: url('${profileImage}');
                                    background-size: cover;
                                    background-position: center;
                                    flex-shrink: 0;
                                    box-shadow: 0 3px 12px rgba(0,0,0,.15);
                                "></div>

                                <div class="text-center text-sm-start">
                                    <h3 class="fw-bold mb-1" id="receiverName">
                                        ${data.receiver.name}
                                    </h3>

                                    <p class="text-muted mb-1">
                                        ${data.receiver.headline ?? "TalentBank Candidate"}
                                    </p>

                                    <small class="text-muted">
                                        <i class="fa-solid fa-location-dot me-1"></i>
                                        ${data.receiver.location ?? "Location not specified"}
                                    </small>
                                </div>

                            </div>

                            <!-- Status -->
                            <div>
                                <span class="badge rounded-pill bg-${statusClass} px-3 py-2 fs-6">
                                    <i class="fa-solid fa-circle-check me-1"></i>
                                    ${data.invitation_status}
                                </span>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <!-- LEFT COLUMN -->
            <div class="col-lg-8">

                <!-- JOB DETAILS -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between
                                    align-items-start gap-3 mb-4">

                            <div>
                                <small class="text-primary fw-semibold text-uppercase">
                                    Job Opportunity
                                </small>

                                <h4 class="fw-bold mb-1 mt-1">
                                    ${data.position.position_title}
                                </h4>

                                <div class="text-muted">
                                    <i class="fa-solid fa-building me-1"></i>
                                    ${data.position.organization?.company_name ?? "Company"}
                                </div>
                            </div>

                            <div class="d-none d-sm-flex
                                        align-items-center justify-content-center
                                        rounded-circle bg-primary bg-opacity-10"
                                style="width: 50px; height: 50px;">

                                <i class="fa-solid fa-briefcase text-primary fs-5"></i>

                            </div>

                        </div>


                        <!-- Job Info -->
                        <div class="row g-3">

                            <div class="col-md-12">
                                <div class="p-3 rounded-3 bg-light h-100">

                                    <div class="d-flex gap-3 align-items-center">

                                        <div class="rounded-circle bg-white
                                                    shadow-sm d-flex
                                                    align-items-center
                                                    justify-content-center"
                                            style="width: 45px; height: 45px;">

                                            <i class="fa-solid fa-briefcase text-primary"></i>

                                        </div>

                                        <div>
                                            <small class="text-muted d-block">
                                                Position
                                            </small>

                                            <span class="fw-semibold">
                                                ${data.position.position_title}
                                            </span>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                            <i class="fa-solid fa-sitemap text-primary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Department</small>
                                            <span class="fw-semibold">${data.position.department}</span>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="p-3 rounded-3 bg-light h-100">

                                    <div class="d-flex gap-3 align-items-center">

                                        <div class="rounded-circle bg-white
                                                    shadow-sm d-flex
                                                    align-items-center
                                                    justify-content-center"
                                            style="width: 45px; height: 45px;">

                                            <i class="fa-solid fa-clock text-primary"></i>

                                        </div>

                                        <div>
                                            <small class="text-muted d-block">
                                                Employment Type
                                            </small>

                                            <span class="fw-semibold">
                                                ${data.position.employment_type}
                                            </span>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="p-3 rounded-3 bg-light h-100">

                                    <div class="d-flex gap-3 align-items-center">

                                        <div class="rounded-circle bg-white
                                                    shadow-sm d-flex
                                                    align-items-center
                                                    justify-content-center"
                                            style="width: 45px; height: 45px;">

                                            <i class="fa-solid fa-location-dot text-primary"></i>

                                        </div>

                                        <div>
                                            <small class="text-muted d-block">
                                                Work Location
                                            </small>

                                            <span class="fw-semibold">
                                                ${data.position.work_location ?? "Not specified"}
                                            </span>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- JOB DESCRIPTION -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            <i class="fa-solid fa-file-lines text-primary me-2"></i>
                            Job Description
                        </h5>

                        <p class="text-muted mb-0 lh-lg">
                            ${data.position.description ?? "No job description provided."}
                        </p>

                    </div>

                </div>

                <!-- PERSONAL MESSAGE -->
                <div class="card">


                </div>

            </div>


            <!-- RIGHT COLUMN -->
            <div class="col-lg-4">

                <!-- COMPANY -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <small class="text-primary fw-semibold text-uppercase">
                            Company
                        </small>

                        <div class="d-flex align-items-center gap-3 mt-3">

                            <div class="rounded-3 bg-light
                                        d-flex align-items-center
                                        justify-content-center overflow-hidden"
                                style="width: 60px; height: 60px;">

                                <img
                                    src="${data.position.organization?.organization_logo ?? ""}"
                                    alt="Company Logo"
                                    style="width:100%;height:100%;object-fit:cover;"
                                    onerror="this.style.display='none'"
                                >

                                <i class="fa-solid fa-building text-muted"></i>

                            </div>

                            <div>
                                <h6 class="fw-bold mb-1">
                                    ${data.position.organization?.company_name ?? "Company"}
                                </h6>

                                <small class="text-muted">
                                    Employer
                                </small>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- INVITATION INFO -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            Invitation Details
                        </h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                Status
                            </span>

                            <span class="fw-semibold text-${statusClass}">
                                ${data.invitation_status}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                Sent
                            </span>

                            <span class="fw-semibold">
                                ${data.created_at
        ? new Date(data.created_at).toLocaleDateString()
        : "-"}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">
                                Expires
                            </span>

                            <span class="fw-semibold">
                                ${data.expires_at ?? "-"}
                            </span>
                        </div>

                    </div>
                </div>


                <!-- ACTIONS -->
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            Actions
                        </h5>

                        <div class="d-grid gap-2">

                            <button
                                class="btn btn-primary btn-message-student">

                                <i class="fa-solid fa-message me-2"></i>
                                Message Student

                            </button>


                            <button
                                data-id="${data.id}"
                                ${data.invitation_status === "Pending" ? "" : "disabled"}
                                class="btn-withdraw-invitation btn btn-outline-danger">

                                <i class="fa-regular fa-trash-can me-2"></i>
                                Withdraw Invitation

                            </button>


                            <button
                                data-id="${data.id}"
                                ${data.invitation_status === "Pending" ? "" : "disabled"}
                                class="btn-edit-invitation btn btn-outline-primary">

                                <i class="fa-solid fa-pen me-2"></i>
                                Edit Invitation

                            </button>

                        </div>

                        ${data.invitation_status !== "Pending"
        ? `
                                    <small class="text-muted d-block text-center mt-3">
                                        <i class="fa-solid fa-lock me-1"></i>
                                        Actions are unavailable because this invitation
                                        is no longer pending.
                                    </small>
                                `
        : ""
    }

                    </div>
                </div>

            </div>

        </div>
    `;
}


export function reciverInvitationList(inv) {
    return `<div role="button" data-id=${inv.id} class="invitation-item d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded" >
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
