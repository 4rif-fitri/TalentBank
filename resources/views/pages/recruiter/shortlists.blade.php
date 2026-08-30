@extends('layouts.internship-layouts')

@section('css')
<style>
    .shortlist-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .shortlist-sidebar {
        width: 350px;
        background: #fff;
        height: 80vh !important;
        overflow-y: auto;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .shortlist-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        z-index: 1040;
    }

    @media (max-width: 1300px) {
        .shortlist-layout {
            display: block;
        }

        .shortlist-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 380px;
            max-width: 85vw;
            height: 100vh !important;
            z-index: 1050;
            border-radius: 0;
            overflow-y: auto;
            transform: translateX(-100%);
        }

        body.filter-open .shortlist-sidebar {
            transform: translateX(0);
        }

        body.filter-open .shortlist-overlay {
            display: block;
        }

        body.filter-open {
            overflow: hidden;
        }
    }

    .shortlist-item:hover {
        border: 1px solid #6d7eca !important;
        color: #6d7eca;

        small {
            color: #6d7eca !important;
        }

    }

    .shortlist-item.active {
        border: 1px solid #5267c4 !important;
        color: #5267c4;

        small {
            color: #5267c4 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Talent Shortlists</h3>
        <button class="btn btn-primary d-xl-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            Shortlists
        </button>
    </div>

    <div class="shortlist-layout">

        <aside class="shortlist-sidebar" id="listContainer">

            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                <h5 class="m-0 fw-bold">Your Shortlists</h5>
                <button type="button"
                    class="btnShowModalAddShortlist btn btn-outline-primary d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-plus fs-5"></i>
                </button>
            </div>

            <div id="shortlistList"></div>

        </aside>

        <div class="shortlist-content flex-grow-1">

            <div class="row g-3" id="shortlistContent"></div>

        </div>
    </div>
</div>

<div class="shortlist-overlay toggleFilter"></div>

<div class="modal fade" id="shortlistModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="shortlistModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="shortlistModalLabel">
                    Add Shortlist
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form id="shortlistForm">

                <div class="modal-body">

                    <div class="row">

                        <input type="text" id="position_id" class="form-control" hidden>

                        <div class="col-md-12 mb-3">
                            <label for="position_title" class="form-label">
                                Position Title
                            </label>

                            <input type="text" name="position_title" id="position_title" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="employment_type" class="form-label">
                                Employment Type
                            </label>

                            <select name="employment_type" id="employment_type" class="form-select" required>

                                @foreach (\App\Constants\AppConstants::EMPLOYMENT_TYPES as $type)
                                <option value="{{ $type }}">
                                    {{ $type }}
                                </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="vacancies" class="form-label">
                                Vacancies
                            </label>

                            <input type="number" name="vacancies" id="vacancies" class="form-control" min="1"
                                placeholder="e.g. 5" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="department" class="form-label">
                                Department
                            </label>

                            <input type="text" name="department" id="department" class="form-control"
                                placeholder="e.g. Information Technology" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="work_location" class="form-label">
                                Work Location
                            </label>

                            <input type="text" name="work_location" id="work_location" class="form-control"
                                placeholder="e.g. Durian Tunggal, Melaka, Malaysia" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea name="description" id="description" class="form-control" rows="5"
                                placeholder="Enter employment description..." required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="btnAddShortlist" class="btn btn-primary">
                        Save
                    </button>
                    <button type="submit" id="btnUpdateShortlist" class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@section('script')
<script type="module">

    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }

    // $(document).on("click", "#btnDeletTalentRow", function() {
    //     Swal.fire({
    //         title: "Are you sure?",
    //         text: "You won't be able to revert this!",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#3085d6",
    //         cancelButtonColor: "#d33",
    //         confirmButtonText: "Yes, delete it!"
    //     }).then((result) => {
    //         if (result.isConfirmed) Swal.fire({
    //             title: "Deleted!",
    //             text: "Your file has been deleted.",
    //             icon: "success"
    //         });
    //     });
    // })

    // $(document).on("click", "#btnDeleteShortlist", function() {
    //     Swal.fire({
    //         title: "Are you sure?",
    //         text: "You won't be able to revert this!",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#3085d6",
    //         cancelButtonColor: "#d33",
    //         confirmButtonText: "Yes, delete it!"
    //     }).then((result) => {
    //         if (result.isConfirmed) Swal.fire({
    //             title: "Deleted!",
    //             text: "Your file has been deleted.",
    //             icon: "success"
    //         });
    //     });
    // })

    let myData, positionId, curruntPosition

    async function getProfileDataByProfileId() {
        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");
        const response = await $.ajax({
            url: url,
            method: 'GET'
        });
        myData = response.data;
        console.log(myData);

        return response.data;
    }

    async function getPositionById(id) {
        let url = "{{ route('positions.getPositionById', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        return await $.ajax({
            url: url,
            method: 'GET'
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

    async function loadData() {
        try {
            const profile = await getProfileDataByProfileId();
            const organizations = profile.organization_users;

            const results = await Promise.all(
                organizations.map(organization => getPositionsByOrgId(organization.organization_id))
            );

            shortListRender.sideBar(results)

        } catch (error) {
            console.error("Ralat semasa loadData:", error);
        }
    }

    async function handleClickShortlist() {
        let newPositionId = $(this).data('id');
        if (newPositionId === positionId) return
        positionId = newPositionId

        try {
            $(".shortlist-item").each(function () {
                $(this).removeClass("active");
            });

            $(this).addClass("active")

            let response = await getPositionById(positionId)
            if (!response) return
            console.log(response.data);
            curruntPosition = response.data

            shortListRender.detail(response.data)

        } catch (error) {
            console.error(error);
        }


    }

    function storePosition(position_title, employment_type, vacancies, department, work_location, description) {
        let formData = new FormData()
        formData.append("organization_id", myData.organization_users[0].organization_id)
        formData.append("position_title", position_title)
        formData.append("employment_type", employment_type)
        formData.append("department", department)
        formData.append("work_location", work_location)
        formData.append("vacancies", vacancies)
        formData.append("description", description)

        return $.ajax({
            url: "{{ route('positions.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
    }

    function updatePositions(id, position_title, employment_type, vacancies, department, work_location, description) {
        let url = "{{ route('positions.update', ['id' => '__ID__']) }}"
        url = url.replace("__ID__", id);
        let formData = new FormData()
        formData.append("organization_id", myData.organization_users[0].organization_id)
        formData.append("position_title", position_title)
        formData.append("employment_type", employment_type)
        formData.append("department", department)
        formData.append("work_location", work_location)
        formData.append("vacancies", vacancies)
        formData.append("description", description)
        formData.append("_method", "PUT");

        return $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
        });
    }

    async function handleAddShortlist(e) {
        e.preventDefault();

        let form = $(this).closest("form");

        let position_title = form.find("#position_title").val()
        let employment_type = form.find("#employment_type").val()
        let vacancies = form.find("#vacancies").val()
        let department = form.find("#department").val()
        let work_location = form.find("#work_location").val()
        let description = form.find("#description").val()

        if (position_title == "") {
            salert.salert("Validation Error", "Please enter a position title", "warning");
            return
        }

        if (employment_type == "") {
            salert.salert("Validation Error", "Please select employment type", "warning");
            return
        }

        if (vacancies == "") {
            salert.salert("Validation Error", "Please enter a vacancies", "warning");
            return
        }

        if (department == "") {
            salert.salert("Validation Error", "Please enter a department", "warning");
            return
        }

        if (work_location == "") {
            salert.salert("Validation Error", "Please enter a work location", "warning");
            return
        }

        console.log({
            position_title,
            employment_type,
            vacancies,
            department,
            work_location,
            description
        });

        try {

            let response = await storePosition(position_title, employment_type, vacancies, department, work_location, description)
            if (!response) return

            console.log(response);
            salert.salert('Success', response.message, 'success');
            form[0].reset();
            bootstrap.Modal.getOrCreateInstance($("#shortlistModal")).hide();

            shortListRender.appendNew(response.data)

        } catch (xhr) {
            console.error(xhr);
            salert.salert("Error", xhr, "error")
        }

    }

    async function handleUpdateShortlist(e) {
        e.preventDefault()

        e.preventDefault();

        let form = $(this).closest("form");

        let position_id = form.find("#position_id").val()
        let position_title = form.find("#position_title").val()
        let employment_type = form.find("#employment_type").val()
        let vacancies = form.find("#vacancies").val()
        let department = form.find("#department").val()
        let work_location = form.find("#work_location").val()
        let description = form.find("#description").val()

        if (position_id == "") {
            salert.salert("Validation Error", "position id is NULL", "warning");
            return
        }

        if (position_title == "") {
            salert.salert("Validation Error", "Please enter a position title", "warning");
            return
        }

        if (employment_type == "") {
            salert.salert("Validation Error", "Please select employment type", "warning");
            return
        }

        if (vacancies == "") {
            salert.salert("Validation Error", "Please enter a vacancies", "warning");
            return
        }

        if (department == "") {
            salert.salert("Validation Error", "Please enter a department", "warning");
            return
        }

        if (work_location == "") {
            salert.salert("Validation Error", "Please enter a work location", "warning");
            return
        }

        console.log({
            position_id,
            position_title,
            employment_type,
            vacancies,
            department,
            work_location,
            description
        });

        try {

            let response = await updatePositions(parseInt(position_id), position_title, employment_type, vacancies, department, work_location, description)
            if (!response) return

            console.log(response);
            salert.salert('Success', response.message, 'success');
            form[0].reset();
            bootstrap.Modal.getOrCreateInstance($("#shortlistModal")).hide();

            shortListRender.detail(response.data)
            let $row = $("#shortlistList").find(`[data-id="${response.data.id}"]`)
            $row.find(".title").text(response.data.position_title)

        } catch (xhr) {
            console.error(xhr);
            salert.salert("Error", xhr, "error")
        }

    }

    $(document).ready(function () {
        loadData()
    });

    $(document).on("click", ".shortlist-item", handleClickShortlist)
    $(document).on("click", ".toggleFilter", toggleFilter)
    $(document).on("click", "#btnAddShortlist", handleAddShortlist)
    $(document).on("click", "#btnUpdateShortlist", handleUpdateShortlist)

    $(document).on("click", ".btnShowModalAddShortlist", function () {
        $("#btnAddShortlist").show()
        $("#btnUpdateShortlist").hide()
        bootstrap.Modal.getOrCreateInstance($("#shortlistModal")).show()
    });

    $(document).on("click", ".btnShowModalUpdateShortlist", function () {
        console.log(curruntPosition);

        $("#shortlistModal #position_id").val(curruntPosition.id)
        $("#shortlistModal #position_title").val(curruntPosition.position_title)
        $("#shortlistModal #employment_type").val(curruntPosition.employment_type)
        $("#shortlistModal #vacancies").val(curruntPosition.vacancies)
        $("#shortlistModal #department").val(curruntPosition.department)
        $("#shortlistModal #work_location").val(curruntPosition.work_location)
        $("#shortlistModal #description").val(curruntPosition.description)

        $("#btnAddShortlist").hide()
        $("#btnUpdateShortlist").show()
        bootstrap.Modal.getOrCreateInstance($("#shortlistModal")).show()
    });


</script>
@endsection