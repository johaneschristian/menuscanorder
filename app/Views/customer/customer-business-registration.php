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
		<title>Business Registration</title>
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
								<a class="nav-link" href="#">Orders</a>
							</li>
              <li class="nav-item">
								<a class="nav-link active" href="#">Business</a>
							</li>
              <li class="nav-item">
								<a class="nav-link" href="#">Profile</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Logout</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
		<main>
      <div class="container">
        
        <section class="d-flex flex-column align-items-center p-3">
          <h1>Create Business</h1>
          <span class="w-mdc-50 w-75 text-center"><i class="bi bi-info-circle me-1"></i>To access the business features, you must first register your business and affiliate it to your account.</span>
          <form action="" method="post" class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
            <div class="" id="affiliated-business-form">
              <div class="mb-3">
                <label for="affiliated-business-name" class="form-label fw-bold lh-sm">Business Name</label>
                <input id="affiliated-business-name" name="business_name" type="text" class="form-control">
              </div>
              <div class="mb-3">
                <label for="affiliated-business-address" class="form-label fw-bold lh-sm">Business Address</label>
                <textarea id="affiliated-business-address" name="address" type="text" rows="1" class="form-control"></textarea>
              </div>
              <div class="mb-3">
                <label for="affiliated-business-table-quantity" class="form-label fw-bold lh-sm">Dine-In Business Size</label>
                <div class="input-group">
                  <input id="affiliated-business-table-quantity" name="num_of_tables" type="number" step="1" class="form-control">
                  <span class="input-group-text bg-soft-gray">tables</span>
                </div>
              </div>
            </div>
            <div class="d-flex flex-sm-row flex-column mt-sm-0 mt-1 justify-content-end gap-1">
              <button class="btn bg-brown text-light">Register Business</button>
              <button class="btn btn-outline-primary">Cancel</button>
            </div>
          </form>
        </section>
      </div>
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
