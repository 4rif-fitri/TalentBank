<!-- Vendor CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/libs/jquery-ui.css') }}">
<!-- Bootstrap via Vite -->
@vite([
    'resources/scss/app.scss',
    'resources/js/app.js'
])
<!-- TalentBank Custom CSS -->
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/layout/newLayout.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/style.css') }}">

@yield('css')
