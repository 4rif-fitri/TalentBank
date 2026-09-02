@extends('layouts.internship-layouts')

@section('content')

<div class="user-card ">
    @if(array_intersect(session('roles') ?? [], ['Recruiter']))
    <div class="position-fixed z-3 p-2 rounded-2" style="right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5);">
        <button class="btn btn-primary">Add to Shortlist</button>
    </div>
    @endif

    <div class="user-card-left">

        <x-sections.userCardHeader />

        <div id="mainTabContent">
            <x-sections.about />
        </div>

        <div id="resultTabContent" class="d-none">
            <x-sections.education-result />
        </div>

        <div id="educationsTabContent" class="d-none">
            <x-sections.education />
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

<x-modals.result-modal />
<x-modals.semester-modal />
<x-modals.pdf-preview-modal />

@endsection

@section('script')
@vite('resources/js/student/index.js')
@endsection