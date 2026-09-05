<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>TalentBank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Parental Relationship Information Management" name="description" />
    <meta content="UTeM" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ URL::asset('assets/internship-assets/images/logoTalentBankWhite.png') }}">
    @include('layouts.header')
</head>

<body>
    @include('layouts.sidebar')

    @include('layouts.topbar')

    <main class="main-content">
        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')
    <script>
        function swalfire(title, text, icon){
            Swal.fire({ title, text, icon });
        }
    </script>
</body>

</html>
