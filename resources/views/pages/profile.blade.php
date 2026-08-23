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


    </div>

</div>

<x-modals.active-educations-modal />

<x-modals.editProfileModal />

<x-modals.editAboutModal />

@endsection

@section('script')
@vite('resources/js/student/index.js')
@endsection
