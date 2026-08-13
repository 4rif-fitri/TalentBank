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

        <article class="gap-2 d-flex flex-column position-relative mb-2">
            <div class="icon-container" style="top: 0; right: 0;">
                <button type="button" class="btn btn-secondary icon" onclick="editEducation(15)">
                    <i class="fa-solid fa-pencil"></i>
                </button>
            </div>
            <div class="d-flex align-items-center gap-2">
                <img src="{{ URL::asset('assets/images/profile/cover-image.png') }}" alt=""
                    style="width: 50px !important; height: 50px !important; border-radius: 50%;">
                <p class="h5 fw-bold">Universiti Teknikal Malaysia Melaka (UTeM)</p>
            </div>
            <div class="col d-flex flex-column mx-1 gap-1">
                <p>Bachelor of Computer Science (Software Engineering)</p>
                <p>Oct 2026 - Oct 2027</p>
                <p>Grade: 4.00</p>
                <p>Currently pursuing a degree in software engineering with emphasis on system</p>

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
                    <input type="text" class="form-control" id="institution" name="institution"
                        placeholder="Universiti Teknikal Malaysia Melaka" required>
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
                    <label class="form-label fw-bold">
                        Start Date
                    </label>

                    <div class="row g-3">

                        <div class="col-6">
                            <label for="startMonth" class="form-label">
                                Month
                            </label>

                            <select class="form-select" id="startMonth" name="start_month" required>
                                <option selected disabled value="">Select Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="startYear" class="form-label">Year</label>
                            <select class="form-select" id="startYear" name="start_year" required>
                                <option selected disabled value="">Select Year</option>
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label class="form-label fw-bold">End Date</label>
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="endMonth" class="form-label">Month</label>
                            <select class="form-select" id="endMonth" name="end_month" required>
                                <option selected disabled value="">Select Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="endYear" class="form-label">Year</label>
                            <select class="form-select" id="endYear" name="end_year" required>
                                <option selected disabled value="">Select Year</option>
                                <option value="2030">2030</option>
                                <option value="2029">2029</option>
                                <option value="2028">2028</option>
                                <option value="2027">2027</option>
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Skills -->
                <div class="mb-3">
                    <label class="form-label">Skill</label>
                    <div>
                        <button id="addSkill" type="button" class="btn btn-outline-primary btn-sm">+ Add
                            Skill</button>
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
<script>
    let educationMode = "add";
    let editingEducationId = null;

    let selectedImage = null;
    let editingMediaIndex = null;

    let mediaList = [];
    let deletedMediaIds = [];
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

    function createSkillRow(skill = "", proficiency = "") {

        let skillRow = document.createElement("div");

        skillRow.classList.add("mb-3", "skill-row");

        skillRow.innerHTML = `
                <div class="input-group">

                    <select class="form-select form-select-sm skill-select" required>
                        <option value="" disabled ${skill === "" ? "selected" : ""}>Select Skill</option>
                        <option value="PHP" ${skill === "PHP" ? "selected" : ""}>PHP</option>
                        <option value="Laravel" ${skill === "Laravel" ? "selected" : ""}>Laravel</option>
                        <option value="JavaScript" ${skill === "JavaScript" ? "selected" : ""}>JavaScript</option>
                        <option value="Vue" ${skill === "Vue" ? "selected" : ""}>Vue.js</option>
                        <option value="React" ${skill === "React" ? "selected" : ""}>React</option>
                        <option value="MySQL" ${skill === "MySQL" ? "selected" : ""}>MySQL</option>
                        <option value="HTML_CSS" ${skill === "HTML_CSS" ? "selected" : ""}>HTML & CSS</option>
                    </select>


                    <select class="form-select form-select-sm proficiency-select" required>
                        <option value="" disabled ${proficiency === "" ? "selected" : ""}>Select Proficiency</option>
                        <option value="Beginner" ${proficiency === "Beginner" ? "selected" : ""}>Beginner</option>
                        <optionvalue="Intermediate"${proficiency === "Intermediate" ? "selected" : ""}>Intermediate</optionvalue=>
                        <optionvalue="Advanced"${proficiency === "Advanced" ? "selected" : ""}>Advanced</optionvalue=>
                        <optionvalue="Expert"${proficiency === "Expert" ? "selected" : ""}>Expert</option>
                    </select>

                    <button type="button"class="btn btn-outline-danger remove-skill">
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
        if (row) row.remove();
    });

    function renderSkills(skills = []) {
        skillContainer.innerHTML = "";
        skills.forEach(function (skill) {
            skillContainer.appendChild(createSkillRow(skill.skill, skill.proficiency));
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
        resetMediaForm();

        document.querySelector("#educationModal .modal-title").textContent = "Add Education";
        btnSaveEducation.textContent = "Save";

        $("#institution").val("");
        $("#fieldOfStudy").val("");
        $("#qualification").val("");
        $("#programmeName").val("");
        $("#cgpaInput").val("");
        $("#descriptionInput").val("");
        $("#startMonth").val("");
        $("#startYear").val("");
        $("#endMonth").val("");
        $("#endYear").val("");

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
        resetMediaForm();

        let data = {
            id: id,
            institution: "Universiti Teknikal Malaysia Melaka (UTeM)",
            field_of_study: "Computer Science",
            qualification: "degree",
            programme_name: "Bachelor of Computer Science (Software Engineering)",
            cgpa: "4.00",
            description: "Currently pursuing a degree in software engineering with emphasis on system",
            start_month: "10",
            start_year: "2026",
            end_month: "10",
            end_year: "2027",
            skills: [
                {
                    skill: "PHP",
                    proficiency: "Advanced"
                }, {
                    skill: "Laravel",
                    proficiency: "Intermediate"
                }
            ],
            media: [
                {
                    id: 1,
                    title: "Project Screenshot",
                    description: "My education media",
                    preview: "../images/cover-image.png",
                    file: null
                }
            ]
        };


        document.querySelector("#educationModal .modal-title").textContent = "Edit Education";
        btnSaveEducation.textContent = "Update";

        $("#institution").val(data.institution);
        $("#fieldOfStudy").val(data.field_of_study);
        $("#qualification").val(data.qualification);
        $("#programmeName").val(data.programme_name);
        $("#cgpaInput").val(data.cgpa);
        $("#descriptionInput").val(data.description);
        $("#startMonth").val(data.start_month);
        $("#startYear").val(data.start_year);
        $("#endMonth").val(data.end_month);
        $("#endYear").val(data.end_year);

        renderSkills(data.skills ?? []);
        mediaList = (data.media ?? []).map(function (media) {
            return {
                id: media.id,
                file: null,
                title: media.title,
                description: media.description ?? "",
                preview: media.preview
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
        formData.append("start_month", $("#startMonth").val());
        formData.append("start_year", $("#startYear").val());
        formData.append("end_month", $("#endMonth").val());
        formData.append("end_year", $("#endYear").val());

        document.querySelectorAll("#skillContainer .skill-row").forEach((row, index) => {
            let skill = row.querySelector(".skill-select").value;
            let proficiency = row.querySelector(".proficiency-select").value;
            formData.append(`skills[${index}][skill]`, skill);
            formData.append(`skills[${index}][proficiency]`, proficiency);
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

        let url;

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

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            /*
            headers: {
                "X-CSRF-TOKEN":
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute(
                            "content"
                        )
            },
            */

            success: function (response) {
                console.log(response);
                educationModal.hide();
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
            }
        });

    });
</script>
@endsection
