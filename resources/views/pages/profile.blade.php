@extends('layouts.internship-layouts')

@section('content')

<div class="user-card">

    <div class="user-card-left">

        <x-sections.userCardHeader />

        <div id="mainTabContent">
            <x-sections.about />
        </div>

        <div id="resultTabContent" class="d-none">
            <x-sections.semesterResults />
        </div>

    </div>

    <div class="user-card-right">
        <x-sections.contact-information />
        <x-sections.languages />
        <x-sections.skills />
    </div>
</div>

<x-modals.active-educations-modal />
<x-modals.profile-modal />
<x-modals.about-modal />
<x-modals.contact-information-modal />
<x-modals.social-media-link-modal />
<x-modals.languages-modal />
<x-modals.skill-modal />

@endsection

@section('script')
@vite('resources/js/student/index.js')
<script>
    $(".profile-tab").on("click", function () {
        $(".profile-tab").removeClass("active");
        $(this).addClass("active");
        let target = $(this).data("target");
        // console.log(target);

        if (target === "main") {
            $("#mainTabContent").removeClass("d-none");
            $("#resultTabContent").addClass("d-none");
            $("#educationsTabContent").addClass("d-none");
        }

        if (target === "result") {
            $("#mainTabContent").addClass("d-none");
            $("#resultTabContent").removeClass("d-none");
            $("#educationsTabContent").addClass("d-none");
        }

        if (target === "education") {
            $("#mainTabContent").addClass("d-none");
            $("#resultTabContent").addClass("d-none");
            $("#educationsTabContent").removeClass("d-none");
        }
    });
</script>
@endsection
