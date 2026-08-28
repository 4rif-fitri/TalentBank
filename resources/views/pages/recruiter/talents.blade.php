@extends('layouts.internship-layouts')

@section('css')
<style>
    .talent-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .filter-panel {
        width: 260px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .filter-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        z-index: 1040;
    }

    @media (max-width: 1000px) {
        .talent-layout {
            display: block;
        }

        .filter-panel {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            max-width: 85vw;
            height: 100vh;
            z-index: 1050;
            border-radius: 0;
            overflow-y: auto;
            transform: translateX(-100%);
        }

        body.filter-open .filter-panel {
            transform: translateX(0);
        }

        body.filter-open .filter-overlay {
            display: block;
        }

        body.filter-open {
            overflow: hidden;
        }
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #dee2e6 !important;
        border-radius: 0.375rem !important;
        min-height: 48px !important;
        padding: 4px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #f0f6ff !important;
        border: 1px solid #bfdbfe !important;
        color: #2563eb !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        margin-top: 4px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #2563eb !important;
        margin-right: 6px !important;
        border-right: none !important;
        font-weight: normal !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background-color: transparent !important;
        color: #1d4ed8 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #2563eb !important;
        border: none !important;
        font-weight: 500 !important;
        font-size: 18px !important;
        line-height: 1 !important;
        margin-right: 0 !important;
        padding: 0 !important;
        position: relative !important;
        z-index: 2 !important;
        cursor: pointer !important;
    }
</style>
@endsection

@section('content')
<div class="content p-4">

    <!-- Header & Toggle Button (Mobile) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="m-0 fw-bold">Find University Talent</h3>
        <button class="btn btn-primary d-lg-none btn-toggle-filter" onclick="toggleFilter()">
            <i class="fa-solid fa-filter"></i> Filters
        </button>
    </div>

    <div class="talent-layout">

        <aside class="filter-panel" id="filterPanel">
            <!-- Header Mobile Filter -->
            <div class="d-flex justify-content-between align-items-center d-md-none mb-3 border-bottom pb-3">
                <h5 class="m-0 fw-bold">Filters</h5>
                <button class="btn btn-sm btn-light" onclick="toggleFilter()">
                    <i class="fa-solid fa-xmark fs-5"></i>
                </button>
            </div>

            <!-- Header Desktop Filter -->
            <div class="d-none d-md-flex justify-content-between align-items-center mb-4">
                <span class="fw-bold">Filters</span>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold" id="searchName">Name</label>
                <input type="email" class="form-control" id="searchName" placeholder="Name">
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">University</label>
                <select id="selectUniversiti" class="form-select select2-skills" multiple="multiple"
                    data-placeholder="Select or type skills...">
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Skill</label>
                <select id="selectSkill" class="form-select select2-skills" multiple="multiple"
                    data-placeholder="Select or type skills...">
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Language</label>
                <select id="selectLanguage" class="form-select select2-skills" multiple="multiple"
                    data-placeholder="Select or type skills...">
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Qualifications</label>
                <select id="selectQualifications" class="form-select select2-skills" multiple="multiple"
                    data-placeholder="Select or type skills...">
                    <option value="Degree">Degree</option>
                    <option value="Diploma">Diploma</option>
                </select>
            </div>

            <div class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" id="verifiedSwitch" checked>
                <label class="form-check-label text-muted small fw-bold" for="verifiedSwitch">University
                    Verified</label>
            </div>
            <button class="btn btn-outline-danger w-100 mt-2" id="btnResetFilter">Reset</button>
            <button class="btn btn-primary w-100 mt-2" id="btnFilter">Filter</button>
        </aside>

        <div class="results-panel flex-grow-1">


            <div class="row g-3">

                <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div></div>
                            <i class="fa-regular fa-heart text-muted" style="cursor:pointer;"></i>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name=Ali+Ahmad&background=random" class="rounded"
                                width="60" alt="Profile">
                            <div>
                                <h6 class="m-0 fw-bold">Ali Ahmad Bin Abl Kasim</h6>
                                <small class="text-primary" style="font-size: 11px;"><i
                                        class="fa-solid fa-circle-check"></i> Verified
                                    Student</small>
                            </div>
                        </div>
                        <div class="mb-2" style="font-size: 12px;">
                            <p class="m-0 text-dark">Universiti Malaya</p>
                            <p class="m-0 text-muted">Bachelor of Computer Science</p>
                            <p class="m-0 text-muted mt-1">CGPA 3.78</p>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">

                            <span class="badge bg-light text-dark border fw-normal">
                                <i class="fa-brands fa-youtube"></i>
                                React
                            </span>
                            <span class="badge bg-light text-dark border fw-normal">Laravel</span>
                            <span class="badge bg-light text-dark border fw-normal">Node.js</span>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="profile-pelajar.html" target="_blank"
                                class="btn btn-sm btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center">
                                View Profile
                            </a>
                            <button class="btn btn-sm btn-primary w-50 fw-bold">Add to
                                Shortlist</button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div></div>
                            <i class="fa-regular fa-heart text-muted" style="cursor:pointer;"></i>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name=Ali+Ahmad&background=random" class="rounded"
                                width="60" alt="Profile">
                            <div>
                                <h6 class="m-0 fw-bold">Ali Ahmad Bin Abl Kasim</h6>
                                <small class="text-primary" style="font-size: 11px;"><i
                                        class="fa-solid fa-circle-check"></i>
                                    Verified Student</small>
                            </div>
                        </div>
                        <div class="mb-2" style="font-size: 12px;">
                            <p class="m-0 text-dark">Universiti Malaya</p>
                            <p class="m-0 text-muted">Bachelor of Computer Science</p>
                            <p class="m-0 text-muted mt-1">CGPA 3.78</p>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">

                            <span class="badge bg-light text-dark border fw-normal">
                                <i class="fa-brands fa-youtube"></i>
                                React
                            </span>
                            <span class="badge bg-light text-dark border fw-normal">Laravel</span>
                            <span class="badge bg-light text-dark border fw-normal">Node.js</span>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="profile-pelajar.html" target="_blank"
                                class="btn btn-sm btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center">
                                View Profile
                            </a>
                            <button class="btn btn-sm btn-primary w-50 fw-bold">Add to
                                Shortlist</button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div></div>
                            <i class="fa-regular fa-heart text-muted" style="cursor:pointer;"></i>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name=Ali+Ahmad&background=random" class="rounded"
                                width="60" alt="Profile">
                            <div>
                                <h6 class="m-0 fw-bold">Ali Ahmad Bin Abl Kasim</h6>
                                <small class="text-primary" style="font-size: 11px;"><i
                                        class="fa-solid fa-circle-check"></i>
                                    Verified Student</small>
                            </div>
                        </div>
                        <div class="mb-2" style="font-size: 12px;">
                            <p class="m-0 text-dark">Universiti Malaya</p>
                            <p class="m-0 text-muted">Bachelor of Computer Science</p>
                            <p class="m-0 text-muted mt-1">CGPA 3.78</p>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">

                            <span class="badge bg-light text-dark border fw-normal">
                                <i class="fa-brands fa-youtube"></i>
                                React
                            </span>
                            <span class="badge bg-light text-dark border fw-normal">Laravel</span>
                            <span class="badge bg-light text-dark border fw-normal">Node.js</span>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="profile-pelajar.html" target="_blank"
                                class="btn btn-sm btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center">
                                View Profile
                            </a>
                            <button class="btn btn-sm btn-primary w-50 fw-bold">Add to
                                Shortlist</button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div></div>
                            <i class="fa-regular fa-heart text-muted" style="cursor:pointer;"></i>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name=Ali+Ahmad&background=random" class="rounded"
                                width="60" alt="Profile">
                            <div>
                                <h6 class="m-0 fw-bold">Ali Ahmad Bin Abl Kasim</h6>
                                <small class="text-primary" style="font-size: 11px;"><i
                                        class="fa-solid fa-circle-check"></i> Verified
                                    Student</small>
                            </div>
                        </div>
                        <div class="mb-2" style="font-size: 12px;">
                            <p class="m-0 text-dark">Universiti Malaya</p>
                            <p class="m-0 text-muted">Bachelor of Computer Science</p>
                            <p class="m-0 text-muted mt-1">CGPA 3.78</p>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">

                            <span class="badge bg-light text-dark border fw-normal">
                                <i class="fa-brands fa-youtube"></i>
                                React
                            </span>
                            <span class="badge bg-light text-dark border fw-normal">Laravel</span>
                            <span class="badge bg-light text-dark border fw-normal">Node.js</span>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="profile-pelajar.html" target="_blank"
                                class="btn btn-sm btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center">
                                View Profile
                            </a>
                            <button class="btn btn-sm btn-primary w-50 fw-bold">Add to
                                Shortlist</button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div></div>
                            <i class="fa-regular fa-heart text-muted" style="cursor:pointer;"></i>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name=Ali+Ahmad&background=random" class="rounded"
                                width="60" alt="Profile">
                            <div>
                                <h6 class="m-0 fw-bold">Ali Ahmad Bin Abl Kasim</h6>
                                <small class="text-primary" style="font-size: 11px;"><i
                                        class="fa-solid fa-circle-check"></i>
                                    Verified Student</small>
                            </div>
                        </div>
                        <div class="mb-2" style="font-size: 12px;">
                            <p class="m-0 text-dark">Universiti Malaya</p>
                            <p class="m-0 text-muted">Bachelor of Computer Science</p>
                            <p class="m-0 text-muted mt-1">CGPA 3.78</p>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">

                            <span class="badge bg-light text-dark border fw-normal">
                                <i class="fa-brands fa-youtube"></i>
                                React
                            </span>
                            <span class="badge bg-light text-dark border fw-normal">Laravel</span>
                            <span class="badge bg-light text-dark border fw-normal">Node.js</span>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="profile-pelajar.html" target="_blank"
                                class="btn btn-sm btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center">
                                View Profile
                            </a>
                            <button class="btn btn-sm btn-primary w-50 fw-bold">Add to
                                Shortlist</button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div></div>
                            <i class="fa-regular fa-heart text-muted" style="cursor:pointer;"></i>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name=Ali+Ahmad&background=random" class="rounded"
                                width="60" alt="Profile">
                            <div>
                                <h6 class="m-0 fw-bold">Ali Ahmad Bin Abl Kasim</h6>
                                <small class="text-primary" style="font-size: 11px;"><i
                                        class="fa-solid fa-circle-check"></i>
                                    Verified Student</small>
                            </div>
                        </div>
                        <div class="mb-2" style="font-size: 12px;">
                            <p class="m-0 text-dark">Universiti Malaya</p>
                            <p class="m-0 text-muted">Bachelor of Computer Science</p>
                            <p class="m-0 text-muted mt-1">CGPA 3.78</p>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">

                            <span class="badge bg-light text-dark border fw-normal">
                                <i class="fa-brands fa-youtube"></i>
                                React
                            </span>
                            <span class="badge bg-light text-dark border fw-normal">Laravel</span>
                            <span class="badge bg-light text-dark border fw-normal">Node.js</span>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="profile-pelajar.html" target="_blank"
                                class="btn btn-sm btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center">
                                View Profile
                            </a>
                            <button class="btn btn-sm btn-primary w-50 fw-bold">Add to
                                Shortlist</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-2 d-flex justify-content-between align-items-center">
                <span class="fw-bold">1000 students found</span>
                <nav aria-label="..." class="">
                    <ul class="pagination">
                        <li class="page-item"><a href="#" class="page-link">Previous</a></li>
                        <li class="page-item"><a class="page-link active" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="filter-overlay" onclick="toggleFilter()"></div>
@endsection

@section('script')
<script>
    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }

    $(document).ready(function () {
        let state = {
            allOrganizations: [],
            allLanguages: [],
            allSkills: [],
            allQualifications: []
        }

        function setOptions() {
            $("#selectUniversiti").empty()
            state.allOrganizations.forEach(organization => {
                let name = organization.company_name.split(" ")
                let lastName = name.length
                let shortName = name[lastName - 1].replace("(", "").replace(")", "")
                $("#selectUniversiti").append(`<option value="${organization.id}">${shortName}</option>`)
            })

            $("#selectLanguage").empty()
            state.allLanguages.forEach(language => {
                $("#selectLanguage").append(`<option value="${language.id}">${language.language_name}</option>`)
            })

            $("#selectSkill").empty()
            state.allSkills.forEach(skill => {
                $("#selectSkill").append(`<option value="${skill.id}">${skill.skill_name}</option>`)
            })
        }

        function getAllOrganizations() {
            return $.ajax({
                url: "{{ route('organization.getAllOrganizations') }}",
                type: 'GET',
                success: response => {
                    state.allOrganizations = response.data
                },
                error: xhr => {
                    console.log(xhr);
                }
            })
        }

        function getAllLanguages() {
            return $.ajax({
                url: "{{ route('languages.getAllLanguages') }}",
                type: 'GET',
                success: response => {
                    state.allLanguages = response.data
                },
                error: xhr => {
                    console.log(xhr);
                }
            })
        }

        function getAllSkills() {
            return $.ajax({
                url: "{{ route('skills.getAllSkills') }}",
                type: 'GET',
                success: response => {
                    state.allSkills = response.data
                },
                error: xhr => {
                    console.log(xhr);
                }
            })
        }

        function getAllQualifications() {
            // return $.ajax({
            //     getAllQualifications
            //     url: "{{ route('languages.getAllLanguages') }}",
            //     type: 'GET',
            //     dataType: "json",
            //     success: response => {
            //         state.allLanguages = response.data
            //     },
            //     error: xhr => {
            //         console.log(xhr);
            //     }
            // })

        }

        async function getData() {
            await Promise.all([
                getAllOrganizations(),
                getAllLanguages(),
                getAllSkills()
            ]);

            setOptions()
        }
        getData()

        $('.select2-skills').select2({
            width: '100%',
            placeholder: function () {
                return $(this).data('placeholder');
            },
            allowClear: true
        });

        $(document).on("click", "#btnResetFilter", () => {
            $("#selectUniversiti").val(null).trigger("change");
            $("#selectLanguage").val(null).trigger("change");
            $("#selectSkill").val(null).trigger("change");
            $("#selectQualifications").val(null).trigger("change");

            $("#verifiedSwitch").prop("checked", false);
            $("#searchName").val("");
        })

        function getAndSentDataFilter() {
            let universities = $("#selectUniversiti").val() || [];
            let skills = $("#selectSkill").val() || [];
            let languages = $("#selectLanguage").val() || [];
            let qualifications = $("#selectQualifications").val() || [];
            let verified = $("#verifiedSwitch").is(":checked");
            let name = $("#searchName").val() || "";

            let formData = new FormData();


            universities.forEach(function (value) {
                formData.append("universities[]", value);
            });

            skills.forEach(function (value) {
                formData.append("skills[]", value);
            });

            languages.forEach(function (value) {
                formData.append("languages[]", value);
            });

            qualifications.forEach(function (value) {
                formData.append("qualifications[]", value);
            });

            formData.append("verified", verified ? 1 : 0);
            formData.append("name", name);

            for (const key of formData) {
                console.log(key);
            }
        }
        getAndSentDataFilter()
        $(document).on("click", "#btnFilter", () => getAndSentDataFilter());
    });
</script>
@endsection