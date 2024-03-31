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
								<a class="nav-link" href="#">User Management</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Logout</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
		<div class="p-5 d-flex flex-column align-items-center">
			<h1>User Details</h1>
			<div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
        <span class="fw-bold fs-4 mb-3">Personal Information</span>
				<div class="detail-group d-flex flex-column mb-3">
					<span class="fw-bold lh-sm">User ID</span>
					<span class="fs-5">9bd43657-90fa-40bf-be17-41e0b2a6a1d9</span>
				</div>
				<div class="detail-group d-flex flex-column mb-3">
					<span class="fw-bold lh-sm">Email</span>
					<span class="fs-5">j.tarigan@uqconnect.edu.au</span>
				</div>
				<div class="detail-group d-flex flex-column mb-3">
					<span class="fw-bold lh-sm">Name</span>
					<span class="fs-5">Johanes Christian Lewi Putrael</span>
				</div>
				<div class="detail-group d-flex flex-column mb-3">
					<span class="fw-bold lh-sm">Account Type</span>
					<span class="fs-5">business</span>
				</div>
        <div class="detail-group d-flex flex-column">
					<span class="fw-bold lh-sm">Status</span>
					<span class="fs-5">active</span>
				</div>
			</div>

      <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
        <span class="fw-bold fs-4 mb-3">Affiliated Business Information</span>
				<div class="detail-group d-flex flex-column mb-3">
					<span class="fw-bold lh-sm">Business Name</span>
					<span class="fs-5">Warteg Bahari</span>
				</div>
				<div class="detail-group d-flex flex-column mb-3">
					<span class="fw-bold lh-sm">Business Address</span>
					<span class="fs-5">090 O'hara Island, O'keefefort, Northern Territory 4828, Australia</span>
				</div>
				<div class="detail-group d-flex flex-column mb-3">
					<span class="fw-bold lh-sm">Dine-In Business Size</span>
					<span class="fs-5">40 Tables</span>
				</div>
				<div class="detail-group d-flex flex-column mb-3">
					<span class="fw-bold lh-sm">Business Subscription Status</span>
					<span class="fs-5">active</span>
				</div>
			</div>

      <div class="row card shadow p-3 mt-3 w-50">
				<div class="w-100 d-flex flex-row justify-content-end">
					<button type="button" class="btn btn-warning">Edit User Information</button>
				</div>
      </div>
		</div>
    
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
