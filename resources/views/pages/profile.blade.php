@extends('layouts.internship-layouts')

@section('content')

<div class="user-card">

    <div class="user-card-left">

        <x-sections.userCardHeader />

        <div id="mainTabContent">
            <x-sections.about />
        </div>

    </div>


    <div class="user-card-right">
        <x-sections.contact-information />
        <x-sections.languages />
    </div>

</div>

<x-modals.active-educations-modal />
<x-modals.profile-modal />
<x-modals.about-modal />
<x-modals.contact-information-modal />
<x-modals.social-media-link-modal />
<x-modals.languages-modal />

@endsection

@section('script')
    @vite('resources/js/student/index.js')
@endsection
