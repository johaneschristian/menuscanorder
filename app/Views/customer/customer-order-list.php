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
		<title>Order List</title>
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
								<a class="nav-link active" href="#">Orders</a>
							</li>
              <li class="nav-item">
								<a class="nav-link" href="#">Business</a>
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
			<div class="p-5">
        <form class="card shadow mb-3 ps-3 pe-3 pb-3 row d-flex flex-row justify-content-between align-items-center">
          <div class="input-group mt-3 me-3 w-mdc-50 w-100">
            <input name="business_name" type="text" class="form-control bg-soft-gray" placeholder="Search Business Name" value="<?= esc($search) ?>">
            <button class="btn bg-brown text-white" type="submit" id="search-button">Search</button>
          </div>
          <div class="input-group mt-3 w-mdc-25 w-100">
            <select name="status_id" class="form-select bg-soft-gray" onchange="this.form.submit()">
              <option value="" selected>All Orders</option>
              <?php foreach($statuses as $status): ?>
                <option value="<?= esc($status->id) ?>" <?= $status->id === $selected_status_id ? "selected" : "" ?>><?= esc($status->status) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </form>
        <div class="card d-md-block d-none w-100 p-3 shadow bg-brown text-white">
          <div class="row w-100">
            <div class="col-4 fw-bold">
              Order ID
            </div>
            <div class="col-2 fw-bold">
              Business Name
            </div>
            <div class="col-2 fw-bold">
              Status
            </div>
            <div class="col-2 fw-bold">
              Duration
            </div>
            <div class="col-2 fw-bold">
              Amount
            </div>
          </div>
        </div>
        <?php foreach ($orders as $order): ?>
          <div class="card clickable w-100 p-3 mt-3 shadow-sm">
            <div class="row fw-bold d-flex flex-md-row flex-column align-items-md-center align-items-start">
              <div class="col-md-4">
                <div class="row">
                  <span><?= esc($order->order_id) ?></span>
                </div>
                <div class="row">
                  <div class="sub-text text-muted d-md-block d-none">View Order</div>
                  <div class="sub-text text-muted d-md-none">Order ID</div>
                </div>
              </div>
              <div class="col-md-2  mt-md-0 mt-2 d-flex flex-md-column flex-row-reverse justify-content-end">
                <div class="row">
                  <span class=" d-block"><?= esc($order->business_name) ?></span>
                  <div class="sub-text text-muted d-md-none">Business Name</div>
                </div>
              </div>
              <div class="col-md-2  p-md-0 mt-md-0 mt-2">
                <span class="badge rounded-pill bg-warning text-dark"><?= esc($order->status_name) ?></span>
              </div>
              <div class="col-md-2  p-md-0 mt-md-0 mt-2">
                <div class="row">
                  <span class="d-md-block d-none"><?= esc($order->duration) ?></span>
                  <span class=" d-md-none d-block"><?= esc($order->duration) ?>, <?= esc($order->start_date) ?></span>
                  <div class="sub-text text-muted d-md-none">Dine-in Duration</div>
                </div>
                <div class="row">
                  <span class="sub-text text-muted d-md-block d-none"><?= esc($order->start_date) ?></span>
                </div>
              </div>
              <div class="col-md-2  p-md-0 mt-md-0 mt-2">
                <span>AUD<?= esc(number_format($order->total_price, 2, '.')) ?></span>
                <div class="sub-text text-muted d-md-none">Total Cost</div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <nav class="mt-5">
          <ul class="pagination justify-content-center">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item active" aria-current="page">
              <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>
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
