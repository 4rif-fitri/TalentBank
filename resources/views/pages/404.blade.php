<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentBank</title>
    <link rel="shortcut icon" href="{{ URL::asset('assets/internship-assets/images/logoTalentBankWhite.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ URL::asset('assets/libs/bootstrap5/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/n/css/login.css') }}">
</head>

<body>
        <div class="login-wrapper">
            <!-- Main centered error card layout -->
            <div class="login-card text-center">

                <!-- Brand Identity -->
                <div class="login-brand text-decoration-none">
                    <i class="bi bi-asterisk"></i>
                    <span>TalentBank</span>
                </div>

                <!-- Giant 404 header with spinning asterisk Zero -->
                <div class="error-title-huge">
                    <span>4</span>
                    <span>0</span>
                    <span>4</span>
                </div>

                <h2 class="error-subtitle">Page Not Found</h2>
                <p class="error-desc">
                    The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>

                <div class="error-actions-group">
                    <a href="{{ route('landingPage') }}" class="btn-custom btn-custom-primary">
                        <i class="bi bi-house"></i> Back To Dashboard
                    </a>
                </div>

            </div>
        </div>

    <script src="{{ URL::asset('assets/libs/bootstrap5/bootstrap.min.js') }}"></script>

</body>

</html>
