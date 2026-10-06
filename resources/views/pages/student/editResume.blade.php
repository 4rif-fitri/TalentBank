@extends('layouts.internship-layouts')

@section('css')
@endsection

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/base.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/modern.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/resume/professional.css') }}">

<div class="content p-4 page-container">

    <!-- <section class="page-header">
        <div class="page-heading">
            <h1>Edit Resume</h1>
        </div>
    </section> -->

    <input type="hidden" id="template_id">

    <div class="row w-100">

        <div class="col-12 col-lg-6 border border-1 bg-body">
            <div id="resumeEditor">
                <button id="zoomOut">-</button>
                <button id="zoomIn">+</button>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script type="module">

    let dataResume, educationByUser, profileData

    function getEducationByUserProfileId(id) {

        let url = "{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}";

        url = url.replace('__ID__', id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }
    function getProfileDataByProfileId(id) {

        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";

        url = url.replace('__ID__', id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }
    function getResumeById(id) {

        let url = "{{ route('resumes.getResumeById', ['id' => '__ID__']) }}";

        url = url.replace('__ID__', id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }

    function loadResumeToApp(data) {
        window.dispatchEvent(new CustomEvent('resumeDataLoaded', {detail: data}));
    }

    $(document).ready(async function () {
        const templateId = new URLSearchParams(window.location.search).get('template_id');
        const profileId = "{{ session('user_profile_id') }}";

        if (templateId) {
            $('#template_id').val(templateId);

            try {
                const response = await getResumeById(templateId);
                dataResume = response.data;

                // loadResumeToApp(dataResume);

            } catch (error) {
                console.error("Failed to load resume:",error);
            }

        }

        else {

            try {
                const [profileResponse,educationResponse] = await Promise.all([
                    getProfileDataByProfileId(profileId),
                    getEducationByUserProfileId(profileId)
                ]);

                profileData = profileResponse.data;
                educationByUser = educationResponse.data;

                dataResume = {
                    profile: profileData,
                    education: educationByUser
                };

                loadResumeToApp(dataResume);

            } catch (error) {
                console.error("Failed to load resume data:",error);
            }
        }
    });
</script>

@vite('resources/js/features/resume/app.js')

@endsection
