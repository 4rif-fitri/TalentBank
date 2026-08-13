@extends('layouts.internship-layouts')

@section('content')
    <div class="user-card">
        <div class="user-card-left">
            <section class="user-card-header">

                <div class="user-card-banner" style="background-image: url('../assets/internship-assets/images/7.jpg');">
                    <div class="icon-container">
                        <label for="coverPhotoInput" class="btn icon bg-body">
                            <i class="fa-solid fa-pencil"></i>
                            <input type="file" hidden id="coverPhotoInput" accept="image/*">
                        </label>
                    </div>
                </div>

                <div class="user-profile-group">
                    <div class="profile-image" style="background-image: url('../assets/internship-assets/images/7.jpg');">
                        <div class="w-100 h-100 position-relative">
                            <label for="profileImageInput" class="btn icon bg-body">
                                <i class="fa-solid fa-camera"></i>
                                <input type="file" hidden id="profileImageInput" accept="image/*">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="user-profile-detail">
                    <div class="icon-container" style="top:-5px;">
                        <button type="button" class="btn btn-secondary icon" id="btnEditProfile">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                    </div>
                    <h2 id="name" class="fw-bold">Lorem bin Ipsum</h2>
                    <p id="headline">Computer Science Student | Web Developer | UI/UX Enthusiast</p>
                    <a id="uni-name" href="#" class="h5 fw-bold">Universiti Teknikal Malaysia Melaka (UTeM)</a>
                    <p id="programme">Bachelor of Computer Science (Software Engineering)</p>

                    <article id="location">
                        <p>
                            <i class="fa-solid fa-location-dot"></i>
                            <span id="profileLocation">Durian Tunggal, Melaka, Malaysia</span>|
                            <i class="fa-solid fa-user-check verified"></i>
                            University Verified
                        </p>
                    </article>

                    <x-links />

                    <div class="btn-container">

                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">
                            Add Section
                        </button>

                    </div>

                </div>

                <nav class="horizontal-nav d-flex gap-4 px-3">
                    <button type="button" class="profile-tab active d-flex justify-content-center" data-target="main">
                        Main
                    </button>
                    <button type="button" class="profile-tab d-flex justify-content-center" data-target="result">
                        Result Sem
                    </button>
                </nav>

            </section>

            <div id="mainTabContent">

                <x-about />

                <x-education />

                <x-projects />

                <x-experience />

                <x-honorsAwards />

                <x-certifications />

            </div>

            <div id="resultTabContent" class="d-none">

                <x-semesterResults />

            </div>

        </div>

        <div class="user-card-right">

            <x-languages />

            <x-skills />

        </div>

    </div>

    <x-offcanvas />

    <x-modals.imagePreviewModal />

    <x-modals.editAboutModal />

    <x-modals.editProfileModal />

    <x-modals.addResultModal />
@endsection

@section('script')
    @if (session('status') == 200)
        <script>
            Swal.fire({
                title: "Success",
                text: @json(session('message')),
                icon: "success"
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            Swal.fire({
                title: "Upload Failed",
                text: @json($errors->first()),
                icon: "error"
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {

            $(".profile-tab").on("click", function() {
                $(".profile-tab").removeClass("active");
                $(this).addClass("active");

                const target = $(this).data("target");
                if (target === "main") {
                    $("#mainTabContent").removeClass("d-none");
                    $("#resultTabContent").addClass("d-none");

                } else if (target === "result") {
                    $("#mainTabContent").addClass("d-none");
                    $("#resultTabContent").removeClass("d-none");
                    if (!resultLoaded) loadSemesterResults()
                }
            });
        });
    </script>
@endsection
