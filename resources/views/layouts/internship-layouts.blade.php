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
    <div class="sidebar-overlay"></div>
    @include('layouts.internship-topbar')

    <main class="main-content">
        @yield('content')
    </main>

    <script src="{{asset('assets/internship-assets/libs/jquery-4.0.0.min.js')}}"></script>
    <script src="{{asset('assets/internship-assets/libs/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/internship-assets/libs/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/internship-assets/libs/sweetalert2@11.js')}}"></script>
    <script src="{{asset('assets/internship-assets/layout/newLayout.js')}}"></script>

    <script>
        if (window.innerWidth > 768 && localStorage.getItem("sidebarCollapsed") === "true") {
            document.documentElement.classList.add("sidebar-collapsed-init");
        }
    </script>
    @yield('script')

</body>

</html>
