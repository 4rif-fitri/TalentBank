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
    <div class="login-wrapper ">

        <!-- Main Login Card -->
        <div class="login-card relative">
            <a href="{{ route('landingPage') }}" class="absolute top-0 btn btn-close-white">Back</a>

            <div class="login-brand text-decoration-none">
                <span>Login</span>
            </div>

            <p class="login-subtitle">
                Sign in to continue to TalentBank
            </p>

            <form action="{{ route('login') }}" method="POST" id="loginForm">

                @csrf

                <div class="login-form-group">

                    <label for="email" class="login-form-label">Email Address</label>

                    <div class="login-input-group">
                        <i class="fa-regular fa-envelope input-icon"></i>

                        <input type="email" name="email" id="email" class="login-input @error('email') is-invalid @enderror"
                            placeholder="name@company.com" value="{{ old('email') }}" autocomplete="email" required>
                    </div>

                    @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <!-- Password -->
                <div class="login-form-group">

                    <label for="password" class="login-form-label">Password</label>

                    <div class="login-input-group">
                        <i class="fa-solid fa-lock input-icon"></i>

                        <input type="password" name="password" id="password"
                            class="login-input login-input-password @error('password') is-invalid @enderror"
                            placeholder="Enter your password" autocomplete="current-password" required>

                        <button type="button" class="password-toggle-btn" id="toggle-password" aria-label="Show password"
                            aria-pressed="false">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <button type="submit" class="btn-login" id="btn-submit">
                    <span>Login</span>
                </button>

            </form>

            <p class="login-footer-text mt-4">
                Don't have an account?
                <a href="{{ route('registerPage') }}" id="link-register">
                    Create account
                </a>
            </p>

        </div>
    </div>

    <script src="{{ URL::asset('assets/libs/bootstrap5/bootstrap.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
                const passwordInput = document.getElementById('password');
                const toggleButton = document.getElementById('toggle-password');

                toggleButton.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';

                    passwordInput.type = isPassword ? 'text' : 'password';

                    toggleButton.innerHTML = isPassword
                        ? '<i class="fa-regular fa-eye-slash"></i>'
                        : '<i class="fa-regular fa-eye"></i>';

                    toggleButton.setAttribute('aria-label',
                        isPassword ? 'Hide password' : 'Show password'
                    );

                    toggleButton.setAttribute('aria-pressed', String(isPassword));
                });
            });
    </script>
</body>

</html>
