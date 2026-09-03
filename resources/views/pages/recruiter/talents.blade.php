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

            <div class="d-none d-md-flex justify-content-between align-items-center mb-4">
                <span class="fw-bold">Filters</span>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Name</label>
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
                </select>
            </div>

            <button class="btn btn-outline-danger w-100 mt-2" id="btnResetFilter">Reset</button>
            <button class="btn btn-primary w-100 mt-2" id="btnFilter">Filter</button>
        </aside>

        <div class="results-panel flex-grow-1">

            <div class="row g-3" id="talent-cards"></div>

            <div class="mt-2 d-flex justify-content-between align-items-center">
                <span class="fw-bold" id="total-found"></span>
                <nav aria-label="..." class="">
                    <ul class="pagination">
                        <!--
                        <li class="page-item"><a href="#" class="page-link">Previous</a></li>
                        <li class="page-item"><a class="page-link active" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                         -->
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
    let current_page = 1
    let last_page

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

            $("#selectQualifications").empty()
            state.allQualifications.forEach(qualification => {
                $("#selectQualifications").append(`<option value="${qualification.id}">${qualification.name}</option>`)
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
            return $.ajax({
                url: "{{ route('programme.getAllQualifications') }}",
                type: 'GET',
                dataType: "json",
                success: response => {
                    state.allQualifications = response.data
                },
                error: xhr => {
                    console.log(xhr);
                }
            })
        }

        async function getData() {
            await Promise.all([
                getAllOrganizations(),
                getAllLanguages(),
                getAllSkills(),
                getAllQualifications()
            ]);
            setOptions()
        }

        $('.select2-skills').select2({
            width: '100%',
            placeholder: function () {
                return $(this).data('placeholder');
            },
            allowClear: true
        });

        function renderPagination() {
            $(".pagination").empty();

            $(".pagination").append(`
                <li data-page="${current_page > 1 ? current_page - 1 : ""}"
                    class="page-target page-item ${current_page === 1 ? "disabled" : ""}">
                    <a class="page-link">Previous</a>
                </li>
            `);

            for (let index = 1; index <= last_page; index++) {
                $(".pagination").append(`
                    <li data-page="${index}" role="button"
                        class="page-target page-item ${index === current_page ? "active" : ""}">
                        <a class="page-link">${index}</a>
                    </li>`);
            }

            $(".pagination").append(`
                <li data-page="${current_page < last_page ? current_page + 1 : ""}"
                    class="page-target page-item ${current_page === last_page ? "disabled" : ""}">
                    <a class="page-link">Next</a>
                </li>`);
        }
        function getAndSentDataFilter() {
            let universities = $("#selectUniversiti").val() || [];
            let skills = $("#selectSkill").val() || [];
            let languages = $("#selectLanguage").val() || [];
            let qualifications = $("#selectQualifications").val() || [];
            let name = $("#searchName").val() || "";

            let searchParams = {
                organizations: universities,
                skills: skills,
                languages: languages,
                qualifications: qualifications,
                name: name,
                page: current_page
            };

            $.ajax({
                url: "{{ route('profile.getAllStudentUserProfiles') }}",
                data: searchParams,
                type: "GET",
                success: function (response) {
                    console.log("response", response.data)
                    let datas = response.data.data
                    current_page = response.data.current_page
                    last_page = response.data.last_page
                    $("#total-found").text(`${response.data.total} students found`)

                    $("#talent-cards").empty()
                    datas.forEach(data => {
                        $("#talent-cards").append(talent.talentCard(data))
                    })

                    renderPagination()
                },
                error: function (xhr) {
                    console.error(xhr.responseJSON.message)
                }
            });

        }


        function handleResetFilter() {
            setOptions()
            getAndSentDataFilter()
        }

        function handleAddToShortlist() {

        }

        getAndSentDataFilter()
        getData()

        function handlePage() {
            let page = $(this).data("page")

            current_page = page
            renderPagination()
            getAndSentDataFilter()
        }

        function hanldeToggleLike() {
            let id = $(this).data("id")
            let parent = $(this).parent()

            console.log(id);

            let fromData = new FormData()
            fromData.append("liked_user_profile_id", id)

            $.ajax({
                url: "{{ route('profile.toggleLike') }}",
                type: "POST",
                data: fromData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {

                    if (response.message.includes("unliked")) {
                        parent.html(`<div></div>
                                    <i role="button" data-id="${id}" class="fa-regular fa-heart text-muted talent-like"></i>`)
                    } else {
                        parent.html(`<div></div>
                                    <i role="button" data-id="${id}" class="fa-solid fa-heart text-danger talent-like"></i>`)
                    }

                },
                error: function (xhr) {
                    console.error(xhr);

                }
            });

        }

        $(document).on("click", "#btnFilter", getAndSentDataFilter);
        $(document).on("click", ".btn-add-to-shortlist", handleAddToShortlist)
        $(document).on("click", "#btnResetFilter", handleResetFilter);
        $(document).on("click", ".page-target", handlePage)
        $(document).on("click", ".talent-like", hanldeToggleLike)

    });
</script>
@endsection