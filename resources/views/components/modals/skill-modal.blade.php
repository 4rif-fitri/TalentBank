<div class="modal fade" id="skillModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h1 class="modal-title fs-5 fw-bolder">Skills</h1>
                <button type="button" id="btnAddSkill" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i>
                    Add Skill
                </button>
            </div>

            <div class="modal-body">
                <div id="skillListModal" class="mb-2">

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btnCencelSaveSkill" class="btn btn-outline-secondary" >Close</button>
            </div>
        </div>
    </div>
</div>

@push('childScript')
<script type="module">

    $(document).ready(function (){

        function showSkillModal(){ //+
            $("#skillListModal").empty()
            stateSkills.skills.forEach(skill => $("#skillListModal").append(xskill.student.rowSkillModal(skill)))
            xmodal.show("skillModal")
        }

        function handleHideSkillModal(){
            xmodal.hide("skillModal")
        }

        function handleAddSkill(){
            $("#skillListModal").prepend(xskill.student.rowSkillAdd(stateSkills.skillOptions))
        }

        function handleEditSkill() { //+
            let $row = $(this).closest(".skill-item");
            let skillId = $row.data("skill-id");
            let userSkillId = $row.data("user-skill-id");

            let option = ""
            stateSkills.allSkill.forEach(skill => {
                option += xskill.student.skillOption(skill, skillId)
            })

            $row.replaceWith(xskill.student.rowSkillUpdate(option, skillId, userSkillId ))
        }

        function handleSaveNewSkill(){ //+

            let $button = $(this);
            let $row = $button.closest(".skill-input-row");

            let skillId = $row.find(".skill-select").val();

            if (!skillId) {
                xalert.fire("Validation Error", "Please select a skill first","error");
                return;
            }

            let data = {
                _token: $('meta[name="csrf-token"]').attr("content"),
                source_type: "user_profile",
                source_id: "{{ session('user_profile_id') }}",
                skill_id: skillId,
            }

            $.ajax({
                url: "{{ route('skills.store') }}",
                type: "POST",
                data,
                success: response => {

                    let userSkill = response.data;
                    let skill = stateSkills.allSkill.find(skill => Number(skill.id) === Number(userSkill.skill_id));

                    if (!skill) {
                        console.error(
                            "Skill not found:",
                            userSkill.skill_id
                        );
                        return;
                    }

                    let skillData = {
                        ...skill,
                        user_skill_id: userSkill.id
                    };

                    $("#skillListModal").prepend(xskill.student.rowSkillModal(skillData));
                    $("#skillList").append(xskill.student.rowSkill(skillData))

                    xalert.fire("Success", response.message ?? "Skill added successfully.", "success");
                    $row.remove();
                },
                error: xhr => {
                    xalert.fire("Error", xhr.responseJSON?.message ?? "Failed to add skill.", "error");
                }
            });

        }

        function handleDeleteSkill(){
            let $row = $(this).closest(".skill-item");
            let userSkillId = $row.data("user-skill-id");

            if (!userSkillId) {
                console.error("Missing user_skill_id");
                return;
            }

            Swal.fire({
                title: "Delete Skill?",
                text: "This skill will be removed from your profile.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#dc3545"

            }).then(async result => {
                if (!result.isConfirmed) return;

                let url = "{{ route('skills.delete', ['id' => '__ID__']) }}"
                url = url.replace("__ID__", userSkillId)

                $.ajax({
                    url,
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: response => {
                        xdebug.line(response);

                        $row.remove();

                        $("#skillList").find(`[data-user-skill-id="${userSkillId}"]`).remove();
                        $("#skillListModal").find(`[data-user-skill-id="${userSkillId}"]`).remove();
                        xalert.fire("Success", response.message ?? "Skill removed successfully.", "success");
                    },
                    error: xhr => {
                        xdebug.line(xhr);
                        xalert.fire("Delete Failed", xhr.responseJSON?.message ?? "Failed to remove skill.", "error");
                    }
                });

            });
        }

        function handleCencelAddSkill(){ //+
            $(this).parent().remove()
        }

        function handleCencelUpdateSkill(){ // +
            let $row = $(this).closest(".skill-input-row");
            let skillId = $row.data("skill-id");
            let userSkillId = $row.data("user-skill-id");

            let skill = stateSkills.allSkill.find(skill => Number(skill.id) === Number(skillId));

            if (!skill) {
                xdebug.fire("Skill not found:", skillId);
                return;
            }

            let skillData = {
                ...skill,
                user_skill_id: userSkillId
            };

            $row.replaceWith(xskill.student.rowSkillModal(skillData));
        }

        function handleUpdateSkill(){ //+
            let $row = $(this).closest(".skill-input-row");

            let userSkillId = $row.data("user-skill-id");
            let newSkillId = $row.find(".skill-select").val();

            let sourceType = "user_profile";
            let sourceId = "{{ session('user_profile_id') }}";

            if (!newSkillId) {
                xalert.fire("Validation Error", "Please select a skill.","error");
                return;
            }

            if (!userSkillId) {
                xalert.fire("Missing user_skill_id");
                return;
            }

            if (!sourceId) {
                xalert.fire("Missing profile_id");
                return;
            }

            let data = {
                _token: $('meta[name="csrf-token"]').attr("content"),
                _method: "PUT",
                source_type: sourceType,
                source_id: sourceId,
                skill_id: newSkillId
            }

            let url = "{{ route('skills.update', ['id' => '__ID__']) }}"
            url = url.replace("__ID__", sourceId)

            $.ajax({
                url,
                type: "POST",
                data,
                success: response =>  {

                    let userSkill = response.data;
                    let skill = stateSkills.allSkill.find( skill => Number(skill.id) === Number(newSkillId));

                    if (!skill) {
                        console.error(
                            "Skill not found:",
                            newSkillId
                        );
                        return;
                    }

                    let skillData = {
                        ...skill,
                        user_skill_id: userSkill.id
                    };

                    $row.replaceWith(xskill.student.rowSkillModal(skillData));

                    $("#skillList").find( `[data-user-skill-id="${userSkillId}"]`)
                        .replaceWith(xskill.student.rowSkill(skillData));

                    xalert.fire("Success", response.message, "success")
                },
                error: xhr =>{
                    xalert.fire("Error", xhr, "error")
                    console.log(xhr);
                }
            });

        }

        $(document).on('click', '#btnSkill', showSkillModal);
        $(document).on('click', '#btnAddSkill', handleAddSkill);
        $(document).on('click', '.btn-edit-skill', handleEditSkill);
        $(document).on('click', '.btn-save-new-skill', handleSaveNewSkill);
        $(document).on('click', '.btn-remove-skill', handleDeleteSkill);
        $(document).on('click', '.btn-cencel-addSkill', handleCencelAddSkill);
        $(document).on('click', '.btn-cancel-update-skill', handleCencelUpdateSkill);//+
        $(document).on('click', '.btn-update-skill', handleUpdateSkill);
        $(document).on('click', '#btnCencelSaveSkill', handleHideSkillModal);

    })

</script>
@endpush
