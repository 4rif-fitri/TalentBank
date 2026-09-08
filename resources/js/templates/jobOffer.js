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
                                <h4 class="fw-semibold">${offer.receiver.name}</h4>
                                <p class="text-primary fw-semibold">${offer.position.position_title}</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
                    </div>`
    },
mainContent: (data) => {

    const profileImage = data.receiver?.profile_image
        ? `${ window.appConfig.profileImageUrl }/${data.receiver.profile_image}`
        : `${window.appConfig.profileImageUrl}/default.png`;

const statusClass = {
    Pending: "warning",
    Accepted: "success",
    Rejected: "danger",
    Withdrawn: "secondary",
    Expired: "secondary",
}[data.offer_status] ?? "secondary";

const canEdit = data.offer_status === "Pending";

const formatMoney = (amount) => {
    if (!amount) return "-";

    return new Intl.NumberFormat("en-MY", {
        style: "currency",
        currency: "MYR"
    }).format(amount);
};

const formatOfferDate = (date) => {
    if (!date) return "Not specified";

    return new Date(date).toLocaleDateString("en-MY", {
        day: "2-digit",
        month: "short",
        year: "numeric"
    });
};

return `
        <div class="row g-4">

            <!-- ================= PROFILE HEADER ================= -->
            <div class="col-12">

                <div class="card border-0 shadow-sm overflow-hidden">

                    <!-- Cover -->
                    <div
                        style="
                            height: 120px;
                            background: linear-gradient(
                                135deg,
                                #0d6efd,
                                #6f42c1
                            );
                        "
                    ></div>

                    <div class="card-body position-relative pt-0">

                        <div
                            class="
                                d-flex
                                flex-column
                                flex-md-row
                                justify-content-between
                                align-items-md-end
                                gap-3
                            "
                        >

                            <!-- Candidate -->
                            <div
                                class="
                                    d-flex
                                    flex-column
                                    flex-sm-row
                                    align-items-center
                                    align-items-sm-end
                                    gap-3
                                "
                            >

                                <!-- Profile -->
                                <div
                                    style="
                                        width: 105px;
                                        height: 105px;
                                        margin-top: -55px;
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

                                    <h3
                                        class="fw-bold mb-1"
                                        id="receiverName"
                                    >
                                        ${data.receiver.name}
                                    </h3>

                                    <p class="text-muted mb-1">
                                        ${data.receiver.headline ?? "Candidate"}
                                    </p>

                                    <small class="text-muted">
                                        <i class="fa-solid fa-location-dot me-1"></i>
                                        ${data.receiver.location ?? "Location not specified"}
                                    </small>

                                </div>

                            </div>


                            <!-- Offer Status -->
                            <div>

                                <span
                                    class="
                                        badge
                                        rounded-pill
                                        bg-${statusClass}
                                        px-3
                                        py-2
                                        fs-6
                                    "
                                >
                                    <i class="fa-solid fa-circle-check me-1"></i>
                                    ${data.offer_status}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================= LEFT COLUMN ================= -->
            <div class="col-lg-8">


                <!-- ================= JOB INFORMATION ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-start
                                gap-3
                                mb-4
                            "
                        >

                            <div>

                                <small
                                    class="
                                        text-primary
                                        fw-semibold
                                        text-uppercase
                                    "
                                >
                                    Job Offer
                                </small>

                                <h4 class="fw-bold mt-1 mb-1">
                                    ${data.position.position_title}
                                </h4>

                                <div class="text-muted">

                                    <i class="fa-solid fa-building me-1"></i>

                                    ${data.position.organization?.company_name
    ?? "Company"
    }

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
                                <i
                                    class="
                                        fa-solid
                                        fa-file-signature
                                        text-primary
                                        fs-5
                                    "
                                ></i>
                            </div>

                        </div>


                        <!-- Position Details -->
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
                                            <i
                                                class="
                                                    fa-solid
                                                    fa-briefcase
                                                    text-primary
                                                "
                                            ></i>
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
                                            <i
                                                class="
                                                    fa-solid
                                                    fa-sitemap
                                                    text-primary
                                                "
                                            ></i>
                                        </div>

                                        <div>

                                            <small class="text-muted d-block">
                                                Department
                                            </small>

                                            <span class="fw-semibold">
                                                ${data.position.department ?? "-"}
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
                                            <i
                                                class="
                                                    fa-solid
                                                    fa-clock
                                                    text-primary
                                                "
                                            ></i>
                                        </div>

                                        <div>

                                            <small class="text-muted d-block">
                                                Employment Type
                                            </small>

                                            <span class="fw-semibold">
                                                ${data.position.employment_type ?? "-"}
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
                                            <i
                                                class="
                                                    fa-solid
                                                    fa-location-dot
                                                    text-primary
                                                "
                                            ></i>
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


                <!-- ================= COMPENSATION ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <div
                                class="
                                    rounded-circle
                                    bg-success
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
                                <i
                                    class="
                                        fa-solid
                                        fa-money-bill-wave
                                        text-success
                                    "
                                ></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-0">
                                    Compensation
                                </h5>

                                <small class="text-muted">
                                    Salary and benefits included in this offer
                                </small>

                            </div>

                        </div>


                        <div class="row g-3">

                            <!-- Salary -->
                            <div class="col-md-6">

                                <div class="border rounded-3 p-3">

                                    <small class="text-muted d-block mb-1">
                                        Salary
                                    </small>

                                    <h4 class="fw-bold text-success mb-0">

                                        ${formatMoney(data.salary_amount)}

                                    </h4>

                                    <small class="text-muted">
                                        ${data.salary_period ?? ""}
                                    </small>

                                </div>

                            </div>


                            <!-- Benefits -->
                            <div class="col-md-6">

                                <div class="border rounded-3 p-3 h-100">

                                    <small class="text-muted d-block mb-1">
                                        Benefits
                                    </small>

                                    <p class="fw-semibold mb-0">
                                        ${data.benefits ?? "No benefits specified."}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ================= EMPLOYMENT PERIOD ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

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
                                <i
                                    class="
                                        fa-solid
                                        fa-calendar-days
                                        text-primary
                                    "
                                ></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-0">
                                    Employment Period
                                </h5>

                                <small class="text-muted">
                                    Expected start and end date
                                </small>

                            </div>

                        </div>


                        <div class="row g-3">

                            <!-- Start -->
                            <div class="col-md-6">

                                <div class="bg-light rounded-3 p-3">

                                    <small class="text-muted d-block mb-1">
                                        Start Date
                                    </small>

                                    <h6 class="fw-bold mb-0">

                                        <i
                                            class="
                                                fa-regular
                                                fa-calendar-check
                                                text-primary
                                                me-2
                                            "
                                        ></i>

                                        ${formatOfferDate(data.start_date)}

                                    </h6>

                                </div>

                            </div>


                            <!-- End -->
                            <div class="col-md-6">

                                <div class="bg-light rounded-3 p-3">

                                    <small class="text-muted d-block mb-1">
                                        End Date
                                    </small>

                                    <h6 class="fw-bold mb-0">

                                        <i
                                            class="
                                                fa-regular
                                                fa-calendar-xmark
                                                text-primary
                                                me-2
                                            "
                                        ></i>

                                        ${formatOfferDate(data.end_date)}

                                    </h6>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ================= TERMS ================= -->
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
                                <i
                                    class="
                                        fa-solid
                                        fa-file-contract
                                        text-primary
                                    "
                                ></i>
                            </div>

                            <div class="flex-grow-1">

                                <h5 class="fw-bold mb-1">
                                    Terms & Conditions
                                </h5>

                                <small class="text-muted d-block mb-3">
                                    Conditions included in this job offer
                                </small>

                                <div class="bg-light rounded-3 p-3">

                                    <p class="mb-0 text-muted lh-lg">
                                        ${data.terms_and_conditions
    ?? "No terms and conditions provided."
    }
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================= RIGHT COLUMN ================= -->
            <div class="col-lg-4">


                <!-- ================= COMPANY ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <small class="text-primary fw-semibold text-uppercase">
                            Company
                        </small>

                        <div class="d-flex align-items-center gap-3 mt-3">

                            <div
                                class="
                                    rounded-3
                                    bg-light
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                    overflow-hidden
                                "
                                style="
                                    width: 60px;
                                    height: 60px;
                                    flex-shrink: 0;
                                "
                            >

                                ${data.position.organization?.organization_logo
        ? `
                                        <img
                                            src="${data.position.organization.organization_logo}"
                                            alt="Company Logo"
                                            style="
                                                width:100%;
                                                height:100%;
                                                object-fit:cover;
                                            "
                                        >
                                    `
        : `
                                        <i
                                            class="
                                                fa-solid
                                                fa-building
                                                text-muted
                                                fs-4
                                            "
                                        ></i>
                                    `
    }

                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    ${data.position.organization?.company_name
    ?? "Company"
    }
                                </h6>

                                <small class="text-muted">
                                    Employer
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ================= OFFER SUMMARY ================= -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">
                            Offer Summary
                        </h5>


                        <!-- Status -->
                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-center
                                mb-3
                            "
                        >

                            <span class="text-muted">
                                Status
                            </span>

                            <span class="badge bg-${statusClass}">
                                ${data.offer_status}
                            </span>

                        </div>


                        <!-- Salary -->
                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-center
                                mb-3
                            "
                        >

                            <span class="text-muted">
                                Salary
                            </span>

                            <span class="fw-bold text-success">
                                ${formatMoney(data.salary_amount)}
                            </span>

                        </div>


                        <!-- Period -->
                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-center
                                mb-3
                            "
                        >

                            <span class="text-muted">
                                Salary Period
                            </span>

                            <span class="fw-semibold">
                                ${data.salary_period ?? "-"}
                            </span>

                        </div>


                        <!-- Start -->
                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-center
                                mb-3
                            "
                        >

                            <span class="text-muted">
                                Start Date
                            </span>

                            <span class="fw-semibold">
                                ${formatOfferDate(data.start_date)}
                            </span>

                        </div>


                        <!-- Expiry -->
                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-center
                            "
                        >

                            <span class="text-muted">
                                Offer Expires
                            </span>

                            <span class="fw-semibold">
                                ${formatOfferDate(data.expires_at)}
                            </span>

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


                            <!-- Withdraw -->
                            <button
                                ${canEdit ? "" : "disabled"}
                                id="btnWithdrawJobOffer"
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

                                <i class="fa-regular fa-trash-can"></i>

                                <span>
                                    Withdraw Job Offer
                                </span>

                            </button>


                            <!-- Edit -->
                            <button
                                ${canEdit ? "" : "disabled"}
                                id="btnEditJobOffer"
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

                                <i class="fa-solid fa-pen"></i>

                                <span>
                                    Edit Job Offer
                                </span>

                            </button>

                        </div>


                        ${!canEdit
        ? `
                                    <small class="text-muted d-block text-center mt-3">
                                        <i class="fa-solid fa-lock me-1"></i>
                                        This offer can no longer be edited.
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

}
