@extends('layouts.internship-layouts')

@section('css')

@endsection

@section('content')
<div class="content p-4 page-container">
    <!-- <x-atom.page-header title="Edit Resume" /> -->

    <section class="page-header">
        <div class="page-heading">
            <h1>Edit Resume</h1>
        </div>
    </section>

    <input type="hidden" id="template_id">

    <div class="row w-100">
        <div class="col-12 col-lg-6 border border-1 bg-body">
            <h1>wd</h1>
        </div>

        <div class="col-12 col-lg-6 border border-1 bg-body">
            <h1>wd</h1>
        </div>
    </div>

</div>
@endsection

@section('script')
    <script type="module">
        $(document).ready(function(){
            let templateId = new URLSearchParams(window.location.search).get('template_id')
            if(templateId){
                $('#template_id').val(templateId)
            }
            console.log(templateId)
        })
    </script>
@endsection
