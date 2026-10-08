@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/select2.css') }}">

<div class="content p-4 page-content">

    <x-atom.page-header title="Find Talents" />

    <div class="talent-layout">
        <x-molecule.filter-panel/>
        <x-molecule.profiles-panel/>
    </div>
</div>

<div class="filter-overlay btn-toggle-filter"></div>

<x-modals.shortlist-modal />
@endsection

@section('script')
<script>
    $(document).ready(function () {

        let myData;
        let positions = [];
        let organizations;

        let myOrg = [];
        let organizationWithPosition = [];

        let state = {
            allOrganizations: [],
            allLanguages: [],
            allSkills: [],
            allQualifications: []
        };

        async function getProfileDataByProfileId() {
            let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
            url = url.replace("__ID__","{{ session('user_profile_id') }}");

            let response = await $.ajax({
                url: url,
                method: 'GET'
            });

            myData = response.data;

            return response.data;
        }

        function getAllOrganizations() {
            return $.ajax({
                url: "{{ route('organization.getAllOrganizations') }}",
                type: 'GET',
                success: response => {
                    state.allOrganizations = response.data;
                },
                error: xhr => {
                    console.error(xhr);
                }
            });
        }

        function getAllLanguages() {
            return $.ajax({
                url: "{{ route('languages.getAllLanguages') }}",
                type: 'GET',
                success: response => {
                    state.allLanguages = response.data;
                },
                error: xhr => {
                    console.error(xhr);
                }
            });
        }

        function getAllSkills() {
            return $.ajax({
                url: "{{ route('skills.getAllSkills') }}",
                type: 'GET',
                success: response => {
                    state.allSkills = response.data;
                },
                error: xhr => {
                    console.error(xhr);
                }
            });
        }

        function getAllQualifications() {
            return $.ajax({
                url: "{{ route('programme.getAllQualifications') }}",
                type: 'GET',
                dataType: "json",
                success: response => {
                    state.allQualifications = response.data;
                },
                error: xhr => {
                    console.error(xhr);
                }
            });
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

            organizations.forEach(organization => {
                myOrg.push(organization);
            });

            const results = await Promise.all(
                myOrg.map(async org => {

                    const response = await getPositionsByOrgId(
                        org.organization_id
                    );

                    positions.push(response.data);

                    return {
                        org: org,
                        position: response.data
                    };
                })
            );

            organizationWithPosition = results;

            return results;
        }

        function setOptions() {

            $("#selectUniversiti").empty();

            state.allOrganizations.forEach(organization => {

                let name = organization.company_name.split(" ");
                let lastName = name.length;

                let shortName = name[lastName - 1].replace("(", "").replace(")", "");

                $("#selectUniversiti").append(`
                    <option value="${organization.id}">
                        ${shortName}
                    </option>`);
            });

            $("#selectLanguage").empty();

            state.allLanguages.forEach(language => {
                $("#selectLanguage").append(`
                    <option value="${language.id}">
                        ${language.language_name}
                    </option>
                `);
            });

            $("#selectSkill").empty();

            state.allSkills.forEach(skill => {
                $("#selectSkill").append(`
                    <option value="${skill.id}">
                        ${skill.skill_name}
                    </option>
                `);
            });

            $("#selectQualifications").empty();

            state.allQualifications.forEach(qualification => {
                $("#selectQualifications").append(`
                    <option value="${qualification.id}">
                        ${qualification.name}
                    </option>
                `);
            });
        }

        function handleToggleLike() {

            let id = $(this).data("id");
            let parent = $(this).parent();

            let formData = new FormData();

            formData.append(
                "liked_user_profile_id",
                id
            );

            $.ajax({
                url: "{{ route('profile.toggleLike') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN":
                        $('meta[name="csrf-token"]').attr("content")
                },

                success: function (response) {

                    if (response.message.includes("unliked")) {

                        parent.html(`
                            <div></div>
                            <i
                                role="button"
                                data-id="${id}"
                                class="fa-regular fa-heart text-muted talent-like">
                            </i>
                        `);

                    } else {

                        parent.html(`
                            <div></div>
                            <i
                                role="button"
                                data-id="${id}"
                                class="fa-solid fa-heart text-danger talent-like">
                            </i>
                        `);
                    }
                },

                error: function (xhr) {
                    console.error(xhr);
                }
            });
        }

        async function getData() {

            await Promise.all([
                getProfileDataByProfileId(),
                getAllOrganizations(),
                getAllLanguages(),
                getAllSkills(),
                getAllQualifications(),
            ]);

            setOptions();

            await getPosition();

            filterPanel.init();
            pagination.init();
            profilesPanel.init(false);
            shortlistModal.init(organizationWithPosition);
        }

        getData();

        $(document).on("click", ".talent-like", handleToggleLike);
        $(document).on("click", ".btn-toggle-filter", toggle);

        $(document).on("click", ".btnAddToShortlist", function () {
            shortlistModal.open({
                id: $(this).data("id"),
                name: $(this).data("name")
            });
        });

        $(document).on("click", "#shortlistModal #btnAddShortlist", function () {
            shortlistModal.save();
        });

        $(document).on("click", "#shortlistModal #btnCloseModal", function () {
            shortlistModal.close();
        });
    });
</script>
@endsection
