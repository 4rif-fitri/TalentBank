@extends('layouts.internship-layouts')

@section('content')

<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/company-profile.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid company-profile py-2">

    {{-- COMPANY BANNER --}}
    <section class="profile-card company-banner bg-body">

        <div class="company-logo" style="position: relative;">

            <div id="companyLogoContainer">
                <div class="text-center">
                    <i class="bi bi-building fs-1 text-primary"></i>
                    <div class="fw-bold mt-2">Company</div>
                </div>
            </div>

            <label for="changeLogoCompany" class="btn-edit top-0 end-0" style="position: absolute; cursor: pointer;">
                <i class="fa-solid fa-pencil"></i>
            </label>

            <input type="file" id="changeLogoCompany" name="organization_logo" accept="image/jpeg,image/png,image/webp"
                hidden>

        </div>

        <div class="flex-grow-1">

            <h1 class="company-name" id="companyName"></h1>

            <p class="company-tagline" id="address"></p>

            <div class="company-meta">
                <span id="state"></span>
                <span id="postcode"></span>
                <span id="city"></span>
            </div>

        </div>

        <button type="button" class="btn-edit position-absolute top-0 end-0 m-3" data-bs-toggle="modal"
            data-bs-target="#editCompanyProfileModal">
            <i class="fa-solid fa-pencil"></i>
        </button>

    </section>


    {{-- COMPANY INFORMATION --}}
    <section class="row" id="overview">

        <div class="col-12 col-xl-8">

            <div class="profile-card bg-body" id="company-information">

                <div class="d-flex justify-content-between align-items-center gap-2">

                    <h2 class="profile-heading border-0 p-0 mb-0">
                        Company Information
                    </h2>

                    <button type="button" class="btn-edit" data-bs-toggle="modal"
                        data-bs-target="#editCompanyInformationModal">
                        <i class="fa-solid fa-pencil"></i>
                    </button>

                </div>

                <div>

                    <div class="info-row">
                        <div class="info-label">SSM Number</div>
                        <div class="info-value" id="ssmNumber"></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Organization Type</div>
                        <div class="info-value" id="organizationType"></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Industry</div>
                        <div class="info-value" id="industry"></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Industry Sector</div>
                        <div class="info-value" id="industrySector"></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Company Email</div>
                        <div class="info-value" id="companyEmail"></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Company Phone</div>
                        <div class="info-value" id="company_phone"></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Website</div>

                        <div class="info-value">
                            <a class="text-primary" href="#" target="_blank" rel="noopener noreferrer"
                                id="companyWebsite"></a>
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- COMPANY DESCRIPTION --}}
        <div class="col-12 col-xl-4">

            <div class="profile-card bg-body">

                <div class="d-flex justify-content-between align-items-center gap-2">

                    <h2 class="profile-heading border-0 p-0 mb-0">
                        Company Description
                    </h2>

                    <button type="button" class="btn-edit" data-bs-toggle="modal"
                        data-bs-target="#editCompanyDescriptionModal">
                        <i class="fa-solid fa-pencil"></i>
                    </button>

                </div>

                <p class="description-text mt-3" id="description"></p>

            </div>

        </div>

    </section>

</div>


{{-- ===================================================== --}}
{{-- EDIT COMPANY PROFILE MODAL --}}
{{-- ===================================================== --}}

<div class="modal fade" id="editCompanyProfileModal" tabindex="-1" aria-labelledby="editCompanyProfileLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">

            <form id="editCompanyProfileForm">

                @csrf

                <div class="modal-header">

                    <div>
                        <h5 class="modal-title fw-bold" id="editCompanyProfileLabel">
                            <i class="bi bi-building text-primary me-2"></i>
                            Edit Company Profile
                        </h5>

                        <small class="text-muted">
                            Update your company's profile information.
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body p-4">

                    <div class="mb-3">

                        <label for="profileCompanyName" class="form-label fw-semibold">
                            Company Name <span class="text-danger">*</span>
                        </label>

                        <input type="text" class="form-control" id="profileCompanyName" name="company_name"
                            maxlength="255" required>

                    </div>

                    <div class="mb-3">

                        <label for="companyAddress" class="form-label fw-semibold">
                            Address <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control" id="companyAddress" name="address" rows="3" maxlength="255"
                            placeholder="Enter company address" required></textarea>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="companyState" class="form-label fw-semibold">
                                State <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" id="companyState" name="state" required>

                                <option value="">Select state</option>
                                <option value="Johor">Johor</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Perak">Perak</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Pulau Pinang">Pulau Pinang</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Putrajaya">Putrajaya</option>
                                <option value="Labuan">Labuan</option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label for="companyPostcode" class="form-label fw-semibold">
                                Postcode <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="companyPostcode" name="postcode"
                                inputmode="numeric" maxlength="5" placeholder="e.g. 76100" required>

                        </div>

                        <div class="col-12">

                            <label for="companyCity" class="form-label fw-semibold">
                                City <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="companyCity" name="city" maxlength="255"
                                placeholder="e.g. Durian Tunggal" required>

                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-light">

                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary" id="saveCompanyProfileButton">
                        <i class="bi bi-check-lg me-1"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ===================================================== --}}
{{-- EDIT COMPANY INFORMATION MODAL --}}
{{-- ===================================================== --}}

<div class="modal fade" id="editCompanyInformationModal" tabindex="-1" aria-labelledby="editCompanyInformationLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">

            <form id="editCompanyInformationForm">

                @csrf

                <div class="modal-header">

                    <div>
                        <h5 class="modal-title fw-bold" id="editCompanyInformationLabel">
                            <i class="bi bi-card-list text-primary me-2"></i>
                            Company Information
                        </h5>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body p-4">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="inputSsmNumber" class="form-label fw-semibold">
                                SSM Registration Number
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="inputSsmNumber" name="ssm_number"
                                maxlength="255" required>

                        </div>

                        <div class="col-md-6">

                            <label for="inputOrganizationType" class="form-label fw-semibold">
                                Organization Type
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" id="inputOrganizationType" name="organization_type_id" required>

                                <option value="">Select organization type</option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label for="inputIndustryCategory" class="form-label fw-semibold">
                                Industry
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" id="inputIndustryCategory" name="industry_category_id" required>

                                <option value="">Select industry</option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label for="inputIndustrySector" class="form-label fw-semibold">
                                Industry Sector
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" id="inputIndustrySector" name="industry_sector_id" required>

                                <option value="">Select industry sector</option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label for="inputCompanyEmail" class="form-label fw-semibold">
                                Company Email
                                <span class="text-danger">*</span>
                            </label>

                            <input type="email" class="form-control" id="inputCompanyEmail" name="company_email"
                                maxlength="255" placeholder="company@example.com" required>

                        </div>

                        <div class="col-md-6">

                            <label for="companyPhone" class="form-label fw-semibold">
                                Company Phone
                                <span class="text-danger">*</span>
                            </label>

                            <input type="tel" class="form-control" id="companyPhone" name="company_phone"
                                maxlength="255" placeholder="+60 6-123 4567" required>

                        </div>

                        <div class="col-12">

                            <label for="companyWebsiteInput" class="form-label fw-semibold">
                                Website
                            </label>

                            <input type="url" class="form-control" id="companyWebsiteInput" name="website"
                                maxlength="255" placeholder="https://www.example.com">

                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-light">

                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary" id="saveCompanyInformationButton">
                        <i class="bi bi-check-lg me-1"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ===================================================== --}}
{{-- EDIT COMPANY DESCRIPTION MODAL --}}
{{-- ===================================================== --}}

<div class="modal fade" id="editCompanyDescriptionModal" tabindex="-1" aria-labelledby="editCompanyDescriptionLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <form id="editCompanyDescriptionForm">

                @csrf

                <div class="modal-header">

                    <div>
                        <h5 class="modal-title fw-bold" id="editCompanyDescriptionLabel">
                            <i class="bi bi-card-text text-primary me-2"></i>
                            Company Description
                        </h5>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body p-4">

                    <div class="mb-4">

                        <label for="companyDescription" class="form-label fw-semibold">
                            Description <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control" id="companyDescription" name="description" rows="7"
                            maxlength="255" placeholder="Describe your company, services, values and goals..."
                            required></textarea>

                        <div class="d-flex justify-content-end mt-2">
                            <small class="text-muted">
                                <span id="descriptionCharacterCount">0</span>/255
                            </small>
                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-light">

                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary" id="saveCompanyDescriptionButton">
                        <i class="bi bi-check-lg me-1"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@section('script')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ==========================================
        // API URLS
        // ==========================================

        const organizationTypesUrl =
            "{{ route('organization.getAllOrganizationTypes') }}";

        const industryCategoriesUrl =
            "{{ route('organization.getAllIndustryCategories') }}";

        const industrySectorsUrl =
            "{{ route('organization.getAllIndustrySectors') }}";

        const organizationUrlTemplate =
            "{{ route('organization.getOrganizationById', ['id' => '__ID__']) }}";

        const updateOrganizationUrlTemplate =
            "{{ route('organization.update', ['orgId' => '__ID__']) }}";

        const uploadOrganizationLogoUrlTemplate =
            "{{ route('organization.uploadOrganizationLogo', ['orgId' => '__ID__']) }}";

        const profileUrlTemplate =
            "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";

        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        )?.getAttribute('content');

        let companyId;
        let companyData;


        // ==========================================
        // HELPER: REPLACE ORGANIZATION ID
        // ==========================================

        function getOrganizationUrl(template) {
            return template.replace('__ID__', encodeURIComponent(companyId));
        }


        // ==========================================
        // GET ALL ORGANIZATION TYPES
        // ==========================================

        function getAllOrganizationTypes(selectedId = null) {

            return $.ajax({
                type: "GET",
                url: organizationTypesUrl,
                dataType: "json",

                success: function (response) {

                    const select = $("#inputOrganizationType");

                    select.empty();

                    select.append(
                        $('<option>', {
                            value: '',
                            text: 'Select organization type'
                        })
                    );

                    (response.data || []).forEach(function (item) {

                        select.append(
                            $('<option>', {
                                value: item.id,
                                text: item.name
                            })
                        );

                    });

                    if (selectedId !== null && selectedId !== undefined) {
                        select.val(String(selectedId));
                    }

                },

                error: function (xhr) {
                    console.error("Failed to load organization types:", xhr);
                }
            });

        }


        // ==========================================
        // GET ALL INDUSTRY CATEGORIES
        // ==========================================

        function getAllIndustryCategories(selectedId = null) {

            return $.ajax({
                type: "GET",
                url: industryCategoriesUrl,
                dataType: "json",

                success: function (response) {

                    const select = $("#inputIndustryCategory");

                    select.empty();

                    select.append(
                        $('<option>', {
                            value: '',
                            text: 'Select industry'
                        })
                    );

                    (response.data || []).forEach(function (item) {

                        select.append(
                            $('<option>', {
                                value: item.id,
                                text: item.name
                            })
                        );

                    });

                    if (selectedId !== null && selectedId !== undefined) {
                        select.val(String(selectedId));
                    }

                },

                error: function (xhr) {
                    console.error("Failed to load industry categories:", xhr);
                }
            });

        }


        // ==========================================
        // GET ALL INDUSTRY SECTORS
        // ==========================================

        function getAllIndustrySectors(selectedId = null) {

            return $.ajax({
                type: "GET",
                url: industrySectorsUrl,
                dataType: "json",

                success: function (response) {

                    const select = $("#inputIndustrySector");

                    select.empty();

                    select.append(
                        $('<option>', {
                            value: '',
                            text: 'Select industry sector'
                        })
                    );

                    (response.data || []).forEach(function (item) {

                        select.append(
                            $('<option>', {
                                value: item.id,
                                text: item.name
                            })
                        );

                    });

                    if (selectedId !== null && selectedId !== undefined) {
                        select.val(String(selectedId));
                    }

                },

                error: function (xhr) {
                    console.error("Failed to load industry sectors:", xhr);
                }
            });

        }


        // ==========================================
        // COMPANY LOGO DISPLAY
        // ==========================================

        function renderCompanyLogo(logoPath) {

            const container = $("#companyLogoContainer");

            container.empty();

            if (logoPath) {

                let logoUrl = logoPath;

                // If the API returns a relative storage path,
                // convert it into a public storage URL.
                if (
                    !logoPath.startsWith('http://') &&
                    !logoPath.startsWith('https://') &&
                    !logoPath.startsWith('/')
                ) {
                    logoUrl = "{{ asset('storage') }}/" + logoPath;
                }

                const image = $('<img>', {
                    src: logoUrl,
                    alt: (companyData?.company_name ?? "Company") + " logo",
                    css: {
                        width: "100%",
                        height: "100%",
                        objectFit: "contain"
                    }
                });

                image.on('error', function () {
                    container.html(`
                    <div class="text-center">
                        <i class="bi bi-building fs-1 text-primary"></i>
                        <div class="fw-bold mt-2">Company</div>
                    </div>
                `);
                });

                container.append(image);

            } else {

                container.html(`
                <div class="text-center">
                    <i class="bi bi-building fs-1 text-primary"></i>
                    <div class="fw-bold mt-2">Company</div>
                </div>
            `);

            }

        }


        // ==========================================
        // LOAD COMPANY PROFILE
        // ==========================================

        function loadCompanyProfile() {

            let url = organizationUrlTemplate.replace(
                '__ID__',
                encodeURIComponent(companyId)
            );

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",

                success: function (response) {

                    companyData = response.data;

                    if (!companyData) {
                        alert("Company information was not found.");
                        return;
                    }

                    console.log("Company data:", companyData);


                    // ==========================================
                    // COMPANY PROFILE DISPLAY
                    // ==========================================

                    $("#companyName").text(
                        companyData.company_name ?? ""
                    );

                    $("#address").text(
                        companyData.address ?? ""
                    );

                    $("#state").text(
                        companyData.state ?? ""
                    );

                    $("#postcode").text(
                        companyData.postcode ?? ""
                    );

                    $("#city").text(
                        companyData.city ?? ""
                    );


                    // ==========================================
                    // COMPANY INFORMATION DISPLAY
                    // ==========================================

                    $("#ssmNumber").text(
                        companyData.ssm_number ?? ""
                    );

                    $("#organizationType").text(
                        companyData.organization_type?.name ?? ""
                    );

                    $("#industry").text(
                        companyData.industry_category?.name ?? ""
                    );

                    $("#industrySector").text(
                        companyData.industry_sector?.name ?? ""
                    );

                    $("#companyEmail").text(
                        companyData.company_email ?? ""
                    );

                    $("#company_phone").text(
                        companyData.company_phone ?? ""
                    );


                    // ==========================================
                    // WEBSITE DISPLAY
                    // ==========================================

                    const website = companyData.website ?? "";

                    $("#companyWebsite")
                        .attr(
                            "href",
                            website && /^https?:\/\//i.test(website)
                                ? website
                                : "#"
                        )
                        .text(website);

                    if (!website) {
                        $("#companyWebsite").removeAttr("target");
                    } else {
                        $("#companyWebsite")
                            .attr("target", "_blank")
                            .attr("rel", "noopener noreferrer");
                    }


                    // ==========================================
                    // COMPANY DESCRIPTION DISPLAY
                    // ==========================================

                    $("#description").text(
                        companyData.description ?? ""
                    );


                    // ==========================================
                    // EDIT COMPANY PROFILE MODAL
                    // ==========================================

                    $("#profileCompanyName").val(
                        companyData.company_name ?? ""
                    );

                    $("#companyAddress").val(
                        companyData.address ?? ""
                    );

                    $("#companyState").val(
                        companyData.state ?? ""
                    );

                    $("#companyPostcode").val(
                        companyData.postcode ?? ""
                    );

                    $("#companyCity").val(
                        companyData.city ?? ""
                    );


                    // ==========================================
                    // EDIT COMPANY INFORMATION MODAL
                    // ==========================================

                    $("#inputSsmNumber").val(
                        companyData.ssm_number ?? ""
                    );

                    $("#inputCompanyEmail").val(
                        companyData.company_email ?? ""
                    );

                    $("#companyPhone").val(
                        companyData.company_phone ?? ""
                    );

                    $("#companyWebsiteInput").val(
                        companyData.website ?? ""
                    );


                    // ==========================================
                    // EDIT DESCRIPTION MODAL
                    // ==========================================

                    $("#companyDescription").val(
                        companyData.description ?? ""
                    );

                    updateDescriptionCounter();


                    // ==========================================
                    // LOAD PARENT TABLES
                    // ==========================================

                    getAllOrganizationTypes(
                        companyData.organization_type_id
                    );

                    getAllIndustryCategories(
                        companyData.industry_category_id
                    );

                    getAllIndustrySectors(
                        companyData.industry_sector_id
                    );


                    // ==========================================
                    // COMPANY LOGO
                    // ==========================================

                    renderCompanyLogo(
                        companyData.organization_logo
                    );

                },

                error: function (xhr) {

                    console.error("Failed to load company profile:", xhr);

                    alert(
                        xhr.responseJSON?.message ??
                        "Failed to load company profile."
                    );

                }
            });

        }


        // ==========================================
        // GET MY DATA
        // ==========================================

        function getMyData() {

            let url = profileUrlTemplate.replace(
                '__ID__',
                "{{ session('user_profile_id') }}"
            );

            return $.ajax({
                url: url,
                type: "GET",
                dataType: "json",

                success: function (response) {

                    const organizationUsers =
                        response.data?.organization_users ?? [];

                    if (
                        organizationUsers.length === 0 ||
                        !organizationUsers[0].organization
                    ) {
                        alert("No organization is associated with this profile.");
                        return;
                    }

                    companyId = organizationUsers[0].organization.id;

                    loadCompanyProfile();

                },

                error: function (xhr) {

                    console.error("Failed to load user profile:", xhr);

                    alert(
                        xhr.responseJSON?.message ??
                        "Failed to load user profile."
                    );

                }
            });

        }


        // ==========================================
        // UPDATE COMPANY PROFILE
        // ==========================================

        $("#editCompanyProfileForm").on("submit", function (event) {

            event.preventDefault();

            if (!companyId) {
                alert("Organization ID is not available.");
                return;
            }

            const form = this;
            const button = $("#saveCompanyProfileButton");

            const formData = {
                company_name: $("#profileCompanyName").val().trim(),
                address: $("#companyAddress").val().trim(),
                state: $("#companyState").val(),
                postcode: $("#companyPostcode").val().trim(),
                city: $("#companyCity").val().trim()
            };

            button.prop("disabled", true);

            $.ajax({
                url: getOrganizationUrl(updateOrganizationUrlTemplate),
                type: "PUT",
                data: formData,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },

                success: function (response) {

                    alert(
                        response.message ??
                        "Company profile updated successfully."
                    );

                    bootstrap.Modal
                        .getOrCreateInstance(
                            document.getElementById("editCompanyProfileModal")
                        )
                        .hide();

                    loadCompanyProfile();

                },

                error: function (xhr) {

                    console.error("Update company profile failed:", xhr);

                    if (xhr.status === 422) {

                        const errors = xhr.responseJSON?.errors ?? {};

                        const message = Object.values(errors)
                            .flat()
                            .join("\n");

                        alert(message || "Please check the submitted information.");

                    } else {

                        alert(
                            xhr.responseJSON?.message ??
                            "Failed to update company profile."
                        );

                    }

                },

                complete: function () {
                    button.prop("disabled", false);
                }
            });

        });


        // ==========================================
        // UPDATE COMPANY INFORMATION
        // ==========================================

        $("#editCompanyInformationForm").on("submit", function (event) {

            event.preventDefault();

            if (!companyId) {
                alert("Organization ID is not available.");
                return;
            }

            const button = $("#saveCompanyInformationButton");

            const formData = {
                company_name: companyData.company_name,
                address: companyData.address,
                postcode: companyData.postcode,
                city: companyData.city,
                state: companyData.state,

                ssm_number: $("#inputSsmNumber").val().trim(),
                organization_type_id: $("#inputOrganizationType").val(),
                industry_category_id: $("#inputIndustryCategory").val(),
                industry_sector_id: $("#inputIndustrySector").val(),
                company_email: $("#inputCompanyEmail").val().trim(),
                company_phone: $("#companyPhone").val().trim(),
                website: $("#companyWebsiteInput").val().trim() || null,

                description: companyData.description ?? ""
            };

            button.prop("disabled", true);

            $.ajax({
                url: getOrganizationUrl(updateOrganizationUrlTemplate),
                type: "PUT",
                data: formData,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },

                success: function (response) {

                    alert(
                        response.message ??
                        "Company information updated successfully."
                    );

                    bootstrap.Modal
                        .getOrCreateInstance(
                            document.getElementById("editCompanyInformationModal")
                        )
                        .hide();

                    loadCompanyProfile();

                },

                error: function (xhr) {

                    console.error("Update company information failed:", xhr);

                    if (xhr.status === 422) {

                        const errors = xhr.responseJSON?.errors ?? {};

                        const message = Object.values(errors)
                            .flat()
                            .join("\n");

                        alert(message || "Please check the submitted information.");

                    } else {

                        alert(
                            xhr.responseJSON?.message ??
                            "Failed to update company information."
                        );

                    }

                },

                complete: function () {
                    button.prop("disabled", false);
                }
            });

        });


        // ==========================================
        // UPDATE COMPANY DESCRIPTION
        // ==========================================

        $("#editCompanyDescriptionForm").on("submit", function (event) {

            event.preventDefault();

            if (!companyId) {
                alert("Organization ID is not available.");
                return;
            }

            const button = $("#saveCompanyDescriptionButton");

            const formData = {
                company_name: companyData.company_name,
                ssm_number: companyData.ssm_number,

                industry_category_id: companyData.industry_category_id,
                industry_sector_id: companyData.industry_sector_id,
                organization_type_id: companyData.organization_type_id,

                address: companyData.address,
                postcode: companyData.postcode,
                city: companyData.city,
                state: companyData.state,

                website: companyData.website || null,
                company_email: companyData.company_email,
                company_phone: companyData.company_phone,

                description: $("#companyDescription").val().trim()
            };

            button.prop("disabled", true);

            $.ajax({
                url: getOrganizationUrl(updateOrganizationUrlTemplate),
                type: "PUT",
                data: formData,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },

                success: function (response) {

                    alert(
                        response.message ??
                        "Company description updated successfully."
                    );

                    bootstrap.Modal
                        .getOrCreateInstance(
                            document.getElementById("editCompanyDescriptionModal")
                        )
                        .hide();

                    loadCompanyProfile();

                },

                error: function (xhr) {

                    console.error("Update company description failed:", xhr);

                    if (xhr.status === 422) {

                        const errors = xhr.responseJSON?.errors ?? {};

                        const message = Object.values(errors)
                            .flat()
                            .join("\n");

                        alert(message || "Please check the submitted information.");

                    } else {

                        alert(
                            xhr.responseJSON?.message ??
                            "Failed to update company description."
                        );

                    }

                },

                complete: function () {
                    button.prop("disabled", false);
                }
            });

        });


        // ==========================================
        // DESCRIPTION CHARACTER COUNTER
        // ==========================================

        const description = document.getElementById("companyDescription");

        const counter = document.getElementById("descriptionCharacterCount");

        function updateDescriptionCounter() {

            if (description && counter) {
                counter.textContent = description.value.length;
            }

        }

        if (description) {
            description.addEventListener("input", updateDescriptionCounter);
        }


        // ==========================================
        // COMPANY LOGO PREVIEW
        // ==========================================

        const logoInput = document.getElementById("changeLogoCompany");

        if (logoInput) {

            logoInput.addEventListener("change", function () {

                const file = this.files[0];

                if (!file) {
                    return;
                }

                const allowedTypes = [
                    "image/jpeg",
                    "image/png",
                    "image/webp"
                ];

                if (!allowedTypes.includes(file.type)) {
                    alert("Please select a JPG, PNG or WebP image.");
                    this.value = "";
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert("The logo must not exceed 2MB.");
                    this.value = "";
                    return;
                }

                const previewUrl = URL.createObjectURL(file);

                $("#companyLogoContainer").empty().append(
                    $("<img>", {
                        src: previewUrl,
                        alt: "Company logo preview",
                        css: {
                            width: "100%",
                            height: "100%",
                            objectFit: "contain"
                        }
                    })
                );

                // Upload the selected logo.
                uploadOrganizationLogo(file);

            });

        }


        // ==========================================
        // UPLOAD COMPANY LOGO
        // ==========================================

        function uploadOrganizationLogo(file) {

            if (!companyId) {
                alert("Organization ID is not available.");
                return;
            }

            const formData = new FormData();

            formData.append("organization_logo", file);

            $.ajax({
                url: getOrganizationUrl(uploadOrganizationLogoUrlTemplate),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },

                success: function (response) {

                    alert(
                        response.message ??
                        "Organization logo uploaded successfully."
                    );

                    // Reload the logo using the latest saved data.
                    loadCompanyProfile();

                },

                error: function (xhr) {

                    console.error("Logo upload failed:", xhr);

                    if (xhr.status === 422) {

                        const errors = xhr.responseJSON?.errors ?? {};

                        const message = Object.values(errors)
                            .flat()
                            .join("\n");

                        alert(message || "Please select a valid logo.");

                    } else {

                        alert(
                            xhr.responseJSON?.message ??
                            "Failed to upload company logo."
                        );

                    }

                    // Restore the currently saved logo after failure.
                    renderCompanyLogo(companyData?.organization_logo);

                    logoInput.value = "";

                }
            });

        }


        // ==========================================
        // INITIAL LOAD
        // ==========================================

        getMyData();

    });
</script>

@endsection
