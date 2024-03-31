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
		<title>Profile Edit</title>
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
      <div class="modal fade" id="reset-password-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form action="" method="post">
            <div class="modal-content">
              <div class="modal-body">
                <p class="fw-bold fs-5">Reset Password</p>
                <label for="new-password" class="form-label">Old Password</label>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" id="old-password" name="old_password" required>
                </div>
                <label for="new-password" class="form-label">New Password</label>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" id="new-password" name="new_password" required>
                </div>
                <label for="new-password-confirmation" class="form-label">New Password Confirmation</label>
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
      <div class="d-flex flex-column align-items-center p-3">
        <h1>Edit User Details</h1>
        <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
          <span class="fw-bold fs-4 mb-3">Personal Information</span>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Email</label>
            <input type="email" class="form-control bg-disabled" value="j.tarigan@uqconnect.edu.au" readonly>
          </div>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Name</label>
            <input type="email" class="form-control" value="Johanes Christian Lewi Putrael">
          </div>
          <div class="w-100 d-flex flex-sm-row flex-column justify-content-between">
            <div class="">
              <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#reset-password-modal">Reset User Password</button>
            </div>
            <div class="d-flex flex-sm-row flex-column mt-sm-0 mt-1 justify-content-end gap-1">
              <button class="btn bg-brown text-light" onclick="document.querySelector('#user-details-form').submit()">Update User Information</button>
              <button class="btn btn-outline-primary">Cancel</button>
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
