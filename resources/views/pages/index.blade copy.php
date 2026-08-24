@push('scripts')
<script>
    let allSkills = [];

    function getUserSkillId(skill) {
        return skill.user_skill_id ??
            skill.pivot?.id ??
            skill.user_skill?.id ??
            null;
    }

    function getSkillId(skill) {
        return Number(
            skill.skill_id ??
            skill.id
        );
    }

    function getSkillName(skill) {
        return skill.skill_name ??
            skill.name ??
            "";
    }

    function getAllSkills() {

        $.ajax({
            url: "{{ route('skills.getAllSkills') }}",
            type: "GET",
            dataType: "json",

            success: function({
                data
            }) {

                allSkills = data ?? [];

                // console.log("ALL SKILLS:", allSkills);
            },

            error: function(xhr) {
                console.error("GET ALL SKILLS ERROR:", xhr);
            }
        });
    }

    function renderProfileSkills(skills = []) {

        let $container = $("#profileSkillList");

        $container.empty();

        let uniqueSkills = [
            ...new Map(
                skills.map(skill => [
                    getSkillId(skill),
                    skill
                ])
            ).values()
        ];

        if (!uniqueSkills.length) {

            $("<span>")
                .addClass("text-secondary")
                .text("No skills added yet.")
                .appendTo($container);

            return;
        }

        uniqueSkills.forEach(skill => {

            let $skill = $("<div>")
                .addClass(
                    "badge rounded-pill bg-light text-dark border " +
                    "d-flex align-items-center gap-2 px-3 py-2"
                );

            $("<i>")
                .addClass(
                    skill.icon_class_name ??
                    "fa-solid fa-code"
                )
                .appendTo($skill);

            $("<span>")
                .text(getSkillName(skill))
                .appendTo($skill);

            $container.append($skill);
        });
    }

    function renderSkillList(skills = []) {

        let $skillList = $("#skillList");

        $skillList.empty();

        let uniqueSkills = [
            ...new Map(
                skills.map(skill => [
                    getSkillId(skill),
                    skill
                ])
            ).values()
        ];

        if (!uniqueSkills.length) {

            $("<p>")
                .addClass("text-secondary text-center mb-0 no-skill-message")
                .text("No skills added yet.")
                .appendTo($skillList);

            return;
        }

        uniqueSkills.forEach(skill => {

            let skillId = getSkillId(skill);
            let userSkillId = getUserSkillId(skill);

            let $item = $("<div>")
                .addClass(
                    "skill-item border rounded p-3 " +
                    "d-flex justify-content-between align-items-center gap-3"
                )
                .attr({
                    "data-skill-id": skillId,
                    "data-user-skill-id": userSkillId ?? ""
                });


            let $info = $("<div>")
                .addClass(
                    "d-flex align-items-center gap-3 flex-grow-1"
                );


            let $icon = $("<div>")
                .addClass(
                    "bg-light rounded d-flex justify-content-center " +
                    "align-items-center flex-shrink-0"
                )
                .css({
                    width: "40px",
                    height: "40px"
                });


            $("<i>")
                .addClass(
                    skill.icon_class_name ??
                    "fa-solid fa-code"
                )
                .appendTo($icon);


            let $text = $("<div>");


            $("<div>")
                .addClass("fw-semibold")
                .text(getSkillName(skill))
                .appendTo($text);


            $("<small>")
                .addClass("text-secondary")
                .text(
                    skill.skill_category ??
                    skill.category ??
                    ""
                )
                .appendTo($text);


            $info.append(
                $icon,
                $text
            );


            let $actions = $("<div>")
                .addClass("d-flex gap-2 flex-shrink-0");


            let $editButton = $("<button>")
                .attr({
                    type: "button",
                    title: "Edit Skill",
                    "data-skill-id": skillId,
                    "data-user-skill-id": userSkillId ?? ""
                })
                .addClass(
                    "btn btn-outline-primary btn-sm btn-edit-skill"
                )
                .html(
                    '<i class="fa-solid fa-pencil"></i>'
                );


            let $deleteButton = $("<button>")
                .attr({
                    type: "button",
                    title: "Delete Skill",
                    "data-skill-id": skillId,
                    "data-user-skill-id": userSkillId ?? ""
                })
                .addClass(
                    "btn btn-outline-danger btn-sm btn-remove-skill"
                )
                .html(
                    '<i class="fa-solid fa-trash"></i>'
                );


            $actions.append(
                $editButton,
                $deleteButton
            );


            $item.append(
                $info,
                $actions
            );


            $skillList.append($item);
        });
    }

    function addSkillInputRow() {

        $(".no-skill-message").remove();

        let $row = $("<div>")
            .addClass(
                "skill-input-row border rounded p-3 " +
                "d-flex align-items-center gap-2"
            );


        let $select = $("<select>")
            .addClass("form-select skill-select");


        $("<option>")
            .val("")
            .text("Select Skill")
            .appendTo($select);


        let existingSkillIds = new Set(
            (profileData?.skills ?? [])
            .map(skill => getSkillId(skill))
        );


        let pendingSkillIds = new Set();


        $(".skill-input-row .skill-select").each(function() {

            let id = $(this).val();

            if (id) {
                pendingSkillIds.add(Number(id));
            }
        });


        allSkills.forEach(skill => {

            let skillId = Number(skill.id);

            if (
                existingSkillIds.has(skillId) ||
                pendingSkillIds.has(skillId)
            ) {
                return;
            }


            $("<option>")
                .val(skill.id)
                .text(
                    skill.skill_name ??
                    skill.name ??
                    ""
                )
                .appendTo($select);
        });


        let $saveBtn = $("<button>")
            .attr({
                type: "button",
                title: "Save Skill"
            })
            .addClass(
                "btn btn-primary btn-save-new-skill"
            )
            .html(
                '<i class="fa-solid fa-check"></i>'
            );


        let $deleteBtn = $("<button>")
            .attr({
                type: "button",
                title: "Remove"
            })
            .addClass(
                "btn btn-outline-danger btn-remove-new-skill-row"
            )
            .html(
                '<i class="fa-solid fa-trash"></i>'
            );


        $row.append(
            $select,
            $saveBtn,
            $deleteBtn
        );


        $("#skillList").append($row);
    }

    function saveSkill(skillId, $button) {

        $.ajax({

            url: "{{ route('skills.store') }}",
            type: "POST",

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            data: {
                skill_id: skillId,
                source_id: "{{ session('user_profile_id') }}",
                source_type: "user_profile"
            },

            beforeSend: function() {

                $button
                    .prop("disabled", true)
                    .html(`
                        <span class="spinner-border spinner-border-sm"></span>
                    `);
            },

            success: function(response) {

                console.log("STORE SKILL:", response);


                let selectedSkill = allSkills.find(
                    skill =>
                    Number(skill.id) === Number(skillId)
                );


                if (!selectedSkill) {
                    return;
                }


                let userSkillId =
                    response?.data?.user_skill_id ??
                    response?.data?.id ??
                    response?.user_skill_id ??
                    response?.id ??
                    null;


                let newSkill = {
                    ...selectedSkill,
                    user_skill_id: userSkillId
                };


                profileData.skills =
                    profileData.skills ?? [];


                profileData.skills.push(newSkill);


                renderSkillList(profileData.skills);
                renderProfileSkills(profileData.skills);


                Swal.fire({
                    icon: "success",
                    title: "Skill Added",
                    timer: 1200,
                    showConfirmButton: false
                });
            },

            error: function(xhr) {

                console.error(xhr);


                $button
                    .prop("disabled", false)
                    .html(
                        '<i class="fa-solid fa-check"></i>'
                    );


                Swal.fire({
                    icon: "error",
                    title: "Failed",
                    text: xhr.responseJSON?.message ??
                        "Failed to add skill."
                });
            }
        });
    }

    $(document).on("click",".btn-save-new-skill",function() {
        let $button = $(this);

        let $row =
            $button.closest(".skill-input-row");

        let skillId =
            $row.find(".skill-select").val();


        if (!skillId) {

            Swal.fire({
                icon: "warning",
                title: "Select Skill",
                text: "Please select a skill first."
            });

            return;
        }
        saveSkill(skillId, $button);
    });

    $(document).on("click","#btnAddSkill",function() {
        addSkillInputRow();
    });

    $(document).on("click",".btn-remove-new-skill-row",function() {
        $(this).closest(".skill-input-row").remove();
    });

    $(document).on("click", ".btn-edit-skill", function() {
        let $button = $(this);
        let $item = $button.closest(".skill-item");
        let currentSkillId = Number($button.data("skill-id"));
        let userSkillId = $button.data("user-skill-id");
        console.log({currentSkillId,userSkillId});

        if (!userSkillId) {
            console.error(
                "Missing user_skill_id", {
                    currentSkillId,
                    skill: profileData.skills
                }
            );

            Swal.fire({
                icon: "error",
                title: "Cannot Edit Skill",
                text: "user_skill_id was not returned by the profile API."
            });

            return;
        }


        let $select = $("<select>")
            .addClass("form-select skill-update-select");


        allSkills.forEach(skill => {

            let skillId =
                Number(skill.id);


            let alreadyExists =
                (profileData?.skills ?? [])
                .some(profileSkill => {

                    let existingSkillId =
                        getSkillId(profileSkill);

                    return (
                        existingSkillId === skillId &&
                        existingSkillId !== currentSkillId
                    );
                });


            if (alreadyExists) {
                return;
            }


            let $option = $("<option>")
                .val(skill.id)
                .text(
                    skill.skill_name ??
                    skill.name ??
                    ""
                );


            if (skillId === currentSkillId) {

                $option.prop(
                    "selected",
                    true
                );
            }


            $select.append($option);
        });


        let $saveButton = $("<button>")
            .attr({
                type: "button",
                title: "Save",
                "data-user-skill-id": userSkillId
            })
            .addClass(
                "btn btn-primary btn-sm btn-update-skill"
            )
            .html(
                '<i class="fa-solid fa-check"></i>'
            );


        let $cancelButton = $("<button>")
            .attr({
                type: "button",
                title: "Cancel"
            })
            .addClass(
                "btn btn-outline-secondary btn-sm btn-cancel-skill-edit"
            )
            .html(
                '<i class="fa-solid fa-xmark"></i>'
            );


        let $editor = $("<div>")
            .addClass(
                "d-flex align-items-center gap-2 w-100"
            );


        $editor.append(
            $select,
            $saveButton,
            $cancelButton
        );


        $item
            .empty()
            .append($editor);
    });

    $(document).on("click", ".btn-cancel-skill-edit", function() {
        renderSkillList(profileData.skills ?? []);
    });

    $(document).on("click", ".btn-update-skill", function() {
        console.log("UPDATE");
        let $button = $(this);
        let $item = $button.closest(".skill-item");
        let userSkillId = $button.data("user-skill-id");
        let skillId = $item.find(".skill-update-select").val();

        if (!userSkillId || !skillId) {
            console.error({userSkillId,skillId});
            return;
        }

        let url = "{{ route('skills.update', ['id' => '__ID__']) }}";

        url = url.replace("__ID__",userSkillId);

        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                _method: "PUT",
                skill_id: skillId,
                source_id: "{{ session('user_profile_id') }}",
                source_type: "user_profile"
            },

            beforeSend: function() {

                $button
                    .prop("disabled", true)
                    .html(`
                    <span class="spinner-border spinner-border-sm"></span>
                `);
            },

            success: function(response) {
                let selectedSkill = allSkills.find(
                    skill =>
                    Number(skill.id) === Number(skillId)
                );

                if (!selectedSkill) {
                    return;
                }

                let index = profileData.skills.findIndex(
                    skill =>
                    Number(getUserSkillId(skill)) ===
                    Number(userSkillId)
                );

                if (index !== -1) {

                    profileData.skills[index] = {
                        ...selectedSkill,

                        /*
                         * Jangan hilangkan user_skills.id
                         */
                        user_skill_id: Number(userSkillId)
                    };
                }

                renderSkillList(
                    profileData.skills
                );

                renderProfileSkills(
                    profileData.skills
                );

                Swal.fire({
                    icon: "success",
                    title: "Skill Updated",
                    text: response.message,
                    timer: 1200,
                    showConfirmButton: false
                });
            },

            error: function(xhr) {

                console.error(
                    "UPDATE SKILL ERROR:",
                    xhr.responseJSON
                );

                $button
                    .prop("disabled", false)
                    .html(
                        '<i class="fa-solid fa-check"></i>'
                    );

                Swal.fire({
                    icon: "error",
                    title: "Failed",
                    text: xhr.responseJSON?.message ??
                        "Failed to update skill."
                });
            }
        });
    });

    $(document).on("click", ".btn-remove-skill", function() {
        console.log("DELETE");

        let $button = $(this);
        let userSkillId = $button.data("user-skill-id");

        if (!userSkillId) {
            Swal.fire({
                icon: "error",
                title: "Cannot Remove Skill",
                text: "user_skill_id was not returned by the profile API."
            });
            return;
        }

        Swal.fire({
            title: "Remove skill?",
            text: "This skill will be removed from your profile.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, remove it",
            cancelButtonText: "Cancel"

        }).then((result) => {
            if (!result.isConfirmed) return;
            let url = "{{ route('skills.delete', ['id' => '__ID__']) }}";
            url = url.replace("__ID__", userSkillId);

            $.ajax({
                url: url,
                type: "DELETE",

                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                beforeSend: function() {

                    $button
                        .prop("disabled", true)
                        .html(`
                                <span class="spinner-border spinner-border-sm"></span>
                            `);
                },

                success: function(response) {
                    profileData.skills = (profileData.skills ?? []).filter(skill => Number(getUserSkillId(skill)) !== Number(userSkillId));
                    renderSkillList(profileData.skills);
                    renderProfileSkills(profileData.skills);

                    Swal.fire({
                        icon: "success",
                        title: "Removed!",
                        text: "The skill has been removed.",
                        timer: 1500,
                        showConfirmButton: false
                    });
                },

                error: function(xhr) {
                    console.error(xhr);
                    $button.prop("disabled", false).html('<i class="fa-solid fa-trash"></i>');
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: xhr.responseJSON?.message ??
                            "Failed to remove skill."
                    });
                }
            });
        });
    });

    $(document).on("click", "#btnSkill", function() {
        renderSkillList(profileData.skills ?? []);
        let skillModal = document.getElementById("skillModal");
        let modal = bootstrap.Modal.getOrCreateInstance(skillModal);
        modal.show();
    });

    $(document).ready(function() {
        getAllSkills();
    });
</script>
@endpush
