@extends('layouts.internship-layouts')

@section('content')

<div class="user-card">

    <div class="user-card-left">

        <x-sections.userCardHeader />


    </div>


    <div class="user-card-right">


    </div>

</div>

<x-modals.active-educations-modal />


@endsection

@section('script')
@vite('resources/js/student/index.js')
@endsection
