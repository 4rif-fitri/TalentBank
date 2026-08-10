<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/profile/login.css') }}">
</head>

<body>
    <div class="card-container">
        <div class="card-content p-4">
            <div class="d-flex justify-content-center">
                <img class="w-50" src="./newLogo.png" alt="">
            </div>
            <h1 class="text-center">Create Your Account</h1>
            <p class="text-center">Join TalentBank and build your professional future</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-white" id="basic-addon-name">
                            <i class="fa-solid fa-user"></i>
                        </span>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                aria-label="Full Name" aria-describedby="basic-addon-name">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-white" id="basic-addon-email">
                                <i class="fa-regular fa-envelope"></i>
                            </span>

                            <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    aria-label="Email" aria-describedby="basic-addon-email">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-white" id="basic-addon-password">
                                    <i class="fa-solid fa-lock"></i>
                                </span>

                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" aria-label="Password"
                                        aria-describedby="basic-addon-password">

                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Create Account</button>

                        </form>

                        <div class="d-flex mt-4 justify-content-center">
                            <p class="mb-0 me-1">Already have an account? - </p>
                            <a href="{{ route('loginPage') }}" class="text-primary fw-bold text-decoration-none">Sign in</a>
                        </div>
                    </div>
                </div>
            </body>

            </html>
