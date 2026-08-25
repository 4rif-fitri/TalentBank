@extends('layouts.internship-layouts')

@section('css')
<style>
    /* --- Talent Finder Layout --- */
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
        /* Z-index tinggi sikit dari sidebar utama */
    }

    /* --- Mobile / Off-Canvas Filter --- */
    @media (max-width: 1000px) {
        .talent-layout {
            display: block;
            /* Buang flexbox supaya results penuh skrin */
        }

        .filter-panel {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            max-width: 85vw;
            height: 100vh;
            z-index: 1050;
            /* Z-index paling tinggi untuk berada atas overlay */
            border-radius: 0;
            overflow-y: auto;
            transform: translateX(-100%);
            /* Sorok filter di luar skrin kiri */
        }

        /* Class yang ditambah dengan JS */
        body.filter-open .filter-panel {
            transform: translateX(0);
            /* Tarik masuk skrin */
        }

        body.filter-open .filter-overlay {
            display: block;
            /* Tunjuk background gelap */
        }

        body.filter-open {
            overflow: hidden;
            /* Elakkan user scroll page belakang masa filter terbukak */
        }
    }

    /* --- Custom Select2 supaya sama macam UI design --- */

    /* Ubah rupa kotak input utama */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #dee2e6 !important;
        /* Ikut border Bootstrap */
        border-radius: 0.375rem !important;
        min-height: 48px !important;
        padding: 4px !important;
    }

    /* Ubah rupa tag (Chips) */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #f0f6ff !important;
        /* Warna background biru cair */
        border: 1px solid #bfdbfe !important;
        /* Warna border biru */
        color: #2563eb !important;
        /* Warna teks biru pekat */
        border-radius: 6px !important;
        padding: 4px 8px !important;
        margin-top: 4px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
    }

    /* Ubah butang 'x' pada tag */
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #2563eb !important;
        margin-right: 6px !important;
        border-right: none !important;
        /* Buang garisan pembahagi default */
        font-weight: normal !important;
    }

    /* Warna butang 'x' bila cursor hover */
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

        /* Tambahan kod untuk pastikan ia hilang bila ditekan */
        position: relative !important;
        z-index: 2 !important;
        /* Paksa 'x' timbul di atas */
        cursor: pointer !important;
        /* Tukar cursor jadi bentuk tangan */
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
                <label class="form-label text-muted small fw-bold">University</label>
                <select id="selectUniversiti" class="form-select select2-skills" multiple="multiple"
                    data-placeholder="Select or type skills...">
                    <option value="1">UTEM</option>
                    <option value="2">UTM</option>
                    <option value="3">UTHM</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Skill</label>
                <!-- Guna class custom 'select2-skills' untuk diaktifkan dalam JS nanti -->
                <select id="selectSkill" class="form-select select2-skills" multiple="multiple"
                    data-placeholder="Select or type skills...">
                    <option value="1">React</option>
                    <option value="2">Laravel</option>
                    <option value="3">Figma</option>
                    <option value="3">Node.js</option>
                    <option value="4">Python</option>
                    <option value="5">Vue</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Language</label>
                <!-- Guna class custom 'select2-skills' untuk diaktifkan dalam JS nanti -->
                <select id="selectLanguage" class="form-select select2-skills" multiple="multiple"
                    data-placeholder="Select or type skills...">
                    <option value="1">Malay</option>
                    <option value="2">Mandarin</option>
                    <option value="3">English</option>
                    <option value="3">Tamil</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Qualifications</label>
                <!-- Guna class custom 'select2-skills' untuk diaktifkan dalam JS nanti -->
                <select id="selectQualifications" class="form-select select2-skills" multiple="multiple"
                    data-placeholder="Select or type skills...">
                    <option value="Degree">Degree</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Kuala Lumpur">Johor</option>
                </select>
            </div>

            <!-- Toggle Switch -->
            <div class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" id="verifiedSwitch" checked>
                <label class="form-check-label text-muted small fw-bold" for="verifiedSwitch">University
                    Verified</label>
            </div>
            <button class="btn btn-outline-danger w-100 mt-2">Reset</button>
            <button class="btn btn-primary w-100 mt-2" id="btnFilter">Filter</button>
        </aside>

        <div class="results-panel flex-grow-1">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <span class="fw-bold">324 students found</span>
            </div>

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

            <nav aria-label="..." class="mt-2 d-flex justify-content-center">
                <ul class="pagination">
                    <li class="page-item"><a href="#" class="page-link">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active">
                        <a class="page-link" href="#" aria-current="page">2</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
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

        $('.select2-skills').select2({
            width: '100%',
            placeholder: function () {
                return $(this).data('placeholder');
            },
            allowClear: true
        });

        $(document).on("click", "#btnFilter", function () {

            let formData = new FormData();

            // University
            let universities = $("#selectUniversiti").val() || [];

            // Skill
            let skills = $("#selectSkill").val() || [];

            // Language
            let languages = $("#selectLanguage").val() || [];

            // Qualification
            let qualifications = $("#selectQualifications").val() || [];

            // Verified
            let verified = $("#verifiedSwitch").is(":checked");

            // Append array values
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

            console.log({
                universities,
                skills,
                languages,
                qualifications,
                verified
            });

        });

    });
</script>
@endsection
