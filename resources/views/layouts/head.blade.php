<!-- Vendor CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/libs/jquery-ui.css') }}">

<script>
    window.appConfig = {
        userId: @json(session('user_profile_id')),
        baseURL: "{{ url('/') }}",
        proficiencies: @json(\App\Constants\AppConstants:: PROFICIENCY_LEVELS),
        semesterResultsFileUrl: "{{ asset(env('SEMESTER_RESULTS_FILE_URL')) }}",
        coverImageUrl: "{{ asset(env('COVER_IMAGE_URL')) }}",
        profileImageUrl: "{{ asset(env('PROFILE_IMAGE_URL')) }}",
        educationFileUrl: "{{ asset(env('EDUCATION_FILE_URL')) }}",
        assets: {
            coverImage: "{{ asset('cover-image-url') }}",
            profileImage: "{{ asset('profile-image-url') }}"
        },
        routes: {
            profile: {
                show: "{{ route('profile.getProfileDataByProfileId', ['id' => session('user_profile_id')]) }}",
                update: "{{ route('profile.update') }}",
                updateAbout: "{{ route('update.updateAboutField') }}",
                uploadProfileImage: "{{ route('update.uploadProfileImage') }}",
                uploadCoverImage: "{{ route('update.uploadCoverImage') }}"
            },
            socialMedia: {
                getAllSocialMedia: "{{ route('social-media.getAllSocialMedia') }}",
                store: "{{ route('social-media.store') }}",
                update: "{{ route('social-media.update', ['id' => '__ID__']) }}",
                delete: "{{ route('social-media.delete', ['id' => '__ID__']) }}",
            },
            education: {
                getEducationByUserProfileId: "{{ route('education.getEducationByUserProfileId',['id' => '__ID__']) }}",
                getEducationById: "{{ route('education.getEducationById', ['id' => '__ID__']) }}",
                educationStore: "{{ route('education.store') }}",
                educationUpdate: "{{ route('education.update', ['id' => '__ID__']) }}",
                educationDelete: "{{ route('education.delete', ['id' => '__ID__']) }}",
                getAllFieldOfStudies: "{{ route('education.getAllFieldOfStudies') }}",
                getAllQualifications: "{{ route('education.getAllQualifications') }}",
            },
            languages: {
                getAllLanguages: "{{ route('languages.getAllLanguages') }}",
                languagesStore: "{{ route('languages.store') }}",
                languagesUpdate: "{{ route('languages.update',['id' => '__ID__']) }}",
                languagesDelete: "{{ route('languages.delete',['id' => '__ID__']) }}",
            },
            skills: {
                getAllSkills: "{{ route('skills.getAllSkills') }}",
                skillsStore: "{{ route('skills.store') }}",
                skillsUpdate: "{{ route('skills.update',['id' => '__ID__']) }}",
                skillsDelete: "{{ route('skills.delete',['id' => '__ID__']) }}",
            },
            semesters: {
                uploadResults: "{{ route('semester.uploadResults',['id' => '__ID__']) }}",
                semesterStore: "{{ route('semester.store') }}",
                semesterUpdate: "{{ route('semester.update',['id' => '__ID__']) }}",

            },
            programmes: {
                getProgrammesByUserProfileId: "{{ route('programme.getProgrammesByUserProfileId',['id' => '__ID__']) }}",
            },
            organizations: {
                getAllOrganizations: "{{ route('organization.getAllOrganizations') }}",
            }
        },

    };
</script>

<!-- Bootstrap via Vite -->
@vite([
'resources/scss/app.scss',
'resources/js/app.js',
'resources/js/api.js',
])
<!-- TalentBank Custom CSS -->
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/layout/newLayout.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/style.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@yield('css')