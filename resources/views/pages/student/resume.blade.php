@extends('layouts.internship-layouts')
@php
    $list = [
        ['status' => 'All Resume', 'class' => 'active',],
        ['status' => 'Latest', 'class' => '',],
        ['status' => 'Oldest', 'class' => '',],
    ];
@endphp
@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">

<div class="content p-4 page-container">

    <x-atom.page-header title="My Resume" />

    <div class="d-flex justify-content-between">

        <x-molecule.nav-tabs :list="$list" />

        <div class="nav-item">
            <button id="btnCreateResume" class="btn-tb btn-tb-primary">
                Create Resume
            </button>
        </div>
    </div>

<section class="item-list">
    <div class="list-item-row row g-3">

        <div class="col-12 col-md-10">
            <div class="row g-2">
                <div class="col-4">
                    Lorem ipsum dolor sit amet.
                </div>

                <div class="col-8">
                    Lorem ipsum dolor sit amet.
                </div>
            </div>
        </div>

        <div class="col-12 col-md-2">
            <div class="d-flex flex-column gap-2">
                <button class="btn-tb btn-tb-primary">
                    Edit Resume
                </button>

                <button class="btn-tb btn-tb-outline">
                    View Resume
                </button>
            </div>
        </div>

    </div>
</section>

</div>

<div class="filter-overlay"></div>

<x-modals.template-resume />

@endsection

@section('script')
<script>
    let listTemplate = []

    function getTemplateResume(){
        $.ajax({
            url: "{{ route('resumes.getAllResumeTemplates') }}",
            type: "GET",
            success: response => {
                listTemplate = response.data
                console.log(listTemplate);
            },
            error: xhr =>{
                console.log(xhr);
            }
        });
    }

    function handleChangeStatus(){
        $(".offer-tab").removeClass("active")
        $(this).addClass("active");
        let status = $(this).data("status")
    }

    $(document).on("click", ".offer-tab", handleChangeStatus)

    $(document).on("click", "#btnCreateResume", function(){
        templateResume.open(listTemplate)
    })

    $(document).ready(function(){
        getTemplateResume()
    })

</script>
@endsection
