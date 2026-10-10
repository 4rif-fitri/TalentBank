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

        <!-- Main Register Card -->
        <div class="login-card relative">
            <a href="{{ route('landingPage') }}" class="absolute top-0 btn btn-close-white">Back</a>

            <h3 class="login-heading text-center mb-2">Create Account</h3>

            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST" id="registerForm">

                @csrf

                <!-- Full Name -->
                <div class="login-form-group">

                    <label for="name" class="login-form-label">
                        Full Name
                    </label>

                    <div class="login-input-group">
                        <i class="fa-solid fa-user input-icon"></i>

                        <input type="text" name="name" id="name" class="login-input @error('name') is-invalid @enderror"
                            placeholder="Enter your full name" value="{{ old('name') }}" autocomplete="name" required>
                    </div>

                    @error('name')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <!-- Email Address -->
                <div class="login-form-group">

                    <label for="email" class="login-form-label">
                        Email Address
                    </label>

                    <div class="login-input-group">
                        <i class="fa-regular fa-envelope input-icon"></i>

                        <input type="email" name="email" id="email" class="login-input @error('email') is-invalid @enderror"
                            placeholder="name@company.com" value="{{ old('email') }}" autocomplete="email" required>
                    </div>

                    @error('email')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <!-- Password -->
                <div class="login-form-group">

                    <label for="password" class="login-form-label">
                        Password
                    </label>

                    <div class="login-input-group">
                        <i class="fa-solid fa-lock input-icon"></i>

                        <input type="password" name="password" id="password"
                            class="login-input login-input-password @error('password') is-invalid @enderror"
                            placeholder="Create a password" autocomplete="new-password" required>

                        <button type="button" class="password-toggle-btn" id="toggle-password" aria-label="Show password"
                            aria-pressed="false">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    @error('password')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <!-- Confirm Password -->
                <div class="login-form-group">

                    <label for="password_confirmation" class="login-form-label">
                        Confirm Password
                    </label>

                    <div class="login-input-group">
                        <i class="fa-solid fa-shield-halved input-icon"></i>

                        <input type="password" name="password_confirmation" id="password_confirmation" class="login-input"
                            placeholder="Confirm your password" autocomplete="new-password" required>

                        <button type="button" class="password-toggle-btn" id="toggle-confirm-password"
                            aria-label="Show confirm password" aria-pressed="false">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                </div>

                <button type="submit" class="btn-login" id="btn-submit">
                    <span>Create Account</span>
                </button>

            </form>

            <!-- Login Link -->
            <p class="login-footer-text mt-4">
                Already have an account?
                <a href="{{ route('loginPage') }}">
                    Login
                </a>
            </p>

        </div>
    </div>
    <script src="{{ URL::asset('assets/libs/bootstrap5/bootstrap.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function setupPasswordToggle(inputId, buttonId) {
                const passwordInput = document.getElementById(inputId);
                const toggleButton = document.getElementById(buttonId);

                if (!passwordInput || !toggleButton) return;

                toggleButton.addEventListener('click', function () {
                    const showPassword = passwordInput.type === 'password';

                    passwordInput.type = showPassword ? 'text' : 'password';

                    toggleButton.innerHTML = showPassword
                        ? '<i class="fa-regular fa-eye-slash"></i>'
                        : '<i class="fa-regular fa-eye"></i>';

                    toggleButton.setAttribute(
                        'aria-label',
                        showPassword ? 'Hide password' : 'Show password'
                    );

                    toggleButton.setAttribute(
                        'aria-pressed',
                        String(showPassword)
                    );
                });
            }

            setupPasswordToggle('password', 'toggle-password');

            setupPasswordToggle(
                'password_confirmation',
                'toggle-confirm-password'
            );

        });
    </script>
</body>

</html>
