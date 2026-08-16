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

@push('scripts')
<script>
    $(".btn-edit-education").on("click", function () {
        let eduId = $(this).data("id");
        console.log("Education ID:", eduId);
        getEducationDetail(eduId);
    });

</script>
@endpush
