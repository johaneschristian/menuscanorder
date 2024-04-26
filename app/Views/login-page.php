<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
			crossorigin="anonymous"
		/>
		<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"
		/>
		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
			crossorigin="anonymous"
			defer
		></script>
    <script src="<?= base_url('js/helper.js') ?>" defer></script>
		<link rel="stylesheet" href="<?= base_url('css/style.css') ?>" />
		<title>User Details</title>
	</head>
	<body class="d-flex flex-column min-vh-100">
		<header>
			<nav class="navbar navbar-expand-lg navbar-dark bg-brown">
				<div class="container">
					<a class="navbar-brand" href="#">MenuScanOrder</a>
					<button
						class="navbar-toggler"
						type="button"
						data-bs-toggle="collapse"
						data-bs-target="#navbarNav"
						aria-controls="navbarNav"
						aria-expanded="false"
						aria-label="Toggle navigation"
					>
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNav">
						<ul class="navbar-nav ms-auto">
							<li class="nav-item">
								<a class="nav-link active" href="#">Login</a>
							</li>
              <li class="nav-item">
								<a class="nav-link" href="#">Register</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
		<main>
			<section class="d-flex flex-row justify-content-center p-5">
				<form action="" method="post" class="card shadow-lg rounded p-3 w-mdc-25 w-75">
					<div class="text-center">
						<span class="fw-bold fs-4 mb-3">Login</span>
					</div>
					<div class="mb-3">
						<label for="" class="form-label fw-bold lh-sm">Email</label>
						<input name="email" type="email" class="form-control bg-soft-gray">
					</div>
					<div class="mb-3">
						<label for="" class="form-label fw-bold lh-sm">Password</label>
						<input name="password" type="password" class="form-control bg-soft-gray">
					</div>
					<div class="d-flex flex-row justify-content-between align-items-end mt-3">
						<a href="http://">Does not have an account?</a>
						<button class="btn bg-brown text-light">Login</button>
					</div>
				</form>
			</section>
		</main>
		<footer class="bg-brown text-light py-4 mt-auto">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<p>&copy; 2024 MenuScanOrder. All rights reserved.</p>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>