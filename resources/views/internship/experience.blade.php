@extends('layouts.internship-layouts')

@section('css')

@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="d-flex justify-content-between">
            <div class="d-flex gap-4">
                <a href="{{ route('profile.student') }}" class="h3">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="fw-bold">Experience</h3>
            </div>
            <div>
                <button type="button" class="btn bg-primary icon" onclick="openAddExperience()">
                    <i class="fa-solid fas fa-plus text-white"></i>
                </button>
            </div>
        </div>

        <hr>

        <article class="m-1 position-relative">
            <div class="icon-container" style="top: 0; right: 0;">
                <button type="button" class="btn btn-secondary icon">
                    <i class="fa-solid fa-pencil" onclick="editExperience(15)"></i>
                </button>
            </div>

            <div class="d-flex gap-1 align-items-center">
                <img src="../images/utemjpg.jpg" alt="" style="width: 50px !important; border-radius: 50%;">
                <p class="h5 fw-bold">Web Developer Intern</p>
            </div>
            <div class="col d-flex flex-column mx-1">
                <div href="">Wahdah Technology - Internship</div>
                <p>Jul 2026 - Oct 2027</p>
                <p>Durian Tunggal, Melaka, Malaysia</p>
                <p>Contributed to Laravel-based web applications</p>

                <div class="skills d-flex flex-wrap align-items-center">
                    Skills:
                    <div class="badge text-bg-secondary m-1">
                        <span>Software Engineering</span>
                    </div>
                    <div class="badge text-bg-secondary m-1">
                        <span>System Analysis</span>
                    </div>
                    <div class="badge text-bg-secondary m-1">
                        <span>Database Design</span>
                    </div>
                    <div class="badge text-bg-secondary m-1">
                        <span>Web Development</span>
                    </div>
                </div>

                <div class="images d-flex flex-wrap">
                    <div class="image rounded-1 m-1"
                        style="background-image: url('{{ URL::asset('assets/images/profile/cover-image.png') }}'); cursor: pointer;"
                        data-bs-toggle="modal" data-bs-target="#imagePreviewModal" onclick="goToSlide(0)">
                    </div>
                    <div class="image rounded-1 m-1"
                        style="background-image: url('{{ URL::asset('assets/images/profile/cover-image.png') }}'); cursor: pointer;"
                        data-bs-toggle="modal" data-bs-target="#imagePreviewModal" onclick="goToSlide(1)">
                    </div>
                    <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                        style="background-image: url('{{ URL::asset('assets/images/profile/cover-image.png') }}'); filter: brightness(0.5); cursor: pointer;"
                        data-bs-toggle="modal" data-bs-target="#imagePreviewModal" onclick="goToSlide(2)">
                        <h4 class="text-white m-0">+5</h4>
                    </div>
                </div>
            </div>
        </article>

    </div>
</div>

<!-- ADD / EDIT EXPERIENCE MODAL -->
<div class="modal fade" id="experienceModal" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

        <div class="modal-content h-75">

            <div class="modal-header">
                <h5 class="modal-title">Add Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- ORGANIZATION -->
                <div class="mb-3">
                    <label for="experienceOrganization" class="form-label">Organization</label>
                    <select id="experienceOrganization" class="form-select" required>
                        <option value="" selected disabled>
                            Select Organization
                        </option>

                        <option value="1">
                            Wahdah Technology
                        </option>

                        <option value="2">
                            UTeM
                        </option>

                        <option value="3">
                            OpenAI
                        </option>
                    </select>

                </div>


                <!-- POSITION -->
                <div class="mb-3">

                    <label for="experiencePosition" class="form-label">
                        Position
                    </label>

                    <input type="text" class="form-control" id="experiencePosition"
                        placeholder="e.g. Web Developer Intern" required>

                </div>


                <!-- EXPERIENCE TYPE -->
                <div class="mb-3">

                    <label for="experienceType" class="form-label">
                        Experience Type
                    </label>

                    <select id="experienceType" class="form-select" required>
                        <option value="" selected disabled>
                            Select Experience Type
                        </option>

                        <option value="internship">
                            Internship
                        </option>

                        <option value="full_time">
                            Full-time
                        </option>

                        <option value="part_time">
                            Part-time
                        </option>

                        <option value="contract">
                            Contract
                        </option>

                        <option value="freelance">
                            Freelance
                        </option>

                        <option value="volunteer">
                            Volunteer
                        </option>
                    </select>

                </div>


                <!-- LOCATION -->
                <div class="mb-3">

                    <label for="experienceLocation" class="form-label">
                        Location
                    </label>

                    <input type="text" class="form-control" id="experienceLocation"
                        placeholder="e.g. Durian Tunggal, Melaka, Malaysia">

                </div>


                <!-- START / END -->
                <div class="row g-3 mb-3">

                    <div class="col-md-6">

                        <label for="experienceStartDate" class="form-label">
                            Start Date
                        </label>

                        <input type="date" class="form-control" id="experienceStartDate" required>

                    </div>


                    <div class="col-md-6">

                        <label for="experienceEndDate" class="form-label">
                            End Date
                        </label>

                        <input type="date" class="form-control" id="experienceEndDate">

                    </div>

                </div>

                <!-- DESCRIPTION -->
                <div class="mb-3">

                    <label for="experienceDescription" class="form-label">
                        Description
                    </label>

                    <textarea id="experienceDescription" class="form-control" rows="4"
                        placeholder="Describe your responsibilities and achievements"></textarea>

                </div>


                <!-- SKILLS -->
                <div class="mb-4">

                    <label class="form-label">
                        Skills
                    </label>

                    <div>

                        <button type="button" id="addExperienceSkill" class="btn btn-outline-primary btn-sm">
                            + Add Skill
                        </button>

                        <div id="experienceSkillContainer" class="mt-3"></div>

                    </div>

                </div>


                <!-- MEDIA -->
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Media
                    </label>

                    <p class="text-muted small mb-2">
                        Add images related to this experience.
                    </p>

                    <input type="file" id="experienceMediaInput" accept="image/*" hidden>

                    <button type="button" id="addExperienceMedia" class="btn btn-outline-primary btn-sm">
                        + Add Media
                    </button>

                    <div id="experienceMediaContainer" class="d-flex flex-wrap gap-2 mt-3"></div>

                </div>

            </div>


            <div class="modal-footer">

                <button type="button" id="btnSaveExperience" class="btn btn-primary">
                    Save
                </button>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="experienceMediaModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Edit media
                </h5>

                <button type="button" class="btn-close" id="closeExperienceMedia"></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">
                        Title
                    </label>

                    <input type="text" id="experienceMediaTitle" class="form-control">

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea id="experienceMediaDescription" class="form-control" rows="4"></textarea>

                </div>


                <div id="experienceMediaPreview"></div>

            </div>


            <div class="modal-footer justify-content-between">

                <button type="button" id="deleteExperienceMedia"
                    class="btn btn-link text-danger text-decoration-none d-none">
                    Delete
                </button>

                <div>

                    <button type="button" id="backExperienceMedia" class="btn btn-outline-secondary">
                        Back
                    </button>

                    <button type="button" id="saveExperienceMedia" class="btn btn-primary">
                        Save
                    </button>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let experienceMode = "add";
    let editingExperienceId = null;

    let experienceMediaList = [];
    let deletedExperienceMediaIds = [];

    let selectedExperienceImage = null;
    let editingExperienceMediaIndex = null;

    let experienceModalEl = document.getElementById("experienceModal");
    let experienceModal = bootstrap.Modal.getOrCreateInstance(experienceModalEl);
    let experienceMediaModalEl = document.getElementById("experienceMediaModal");
    let experienceMediaModal = bootstrap.Modal.getOrCreateInstance(experienceMediaModalEl);
    let experienceSkillContainer = document.getElementById("experienceSkillContainer");
    let addExperienceSkillBtn = document.getElementById("addExperienceSkill");
    let addExperienceMediaBtn = document.getElementById("addExperienceMedia");
    let experienceMediaInput = document.getElementById("experienceMediaInput");
    let experienceMediaContainer = document.getElementById("experienceMediaContainer");
    let experienceMediaTitle = document.getElementById("experienceMediaTitle");
    let experienceMediaDescription = document.getElementById("experienceMediaDescription");
    let experienceMediaPreview = document.getElementById("experienceMediaPreview");
    let saveExperienceMediaBtn = document.getElementById("saveExperienceMedia");
    let deleteExperienceMediaBtn = document.getElementById("deleteExperienceMedia");
    let backExperienceMediaBtn = document.getElementById("backExperienceMedia");
    let closeExperienceMediaBtn = document.getElementById("closeExperienceMedia");
    let btnSaveExperience = document.getElementById("btnSaveExperience");

    document.getElementById("experienceCurrent").addEventListener("change",
        function () {
            let endDate = document.getElementById("experienceEndDate");
            if (this.checked) {
                endDate.value = "";
                endDate.disabled = true;
            } else {
                endDate.disabled = false;
            }
        }
    );

    function createExperienceSkillRow(skill = "", proficiency = "") {
        let row = document.createElement("div");
        row.className = "experience-skill-row mb-2";
        row.innerHTML = `
		<div class="input-group">

			<select class="form-select experience-skill">
				<option value=""disabled ${skill === "" ? "selected" : ""}>Select Skill</option>
                <option value="PHP" ${skill === "PHP" ? "selected" : ""}>PHP</option>
				<option value="Laravel" ${skill === "Laravel" ? "selected" : ""}>Laravel</option>
				<option value="JavaScript" ${skill === "JavaScript" ? "selected" : ""}>JavaScript</option>
				<option value="Vue" ${skill === "Vue" ? "selected" : ""}>Vuejs</option>
				<option value="React" ${skill === "React" ? "selected" : ""}>React</option>
				<option value="MySQL" ${skill === "MySQL" ? "selected" : ""}>MySQL</option>
				<option value="HTML_CSS" ${skill === "HTML_CSS" ? "selected" : ""}>HTML& CSS</option>
			</select>

			<select class="form-select experience-proficiency">
				<option value="" disabled ${proficiency === "" ? "selected" : ""}>Select Proficiency</option>
				<option value="Beginner" ${proficiency === "Beginner" ? "selected" : ""}>Beginner</option>
				<option value="Intermediate" ${proficiency === "Intermediate" ? "selected" : ""}>Intermediate</option>
				<option value="Advanced" ${proficiency === "Advanced" ? "selected" : ""}>Advanced</option>
				<option value="Expert" ${proficiency === "Expert" ? "selected" : ""}>Expert</option>
			</select>

			<button type="button" class="btn btn-outline-danger remove-experience-skill">
				<i class="fa-solid fa-trash"></i>
			</button>
		</div>`;
        return row;
    }

    addExperienceSkillBtn.addEventListener("click", function () {
        experienceSkillContainer.appendChild(createExperienceSkillRow());
    });

    experienceSkillContainer.addEventListener("click", function (event) {
        let button = event.target.closest(".remove-experience-skill");
        if (!button) return;
        let row = button.closest(".experience-skill-row");
        if (row) row.remove();
    });

    function renderExperienceSkills(skills = []) {
        experienceSkillContainer.innerHTML = "";
        skills.forEach(function (skill) {
            experienceSkillContainer.appendChild(
                createExperienceSkillRow(
                    skill.skill,
                    skill.proficiency
                )
            );

        });
    }

    addExperienceMediaBtn.addEventListener("click", function () {
        editingExperienceMediaIndex = null;
        selectedExperienceImage = null;
        experienceMediaInput.value = "";
        deleteExperienceMediaBtn.classList.add("d-none");
        experienceMediaInput.click();
    });

    experienceMediaInput.addEventListener("change", function () {
        let file = this.files[0];
        if (!file) return;

        if (!file.type.startsWith("image/")) {
            alert("Please select an image.");
            this.value = "";
            return;
        }

        let maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert("Image must be less than 5MB.");
            this.value = "";
            return;
        }

        selectedExperienceImage = file;
        editingExperienceMediaIndex = null;
        experienceMediaTitle.value = file.name.replace(/\.[^/.]+$/, "");
        experienceMediaDescription.value = "";
        showExperienceMediaPreview(file);
        experienceModalEl.addEventListener("hidden.bs.modal", function () {
            experienceMediaModal.show();

        }, { once: true });
        experienceModal.hide();
    });

    function showExperienceMediaPreview(file) {
        let url = URL.createObjectURL(file);
        experienceMediaPreview.innerHTML = `
		    <img src="${url}" class="rounded border"
			    style="width:120px;height:90px;object-fit:cover;">`;
    }

    saveExperienceMediaBtn.addEventListener("click", function () {
        let title = experienceMediaTitle.value.trim();
        let description = experienceMediaDescription.value.trim();

        if (!title) {
            alert("Title is required.");
            return;
        }

        if (editingExperienceMediaIndex === null) {
            if (!selectedExperienceImage) return;

            experienceMediaList.push({
                id: null,
                file: selectedExperienceImage,
                title: title,
                description: description,
                preview: URL.createObjectURL(selectedExperienceImage)
            });
        }

        else {
            experienceMediaList[editingExperienceMediaIndex].title = title;
            experienceMediaList[editingExperienceMediaIndex].description = description;
        }

        renderExperienceMedia();
        resetExperienceMediaForm();
        backToExperience();
    });

    function renderExperienceMedia() {
        experienceMediaContainer.innerHTML = "";
        experienceMediaList.forEach((media, index) => {
            let item = document.createElement("div");
            item.innerHTML = `
				<div class="position-relative"
					style="width:100px; height:75px;">

                    <img src="${media.preview}"
						class="rounded border"
						style="width:100%;height:100%;object-fit:cover;">

					<button type="button"
						class="btn btn-light btn-sm rounded-circle position-absolute bottom-0 end-0"
						onclick="editExperienceMedia(${index})">
						<i class="fa-solid fa-pen"></i>
					</button>
				</div>

				<small class="d-block text-truncate" style="width:100px;">
					${media.title}
				</small>`;

            experienceMediaContainer.appendChild(item);
        });
    }

    function editExperienceMedia(index) {

        if (!experienceMediaList[index]) return;
        editingExperienceMediaIndex = index;
        let media = experienceMediaList[index];
        selectedExperienceImage = media.file ?? null;
        experienceMediaTitle.value = media.title;
        experienceMediaDescription.value = media.description ?? "";

        experienceMediaPreview.innerHTML = `
		<img
			src="${media.preview}"
			class="rounded border"
			style="
				width:120px;
				height:90px;
				object-fit:cover;
			"
		>
	`;
        deleteExperienceMediaBtn.classList.remove("d-none");
        experienceModalEl.addEventListener("hidden.bs.modal", function () {
            experienceMediaModal.show();
        }, { once: true });
        experienceModal.hide();
    }

    deleteExperienceMediaBtn.addEventListener("click", function () {
        if (editingExperienceMediaIndex === null) return

        let media = experienceMediaList[editingExperienceMediaIndex];
        if (media.id) {
            deletedExperienceMediaIds.push(media.id);
        }

        if (media.preview && media.preview.startsWith("blob:")) {
            URL.revokeObjectURL(media.preview);
        }
        experienceMediaList.splice(editingExperienceMediaIndex, 1);

        renderExperienceMedia();
        resetExperienceMediaForm();
        backToExperience();
    });

    function backToExperience() {
        experienceMediaModalEl.addEventListener("hidden.bs.modal", function () {
            experienceModal.show();
        }, { once: true });

        experienceMediaModal.hide();
    }


    backExperienceMediaBtn.addEventListener("click", function () {
        resetExperienceMediaForm();
        backToExperience();
    });

    closeExperienceMediaBtn.addEventListener("click", function () {
        resetExperienceMediaForm();
        backToExperience();
    });

    function resetExperienceMediaForm() {
        selectedExperienceImage = null;
        editingExperienceMediaIndex = null;
        experienceMediaInput.value = "";
        experienceMediaTitle.value = "";
        experienceMediaDescription.value = "";
        experienceMediaPreview.innerHTML = "";
        deleteExperienceMediaBtn.classList.add("d-none");
    }

    function openAddExperience() {
        experienceMode = "add";
        editingExperienceId = null;
        deletedExperienceMediaIds = [];

        document.querySelector("#experienceModal .modal-title").textContent = "Add Experience";
        btnSaveExperience.textContent = "Save";

        $("#experienceOrganization").val("");
        $("#experiencePosition").val("");
        $("#experienceType").val("");
        $("#experienceLocation").val("");
        $("#experienceStartDate").val("");
        $("#experienceEndDate").val("").prop("disabled", false);
        $("#experienceCurrent").prop("checked", false);
        $("#experienceDescription").val("");

        experienceSkillContainer.innerHTML = "";
        experienceMediaList = [];
        renderExperienceMedia();
        experienceModal.show();
    }

    function editExperience(id) {
        experienceMode = "edit";
        editingExperienceId = id;
        deletedExperienceMediaIds = [];

        let data = {
            id:
                id,
            organization_id:
                "1",
            position:
                "Web Developer Intern",
            experience_type:
                "internship",
            start_date:
                "2026-07-01",
            end_date:
                "2027-07-01",
            location:
                "Durian Tunggal, Melaka, Malaysia",
            description:
                "Contributed to Laravel-based web applications",
            skills: [
                {
                    skill:
                        "Laravel",
                    proficiency:
                        "Advanced"
                },
                {
                    skill:
                        "JavaScript",
                    proficiency:
                        "Intermediate"
                }
            ],
            media: [
                {
                    id:
                        1,
                    file:
                        null,
                    title:
                        "Internship Project",
                    description:
                        "Project screenshot",
                    preview:
                        "../images/cover-image.png"
                }
            ]
        };


        document.querySelector("#experienceModal .modal-title").textContent = "Edit Experience";
        btnSaveExperience.textContent = "Update";
        $("#experienceOrganization").val(data.organization_id);
        $("#experiencePosition").val(data.position);
        $("#experienceType").val(data.experience_type);
        $("#experienceLocation").val(data.location);
        $("#experienceStartDate").val(data.start_date);
        $("#experienceDescription").val(data.description);

        if (!data.end_date) {
            $("#experienceCurrent").prop("checked", true);
            $("#experienceEndDate").val("").prop("disabled", true);

        } else {
            $("#experienceCurrent").prop("checked", false);
            $("#experienceEndDate").val(data.end_date).prop("disabled", false);
        }

        renderExperienceSkills(data.skills ?? []);

        experienceMediaList = data.media ?? [];

        renderExperienceMedia();
        experienceModal.show();
    }

    btnSaveExperience.addEventListener("click", function () {
        let formData = new FormData();

        formData.append(
            "organization_id",
            $("#experienceOrganization").val()
        );

        formData.append(
            "position",
            $("#experiencePosition").val()
        );

        formData.append(
            "experience_type",
            $("#experienceType").val()
        );

        formData.append(
            "start_date",
            $("#experienceStartDate").val()
        );

        formData.append(
            "end_date",
            $("#experienceEndDate").val()
        );

        formData.append(
            "location",
            $("#experienceLocation").val()
        );

        formData.append(
            "description",
            $("#experienceDescription").val()
        );

        document.querySelectorAll(".experience-skill-row").forEach((row, index) => {

            formData.append(
                `skills[${index}][skill]`,
                row.querySelector(".experience-skill"

                ).value);

            formData.append(
                `skills[${index}][proficiency]`,
                row.querySelector(
                    ".experience-proficiency"
                ).value
            );

        });

        experienceMediaList.forEach(function (media, index) {
            if (media.id) {
                formData.append(
                    `media[${index}][id]`,
                    media.id
                );
            }

            if (media.file) {
                formData.append(
                    `media[${index}][file]`,
                    media.file
                );
            }

            formData.append(
                `media[${index}][title]`,
                media.title
            );

            formData.append(
                `media[${index}][description]`,
                media.description ?? ""
            );

        }
        );

        deletedExperienceMediaIds.forEach((id, index) => {
            formData.append(
                `deleted_media_ids[${index}]`,
                id
            );

        });

        let url;
        if (experienceMode === "add") {
            url = "/experience";

        } else {

            url = `/experience/${editingExperienceId}`;

            formData.append(
                "_method",
                "PUT"
            );
        }

        for (let [key, value] of formData.entries()) {
            console.log(key,value);
        }

        $.ajax({
            url:
                url,
            type:
                "POST",
            data:
                formData,
            processData:
                false,
            contentType:
                false,
            success:
                function (response) {
                    console.log(response);
                    experienceModal.hide();
                },
            error:
                function (xhr) {
                    console.log(xhr.responseJSON);
                }
        });

    });
</script>
@endsection
