@extends('layouts.internship-layouts')

@section('css')
<style>
    .talent-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .filter-panel {
        width: 350px;
        background-color: white !important;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .filter-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        z-index: 1040;
    }

    .results-panel {
        padding: 1rem;
        background-color: #fff;
    }

    @media (max-width: 768px) {
        .talent-layout {
            display: block;
        }

        .filter-panel {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            max-width: 100vw;
            height: 100vh;
            z-index: 1050;
            border-radius: 0;
            overflow-y: auto;
            transform: translateY(100%);
        }

        body.filter-open .filter-panel {
            transform: translateY(10%);
            border-radius: 14px;
        }

        body.filter-open .filter-overlay {
            display: block;
        }

        body.filter-open {
            overflow: hidden;
        }
    }
</style>
@endsection

@section('content')
<div class="content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Recruitment Invitation</h3>
    </div>

    <div class="talent-layout">

        <aside class="results-panel" id="filterPanel">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button class="nav-link active text-primary">All</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Pending</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Accepted</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Dealined</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Exprired</button>
                    </li>
                </ul>
            </div>

            <div class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded"
                onclick="toggleFilter()">
                <div role="button" class="d-flex align-items-center gap-3">
                    <div class="flex-grow-1 d-flex flex-column">
                        <p class="fw-semibold">
                            Anak2you sdn bhd
                        </p>
                        <p class="text-primary fw-semibold">FrontEnd intern</p>
                        <p class="text-primary fw-semibold">FrontEnd intern</p>
                        <small class="text-primary">
                            Expires 22 May 2025
                        </small>
                    </div>
                </div>
                <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
            </div>


        </aside>

        <div class=" filter-panel flex-grow-1">
            <div class="row g-3 ">
                <div class="card h-100 shadow-sm border-0 p-3 position-relative">


                    <div class="d-flex gap-3">
                        <i class="fa-solid fa-circle-user" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                        <div>
                            <h3 class="fw-semibold">Dr Lorem Ipsum Dolor Sit Amit</h3>
                            <p class="fw-semibold">Sofware inginer with Honor</p>
                            <small class="text-muted d-block">Universiti Teknikal Malaysia Melaka(UTEM)</small>
                            <small class="text-muted d-block">
                                <i class="fa-solid fa-location-dot" style="color: rgb(0, 0, 0);"></i>
                                Durian Tunggal, Malaka, Malaysia
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between my-3">
                        <div class="border p-1">
                            <small class="text-muted">Position</small>
                            <h5 class="fw-semibold">FrontEnd Intern</h5>
                        </div>
                        <div>
                            <small class="text-muted">Department</small>
                            <h5 class="fw-semibold">Department IT and DEV</h5>
                        </div>
                        <div>
                            <small class="text-muted">employment_type</small>
                            <h5 class="fw-semibold">employment_type</h5>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-semibold">Personal Message</h5>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste reprehenderit veniam sunt dicta
                            iusto, temporibus ex consequatur perferendis quasi. Corrupti, mollitia aperiam! Dolorem,
                            ipsum! Quo expedita reiciendis quae libero accusamus!</p>
                    </div>

                    <div class="mt-2">
                        <h6 class="fw-semibold mb-1">Actions</h6>
                        <div>
                            <button class="btn btn-outline-primary">
                                <i class="fa-regular fa-message text-primary"></i>
                                Message Student
                            </button>
                            <button class="btn btn-outline-danger">
                                <i class="fa-regular fa-trash-can text-danger"></i>
                                Withdraw Invitation
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fa-solid fa-pen text-primary"></i>
                                Edit Invitation
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay Gelap untuk Mobile -->
<div class="filter-overlay" onclick="toggleFilter()"></div>
@endsection

@section('script')
<script>
    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }
</script>
<script>
    let url = "{{ route('invitations.getInvitationsByReceiverId', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 2)

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            console.log("getInvitationsByReceiverId", response)
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

    url = "{{ route('invitations.getInvitationById', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 6)

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            console.log("getInvitationsByReceiverId", response)
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

    // Once update only
    url = "{{ route('invitations.acceptInvitation', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 6)
    let formData = new FormData()
    formData.append("_method", "PUT")

    $.ajax({
        url,
        data: formData,
        type: "POST",
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function (response) {
            console.log("acceptInvitation", response)
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

    // Once update only
    url = "{{ route('invitations.rejectInvitation', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 7)
    formData = new FormData()
    formData.append("_method", "PUT")

    $.ajax({
        url,
        data: formData,
        type: "POST",
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function (response) {
            console.log("rejectInvitation", response)
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

</script>
@endsection