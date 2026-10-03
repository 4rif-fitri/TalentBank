@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">

<div class="content p-4 page-container">

    <section class="page-header">
        <div class="page-heading">
            <h1>My Resume</h1>
        </div>
    </section>

    <section class="resume-list d-flex gap-2 flex-wrap justify-content-center">

        <div class="add-resume card d-flex justify-content-center align-content-center" role="button" style="min-width: 20rem !important; height: 27rem !important;">
            <h1 class="text-center fw-bolder" style="font-size: 5rem;">+</h1>
        </div>

    </section>
</div>

<div class="filter-overlay"></div>

<x-modals.template-resume />

@endsection

@section('script')
<script>

    let id = "{{ session('user_profile_id') }}";

    function template(data){
        return`<div data-id=${data.id} class="resume-item card" role="button" style="width: 20rem;">
                    <div class="resume-image bg-light w-100" style="height: 20rem;"></div>
                    <div class="mt-1">
                        <p>Template:</p>
                        <h4 class="fw-semibold">${data.resume_template.template_file_name}</h4>
                    </div>
                </div>`
    }

    function getResumesByUserProfileId() {
        let url = "{{ route('resumes.getResumesByUserProfileId', ['id' => '__ID__']) }}";
        url = url.replace('__ID__', id);

        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                response.data.forEach(function (resume) {
                    $(".resume-list").append(template(resume));
                });
            }
        });
    }
    getResumesByUserProfileId();

    $(".add-resume").on("click", function(){
        window.location.href = "{{ route('student.edit.resume') }}"
    })

    $(document).on("click", ".resume-item", function () {
        let id = $(this).data('id')
        let url = "{{ route('student.edit.resume', ['id' => '__ID__']) }}";
        url = url.replace('__ID__', id);
        window.location.href = url
    })

</script>
@endsection
