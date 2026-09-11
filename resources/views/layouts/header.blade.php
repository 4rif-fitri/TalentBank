<!-- Vendor CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/libs/select2.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/libs/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/libs/datatables.min.css') }}">

<script>
    window.appConfig = {
        role: @json(session('roles')[0]),
        userId: @json(session('user_profile_id')),
        baseURL: "{{ url('/') }}",
        proficiencies: @json(\App\Constants\AppConstants:: PROFICIENCY_LEVELS),
        semesterResultsFileUrl: "{{ asset('storage/' . env('SEMESTER_RESULTS_FILE_URL')) }}",
        coverImageUrl: "{{ asset('storage/' . env('COVER_IMAGE_URL')) }}",
        profileImageUrl: "{{ asset('storage/' .env('PROFILE_IMAGE_URL')) }}",
        educationFileUrl: "{{ asset('storage/' .env('EDUCATION_FILE_URL')) }}",
        organizationLogoUrl: "{{ asset('storage/' .env('ORGANIZATION_LOGO_URL')) }}",
    };
</script>

@vite([
    'resources/scss/app.scss',
    'resources/js/app.js',
])

<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/layout/newLayout.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/style.css') }}">

@yield('css')
