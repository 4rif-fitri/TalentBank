@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/company-profile.css') }}">

<div class="container-fluid company-profile py-2">

    <section class="profile-card company-banner bg-body">

        <div class="company-logo">
            @if(!empty($company['logo']))
            <img src="{{ asset('storage/' . $company['logo']) }}" alt="{{ $company['name'] }} logo">
            @else
            <div class="text-center">
                <i class="bi bi-building fs-1 text-primary"></i>
                <div class="fw-bold mt-2">
                    UTEM
                </div>
            </div>
            @endif
        </div>

        <div class="flex-grow-1">

            <h1 class="company-name">
                Universiti Teknikal Malaysia Melaka(UTeM)
            </h1>

            <p class="company-tagline">
                Address
            </p>

            <div class="company-meta">

                <span>
                    <i class="bi bi-building"></i>
                    State
                </span>

                <span>
                    <i class="bi bi-geo-alt"></i>
                    postcode
                </span>

                <span>
                    <i class="bi bi-people"></i>
                    city
                </span>

            </div>
        </div>

        <button type="button" class="btn-edit position-absolute top-0 end-0 m-3" data-bs-toggle="modal"
            data-bs-target="#editCompanyProfileModal">
            Edit
            <i class="bi bi-pencil ms-2"></i>
        </button>

    </section>

    <section class="row" id="overview">

        {{-- Left Column --}}
        <div class="col-12 col-xl-8">

            {{-- Company Information --}}
            <div class="profile-card bg-body" id="company-information">

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <h2 class="profile-heading border-0 p-0 mb-0">
                        Company Information
                    </h2>

                    <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editCompanyInformationModal">
                        <i class="bi bi-pencil-fill me-1"></i>
                        Edit
                    </button>
                </div>

                <div>

                    <div class="info-row">
                        <div class="info-label">ssm number</div>
                        <div class="info-value">
                            12345678958
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">ORGANIZATION_TYPE</div>
                        <div class="info-value">
                            ORGANIZATION_TYPE
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Industry</div>
                        <div class="info-value">
                            Education
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">industry_sector</div>
                        <div class="info-value">
                            industry_sector
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Company email</div>
                        <div class="info-value">
                            company_email
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">company_phone</div>
                        <div class="info-value">
                            company_phone
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Website</div>
                        <div class="info-value">
                            <a href="" target="_blank" rel="noopener noreferrer" class="company-link">
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-12 col-xl-4">

            <div class="profile-card bg-body">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <h2 class="profile-heading border-0 p-0 mb-0">
                        Company Description
                    </h2>
                <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editCompanyDescriptionModal">
                    <i class="bi bi-pencil-fill me-1"></i>
                    Edit
                </button>
                </div>
                <p class="description-text mt-3">
                    Description
                </p>
            </div>

        </div>

    </section>
</div>

<div class="modal fade" id="editCompanyProfileModal" tabindex="-1" aria-labelledby="editCompanyProfileLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">

                    {{-- Company Logo --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Company Logo
                        </label>

                        <div class="d-flex align-items-center gap-3">

                            <div class="border rounded-3 p-2 bg-light">
                                @if(!empty($company['logo']))
                                <img src="{{ asset('storage/' . $company['logo']) }}" alt="Company logo"
                                    id="companyLogoPreview" style="width:80px;height:80px;object-fit:contain;">
                                @else
                                <div id="companyLogoPlaceholder"
                                    class="d-flex align-items-center justify-content-center"
                                    style="width:80px;height:80px;">
                                    <i class="bi bi-building fs-1 text-primary"></i>
                                </div>

                                <img src="" alt="Company logo preview" id="companyLogoPreview" class="d-none"
                                    style="width:80px;height:80px;object-fit:contain;">
                                @endif
                            </div>

                            <div>
                                <input type="file" class="form-control" name="logo" id="companyLogo"
                                    accept="image/png,image/jpeg,image/webp">

                                <small class="text-muted">
                                    PNG, JPG or WEBP. Maximum 2MB.
                                </small>
                            </div>

                        </div>
                    </div>

                    {{-- Company Name --}}
                    <div class="mb-3">
                        <label for="profileCompanyName" class="form-label fw-semibold">
                            Company Name <span class="text-danger">*</span>
                        </label>

                        <input type="text" class="form-control" id="profileCompanyName" name="company_name"
                            value="Universiti Teknikal Malaysia Melaka (UTeM)" required>
                    </div>

                    {{-- Address --}}
                    <div class="mb-3">
                        <label for="companyAddress" class="form-label fw-semibold">
                            Address <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control" id="companyAddress" name="address" rows="3"
                            placeholder="Enter company address" required></textarea>
                    </div>

                    <div class="row g-3">

                        {{-- State --}}
                        <div class="col-md-6">
                            <label for="companyState" class="form-label fw-semibold">
                                State <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" id="companyState" name="state" required>
                                <option value="">Select state</option>
                                <option value="Melaka" selected>Melaka</option>
                                <option value="Johor">Johor</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Perak">Perak</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="Pulau Pinang">Pulau Pinang</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Putrajaya">Putrajaya</option>
                                <option value="Labuan">Labuan</option>
                            </select>
                        </div>

                        {{-- Postcode --}}
                        <div class="col-md-6">
                            <label for="companyPostcode" class="form-label fw-semibold">
                                Postcode <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="companyPostcode" name="postcode"
                                inputmode="numeric" maxlength="5" placeholder="e.g. 76100" required>
                        </div>

                        {{-- City --}}
                        <div class="col-12">
                            <label for="companyCity" class="form-label fw-semibold">
                                City <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="companyCity" name="city"
                                placeholder="e.g. Durian Tunggal" required>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCompanyInformationModal" tabindex="-1" aria-labelledby="editCompanyInformationLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form action="#" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="editCompanyInformationLabel">
                            <i class="bi bi-card-list text-primary me-2"></i>
                            Company Information
                        </h5>

                        <small class="text-muted">
                            Manage your company's registration and contact details.
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- SSM Number --}}
                        <div class="col-md-6">
                            <label for="ssmNumber" class="form-label fw-semibold">
                                SSM Registration Number
                            </label>

                            <input type="text" class="form-control" id="ssmNumber" name="ssm_number" value="12345678958"
                                placeholder="Enter SSM number">
                        </div>

                        {{-- Organization Type --}}
                        <div class="col-md-6">
                            <label for="organizationType" class="form-label fw-semibold">
                                Organization Type
                            </label>

                            <select class="form-select" id="organizationType" name="organization_type">

                                <option value="">Select organization type</option>
                                <option value="Government">Government</option>
                                <option value="Private">Private</option>
                                <option value="Public University" selected>
                                    Public University
                                </option>
                                <option value="Private University">
                                    Private University
                                </option>
                                <option value="NGO">NGO</option>
                                <option value="Other">Other</option>

                            </select>
                        </div>

                        {{-- Industry --}}
                        <div class="col-md-6">
                            <label for="companyIndustry" class="form-label fw-semibold">
                                Industry
                            </label>

                            <select class="form-select" id="companyIndustry" name="industry">

                                <option value="">Select industry</option>
                                <option value="Education" selected>Education</option>
                                <option value="Information Technology">
                                    Information Technology
                                </option>
                                <option value="Engineering">Engineering</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Finance">Finance</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Energy & Utilities">
                                    Energy & Utilities
                                </option>
                                <option value="Other">Other</option>

                            </select>
                        </div>

                        {{-- Industry Sector --}}
                        <div class="col-md-6">
                            <label for="industrySector" class="form-label fw-semibold">
                                Industry Sector
                            </label>

                            <input type="text" class="form-control" id="industrySector" name="industry_sector"
                                placeholder="e.g. Higher Education">
                        </div>

                        {{-- Company Email --}}
                        <div class="col-md-6">
                            <label for="companyEmail" class="form-label fw-semibold">
                                Company Email
                            </label>

                            <input type="email" class="form-control" id="companyEmail" name="company_email"
                                placeholder="company@example.com">
                        </div>

                        {{-- Company Phone --}}
                        <div class="col-md-6">
                            <label for="companyPhone" class="form-label fw-semibold">
                                Company Phone
                            </label>

                            <input type="tel" class="form-control" id="companyPhone" name="company_phone"
                                placeholder="+60 6-123 4567">
                        </div>

                        {{-- Website --}}
                        <div class="col-12">
                            <label for="companyWebsite" class="form-label fw-semibold">
                                Website
                            </label>

                            <input type="url" class="form-control" id="companyWebsite" name="website"
                                placeholder="https://www.example.com">
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCompanyDescriptionModal" tabindex="-1" aria-labelledby="editCompanyDescriptionLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <form action="#" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="editCompanyDescriptionLabel">
                            <i class="bi bi-card-text text-primary me-2"></i>
                            Company Description
                        </h5>

                        <small class="text-muted">
                            Tell candidates more about your organization.
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="companyDescription" class="form-label fw-semibold">
                            Description <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control" id="companyDescription" name="description" rows="7"
                            maxlength="2000" placeholder="Describe your company, services, values and goals..."
                            required></textarea>

                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">
                                Provide a clear description of your organization.
                            </small>

                            <small class="text-muted">
                                <span id="descriptionCharacterCount">0</span>/2000
                            </small>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary px-4">
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

        let companyId, companyData

        function loadCompanyProfile(){
            let url = "{{ route('organization.getOrganizationById', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", `${companyId}`);

            $.ajax({
                url,
                type: "GET",
                success: function (response) {
                    companyData = response.data
                    console.log(companyData);
                },
                error: function(xhr){
                    console.log(xhr);
                }
            });
        }

        function getMyData() {
            let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", "{{ session('user_profile_id') }}");

            return $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    companyId = response.data.organization_users[0].organization.id

                    loadCompanyProfile()
                },
                error: function (xhr) {
                    console.error(xhr);
                }
            });
        }

        getMyData()

        const description = document.getElementById('companyDescription');
        const counter = document.getElementById('descriptionCharacterCount');

        if (description && counter) {
            function updateCounter() {
                counter.textContent = description.value.length;
            }

            description.addEventListener('input', updateCounter);
            updateCounter();
        }

        // Preview company logo
        const logoInput = document.getElementById('companyLogo');
        const logoPreview = document.getElementById('companyLogoPreview');
        const logoPlaceholder = document.getElementById('companyLogoPlaceholder');

        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function () {
                const file = this.files[0];

                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert('Please select a valid image file.');
                    this.value = '';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('The logo must not exceed 2MB.');
                    this.value = '';
                    return;
                }

                logoPreview.src = URL.createObjectURL(file);
                logoPreview.classList.remove('d-none');

                if (logoPlaceholder) {
                    logoPlaceholder.classList.add('d-none');
                }
            });
        }
    });
</script>
@endsection
