<section id="skills" class="d-flex flex-column gap-1">

    <div class="d-flex justify-content-between align-items-center">

        <h3 class="fw-bold text-sm-center text-lg-start mb-0">
            Skills
        </h3>

        <div class="icon-container">

            <button id="btnSkill" type="button" class="btn btn-secondary icon" title="Edit Skills">
                <i class="fa-solid fa-pencil"></i>
            </button>

        </div>

    </div>

    <hr>

    <div id="profileSkillList">

        <p class="text-secondary mb-0">
            Loading skills...
        </p>

    </div>

</section>




@push('scripts')

<script>

    let allSkills = [];
    let PROFILE_SKILL_SOURCE_TYPE = "user_profile";
    let PROFILE_SKILL_SOURCE_ID = "{{ session('user_profile_id') }}";

    function getAllSkills() {

        return $.ajax({

            url: "{{ route('skills.getAllSkills') }}",

            type: "GET",

            dataType: "json",


            success: function (response) {

                allSkills =
                    response.data ?? [];

                console.log(
                    "ALL SKILLS:",
                    allSkills
                );

            },


            error: function (xhr) {

                console.error(
                    "GET ALL SKILLS ERROR:",
                    xhr.responseJSON
                );


                swalfire(
                    "Failed",
                    xhr.responseJSON?.message ??
                    "Failed to load skills.",
                    "error"
                );

            }

        });

    }

    function renderSkills(skills = []) {
        let $container = $("#profileSkillList");
        $container.empty();

        if (!skills.length) {
            $container.html(`
                <p class="text-secondary mb-0">
                    No skills added.
                </p>
            `);
            return;
        }


        skills.forEach(function (item) {
            let skillName = item.skill_name ?? "";
            let skillCategory = item.skill_category ?? "";
            let iconClass = item.icon_class_name ?? "";

            $container.append(`
                <article class="border-bottom py-2 d-flex align-items-start gap-2">
                    ${iconClass ? `
                        <div class="pt-1">
                            <i class="${escapeHtml(iconClass)}"></i>
                        </div>` : ""}
                    <div>
                        <p class="fw-bold mb-0">
                            ${escapeHtml(skillName)}
                        </p>
                        ${skillCategory ? `
                            <small class="text-secondary">
                                ${escapeHtml(skillCategory)}
                            </small>`: ""}
                    </div>
                </article>
            `);
        });
    }

    function refreshProfileSkills() {
        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", PROFILE_SKILL_SOURCE_ID);

        return $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function ({ data }) {
                profileData = data;

                renderSkills(data.skills ?? []);
                renderSkillModal();
            },
            error: function (xhr) {
                console.error("REFRESH PROFILE SKILLS ERROR:", xhr.responseJSON);
            }
        });
    }


</script>

@endpush