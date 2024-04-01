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
								<a class="nav-link" href="#">Login</a>
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
      <section class="container p-5">
        <div class="row d-flex flex-row justify-content-center">
          <div class="col-lg-4 d-flex flex-column justify-content-center mb-5">
            <span class="display-2">Sit, Scan, Order.</span>
            <p class="fs-4 fw-light">Enhance your overall dining experience with straightforward ordering upon arrival, equipped with the ability to track the status of your order.</p>
            <div class="">
              <button class="btn bg-brown text-light clickable">Get Started</button>
            </div>
          </div>
          <div class="col-lg-4">
            <img src="<?= base_url('images/online-ordering-business.jpg') ?>" alt="" class="shadow-lg rounded w-100">
          </div>
        </div>
      </section>
      <section class="container p-3 d-flex flex-column align-items-center gap-3 w-100 mb-5">
        <span class="fs-1 fw-bold">Why join us?</span>
        <div class="container d-flex flex-md-row flex-column justify-content-center gap-3 w-100">
          <div class="card shadow-lg bg-brown text-light w-mdc-25">
            <div class="card-body">
              <div class="card-title h3 mb-3">Digital Menu</div>
              <p class="fw-light">Access the wide range of menu from your pocket, allowing you to reorder at any time. This also improves business flexibility in updating their menu, removing the overhead cost of reprinting menu books.</p>
            </div>            
          </div>
          <div class="card shadow-lg bg-brown text-light w-mdc-25">
            <div class="card-body">
              <div class="card-title h3 mb-3">Seamless Ordering</div>
              <p class="fw-light">Removes the need to call staff when ordering, which eliminates the need to wait for a waiter during peak hours and frees up more staff to focus on food preparation and delivery. </p>
            </div>            
          </div>
          <div class="card shadow-lg bg-brown text-light w-mdc-25">
            <div class="card-body">
              <div class="card-title h3 mb-3">Order Tracking</div>
              <p class="fw-light">Check the status of your food at any time, increasing transparency in the dining experience. This allows both customers and businesses to stay on top of their orders.</p>
            </div>            
          </div>
        </div>
        
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
