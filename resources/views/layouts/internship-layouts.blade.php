<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>TalentBank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Parental Relationship Information Management" name="description" />
    <meta content="UTeM" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo/JobTalent-logo-cropped-cropped.svg') }}">
    <!-- Web Application Manifest -->
    <!-- <link rel="manifest" href="/manifest.json"> -->
    @include('layouts.head')
</head>
<body>
    @include('layouts.internship-sidebar')

    @include('layouts.internship-topbar')

    <main class="main-content">
        @yield('content')
    </main>

    @include('layouts.footer-script')

    @stack('scripts')
</body>
</html>
