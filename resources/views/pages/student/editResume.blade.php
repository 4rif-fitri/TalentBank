@extends('layouts.internship-layouts')

@section('css')
@endsection

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/base.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/modern.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/professional.css') }}">

<div class="content p-4 page-container">

    <section class="page-header">
        <div class="page-heading">
            <h1>Edit Resume</h1>
        </div>
    </section>

    <input type="hidden" id="template_id">

    <div class="row w-100">

        <div class="col-12 col-lg-6 border border-1 bg-body">

            <!-- Resume Editor -->
            <div id="resumeEditor">

                <input type="text" id="name" value="Arif Fitri">

                <input type="text" id="jobTitle" value="Full Stack Web Developer">

                <input type="text" id="email" value="arif@example.com">

                <input type="text" id="phone" value="+60 12-345 6789">

                <input type="text" id="location" value="Melaka, Malaysia">

                <textarea id="summary"></textarea>

                <select id="templateSelect">
                    <option value="modern">
                        Modern
                    </option>

                    <option value="professional">
                        Professional
                    </option>
                </select>

                <button id="downloadResume">
                    Download PDF
                </button>

            </div>

        </div>


        <div class="col-12 col-lg-6 border border-1 bg-body workspace">

            <div id="resumePages"></div>

        </div>

    </div>

</div>

@endsection

@section('script')

<script type="module">

    const templateId =
        new URLSearchParams(
            window.location.search
        ).get('template_id');

    if (templateId) {
        document
            .querySelector('#template_id')
            .value = templateId;
    }

</script>

@vite('resources/js/features/resume/app.js')

@endsection
