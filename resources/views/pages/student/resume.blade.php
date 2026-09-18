@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">

<div class="content p-4 page-container">

    <section class="page-header">
        <div class="page-heading">
            <h1>My Resume</h1>
        </div>
    </section>

    <div class="d-flex justify-content-between">
        <nav class="tabs" aria-label="Job offer status">
            <x-atom.tab-status status="All Resume" class="active" />
            <x-atom.tab-status status="Latest" class="" />
            <x-atom.tab-status status="Oldest" class="" />
        </nav>

        <div class="nav-item">
            <button class="offer-tab active">
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
@endsection

@section('script')
<script>

    function handleChangeStatus(){
        $(".offer-tab").removeClass("active")
        $(this).addClass("active");
        let status = $(this).data("status")
    }

    $(document).on("click", ".offer-tab", handleChangeStatus)

</script>
@endsection
