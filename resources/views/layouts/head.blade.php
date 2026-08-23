<!-- Vendor CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/libs/jquery-ui.css') }}">

<script>
    window.appConfig = {
        userId: @json(session('user_profile_id')),
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

@yield('css')
