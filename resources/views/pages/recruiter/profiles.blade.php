@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/select2') }}">

<div class="content p-4">

    <!-- Header & Toggle Button (Mobile) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="m-0 fw-bold">Find University Talent</h3>
        <button class="btn btn-primary d-lg-none btn-toggle-filter">
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

<div class="filter-overlay btn-toggle-filter"></div>

<x-modals.shortlist-modal />

@endsection

@section('script')
<script>
    $(document).ready(function (){
        let current_page = 1
        let last_page
        let myData
        let positions = []
        let organizations

        let myOrg = []
        let organizationWithPosition = [];

        let state = {
            allOrganizations: [],
            allLanguages: [],
            allSkills: [],
            allQualifications: []
        }

        function toggleFilter() {
            document.body.classList.toggle('filter-open');
        }

        async function getProfileDataByProfileId() {
            let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", "{{ session('user_profile_id') }}");
            let response = await $.ajax({
                url: url,
                method: 'GET'
            });
            myData = response.data;
            return response.data;
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

        async function getPositionsByOrgId(id) {
            let url = "{{ route('positions.getPositionsByOrgId', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", id);

            return await $.ajax({
                url: url,
                method: 'GET'
            });
        }

        async function getPosition() {

            organizations = myData.organization_users;
            organizations.forEach(organization => myOrg.push(organization));


            results = await Promise.all(
                myOrg.map(async org => {
                    let response = await getPositionsByOrgId(org.organization_id);

                    positions.push(response.data);

                    return {
                        org: org,
                        position: response.data
                    };
                })
            );

            organizationWithPosition = results;
        }

        async function getData() {
            await Promise.all([
                getProfileDataByProfileId(),
                getAllOrganizations(),
                getAllLanguages(),
                getAllSkills(),
                getAllQualifications(),
            ]);
            setOptions()
            getPosition()
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
                <li role="button" data-page="${current_page > 1 ? current_page - 1 : ""}"
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
                <li role="button" data-page="${current_page < last_page ? current_page + 1 : ""}"
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
                    let datas = response.data.data
                    console.log(datas);

                    current_page = response.data.current_page
                    last_page = response.data.last_page
                    $("#total-found").text(`${response.data.total} students found`)

                    $("#talent-cards").empty()
                    datas.forEach(data => {
                        $("#talent-cards").append(xprofile.student.talentCard(data))
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

        function handleAddToShortlist(e) {
            e.preventDefault();

            let profileId = $("#candidateId").val();
            let positionId = $("#selectPosition").val();

            $.ajax({
                url: "{{ route('shortlists.store') }}",
                type: "POST",
                data: {
                    user_profile_id: profileId,
                    position_id: positionId,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                success: response => {
                    console.log(response);
                    xalert.alert('Success', response.message, 'success');
                },
                error: xhr => {
                    console.log(xhr);
                    xalert.alert("Error", xhr.responseJSON.message, "error")
                },
                complete: function () {
                    modal.hide("shortlistModal")
                }
            });
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

        function getShortlistedPositionIds(profileId, orgId) {
            let url = "{{ route('shortlists.getShortlistedPositionIds', ['profileId' => '__profileId__', 'orgId' => '__orgId__']) }}"
            url = url.replace("__profileId__", profileId)
            url = url.replace("__orgId__", orgId)
            return $.ajax({
                url,
                type: "GET",
            });
        }

        async function handleAddtoShortlist() {
            let id = $(this).data("id")
            let name = $(this).data("name")

            xmodal.show("shortlistModal")

            $('#candidateId').val(id)
            $('#candidateName').val(name)

            let html = ""
            for (const data of organizationWithPosition) {
                let shortlistedPositionIds = await getShortlistedPositionIds(id, data.org.organization.id)
                console.log(shortlistedPositionIds.data);
                let xxx = shortlistedPositionIds.data

                html += `<optgroup label="${data.org.organization.company_name}">`
                data.position.forEach(pos => {

                    html += `<option ${xxx.includes(pos.id) ? "disabled" : ""} value="${pos.id}">${pos.position_title} ${xxx.includes(pos.id) ? "(Added)" : ""}</option>`
                })
                html += `</optgroup>`
            }

            $("#selectPosition").html(html)
        }

        $(document).on("click", "#btnFilter", getAndSentDataFilter);
        $(document).on("click", ".btn-add-to-shortlist", handleAddToShortlist)
        $(document).on("click", "#btnResetFilter", handleResetFilter);
        $(document).on("click", ".page-target", handlePage)
        $(document).on("click", ".talent-like", hanldeToggleLike)
        $(document).on("click", ".btnAddToShortlist", handleAddtoShortlist)
        $(document).on("submit", "#shortlistForm", handleAddToShortlist)
        $(document).on("click", ".btn-toggle-filter", toggleFilter)
    })

</script>
@endsection
