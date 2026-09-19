import {
    formatDate,
    formatDateFull,
    formatDateTime
} from "../../shared/utils/format.js";


function renderStatus(status) {

    let class_name = "";

    if (status === "Scheduled" || status === "Pending") {
        class_name = "text-warning bg-warning-subtle border-warning";
    }

    if (status === "Completed" || status === "Passed") {
        class_name = "text-success bg-success-subtle border-success";
    }

    if (status === "Cancelled" || status === "Failed") {
        class_name = "text-danger bg-danger-subtle border-danger";
    }

    if (status === "Expired" || status === "Exprired" || status === "Withdrawn") {
        class_name = "text-secondary bg-secondary-subtle border-secondary";
    }

    return `
        <div class="offer-status d-block ${class_name}">
            ${status ?? "-"}
        </div>
    `;
}


export let student = {

    sideList(invite, imageUrl) {

        return `
            <button
                type="button"
                data-id="${invite.id}"
                class="list-item invitation-item">

                <div class="company-logo">
                    <div
                        class="thum-image"
                        style="
                            background-image: url('${imageUrl}');
                            background-position: center;
                            background-repeat: no-repeat;
                            background-size: cover;
                        ">
                    </div>
                </div>

                <div class="list-item__content">

                    <h3 class="student-name">
                        ${invite.position.organization.company_name}
                    </h3>

                    <p class="student-position">
                        ${invite.position.position_title}
                    </p>

                    <small class="text-muted">
                        ${formatDate(invite.scheduled_at)}
                    </small>

                    ${renderStatus(invite.interview_result)}

                </div>

                <i class="fa-solid fa-arrow-right offer-arrow"></i>

            </button>
        `;
    },


    mainContent(invitation, imageUrl) {

        return `
            <article
                class="content-card results-panel"
                id="interviewDetails">

                <header class="content-card__header">

                    <div class="company-logo company-logo-large">
                        <div
                            class="thum-image-lg"
                            style="
                                background-image: url('${imageUrl}');
                                background-position: center;
                                background-size: cover;
                                background-repeat: no-repeat;
                            ">
                        </div>
                    </div>

                    <div class="company-heading">

                        <h2>
                            ${invitation.position.organization.company_name}
                        </h2>

                        <small>
                            ${invitation.position.work_location ?? "-"}
                        </small>

                        ${renderStatus(invitation.interview_status)}

                    </div>

                </header>


                <div class="offer-details-grid">

                    <div class="offer-info-column">

                        <div class="offer-info-row">
                            <span>Position</span>
                            <strong>
                                ${invitation.position.position_title}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Department</span>
                            <strong>
                                ${invitation.position.department ?? "-"}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Interview mode</span>
                            <strong>
                                ${invitation.interview_mode ?? "-"}
                            </strong>
                        </div>

                        ${invitation.interview_mode === "Online"
                ? `
                                    <div class="offer-info-row">
                                        <span>Interview Link</span>
                                        <strong>
                                            ${invitation.meeting_url ?? "-"}
                                        </strong>
                                    </div>
                                `
                : ""
            }

                        ${invitation.interview_mode === "On-site"
                ? `
                                    <div class="offer-info-row">
                                        <span>Interview Location</span>
                                        <strong>
                                            ${invitation.location ?? "-"}
                                        </strong>
                                    </div>
                                `
                : ""
            }

                    </div>


                    <div class="offer-info-column">

                        <div class="offer-info-row">
                            <span>Scheduled</span>
                            <strong>
                                ${formatDateTime(invitation.scheduled_at)}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Interview Result</span>
                            <strong>
                                ${renderStatus(invitation.interview_result)}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Status</span>
                            <strong>
                                ${renderStatus(invitation.interview_status)}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Recruiter Comment</span>
                            <strong>
                                ${invitation.recruiter_comment ?? "-"}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Created At</span>
                            <strong>
                                ${formatDate(invitation.created_at)}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Created By</span>
                            <strong>
                                ${invitation.interviewee?.name ?? "-"}
                            </strong>
                        </div>

                    </div>

                </div>


                <footer class="offer-actions">

                    <h3>Actions</h3>

                    <div class="action-buttons">

                        <button
                            type="button"
                            id="messageStudentBtn"
                            class="btn-tb btn-tb-primary">

                            <i class="fa-solid fa-message"></i>
                            Message Recruiter

                        </button>

                    </div>

                </footer>

            </article>
        `;
    }

};


export let recruiter = {

    noInterviewSelected() {

        return `
            <div class="border-0 p-3 d-flex flex-column align-items-center">

                <i
                    class="fa-regular fa-folder-open"
                    style="
                        color: rgb(0, 0, 0);
                        font-size: 5rem;
                    ">
                </i>

                <h4 class="mt-2">
                    No Interview Selected Yet
                </h4>

                <button
                    type="button"
                    class="btn btn-primary d-block d-lg-none
                           btn-toggle-filter toggleFilter">

                    <i class="fa-solid fa-filter"></i>
                    Interview

                </button>

            </div>
        `;
    },


    mainContent(invitation, imageUrl, educatios = []) {

        return `
            <article
                class="content-card results-panel"
                id="interviewDetails">

                <header class="content-card__header">

                    <div class="company-logo company-logo-large">
                        <div
                            class="thum-image-lg"
                            style="
                                background-image: url('${imageUrl}');
                                background-position: center;
                                background-size: cover;
                                background-repeat: no-repeat;
                            ">
                        </div>
                    </div>

                    <div class="company-heading">

                        <h2>
                            ${invitation.interviewee?.name ?? "-"}
                        </h2>

                        <small>
                            ${invitation.interviewee?.location ??
            "Location not specified"}
                        </small>

                        ${educatios[0]?.programme?.organization?.company_name
                ? `
                                    <p class="mb-0 fw-semibold">
                                        ${educatios[0].programme.organization.company_name}
                                    </p>
                                `
                : ""
            }

                        ${educatios[0]?.programme?.programme_name
                ? `
                                    <p class="mb-0">
                                        ${educatios[0].programme.programme_name}
                                    </p>
                                `
                : ""
            }

                        ${educatios[0]?.programme?.programme_name
                ? `
                                    <button
                                        type="button"
                                        class="badge bg-primary border-0 btnSeeMore">

                                        See More

                                    </button>
                                `
                : ""
            }

                    </div>

                </header>


                <div class="offer-details-grid">

                    <div class="offer-info-column">

                        <div class="offer-info-row">
                            <span>Company</span>
                            <strong>
                                ${invitation.position.organization.company_name}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Position</span>
                            <strong>
                                ${invitation.position.position_title}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Department</span>
                            <strong>
                                ${invitation.position.department ?? "-"}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Interview Mode</span>
                            <strong>
                                ${invitation.interview_mode ?? "-"}
                            </strong>
                        </div>

                        ${invitation.interview_mode === "Online"
                ? `
                                    <div class="offer-info-row">
                                        <span>Interview Link</span>
                                        <strong>
                                            ${invitation.meeting_url ?? "-"}
                                        </strong>
                                    </div>
                                `
                : ""
            }

                        ${invitation.interview_mode === "On-site"
                ? `
                                    <div class="offer-info-row">
                                        <span>Interview Location</span>
                                        <strong>
                                            ${invitation.location ?? "-"}
                                        </strong>
                                    </div>
                                `
                : ""
            }

                    </div>


                    <div class="offer-info-column">

                        <div class="offer-info-row">
                            <span>Scheduled</span>
                            <strong>
                                ${formatDateTime(invitation.scheduled_at)}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Interview Result</span>
                            <strong>
                                ${renderStatus(invitation.interview_result)}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Interview Status</span>
                            <strong>
                                ${renderStatus(invitation.interview_status)}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Recruiter Comment</span>
                            <strong>
                                ${invitation.recruiter_comment ?? "-"}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Created At</span>
                            <strong>
                                ${formatDate(invitation.created_at)}
                            </strong>
                        </div>

                        <div class="offer-info-row">
                            <span>Created By</span>
                            <strong>
                                ${invitation.interviewee?.name ?? "-"}
                            </strong>
                        </div>

                    </div>

                </div>


                <footer class="offer-actions">

                    <h3>Actions</h3>

                    <div class="action-buttons">

                        <button
                            type="button"
                            class="btn-tb btn-tb-primary btn-message-student">

                            <i class="fa-solid fa-message"></i>
                            Message Student

                        </button>


                        <button
                            id="btnCencelInterview"
                            data-id="${invitation.id}"
                            type="button"
                            class="btn-tb btn-tb-danger"
                            ${invitation.interview_status === "Scheduled"
                ? ""
                : "disabled"
            }>

                            <i class="fa-regular fa-calendar-xmark"></i>
                            Cancel

                        </button>


                        <button
                            id="btnCompletedInterview"
                            data-id="${invitation.id}"
                            type="button"
                            class="btn-tb btn-tb-success"
                            ${invitation.interview_status === "Scheduled"
                ? ""
                : "disabled"
            }>

                            <i class="fa-solid fa-check"></i>
                            Completed

                        </button>


                        <button
                            id="btnUpdateInterview"
                            data-id="${invitation.id}"
                            type="button"
                            class="btn-tb btn-tb-outline">

                            <i class="fa-solid fa-pen"></i>
                            Edit Interview

                        </button>

                    </div>

                </footer>

            </article>
        `;
    },


    sideBar(interview, imageUrl) {
        console.log(interview);

        const profileImage = imageUrl ??
            `${window.appConfig.profileImageUrl}/${interview.interviewee.profile_image}`;

        return `
            <button
                type="button"
                data-id="${interview.id}"
                class="list-item interview-item shortlist-item">

                <div class="company-logo">

                    <div
                        class="thum-image"
                        style="
                            background-image: url('${profileImage}');
                            background-position: center;
                            background-repeat: no-repeat;
                            background-size: cover;
                        ">
                    </div>

                </div>

                <div class="list-item__content">

                    <h3 class="student-name">
                        ${interview.receiver.name}
                    </h3>

                    <p class="student-position">
                        ${interview.position.position_title}
                    </p>

                    <small class="text-muted">
                        ${formatDateFull(interview.scheduled_at)}
                    </small>

                </div>

                <i class="fa-solid fa-arrow-right offer-arrow"></i>

            </button>
        `;
    }

};


export let common = {

    sidebar(interview, imageUrl) {

        return `
            <button
                type="button"
                data-id="${interview.id}"
                class="list-item interview-item shortlist-item">

                <div class="company-logo">

                    <div
                        class="thum-image"
                        style="
                            background-image: url('${imageUrl}');
                            background-position: center;
                            background-repeat: no-repeat;
                            background-size: cover;
                        ">
                    </div>

                </div>

                <div class="list-item__content">

                    <h3 class="student-name">
                        ${interview.interviewee.name}
                    </h3>

                    <p class="student-position">
                        ${interview.position.position_title}
                    </p>

                    <small class="text-muted">
                        ${formatDateFull(interview.scheduled_at)}
                    </small>

                </div>

                <i class="fa-solid fa-arrow-right offer-arrow"></i>

            </button>
        `;
    }

};
