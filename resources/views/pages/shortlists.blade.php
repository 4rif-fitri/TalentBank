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
        background: #fff;
        height: 80vh !important;
        overflow-y: auto;
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

    @media (max-width: 1300px) {
        .talent-layout {
            display: block;
        }

        .filter-panel {
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

        body.filter-open .filter-panel {
            transform: translateX(0);
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
        <h3 class="m-0 fw-bold">Talent Shortlists</h3>
        <button class="btn btn-primary d-xl-none btn-toggle-filter" onclick="toggleFilter()">
            <i class="fa-solid fa-filter"></i>
            Shortlists
        </button>
    </div>

    <div class="talent-layout">

        <aside class="filter-panel" id="filterPanel">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                <h5 class="m-0 fw-bold">Your Shortlists</h5>
                <button class="btn btn-sm" onclick="toggleFilter()">
                    <i class="fa-solid fa-plus fs-5"></i>
                </button>
            </div>

            <div class="d-flex justify-content-center align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                <div role="button" class="d-flex align-items-center gap-3">
                    <i class="fa-regular fa-folder fa-xl"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">
                            Software Engineering Interns
                        </div>
                        <small class="text-muted">
                            Created 22 May 2025
                        </small>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                <div role="button" class="d-flex align-items-center gap-3">
                    <i class="fa-regular fa-folder fa-xl"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">
                            Software Engineering Interns
                        </div>
                        <small class="text-muted">
                            Created 22 May 2025
                        </small>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                <div role="button" class="d-flex align-items-center gap-3">
                    <i class="fa-regular fa-folder fa-xl"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">
                            Software Engineering Interns
                        </div>
                        <small class="text-muted">
                            Created 22 May 2025
                        </small>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center gap-3 shortlist-item mb-2 p-3 border rounded">
                <div role="button" class="d-flex align-items-center gap-3">
                    <i class="fa-regular fa-folder fa-xl"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">
                            Software Engineering Interns
                        </div>
                        <small class="text-muted">
                            Created 22 May 2025
                        </small>
                    </div>
                </div>
            </div>
        </aside>

        <div class="results-panel flex-grow-1">
            <div class="row g-3 ">

                <div class="card h-100 shadow-sm border-0 p-3 position-relative">
                    <div class="dropdown position-absolute top-0 end-0 m-3">

                        <button type="button" class="btn btn-sm p-1" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                            <li>
                                <button class="dropdown-item">
                                    <i class="fa-regular fa-copy me-2"></i>
                                    Edit
                                </button>
                            </li>

                            <li>
                                <button class="dropdown-item text-danger">
                                    <i class="fa-regular fa-trash-can me-2"></i>
                                    Delete
                                </button>
                            </li>

                        </ul>

                    </div>

                    <h1 class="fw-bolder">Software Engineering Interns 2026</h1>
                    <p class="mt-1">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quibusdam
                        dolorum unde
                        praesentium iusto</p>

                    <div class="d-flex mt-2 gap-2">
                        <div class="badge text-dark border">lorem</div>
                        <div class="badge text-dark border">IT Department</div>
                        <div class="badge text-dark border">Melaka</div>
                        <div class="badge text-dark border">Melaka</div>
                        <div class="badge text-dark border">5 person</div>
                    </div>
                    <hr>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">Candidate</th>
                                <th scope="col" class="d-none d-md-inline-block border-0">Skills</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <div class="d-flex gap-2">
                                        <h6 class="text-start">Muhammad Haziq Irfan Bin Muhammad Zamri</h6>
                                        <p class="text-muted text-start">(UTEM)</p>
                                    </div>
                                </td>
                                <td class="d-none d-md-inline-block">
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-plus "></i>
                                        5
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-primary">
                                        Saved
                                    </div>
                                </td>
                                <td class="position-relative">
                                    <button type="button" class="btn btn-light border" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="dropdown position-absolute top-0 end-0">
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-solid fa-address-book"></i>
                                                    View Profile
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-regular fa-paper-plane me-2"></i>
                                                    Invite to interview
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger">
                                                    <i class="fa-regular fa-trash-can me-2"></i>
                                                    Delete
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="d-flex gap-2">
                                        <h6 class="text-start">Muhammad Haziq Irfan Bin Muhammad Zamri</h6>
                                        <p class="text-muted text-start">(UTEM)</p>
                                    </div>
                                </td>
                                <td class="d-none d-md-inline-block">
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-plus "></i>
                                        5
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-warning">Invited</div>
                                </td>
                                <td class="position-relative">
                                    <button type="button" class="btn btn-light border" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="dropdown position-absolute top-0 end-0">
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-solid fa-address-book"></i>
                                                    View Profile
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger">
                                                    <i class="fa-solid fa-circle-minus"></i>
                                                    Cencel Invite
                                                </button>
                                            </li>

                                            <li>
                                                <button class="dropdown-item text-danger">
                                                    <i class="fa-regular fa-trash-can me-2"></i>
                                                    Delete
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="d-flex gap-2">
                                        <h6 class="text-start">Muhammad Haziq Irfan Bin Muhammad Zamri</h6>
                                        <p class="text-muted text-start">(UTEM)</p>
                                    </div>
                                </td>
                                <td class="d-none d-md-inline-block">
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-plus "></i>
                                        5
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-info">
                                        Accpet Invite
                                    </div>
                                </td>
                                <td class="position-relative">
                                    <button type="button" class="btn btn-light border" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="dropdown position-absolute top-0 end-0">
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-solid fa-address-book"></i>
                                                    View Profile
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-regular fa-calendar"></i>
                                                    Set Interview
                                                </button>
                                            </li>

                                            <li>
                                                <button class="dropdown-item text-danger">
                                                    <i class="fa-regular fa-trash-can me-2"></i>
                                                    Delete
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="d-flex gap-2">
                                        <h6 class="text-start">Muhammad Haziq Irfan Bin Muhammad Zamri</h6>
                                        <p class="text-muted text-start">(UTEM)</p>
                                    </div>
                                </td>
                                <td class="d-none d-md-inline-block">
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-plus "></i>
                                        5
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-secondary">
                                        Interview
                                    </div>
                                </td>
                                <td class="position-relative">
                                    <button type="button" class="btn btn-light border" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="dropdown position-absolute top-0 end-0">
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-solid fa-address-book"></i>
                                                    View Profile
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-solid fa-file-circle-plus"></i>
                                                    Sent Job Offer
                                                </button>
                                            </li>

                                            <li>
                                                <button class="dropdown-item text-danger">
                                                    <i class="fa-regular fa-trash-can me-2"></i>
                                                    Delete
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="d-flex gap-2">
                                        <h6 class="text-start">Muhammad Haziq Irfan Bin Muhammad Zamri</h6>
                                        <p class="text-muted text-start">(UTEM)</p>
                                    </div>
                                </td>
                                <td class="d-none d-md-inline-block">
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-plus "></i>
                                        5
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-secondary">
                                        Sent Offer
                                    </div>
                                </td>
                                <td class="position-relative">
                                    <button type="button" class="btn btn-light border" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="dropdown position-absolute top-0 end-0">
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-solid fa-address-book"></i>
                                                    View Profile
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item">
                                                    <i class="fa-solid fa-file-circle-minus"></i>
                                                    Cencel Sent Offer
                                                </button>
                                            </li>

                                            <li>
                                                <button class="dropdown-item text-danger">
                                                    <i class="fa-regular fa-trash-can me-2"></i>
                                                    Delete
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="d-flex gap-2">
                                        <h6 class="text-start">Muhammad Haziq Irfan Bin Muhammad Zamri</h6>
                                        <p class="text-muted text-start">(UTEM)</p>
                                    </div>
                                </td>
                                <td class="d-none d-md-inline-block">
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-laravel fa-lg"></i>
                                        laravel
                                    </div>
                                    <div class="badge border text-black">
                                        <i class="fa-brands fa-plus "></i>
                                        5
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-success">
                                        Accept Offer
                                    </div>
                                </td>
                                <td class="position-relative">

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="filter-overlay" onclick="toggleFilter()"></div>

@endsection

@section('script')
<script>
    // Fungsi untuk toggle filter panel pada mobile
    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }
</script>
@endsection
