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
		<title>Order Details</title>
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
      <div class="container mt-4 w-mdc-75 w-100">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Details</li>
          </ol>
        </nav>
        <div class="card shadow p-md-5 p-3">
          <span class="h2 fw-bold">Order Details</span>
          <table class="table w-mdc-50 w-100 h6 align-middle">
            <tr>
              <td>Order ID</td>
              <td>: <?= esc($order->order_id) ?></td>
            </tr>
            <tr>
              <td>Order Time</td>
              <td>: <?= esc($order->formatted_creation_time) ?></td>
            </tr>
            <tr>
              <td>Business Name</td>
              <td>: <?= esc($order->business_name) ?></td>
            </tr>
            <tr>
              <td>Order Table</td>
              <td>: <?= esc($order->table_number) ?></td>
            </tr>
          </table>
          <p class="h4 fw-bold mt-3 mb-3">Order Summary</p>
          <table class="table table-hover">
            <thead class="align-middle">
              <tr>
                <th>Number</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Item Price (AUD)</th>
                <th>Subtotal (AUD)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($order->order_summary as $index => $order_item_summary): ?>
                <tr>
                  <td><?= esc($index+1) ?></td>
                  <td class="d-flex flex-column">
                    <span><?= esc($order_item_summary['menu_item_name']) ?></span>
                    <?php foreach($order_item_summary['notes'] as $notes): ?>
                      <span class="small text-secondary">- <?= esc($notes) ?></span>
                    <?php endforeach; ?>
                  </td>
                  <td><?= esc($order_item_summary['num_of_items']) ?></td>
                  <td><?= esc($order_item_summary['price_when_bought']) ?></td>
                  <td><?= esc($order_item_summary['subtotal']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="d-flex justify-content-end align-items-end">
            <h6 class="me-1 h6">Order Total:</h6><h4 class="fw-bold"><?= esc(number_format($order->total_price, 2, '.')) ?> (AUD)</h4>
          </div>
          <div class="d-flex justify-content-end align-items-end mt-3">
            <button type="button" class="btn btn-success me-2" onclick='window.location.href=`<?= base_url("/customer/orders/menu/{$order->receiving_business_id}/{$order->table_number}") ?>`'>Go to Business Menu</button>
            <button type="button" class="btn btn-outline-danger" onclick='window.location.href=`<?= base_url("/customer/orders") ?>`'>Go Back to Orders Page</button>
          </div>
        </div>
        <div class="card shadow p-md-5 p-3 mt-3 mb-3">
          <span class="h4 fw-bold">Order Item Details</span>
          <div class="card d-md-block d-none w-100 p-3 mt-2 shadow-sm bg-brown text-white">
            <div class="row w-100">
              <div class="col-1 fw-bold table-header-card">
                No
              </div>
              <div class="col-4 fw-bold table-header-card">
                Item
              </div>
              <div class="col-2 fw-bold table-header-card">
                Quantity
              </div>
              <div class="col-3 fw-bold table-header-card">
                Time Ordered
              </div>
              <div class="col-2 fw-bold table-header-card">
                Status
              </div>
            </div>
          </div>
          <?php foreach ($order->order_items as $index => $order_item): ?>
            <div class="card w-100 p-3 mt-2 shadow-sm">
              <div class="row d-flex flex-md-row flex-column align-items-md-center align-items-start">
                <div class="col-md-1 d-flex flex-md-column flex-column-reverse">
                  <div class="row">
                    <div class="fw-bold"><?= esc($index+1) ?></div>
                  </div>
                  <div class="row">
                    <div class="sub-text text-muted d-md-none">No.</div>
                  </div>
                </div>
                <div class="col-md-4 mt-md-0 mt-2 d-flex flex-column justify-content-end">
                  <div class="row">
                    <span class="fw-bold d-md-block d-none"><?= esc($order_item->menu_item_name) ?></span>
                  </div>
                  <div class="row d-flex flex-md-column flex-column-reverse">
                    <div>
                      <span class="fw-bold d-md-none d-block"><?= esc($order_item->menu_item_name) ?></span>
                      <?php if ($order_item->notes !== NULL): ?>
                        <span class="small text-secondary">- <?= esc($order_item->notes) ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="sub-text text-muted d-md-none w-auto">Item</div>
                  </div>
                </div>
                <div class="col-md-2 fw-bold p-md-0 mt-md-0 mt-2">
                  <span class="fw-bold d-md-block d-none">1</span>
                </div>
                <div class="col-md-3 fw-bold p-md-0 mt-md-0 mt-2">
                  <div class="row">
                    <span class="d-md-block d-none"><?= esc($order_item->formatted_item_order_time) ?></span>
                    <div class="sub-text text-muted d-md-none">Time Ordered</div>
                    <span class="fw-bold d-md-none d-block"><?= esc($order_item->formatted_item_order_time) ?>, <?= esc($order_item->formatted_item_order_date) ?></span>
                  </div>
                  <div class="row">
                    <span class="sub-text text-muted d-md-block d-none"><?= esc($order_item->formatted_item_order_date) ?></span>
                  </div>
                </div>
                <div class="col-md-2 fw-bold p-md-0 mt-md-0 mt-2">
                  <div class="sub-text text-muted d-md-none">Status</div>
                  <span class="badge rounded-pill bg-warning text-dark text-wrap"><?= esc($order_item->status_name) ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
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
