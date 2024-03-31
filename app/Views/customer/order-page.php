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
    <link rel="stylesheet" href="<?= base_url('css/business/orderPage.css') ?>">
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
					<span class="h1 ms-1 text-brown fw-bold">Warteg Bahari, Depok</span>
					<span class="h1 fw-bold">!</span>
				</div>
				<div
					id="checkout-button"
					class="position-fixed bottom-0 mb-3 start-50 translate-middle-x z-3 shadow-lg p-3 w-mdc-50 w-75 rounded-5 text-white fw-bold bg-brown d-flex flex-row justify-content-between clickable d-none"
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
										<td>: Warteg Bahari Restaurant</td>
									</tr>
									<tr>
										<td class="fw-bold">Table Number</td>
										<td>: 13</td>
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
									<tbody id="cart-items-table-body">
										<tr>
											<td>1</td>
											<td>Nasi Goreng</td>
											<td>1</td>
											<td>30.00</td>
											<td>Make it less spicy</td>
										</tr>
									</tbody>
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
					<div
						class="menu-category col text-center fw-bold border-bottom border-3 selected"
						id="menu-category-2"
						onclick="setMenuCategoryActive(this)"
					>
						Appetizer
					</div>
					<div
						class="menu-category col text-center fw-bold border-bottom border-3"
						id="menu-category-3"
						onclick="setMenuCategoryActive(this)"
					>
						Main
					</div>
					<div
						class="menu-category col text-center fw-bold border-bottom border-3"
						id="menu-category-4"
						onclick="setMenuCategoryActive(this)"
					>
						Dessert
					</div>
					<div
						class="menu-category col text-center fw-bold border-bottom border-3"
						id="menu-category-5"
						onclick="setMenuCategoryActive(this)"
					>
						Drinks
					</div>
					<div
						class="menu-category col text-center fw-bold border-bottom border-3"
						id="menu-category-6"
						onclick="setMenuCategoryActive(this)"
					>
						Add-Ons
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row p-3 d-flex justify-content-center">
					<div class="col-auto">
						<div class="card mb-3 shadow" style="max-width: 25rem">
							<div class="row g-0">
								<div class="col-6">
									<img
										src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9MB0obFxFhXf-jRnhS_hherNUr9ZaoxSjZjFaD6H-VT5lSuK-"
										id="menu-menu_item_id_1-image"
										class="w-100 object-fit-cover rounded-start"
										style="object-fit: cover; height: 16rem"
										alt="Nasi Goreng"
									/>
								</div>
								<div class="col-6">
									<div class="card-body">
										<h5 class="card-title" id="menu-menu_item_id_1-name">
											Nasi Goreng
										</h5>
										<span
											class="badge rounded-pill bg-warning fw-bold text-dark"
											>AUD<span id="menu-menu_item_id_1-price">4.00</span></span
										>
										<p
											id="menu-menu_item_id_1-description"
											class="card-text trunc-3 mb-2"
										>
											<small
												>Nasi goreng, often hailed as the national dish of
												Indonesia, is a tantalizing and aromatic fried rice dish
												that captivates the senses with its rich blend of
												flavors and textures. Rooted in Indonesian culinary
												heritage, nasi goreng is a beloved street food staple,
												frequently enjoyed across the archipelago and
												beyond.</small
											>
										</p>
										<button
											class="badge rounded-pill bg-dark mb-3 border-0"
											data-bs-toggle="modal"
											data-bs-target="#menu-item-modal"
											onclick="toggleReadMore('menu_item_id_1')"
										>
											Read More
										</button>
										<div class="d-flex flex-row justify-content-between">
											<div
												class="d-flex flex-row align-items-center justify-content-start"
											>
												<button
													class="btn bg-brown text-white rounded-circle me-2 fw-bold"
													type="submit"
													onclick="removeMenuQuantity('menu_item_id_1')"
												>
													&minus;
												</button>
												<span
													class="h6 fw-bold m-0"
													id="menu-menu_item_id_1-quantity"
													>0</span
												>
												<button
													class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
													type="submit"
													onclick="addMenuQuantity('menu_item_id_1')"
												>
													&plus;
												</button>
											</div>
											<button
												id="menu-menu_item_id_1-edit-note-button"
												class="btn btn-outline-primary d-none"
												data-bs-toggle="collapse"
												data-bs-target="#menu-menu_item_id_1-note-collapse"
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
								id="menu-menu_item_id_1-note-collapse"
							>
								<textarea
									class="form-control w-100 bg-light"
									id="menu-menu_item_id_1-note"
									placeholder="Additional notes..."
									onkeyup="toggleEditNoteButtonColor('menu_item_id_1')"
								></textarea>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="card mb-3 shadow" style="max-width: 25rem">
							<div class="row g-0">
								<div class="col-6">
									<img
										src="https://images.slurrp.com/prod/recipe_images/asian-food-network/es-doger-1623090776_ZFLYTGBQBBABDYEETIF4.webp"
										class="w-100 object-fit-cover rounded-start gray-image"
										style="object-fit: cover; height: 16rem"
										alt="..."
									/>
								</div>
								<div class="col-6">
									<div class="card-body">
										<h5 class="card-title">Es Doger</h5>
										<span class="badge rounded-pill bg-danger"
											>Not Available</span
										>
										<span
											class="badge rounded-pill bg-warning fw-bold text-dark"
											>AUD5.00</span
										>
										<span class="card-text trunc-3 mb-2"
											><small
												>Nasi goreng, often hailed as the national dish of
												Indonesia, is a tantalizing and aromatic fried rice dish
												that captivates the senses with its rich blend of
												flavors and textures. Rooted in Indonesian culinary
												heritage, nasi goreng is a beloved street food staple,
												frequently enjoyed across the archipelago and
												beyond.</small
											></span
										>
										<button class="badge rounded-pill bg-dark mb-3 border-0">
											Read More
										</button>

										<div
											class="d-flex flex-row align-items-center justify-content-start"
										>
											<button
												class="btn bg-brown text-white rounded-circle me-2 fw-bold"
												type="submit"
												disabled
											>
												&minus;
											</button>
											<span class="h6 fw-bold m-0">0</span>
											<button
												class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
												type="submit"
												disabled
											>
												&plus;
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="card mb-3 shadow" style="max-width: 25rem">
							<div class="row g-0">
								<div class="col-6">
									<img
										src="https://www.kitchensanctuary.com/wp-content/uploads/2020/07/Nasi-Goreng-square-FS-57-500x375.jpg"
										class="w-100 object-fit-cover rounded-start"
										style="object-fit: cover; height: 16rem"
										alt="..."
									/>
								</div>
								<div class="col-6">
									<div class="card-body">
										<h5 class="card-title">Nasi Goreng</h5>
										<span
											class="badge rounded-pill bg-warning fw-bold text-dark"
											>AUD4.00</span
										>
										<span class="card-text trunc-3 mb-2"
											><small
												>Nasi goreng, often hailed as the national dish of
												Indonesia, is a tantalizing and aromatic fried rice dish
												that captivates the senses with its rich blend of
												flavors and textures. Rooted in Indonesian culinary
												heritage, nasi goreng is a beloved street food staple,
												frequently enjoyed across the archipelago and
												beyond.</small
											></span
										>
										<button class="badge rounded-pill bg-dark mb-3 border-0">
											Read More
										</button>

										<div
											class="d-flex flex-row align-items-center justify-content-start"
										>
											<button
												class="btn bg-brown text-white rounded-circle me-2 fw-bold"
												type="submit"
											>
												&minus;
											</button>
											<span class="h6 fw-bold m-0">0</span>
											<button
												class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
												type="submit"
											>
												&plus;
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="card mb-3 shadow" style="max-width: 25rem">
							<div class="row g-0">
								<div class="col-6">
									<img
										src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9MB0obFxFhXf-jRnhS_hherNUr9ZaoxSjZjFaD6H-VT5lSuK-"
										class="w-100 object-fit-cover rounded-start"
										style="object-fit: cover; height: 16rem"
										alt="..."
									/>
								</div>
								<div class="col-6">
									<div class="card-body">
										<h5 class="card-title">Nasi Goreng</h5>
										<span
											class="badge rounded-pill bg-warning fw-bold text-dark"
											>AUD4.00</span
										>
										<span class="card-text trunc-3 mb-2"
											><small
												>Nasi goreng, often hailed as the national dish of
												Indonesia, is a tantalizing and aromatic fried rice dish
												that captivates the senses with its rich blend of
												flavors and textures. Rooted in Indonesian culinary
												heritage, nasi goreng is a beloved street food staple,
												frequently enjoyed across the archipelago and
												beyond.</small
											></span
										>
										<button class="badge rounded-pill bg-dark mb-3 border-0">
											Read More
										</button>

										<div
											class="d-flex flex-row align-items-center justify-content-start"
										>
											<button
												class="btn bg-brown text-white rounded-circle me-2 fw-bold"
												type="submit"
											>
												&minus;
											</button>
											<span class="h6 fw-bold m-0">0</span>
											<button
												class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
												type="submit"
											>
												&plus;
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="card mb-3 shadow" style="max-width: 25rem">
							<div class="row g-0">
								<div class="col-6">
									<img
										src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9MB0obFxFhXf-jRnhS_hherNUr9ZaoxSjZjFaD6H-VT5lSuK-"
										class="w-100 object-fit-cover rounded-start"
										style="object-fit: cover; height: 16rem"
										alt="..."
									/>
								</div>
								<div class="col-6">
									<div class="card-body">
										<h5 class="card-title">Nasi Goreng</h5>
										<span
											class="badge rounded-pill bg-warning fw-bold text-dark"
											>AUD4.00</span
										>
										<span class="card-text trunc-3 mb-2"
											><small
												>Nasi goreng, often hailed as the national dish of
												Indonesia, is a tantalizing and aromatic fried rice dish
												that captivates the senses with its rich blend of
												flavors and textures. Rooted in Indonesian culinary
												heritage, nasi goreng is a beloved street food staple,
												frequently enjoyed across the archipelago and
												beyond.</small
											></span
										>
										<button class="badge rounded-pill bg-dark mb-3 border-0">
											Read More
										</button>

										<div
											class="d-flex flex-row align-items-center justify-content-start"
										>
											<button
												class="btn bg-brown text-white rounded-circle me-2 fw-bold"
												type="submit"
											>
												&minus;
											</button>
											<span class="h6 fw-bold m-0">0</span>
											<button
												class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
												type="submit"
											>
												&plus;
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="card mb-3 shadow" style="max-width: 25rem">
							<div class="row g-0">
								<div class="col-6">
									<img
										src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9MB0obFxFhXf-jRnhS_hherNUr9ZaoxSjZjFaD6H-VT5lSuK-"
										class="w-100 object-fit-cover rounded-start"
										style="object-fit: cover; height: 16rem"
										alt="..."
									/>
								</div>
								<div class="col-6">
									<div class="card-body">
										<h5 class="card-title">Nasi Goreng</h5>
										<span
											class="badge rounded-pill bg-warning fw-bold text-dark"
											>AUD4.00</span
										>
										<span class="card-text trunc-3 mb-2"
											><small
												>Nasi goreng, often hailed as the national dish of
												Indonesia, is a tantalizing and aromatic fried rice dish
												that captivates the senses with its rich blend of
												flavors and textures. Rooted in Indonesian culinary
												heritage, nasi goreng is a beloved street food staple,
												frequently enjoyed across the archipelago and
												beyond.</small
											></span
										>
										<button class="badge rounded-pill bg-dark mb-3 border-0">
											Read More
										</button>

										<div
											class="d-flex flex-row align-items-center justify-content-start"
										>
											<button
												class="btn bg-brown text-white rounded-circle me-2 fw-bold"
												type="submit"
											>
												&minus;
											</button>
											<span class="h6 fw-bold m-0">0</span>
											<button
												class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
												type="submit"
											>
												&plus;
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="card mb-3 shadow" style="max-width: 25rem">
							<div class="row g-0">
								<div class="col-6">
									<img
										src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9MB0obFxFhXf-jRnhS_hherNUr9ZaoxSjZjFaD6H-VT5lSuK-"
										class="w-100 object-fit-cover rounded-start"
										style="object-fit: cover; height: 16rem"
										alt="..."
									/>
								</div>
								<div class="col-6">
									<div class="card-body">
										<h5 class="card-title">Nasi Goreng</h5>
										<span
											class="badge rounded-pill bg-warning fw-bold text-dark"
											>AUD4.00</span
										>
										<span class="card-text trunc-3 mb-2"
											><small
												>Nasi goreng, often hailed as the national dish of
												Indonesia, is a tantalizing and aromatic fried rice dish
												that captivates the senses with its rich blend of
												flavors and textures. Rooted in Indonesian culinary
												heritage, nasi goreng is a beloved street food staple,
												frequently enjoyed across the archipelago and
												beyond.</small
											></span
										>
										<button class="badge rounded-pill bg-dark mb-3 border-0">
											Read More
										</button>

										<div
											class="d-flex flex-row align-items-center justify-content-start"
										>
											<button
												class="btn bg-brown text-white rounded-circle me-2 fw-bold"
												type="submit"
											>
												&minus;
											</button>
											<span class="h6 fw-bold m-0">0</span>
											<button
												class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
												type="submit"
											>
												&plus;
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="card mb-5 shadow" style="max-width: 25rem">
							<div class="row g-0">
								<div class="col-6">
									<img
										src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9MB0obFxFhXf-jRnhS_hherNUr9ZaoxSjZjFaD6H-VT5lSuK-"
										class="w-100 object-fit-cover rounded-start"
										style="object-fit: cover; height: 16rem"
										alt="..."
									/>
								</div>
								<div class="col-6">
									<div class="card-body">
										<h5 class="card-title">Nasi Goreng</h5>
										<span
											class="badge rounded-pill bg-warning fw-bold text-dark"
											>AUD4.00</span
										>
										<span class="card-text trunc-3 mb-2"
											><small
												>Nasi goreng, often hailed as the national dish of
												Indonesia, is a tantalizing and aromatic fried rice dish
												that captivates the senses with its rich blend of
												flavors and textures. Rooted in Indonesian culinary
												heritage, nasi goreng is a beloved street food staple,
												frequently enjoyed across the archipelago and
												beyond.</small
											></span
										>
										<button class="badge rounded-pill bg-dark mb-3 border-0">
											Read More
										</button>

										<div
											class="d-flex flex-row align-items-center justify-content-start"
										>
											<button
												class="btn bg-brown text-white rounded-circle me-2 fw-bold"
												type="submit"
											>
												&minus;
											</button>
											<span class="h6 fw-bold m-0">0</span>
											<button
												class="btn bg-brown text-white rounded-circle ms-2 fw-bold"
												type="submit"
											>
												&plus;
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
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
