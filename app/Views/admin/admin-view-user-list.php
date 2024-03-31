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
		<link rel="stylesheet" href="<?= base_url('css/style.css'); ?>" />
		<title>All Users</title>
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
								<a class="nav-link active active" href="#">User Management</a>
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
      <div class="card shadow p-3 w-100 d-flex flex-md-row flex-column justify-content-between align-items-center">
        <form action="" method="get" class="w-mdc-50 w-75">
          <div class="input-group mb-md-0 mb-3 me-3 p-0">
            <input
              type="text"
              class="form-control bg-soft-gray"
              name="search"
              placeholder="Enter your search"
            />
            <button
              class="btn bg-brown text-white"
              type="submit"
              id="search-button"
            >
              Search
            </button>
          </div>
        </form>
        <button type="button" class="btn bg-brown text-white" style="width: fit-content;">Add User</button>
      </div>
			<div class="card mt-4 p-3 shadow w-100 overflow-x-auto">
				<table class="table table-hover align-middle">
					<thead class="align-middle">
						<tr>
							<th>No</th>
							<th>Email</th>
							<th>Name</th>
							<th>Type</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td id="user-user_id_1-email">admin1@admin.com</td>
							<td id="user-user_id_1-name">Admin John Doe</td>
							<td><span id="user-user_id_1-type">admin</span></td>
							<td>
								<span
									id="user-user_id_1-status"
									class="badge rounded-pill bg-success"
									>active</span
								>
							</td>
							<td>
								<button class="btn btn-sm btn-primary me-2 mb-1">
									<i class="bi bi-eye-fill"></i>
								</button>
								<button
									class="btn btn-sm btn-danger mb-1"
									data-bs-toggle="modal"
									data-bs-target="#update-status-modal"
									onclick=""
								>
									<i class="bi bi-pencil"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>2</td>
							<td>user1@user.com</td>
							<td>User John Doe</td>
							<td><span>customer</span></td>
							<td><span class="badge rounded-pill bg-success">active</span></td>
							<td>
								<button class="btn btn-sm btn-primary me-2 mb-1">
									<i class="bi bi-eye-fill"></i>
								</button>
								<button class="btn btn-sm btn-danger mb-1">
									<i class="bi bi-pencil"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>3</td>
							<td>user2@user.com</td>
							<td>User Jane Doe</td>
							<td><span>business</span></td>
							<td><span class="badge rounded-pill bg-success">active</span></td>
							<td>
								<button class="btn btn-sm btn-primary me-2 mb-1">
									<i class="bi bi-eye-fill"></i>
								</button>
								<button class="btn btn-sm btn-danger mb-1">
									<i class="bi bi-pencil"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>4</td>
							<td>user3@user.com</td>
							<td>User John Dae</td>
							<td><span>business</span></td>
							<td>
								<span class="badge rounded-pill bg-warning text-dark"
									>archived</span
								>
							</td>
							<td>
								<button class="btn btn-sm btn-primary me-2 mb-1">
									<i class="bi bi-eye-fill"></i>
								</button>
								<button class="btn btn-sm btn-danger mb-1">
									<i class="bi bi-pencil"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
				<nav>
					<ul class="pagination justify-content-center">
						<li class="page-item disabled">
							<a class="page-link" href="#" tabindex="-1" aria-disabled="true"
								>Previous</a
							>
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
