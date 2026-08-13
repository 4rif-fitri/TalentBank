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
                <h3 class="fw-bold">Education</h3>
            </div>
            <div>
                <button onclick="openAddEducation()" type="button" class="btn bg-primary icon">
                    <i class="fa-solid fa-plus text-white"></i>
                </button>
            </div>
        </div>
        <hr>

        @foreach ($educations as $education)
        <article class="gap-2 d-flex flex-column position-relative mb-4">

            <div class="icon-container" style="top: 0; right: 0;">
                <button type="button" class="btn btn-secondary icon" onclick="editEducation({{ $education['id'] }})">
                    <i class="fa-solid fa-pencil"></i>
                </button>
            </div>

            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('assets/images/profile/cover-image.png') }}" alt=""
                    style="width: 50px; height: 50px; border-radius: 50%;">

                <p class="h5 fw-bold">
                    {{ $education['institution_name'] }}
                </p>
            </div>

            <div class="col d-flex flex-column mx-1 gap-1">

                <p>{{ $education['programme_name'] }}</p>

                <p>
                    {{ \Carbon\Carbon::parse($education['start_date'])->format('M Y') }}
                    -
                    {{ \Carbon\Carbon::parse($education['end_date'])->format('M Y') }}
                </p>

                <p>Grade: {{ $education['cgpa'] }}</p>

                <p>{{ $education['description'] }}</p>

            </div>
            @if (!empty($education['skills']))
            <div class="skills d-flex flex-wrap align-items-center">
                Skills:

                @foreach ($education['skills'] as $skill)
                <div class="badge text-bg-secondary m-1">
                    <span>{{ $skill['skill_name'] }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if (!empty($education['media']))
            <div class="images d-flex flex-wrap" data-image-preview-group>

                @foreach ($education['media'] as $media)
                <div class="image rounded-1 m-1" data-preview-image data-image="{{ $media['file_url'] }}"
                    data-title="{{ $media['title'] }}" data-description="{{ $media['description'] ?? '' }}"
                    style="background-image: url('{{ $media['file_url'] }}'); cursor: pointer;">
                </div>
                @endforeach

            </div>
            @endif
        </article>

        @endforeach
        @include('components.image-preview')
    </div>
</div>

<div class="modal fade" id="educationModal" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content h-75">

            <div class="modal-header">
                <h1 class="h3 modal-title fs-5">Add Education</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <!-- Institution -->
                <div class="mb-3">
                    <label for="institution" class="form-label">Institution</label>
                    <select class="form-select" id="institution" name="institution" required>
                        <option value="" selected disabled>Select Institution</option>
                    </select>
                </div>

                <!-- Field of Study -->
                <div class="mb-3">
                    <label for="fieldOfStudy" class="form-label">Field of Study</label>
                    <input type="text" class="form-control" id="fieldOfStudy" name="field_of_study"
                        placeholder="e.g. Computer Science" required>
                </div>

                <!-- Qualification -->
                <div class="mb-3">
                    <label for="qualification" class="form-label">Qualification</label>
                    <select class="form-select" id="qualification" name="qualification" required>
                        <option selected disabled value="">Select Qualification</option>
                        <option value="diploma">Diploma</option>
                        <option value="degree">Degree</option>
                        <option value="master">Master</option>
                        <option value="doctorate">Doctorate</option>
                    </select>
                </div>

                <!-- Programme Name -->
                <div class="mb-3">
                    <label for="programmeName" class="form-label">Programme Name</label>
                    <input type="text" class="form-control" id="programmeName" name="programme_name"
                        placeholder="e.g. Bachelor of Computer Science" required>
                </div>

                <!-- CGPA -->
                <div class="mb-3">
                    <label for="cgpaInput" class="form-label">
                        CGPA
                    </label>

                    <input type="number" class="form-control" id="cgpaInput" name="cgpa" placeholder="e.g. 3.85"
                        step="0.01" min="0" max="4.00">
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">
                        Description
                    </label>

                    <textarea class="form-control" id="descriptionInput" name="description" rows="4"
                        placeholder="Describe your education"></textarea>
                </div>

                <!-- Start Date -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Start Date</label>

                    <div class="row g-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate" name="start_date" required>
                    </div>

                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDate" name="end_date" required>
                </div>

                <!-- Skills -->
                <div class="mb-3">
                    <label class="form-label">Skill</label>
                    <div>
                        <button id="addSkill" type="button" class="btn btn-outline-primary btn-sm">+ Add Skill</button>
                        <div class="mt-2" id="skillContainer">

                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Media</label>
                    <p class="text-muted small mb-2">
                        Add media like images, documents or presentations.
                    </p>

                    <input type="file" id="mediaFileInput" accept="image/*" hidden>

                    <button type="button" id="addMedia" class="btn btn-outline-primary btn-sm">
                        + Add Media
                    </button>

                    <div id="mediaContainer" class="d-flex gap-2 flex-wrap mt-3"></div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnSaveEducation">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editMediaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit media</h5>

                <button type="button" class="btn-close" id="closeMediaModal"></button>
            </div>

            <div class="modal-body">

                <!-- Title -->
                <div class="mb-4">
                    <label class="form-label">
                        Title <span class="text-danger">*</span>
                    </label>

                    <input type="text" id="mediaTitle" class="form-control" maxlength="200">

                    <div class="text-end text-muted small">
                        <span id="mediaTitleCount">0</span>/200
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label">
                        Description
                    </label>

                    <textarea id="mediaDescription" class="form-control"></textarea>

                </div>

                <!-- Preview -->
                <div id="mediaEditPreview"></div>

            </div>

            <div class="modal-footer justify-content-between">

                <button type="button" id="deleteMedia" class="btn btn-link text-danger text-decoration-none">
                    Delete
                </button>

                <div>
                    <button type="button" class="btn btn-outline-secondary me-2" id="backMedia">
                        Back
                    </button>

                    <button type="button" class="btn btn-primary" id="saveMedia">
                        Save
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection

@section('script')
<script defer>
    let educationList = @json($educations);

    let educationMode = "add";
    let editingEducationId = null;

    let selectedImage = null;
    let editingMediaIndex = null;

    let mediaList = [];
    let deletedMediaIds = [];
    let skillList = [];
    let deletedSkillIds = [];

    let educationModalEl = document.getElementById("educationModal");
    let editMediaModalEl = document.getElementById("editMediaModal");
    let educationModal = bootstrap.Modal.getOrCreateInstance(educationModalEl);
    let editMediaModal = bootstrap.Modal.getOrCreateInstance(editMediaModalEl);

    let addSkillBtn = document.getElementById("addSkill");
    let skillContainer = document.getElementById("skillContainer");

    let addMediaBtn = document.getElementById("addMedia");
    let mediaFileInput = document.getElementById("mediaFileInput");
    let mediaTitle = document.getElementById("mediaTitle");
    let mediaDescription = document.getElementById("mediaDescription");
    let mediaEditPreview = document.getElementById("mediaEditPreview");
    let mediaContainer = document.getElementById("mediaContainer");
    let saveMediaBtn = document.getElementById("saveMedia");
    let backMediaBtn = document.getElementById("backMedia");
    let closeMediaModalBtn = document.getElementById("closeMediaModal");
    let deleteMediaBtn = document.getElementById("deleteMedia");
    let mediaTitleCount = document.getElementById("mediaTitleCount");
    let btnSaveEducation = document.getElementById("btnSaveEducation");

    let skillRequest;
    let institutionRequest;

    function loadInstitutions() {
        institutionRequest = $.ajax({
            url: "{{ asset('assets/internship-assets/data/institution.json') }}",
            type: "GET",
            dataType: "json",

            success: function (response) {
                $("#institution").html('<option value="" selected disabled>Select Institution</option>');

                response.forEach(function (institution) {
                    $("#institution").append(`
                    <option value="${institution.id}">${institution.name}</option>
                `);
                });
            },

            error: function (xhr) {
                console.log(xhr);
            }
        });
    }


    function loadSkills() {
        skillRequest = $.ajax({
            url: "{{ asset('assets/internship-assets/data/skills.json') }}",
            type: "GET",
            dataType: "json",

            success: function (response) {
                skillList = response;
            },

            error: function (xhr) {
                console.log(xhr.responseJSON);
            }
        });
    }

    // load ajax
    loadInstitutions();
    loadSkills();

    function createSkillRow(skill = "", proficiency = "", skillRowId = null) {
        let skillRow = document.createElement("div");

        skillRow.classList.add("mb-3", "skill-row");

        if (skillRowId) {
            skillRow.dataset.id = skillRowId;
        }

        let skillOptions = "";

        skillList.forEach(function (item) {
            skillOptions += `
			<option value="${item.id}" ${skill == item.id ? "selected" : ""}>${item.name}</option>
		`;
        });

        skillRow.innerHTML = `
		<div class="input-group">

			<select class="form-select form-select-sm skill-select" required>
				<option value="" disabled ${skill === "" ? "selected" : ""}>Select Skill</option>
				${skillOptions}
			</select>

			<select class="form-select form-select-sm proficiency-select" required>
				<option value="" disabled ${proficiency === "" ? "selected" : ""}>Select Proficiency</option>
				<option value="Beginner" ${proficiency === "Beginner" ? "selected" : ""}>Beginner</option>
				<option value="Intermediate" ${proficiency === "Intermediate" ? "selected" : ""}>Intermediate</option>
				<option value="Advanced" ${proficiency === "Advanced" ? "selected" : ""}>Advanced</option>
				<option value="Expert" ${proficiency === "Expert" ? "selected" : ""}>Expert</option>
			</select>

			<button type="button" class="btn btn-outline-danger remove-skill">
				<i class="fa-solid fa-trash"></i>
			</button>

		</div>
	`;

        return skillRow;
    }

    addSkillBtn.addEventListener("click", function () {
        skillContainer.appendChild(createSkillRow());
    });


    skillContainer.addEventListener("click", function (event) {
        let removeBtn = event.target.closest(".remove-skill");
        if (!removeBtn) return;
        let row = removeBtn.closest(".skill-row");
        if (!row) return;
        if (row.dataset.id) {
            deletedSkillIds.push(row.dataset.id);
        }
        row.remove();
    });

    function renderSkills(skills = []) {
        skillContainer.innerHTML = "";

        skills.forEach(function (skill) {
            skillContainer.appendChild(
                createSkillRow(
                    skill.skill_id,
                    skill.proficiency,
                    skill.id
                )
            );
        });
    }

    addMediaBtn.addEventListener("click", function () {
        editingMediaIndex = null;
        selectedImage = null;

        deleteMediaBtn.classList.add("d-none");
        mediaFileInput.value = "";
        mediaFileInput.click();
    })


    mediaFileInput.addEventListener("change", function () {
        let file = this.files[0];
        if (!file) return

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

        editingMediaIndex = null;
        selectedImage = file;
        mediaTitle.value = file.name.replace(/\.[^/.]+$/, "");
        mediaDescription.value = "";

        updateMediaTitleCount();
        showImagePreview(file);

        deleteMediaBtn.classList.add("d-none");
        educationModalEl.addEventListener("hidden.bs.modal", function () {
            editMediaModal.show();
        }, { once: true });

        educationModal.hide();
    });

    function showImagePreview(file) {

        let imageURL = URL.createObjectURL(file);
        mediaEditPreview.innerHTML = `
				<img src="${imageURL}" class="rounded border" style="width: 120px; height: 90px; object-fit: cover;">
			`;

        let img = mediaEditPreview.querySelector("img");

        img.addEventListener("load", function () {
            URL.revokeObjectURL(imageURL);
        }, { once: true });
    }

    function editImage(index) {
        if (!mediaList[index]) return

        editingMediaIndex = index;
        let media = mediaList[index];
        selectedImage = media.file ?? null;
        mediaTitle.value = media.title ?? "";
        mediaDescription.value = media.description ?? "";

        updateMediaTitleCount();
        deleteMediaBtn.classList.remove("d-none");

        mediaEditPreview.innerHTML = `
				<img src="${media.preview}" class="rounded border"
					style="width: 120px; height: 90px; object-fit: cover;">`;

        educationModalEl.addEventListener("hidden.bs.modal", function () {
            editMediaModal.show();
        },
            { once: true });

        educationModal.hide();
    }

    saveMediaBtn.addEventListener("click", function () {
        if (editingMediaIndex === null && !selectedImage) return

        let title = mediaTitle.value.trim();
        let description = mediaDescription.value.trim();

        if (title === "") {
            alert("Title is required.");
            mediaTitle.focus();
            return;
        }

        if (editingMediaIndex === null) {
            let previewURL = URL.createObjectURL(selectedImage);

            mediaList.push({
                id: null,
                file: selectedImage,
                title: title,
                description: description,
                preview: previewURL
            });

        } else {
            mediaList[editingMediaIndex].title = title;
            mediaList[editingMediaIndex].description = description;
        }

        renderMedia();
        resetMediaForm();
        backToEducation();
    });


    deleteMediaBtn.addEventListener("click", function () {
        if (editingMediaIndex === null) return

        let media = mediaList[editingMediaIndex];

        if (media.id) deletedMediaIds.push(media.id);

        if (media.preview && media.preview.startsWith("blob:")) {
            URL.revokeObjectURL(media.preview);
        }

        mediaList.splice(editingMediaIndex, 1);

        renderMedia();
        resetMediaForm();
        backToEducation();
    });

    backMediaBtn.addEventListener("click", function () {
        resetMediaForm();
        backToEducation();
    });

    if (closeMediaModalBtn) {
        closeMediaModalBtn.addEventListener("click", function () {
            resetMediaForm();
            backToEducation();
        });
    }

    function resetMediaForm() {
        selectedImage = null;
        editingMediaIndex = null;
        mediaFileInput.value = "";
        mediaTitle.value = "";
        mediaDescription.value = "";
        mediaEditPreview.innerHTML = "";
        deleteMediaBtn.classList.add("d-none");
        if (mediaTitleCount) {
            mediaTitleCount.textContent = "0";
        }
    }

    function backToEducation() {
        editMediaModalEl.addEventListener("hidden.bs.modal", function () {
            educationModal.show();

        }, { once: true });
        editMediaModal.hide();
    }

    function updateMediaTitleCount() {
        if (!mediaTitleCount) return
        mediaTitleCount.textContent = mediaTitle.value.length;
    }

    if (mediaTitleCount) {
        mediaTitle.addEventListener("input", updateMediaTitleCount)
    }

    function renderMedia() {
        mediaContainer.innerHTML = "";

        mediaList.forEach(function (media, index) {
            let item = document.createElement("div");
            item.classList.add("position-relative", "media-item");

            item.innerHTML = `
					<div class="position-relative"
						style="width: 100px; height: 75px;">

						<img src="${media.preview}" class="rounded border"
							style="width: 100%; height: 100%; object-fit: cover;">

						<button type="button"
							class="btn btn-light btn-sm rounded-circle position-absolute bottom-0 end-0 shadow-sm"
							onclick="editImage(${index})"
							style="width: 28px;height: 28px;padding: 0;">
							<i class="fa-solid fa-pen"></i>
						</button>
					</div>

					<small class="d-block text-truncate mt-1"
						style="width: 100px;">
						${media.title}
					</small>
				`;

            mediaContainer.appendChild(item);
        });
    }

    function openAddEducation() {
        educationMode = "add";
        editingEducationId = null;
        deletedMediaIds = [];
        deletedSkillIds = [];
        resetMediaForm();

        $(".modal-title").text("Add Education")
        $("#btnSaveEducation").text("Add")

        $("#institution").val("");
        $("#fieldOfStudy").val("");
        $("#qualification").val("");
        $("#programmeName").val("");
        $("#cgpaInput").val("");
        $("#descriptionInput").val("");
        $("#startDate").val("");
        $("#endDate").val("");

        skillContainer.innerHTML = "";
        mediaList.forEach(function (media) {
            if (media.preview && media.preview.startsWith("blob:")) {
                URL.revokeObjectURL(media.preview);
            }
        });

        mediaList = [];
        renderMedia();
        educationModal.show();
    }

    function editEducation(id) {
        educationMode = "edit";
        editingEducationId = id;
        deletedMediaIds = [];
        deletedSkillIds = [];
        resetMediaForm();

        let data = educationList.find((education) => {
            return education.id == id;
        });

        if (!data) return;

        $(".modal-title").text("Edit Education")
        $("#btnSaveEducation").text("Update")

        institutionRequest.done(function () {
            $("#institution").val(data.institution);
        });
        $("#fieldOfStudy").val(data.field_of_study);
        $("#qualification").val(data.qualification);
        $("#programmeName").val(data.programme_name);
        $("#cgpaInput").val(data.cgpa);
        $("#descriptionInput").val(data.description);
        $("#startDate").val(data.start_date);
        $("#endDate").val(data.end_date);

        skillRequest.done(function () {
            renderSkills(data.skills ?? []);
        });

        mediaList = (data.media ?? []).map(function (media) {
            return {
                id: media.id,
                file: null,
                title: media.title,
                description: media.description ?? "",
                preview: media.file_url
            };
        });

        renderMedia();
        educationModal.show();
    }
    btnSaveEducation.addEventListener("click", function (event) {
        event.preventDefault();
        let formData = new FormData();

        formData.append("institution", $("#institution").val());
        formData.append("field_of_study", $("#fieldOfStudy").val());
        formData.append("qualification", $("#qualification").val());
        formData.append("programme_name", $("#programmeName").val());
        formData.append("cgpa", $("#cgpaInput").val());
        formData.append("description", $("#descriptionInput").val());
        formData.append("start_date", $("#startDate").val());
        formData.append("end_date", $("#endDate").val());

        document.querySelectorAll("#skillContainer .skill-row").forEach((row, index) => {
            let skill = row.querySelector(".skill-select").value;
            let proficiency = row.querySelector(".proficiency-select").value;

            if (row.dataset.id) {
                formData.append(`skills[${index}][id]`, row.dataset.id);
            }

            formData.append(`skills[${index}][skill_id]`, skill);
            formData.append(`skills[${index}][proficiency]`, proficiency);
        });

        deletedSkillIds.forEach(function (id, index) {
            formData.append(`deleted_skill_ids[${index}]`, id);
        });

        mediaList.forEach(function (media, index) {
            if (media.id) {
                formData.append(`media[${index}][id]`, media.id);
            }

            if (media.file) {
                formData.append(`media[${index}][file]`, media.file);
            }

            formData.append(`media[${index}][title]`, media.title);
            formData.append(`media[${index}][description]`, media.description ?? "");
        });

        deletedMediaIds.forEach(function (id, index) {
            formData.append(`deleted_media_ids[${index}]`, id);
        });

        let url
        if (educationMode === "add") {
            url = "/education";
        } else {
            url = `/education/${editingEducationId}`;
            formData.append("_method", "PUT");
        }

        console.log("MODE:", educationMode);
        console.log("URL:", url);
        console.log("FORM DATA:");
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        return

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {
                document.activeElement.blur();
                window.location.reload();
            },

            error: function (xhr) {
                console.log(xhr.responseJSON);
            }
        });

    });
</script>
@endsection
<!-- Add request
{
    "institution": 1,
    "field_of_study": "Computer Science",
    "qualification": "degree",
    "programme_name": "Bachelor of Computer Science (Software Engineering)",
    "cgpa": "4.00",
    "description": "Currently pursuing a degree in software engineering",
    "start_date": "2026-10-01",
    "end_date": "2027-10-01",

    "skills": [
        {
            "skill_id": 1,
            "proficiency": "Advanced"
        },
        {
            "skill_id": 3,
            "proficiency": "Intermediate"
        }
    ],

    "media": [
        {
            "file": "FILE",
            "title": "Project Screenshot",
            "description": "Screenshot of my university project"
        },
        {
            "file": "FILE",
            "title": "System Design",
            "description": "System interface design"
        }
    ]
}

update request
{
    "institution": 1,
    "field_of_study": "Computer Science",
    "qualification": "degree",
    "programme_name": "Bachelor of Computer Science (Software Engineering)",
    "cgpa": "4.00",
    "description": "Updated education description",
    "start_date": "2026-10-01",
    "end_date": "2027-10-01",

    "skills": [
        {
            "id": 10,
            "skill_id": 1,
            "proficiency": "Advanced"
        },
        {
            "skill_id": 6,
            "proficiency": "Intermediate"
        }
    ],

    "deleted_skill_ids": [
        11
    ],

    "media": [
        {
            "id": 1,
            "title": "Project Screenshot",
            "description": "Image lama yang kekal"
        },
        {
            "title": "New Screenshot",
            "description": "Image baru",
            "file": "FILE"
            "source_name":EXPERIENCES" huruf kecil,
            "source_id":"id_table"
        }
    ],

    "deleted_media_ids": [
        2,
        3
    ]
}
-->
