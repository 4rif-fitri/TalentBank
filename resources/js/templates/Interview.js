import { formatDateFull, formatDate, formatTime } from "../utils/format.js"

export function sidebar(interview) {

    return `<div data-id=${interview.id} class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                    <div role="button" class="d-flex align-items-center gap-3">
                    <div class="bg-primary" style="width:4rem; border-radius: 50%; height:4rem; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${interview.interviewee.profile_image}')"></div>
                        <div class="flex-grow-1 d-flex flex-column">
                            <p class="fw-semibold">${interview.interviewee.name}</p>
                            <p>${interview.position.position_title}</p>
                            <smoll>${formatDateFull(interview.scheduled_at)}</smoll>
                        </div>
                    </div>
                </div>`
}

function conditionRendering(mode, data) {
    if (mode == "On-site") {
        return `<div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Location</small>
                                <h5 class="fw-semibold">${data.location}</h5>
                            </div>
                        </div>`
    }
    else if (mode == "Online") {
        return `<div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Meeting Link</small>
                                <h5 class="fw-semibold">${data.meeting_url}</h5>
                            </div>
                        </div>`
    }
    else if (mode == "Phone") {
        return ``
    }



}

export function mainContent(data, educations) {

    const education = educations?.[0];

    const profileImage = data.interviewee?.profile_image
        ? `${ window.appConfig.profileImageUrl }/${data.interviewee.profile_image}`
        : `${window.appConfig.profileImageUrl}/default.png`;

const educationName =
    education?.programme?.programme_name ?? "Education not available";

const institution =
    education?.programme?.organization?.company_name ??
    "Institution not available";

const interviewStatusClass = {
    Scheduled: "primary",
    Completed: "success",
    Cancelled: "danger",
    Pending: "warning",
}[data.interview_status] ?? "secondary";

const resultClass = {
    Pending: "warning",
    Passed: "success",
    Failed: "danger",
}[data.interview_result] ?? "secondary";

return `
        <div class="row g-4">

            <!-- ================= PROFILE ================= -->
            <div class="col-12">

                <div class="card border-0 shadow-sm overflow-hidden">

                    <!-- Cover -->
                    <div
                        style="
                            height: 110px;
                            background: linear-gradient(
                                135deg,
                                #0d6efd,
                                #6f42c1
                            );
                        "
                    ></div>

                    <div class="card-body position-relative pt-0">

                        <div class="
                            d-flex
                            flex-column
                            flex-md-row
                            justify-content-between
                            align-items-md-end
                            gap-3
                        ">

                            <!-- Candidate -->
                            <div class="
                                d-flex
                                flex-column
                                flex-sm-row
                                align-items-center
                                align-items-sm-end
                                gap-3
                            ">

                                <!-- Profile Image -->
                                <div
                                    style="
                                        width: 105px;
                                        height: 105px;
                                        margin-top: -52px;
                                        border-radius: 50%;
                                        border: 5px solid white;
                                        background-image: url('${profileImage}');
                                        background-size: cover;
                                        background-position: center;
                                        flex-shrink: 0;
                                        box-shadow: 0 3px 12px rgba(0,0,0,.15);
                                    "
                                ></div>

                                <!-- Candidate Info -->
                                <div class="text-center text-sm-start">

                                    <h3 class="fw-bold mb-1">
                                        ${data.interviewee.name}
                                    </h3>

                                    <p class="text-muted mb-1">
                                        ${data.interviewee.headline ?? "Candidate"}
                                    </p>

                                    <small class="text-muted">
                                        <i class="fa-solid fa-location-dot me-1"></i>
                                        ${data.interviewee.location ?? "Location not specified"}
                                    </small>

                                </div>

                            </div>


                            <!-- Interview Status -->
                            <div>

                                <span
                                    class="
                                        badge
                                        rounded-pill
                                        bg-${interviewStatusClass}
                                        px-3
                                        py-2
                                        fs-6
                                    "
                                >
                                    <i class="fa-solid fa-circle-dot me-1"></i>
                                    ${data.interview_status}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================= LEFT ================= -->
            <div class="col-lg-8">


                <!-- ================= JOB ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="
                            d-flex
                            justify-content-between
                            align-items-start
                            gap-3
                            mb-4
                        ">

                            <div>

                                <small
                                    class="
                                        text-primary
                                        fw-semibold
                                        text-uppercase
                                    "
                                >
                                    Interview For
                                </small>

                                <h4 class="fw-bold mt-1 mb-1">
                                    ${data.position.position_title}
                                </h4>

                                <div class="text-muted">

                                    <i class="fa-solid fa-building me-1"></i>

                                    ${data.position.organization?.company_name ?? "Company"}

                                </div>

                            </div>


                            <div
                                class="
                                    d-none
                                    d-sm-flex
                                    align-items-center
                                    justify-content-center
                                    rounded-circle
                                    bg-primary
                                    bg-opacity-10
                                "
                                style="
                                    width: 50px;
                                    height: 50px;
                                "
                            >
                                <i class="fa-solid fa-briefcase text-primary fs-5"></i>
                            </div>

                        </div>


                        <!-- Job Information -->
                        <div class="row g-3">

                            <!-- Position -->
                            <div class="col-md-6">

                                <div class="bg-light rounded-3 p-3 h-100">

                                    <div class="d-flex gap-3 align-items-center">

                                        <div
                                            class="
                                                rounded-circle
                                                bg-white
                                                shadow-sm
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                            "
                                            style="
                                                width: 45px;
                                                height: 45px;
                                            "
                                        >
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


                            <!-- Department -->
                            <div class="col-md-6">

                                <div class="bg-light rounded-3 p-3 h-100">

                                    <div class="d-flex gap-3 align-items-center">

                                        <div
                                            class="
                                                rounded-circle
                                                bg-white
                                                shadow-sm
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                            "
                                            style="
                                                width: 45px;
                                                height: 45px;
                                            "
                                        >
                                            <i class="fa-solid fa-sitemap text-primary"></i>
                                        </div>

                                        <div>

                                            <small class="text-muted d-block">
                                                Department
                                            </small>

                                            <span class="fw-semibold">
                                                ${data.position.department}
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- Employment -->
                            <div class="col-md-6">

                                <div class="bg-light rounded-3 p-3 h-100">

                                    <div class="d-flex gap-3 align-items-center">

                                        <div
                                            class="
                                                rounded-circle
                                                bg-white
                                                shadow-sm
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                            "
                                            style="
                                                width: 45px;
                                                height: 45px;
                                            "
                                        >
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


                            <!-- Work Location -->
                            <div class="col-md-6">

                                <div class="bg-light rounded-3 p-3 h-100">

                                    <div class="d-flex gap-3 align-items-center">

                                        <div
                                            class="
                                                rounded-circle
                                                bg-white
                                                shadow-sm
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                            "
                                            style="
                                                width: 45px;
                                                height: 45px;
                                            "
                                        >
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


                <!-- ================= INTERVIEW ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="
                            d-flex
                            justify-content-between
                            align-items-center
                            mb-4
                        ">

                            <div>

                                <small class="text-primary fw-semibold text-uppercase">
                                    Interview Details
                                </small>

                                <h4 class="fw-bold mb-0 mt-1">
                                    Interview Information
                                </h4>

                            </div>

                            <span
                                class="
                                    badge
                                    rounded-pill
                                    bg-${resultClass}
                                    px-3
                                    py-2
                                "
                            >
                                Result: ${data.interview_result}
                            </span>

                        </div>


                        <!-- Interview Mode -->
                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="border rounded-3 p-3 h-100">

                                    <div class="d-flex gap-3">

                                        <div
                                            class="
                                                rounded-circle
                                                bg-primary
                                                bg-opacity-10
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                            "
                                            style="
                                                width: 45px;
                                                height: 45px;
                                            "
                                        >
                                            <i class="fa-solid fa-video text-primary"></i>
                                        </div>

                                        <div>

                                            <small class="text-muted d-block">
                                                Interview Mode
                                            </small>

                                            <h6 class="fw-bold mb-0">
                                                ${data.interview_mode}
                                            </h6>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            ${conditionRendering(data.interview_mode, data)}

                        </div>

                    </div>

                </div>


                <!-- ================= SCHEDULE ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-2 mb-4">

                            <div
                                class="
                                    rounded-circle
                                    bg-primary
                                    bg-opacity-10
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                "
                                style="
                                    width: 45px;
                                    height: 45px;
                                "
                            >
                                <i class="fa-solid fa-calendar-days text-primary"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-0">
                                    Interview Schedule
                                </h5>

                                <small class="text-muted">
                                    Scheduled interview date and time
                                </small>

                            </div>

                        </div>


                        <div class="row g-3">

                            <!-- Date -->
                            <div class="col-md-6">

                                <div class="bg-light rounded-3 p-3">

                                    <small class="text-muted d-block mb-1">
                                        Date
                                    </small>

                                    <h6 class="fw-bold mb-0">

                                        <i class="fa-regular fa-calendar me-2 text-primary"></i>

                                        ${formatDate(data.scheduled_at)}

                                    </h6>

                                </div>

                            </div>


                            <!-- Time -->
                            <div class="col-md-6">

                                <div class="bg-light rounded-3 p-3">

                                    <small class="text-muted d-block mb-1">
                                        Time
                                    </small>

                                    <h6 class="fw-bold mb-0">

                                        <i class="fa-regular fa-clock me-2 text-primary"></i>

                                        ${formatTime(data.scheduled_at)}

                                    </h6>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ================= RECRUITER COMMENT ================= -->
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex gap-3">

                            <div
                                class="
                                    rounded-circle
                                    bg-primary
                                    bg-opacity-10
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                    flex-shrink-0
                                "
                                style="
                                    width: 45px;
                                    height: 45px;
                                "
                            >
                                <i class="fa-solid fa-comment text-primary"></i>
                            </div>

                            <div class="flex-grow-1">

                                <h5 class="fw-bold mb-1">
                                    Recruiter Comment
                                </h5>

                                <small class="text-muted d-block mb-3">
                                    Internal interview note
                                </small>

                                <div class="bg-light rounded-3 p-3">

                                    <p class="mb-0 text-muted">
                                        ${data.recruiter_comment ?? "No recruiter comment provided."}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================= RIGHT ================= -->
            <div class="col-lg-4">


                <!-- ================= EDUCATION ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <small class="text-primary fw-semibold text-uppercase">
                            Candidate Education
                        </small>

                        <div class="d-flex gap-3 mt-3">

                            <div
                                class="
                                    rounded-3
                                    bg-primary
                                    bg-opacity-10
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                    flex-shrink-0
                                "
                                style="
                                    width: 50px;
                                    height: 50px;
                                "
                            >
                                <i class="fa-solid fa-graduation-cap text-primary"></i>
                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    ${institution}
                                </h6>

                                <small class="text-muted d-block mb-2">
                                    ${educationName}
                                </small>

                                ${education
        ? `
                                            <span class="badge bg-light text-dark border">
                                                ${education.programme?.programme_level ?? ""}
                                            </span>
                                        `
        : ""
    }

                            </div>

                        </div>

                        <button
                            class="
                                btn
                                btn-sm
                                btn-outline-primary
                                w-100
                                mt-3
                                btnSeeMoreEducation
                            "
                        >
                            <i class="fa-solid fa-eye me-1"></i>
                            View Education
                        </button>

                    </div>

                </div>


                <!-- ================= CANDIDATE ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <small class="text-primary fw-semibold text-uppercase">
                            Candidate
                        </small>

                        <div class="d-flex align-items-center gap-3 mt-3">

                            <img
                                src="${profileImage}"
                                alt="Profile"
                                class="rounded-circle"
                                style="
                                    width: 55px;
                                    height: 55px;
                                    object-fit: cover;
                                "
                            >

                            <div>

                                <h6 class="fw-bold mb-1">
                                    ${data.interviewee.name}
                                </h6>

                                <small class="text-muted">
                                    ${data.interviewee.headline ?? "Candidate"}
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ================= ACTIONS ================= -->
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            Actions
                        </h5>


                        <div class="d-grid gap-2">


                            <!-- Message -->
                            <button
                                id="btnMessageStudent"
                                class="
                                    btn
                                    btn-primary
                                    d-flex
                                    justify-content-center
                                    align-items-center
                                    gap-2
                                "
                            >

                                <i class="fa-regular fa-message"></i>

                                <span>
                                    Message Student
                                </span>

                            </button>


                            <!-- Cancel -->
                            <button
                                id="btnCencelInterview"
                                data-id="${data.id}"
                                class="
                                    btn
                                    btn-outline-danger
                                    d-flex
                                    justify-content-center
                                    align-items-center
                                    gap-2
                                "
                            >

                                <i class="fa-solid fa-xmark"></i>

                                <span>
                                    Cancel Interview
                                </span>

                            </button>


                            <!-- Complete -->
                            <button
                                id="btnCompletedInterview"
                                data-id="${data.id}"
                                class="
                                    btn
                                    btn-outline-success
                                    d-flex
                                    justify-content-center
                                    align-items-center
                                    gap-2
                                "
                            >

                                <i class="fa-solid fa-check"></i>

                                <span>
                                    Mark as Completed
                                </span>

                            </button>


                            <!-- Edit -->
                            <button
                                id="btnUpdateInterview"
                                data-id="${data.id}"
                                class="
                                    btn
                                    btn-outline-primary
                                    d-flex
                                    justify-content-center
                                    align-items-center
                                    gap-2
                                "
                            >

                                <i class="fa-regular fa-calendar-check"></i>

                                <span>
                                    Edit Interview
                                </span>

                            </button>

                        </div>


                        <small class="text-muted d-block text-center mt-3">
                            Manage the interview using the actions above.
                        </small>

                    </div>

                </div>

            </div>

        </div>
    `;
}


