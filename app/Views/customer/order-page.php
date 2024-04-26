<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
			crossorigin="anonymous"
		/>
		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
			crossorigin="anonymous"
			defer
		></script>
		<script src="<?= base_url('js/helper.js') ?>" defer></script>
		<script src="<?= base_url('js/customer/index.js') ?>" defer></script>
		<link rel="stylesheet" href="<?= base_url('css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('css/customer/orderPage.css') ?>">
		<title>Order Page</title>
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
			<div class="container">
				<div class="p-3">
					<span class="h3 fw-bold">Welcome to</span>
					<span class="h1 ms-1 text-brown fw-bold"><?= esc($business->business_name) ?></span>
					<span class="h1 fw-bold">!</span>
				</div>
				<div
					id="checkout-button"
					class="position-fixed bottom-0 mb-5 start-50 translate-middle-x z-3 shadow-lg p-3 w-mdc-50 w-75 rounded-5 text-white fw-bold bg-brown d-flex flex-row justify-content-between clickable d-none"
					data-bs-toggle="modal"
					data-bs-target="#card-modal"
					onclick="setCartContent()"
				>
					<span>Complete My Order</span>
					<span>AUD<span id="order-total-value">0.00</span></span>
				</div>
				<div
					class="modal fade"
					id="menu-item-modal"
					data-bs-keyboard="false"
					tabindex="-1"
					aria-labelledby="staticBackdropLabel"
					aria-hidden="true"
				>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<img
									id="menu-detail-image"
									class="w-100 object-fit-cover rounded-start"
									style="object-fit: cover; height: 20rem"
									alt=""
								/>
								<h1 class="fs-4 fw-bold mt-3" id="menu-detail-name"></h1>
								<small id="menu-detail-description"></small>
							</div>
							<div class="modal-footer">
								<button
									type="button"
									class="btn btn-outline-warning"
									data-bs-dismiss="modal"
								>
									Close
								</button>
							</div>
						</div>
					</div>
				</div>
				<div
					class="modal modal-lg fade"
					id="card-modal"
					data-bs-backdrop="static"
					data-bs-keyboard="false"
					tabindex="-1"
					aria-labelledby="staticBackdropLabel"
					aria-hidden="true"
				>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="staticBackdropLabel">
									Hi! Before you submit, kindly review your order.
								</h1>
								<button
									type="button"
									class="btn-close"
									data-bs-dismiss="modal"
									aria-label="Close"
								></button>
							</div>
							<div class="modal-body">
								<table class="table w-75">
									<tr>
										<td class="fw-bold">Business Name</td>
										<td>: <?= esc($business->business_name) ?></td>
									</tr>
									<tr>
										<td class="fw-bold">Table Number</td>
										<td>: <span id="order-table-number">13</span></td>
									</tr>
									<tr>
										<td class="fw-bold">Total Price</td>
										<td>: AUD<span id="order-total-value-modal">0.00</span></td>
									</tr>
								</table>

								<table class="table">
									<thead>
										<tr>
											<th>No.</th>
											<th>Item</th>
											<th>Quantity</th>
											<th>Subtotal (AUD)</th>
											<th>Notes</th>
										</tr>
									</thead>
									<tbody id="cart-items-table-body"></tbody>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn bg-brown text-light">
									Yes, I would like to submit my order.
								</button>
								<button
									type="button"
									class="btn btn-outline-primary"
									data-bs-dismiss="modal"
								>
									Cancel
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row d-flex justify-content-between">
					<?php for($categoryIndex = 0; $categoryIndex < sizeof($categories_and_menu); $categoryIndex++): ?>
						<div
							class='menu-category col text-center fw-bold border-bottom border-3 <?= $categoryIndex === 0 ? "selected" : "" ?>'
							id="menu-category-<?= esc($categories_and_menu[$categoryIndex]['category_id']) ?>"
							onclick="setMenuCategoryActive(this)"
						>
							<?= esc($categories_and_menu[$categoryIndex]['name']) ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
			<div class="container">
				<?php foreach ($categories_and_menu as $category): ?>
					<div id='category-holder-<?= esc($category['category_id']) ?>' class='row p-3 d-flex justify-content-center <?= $category['category_id'] !== $categories_and_menu[0]['category_id'] ? "d-none" : ""?>'>
						<?php foreach($category['menus'] as $menu): ?>
							<div class="col-auto">
								<div class="card mb-3 shadow" style="width: 25rem">
									<div class="row g-0">
										<div class="col-6">
											<img
												src='<?= $menu->image_url !== NULL ? base_url("/business/menu/{$menu->menu_item_id}/image") : "" ?>'
												id="menu-<?= esc($menu->menu_item_id) ?>-image"
												class="w-100 object-fit-cover rounded-start"
												style="object-fit: cover; height: 16rem"
												alt="Nasi Goreng"
												onerror="this.src='https://theme-assets.getbento.com/sensei/7c1964e.sensei/assets/images/catering-item-placeholder-704x520.png'"
											/>
										</div>
										<div class="col-6">
											<div class="card-body d-flex flex-column justify-content-between h-100">
												<div>
													<h5 class="card-title" id="menu-<?= esc($menu->menu_item_id) ?>-name"><?= esc($menu->name) ?></h5>
													<span
														class="badge rounded-pill bg-warning fw-bold text-dark"
														>AUD<span id="menu-<?= esc($menu->menu_item_id) ?>-price"><?= esc(number_format($menu->price, 2, '.')) ?></span></span
													>
													<p
														id="menu-<?= esc($menu->menu_item_id) ?>-description"
														class="card-text trunc-3 mb-1"
													>
														<small><?= esc($menu->description) ?></small>
													</p>
													<?php if($menu->description !== "" && $menu->description !== NULL): ?>
														<button
															class="badge rounded-pill bg-dark mb-3 border-0"
															data-bs-toggle="modal"
															data-bs-target="#menu-item-modal"
															onclick="toggleReadMore('<?= esc($menu->menu_item_id) ?>')"
														>
															Read More
														</button>
													<?php endif; ?>
												</div>
												<div class="d-flex flex-row justify-content-between">
													<div
														class="d-flex flex-row align-items-center justify-content-start"
													>
														<button
															class="btn bg-brown text-white rounded-circle me-2 fw-bold"
															type="submit"
															onclick="removeMenuQuantity('<?= esc($menu->menu_item_id) ?>')"
														>
															&minus;
														</button>
														<span
															class="h6 fw-bold m-0"
															id="menu-<?= esc($menu->menu_item_id) ?>-quantity"
															>0</span
														>
														<button
															class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
															type="submit"
															onclick="addMenuQuantity('<?= esc($menu->menu_item_id) ?>')"
														>
															&plus;
														</button>
													</div>
													<button
														id="menu-<?= esc($menu->menu_item_id) ?>-edit-note-button"
														class="btn btn-outline-primary d-none"
														data-bs-toggle="collapse"
														data-bs-target="#menu-<?= esc($menu->menu_item_id) ?>-note-collapse"
													>
														<svg
															xmlns="http://www.w3.org/2000/svg"
															width="16"
															height="16"
															fill="currentColor"
															class="bi bi-pencil"
															viewBox="0 0 16 16"
														>
															<path
																d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"
															/>
														</svg>
													</button>
												</div>
											</div>
										</div>
									</div>
									<div
										class="collapse p-2 fw-bold"
										id="menu-<?= esc($menu->menu_item_id) ?>-note-collapse"
									>
										<textarea
											class="form-control w-100 bg-light"
											id="menu-<?= esc($menu->menu_item_id) ?>-note"
											placeholder="Additional notes..."
											onkeyup="toggleEditNoteButtonColor('<?= esc($menu->menu_item_id) ?>')"
										></textarea>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
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
