<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ URL::asset('assets/css/profile/login.css') }}">
</head>

<body>
	<div class="card-container">
		<div class="card-content p-4">
			<div class="d-flex justify-content-center">
				<img class="w-50" src="{{ URL::asset('assets/images/profile/newLogo.png') }}" alt="">
			</div>
			<h1 class="text-center">Welcome Back</h1>
			<p class="text-center">Sign in to continue to TalentBank</p>

			<form action="{{ route('login') }}" method="POST">
				@csrf

				<div class="mb-3">
					<label for="email" class="form-label">Email Address</label>
					<div class="input-group has-validation">
						<span class="input-group-text bg-white" id="basic-addon-email">
							<i class="fa-regular fa-envelope"></i>
						</span>

						<input type="email" name="email" id="email"
							class="form-control @error('email') is-invalid @enderror"
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
							<i class="fas fa-lock"></i>
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

				<div class="d-flex justify-content-between my-2">
					<div></div>
					<a href="" class="text-primary fw-bold text-decoration-none">Forgot password?</a>
				</div>

				<button type="submit" class="btn btn-primary w-100">Sign In</button>

			</form>

			<div class="d-flex mt-4 justify-content-center">
				<p class="mb-0 me-1">Don't have an account? - </p>
				<a href="{{ route('registerPage') }}" class="text-primary fw-bold text-decoration-none">Create account</a>
			</div>
		</div>
	</div>
</body>

</html>

