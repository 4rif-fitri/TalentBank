@extends('layouts.internship-layouts')

@section('content')
    <div class="user-card">
        <div class="user-card-left">

            <x-userCardHeader />

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
