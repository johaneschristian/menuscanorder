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
    <script src="<?= base_url('js/admin/adminCreateEditUser.js') ?>" defer></script>
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
      <div class="modal fade" id="reset-password-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form action="" method="post">
            <div class="modal-content">
              <div class="modal-body">
                <p class="fw-bold fs-5">Reset Password</p>
                <label for="new-password" class="form-label">Password</label>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" id="new-password" name="new_password" required>
                </div>
                <label for="new-password-confirmation" class="form-label">Password Confirmation</label>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" id="new-password-confirmation" name="new_password_confirmation" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Reset Password</button>
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
			<h1>Create User</h1>
      <form action="" method="post" class="w-100 d-flex flex-column align-items-center" id="user-details-form">
        <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5">
          <span class="fw-bold fs-4 mb-3">Personal Information</span>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Email</label>
            <input name="email" type="email" class="form-control">
          </div>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Name</label>
            <input name="name" type="text" class="form-control">
          </div>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Account Type</label>
            <select name="account_type" class="form-select">
              <option value="user" selected>user</option>
              <option value="admin">admin</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Subscription Status</label>
            <select name="subscription_status" class="form-select">
              <option value="active" selected>active</option>
              <option value="archived">archived</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Password</label>
            <input name="password" type="password" class="form-control">
          </div>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Password Confirmation</label>
            <input name="password_confirmation" type="password" class="form-control">
          </div>
        </div>

        <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
          <span class="fw-bold fs-4 mb-3">Affiliated Business Information</span>
          <div class="d-none" id="affiliated-business-form">
            <div class="mb-3">
              <label for="affiliated-business-name" class="form-label fw-bold lh-sm">Business Name</label>
              <input name="business_name" id="affiliated-business-name" type="text" class="form-control">
            </div>
            <div class="mb-3">
              <label for="affiliated-business-address" class="form-label fw-bold lh-sm">Business Address</label>
              <textarea name="address" id="affiliated-business-address" type="text" rows="1" class="form-control"></textarea>
            </div>
            <div class="mb-3">
              <label for="affiliated-business-table-quantity" class="form-label fw-bold lh-sm">Dine-In Business Size</label>
              <div class="input-group">
                <input name="num_of_tables" id="affiliated-business-table-quantity" type="number" step="1" class="form-control">
                <span class="input-group-text bg-soft-gray">tables</span>
              </div>
            </div>
            <div class="mb-3">
              <label for="affiliated-business-subcription-status" class="form-label fw-bold lh-sm">Business Subscription Status</label>
              <select name="business_subscription_status" id="affiliated-business-subcription-status" class="form-select">
                <option value="active" selected>active</option>
                <option value="archived">archived</option>
              </select>
            </div>
          </div>
          <button id="add-business-button" type="button" class="btn btn-success" onclick="displayAffiliatedBusinessForm()">Create a Business for User</button>
          <button id="remove-business-button" type="button" class="btn btn-secondary d-none" onclick="hideAffiliatedBusinessForm()">Remove Business from User</button>
        </div>
      </form>
      <div class="card shadow w-mdc-50 w-100 p-3 d-flex flex-sm-row flex-column justify-content-between mt-3">
        <div class="d-flex flex-sm-row flex-column mt-sm-0 mt-1 justify-content-end gap-1 w-100">
          <button class="btn bg-brown text-light" onclick="document.querySelector('#user-details-form').submit()">Create User</button>
          <button class="btn btn-outline-primary">Cancel</button>
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
