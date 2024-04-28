<?= $this->extend('base_templates/customer-base-template') ?>

<?= $this->section('title') ?>
<title>Register</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
	<section class="d-flex flex-row justify-content-center p-5">
		<form action="" method="post" class="card shadow-lg rounded p-3 w-mdc-50 w-75">
			<div class="text-center">
				<span class="fw-bold fs-4 mb-3">Register</span>
			</div>
			<div class="mb-3">
				<label for="" class="form-label fw-bold lh-sm">Email</label>
				<input name="email" type="email" class="form-control bg-soft-gray" required>
			</div>
			<div class="mb-3">
				<label for="" class="form-label fw-bold lh-sm">Name</label>
				<input name="name" type="text" class="form-control bg-soft-gray" required>
			</div>
			<div class="mb-3">
				<label for="" class="form-label fw-bold lh-sm">Password</label>
				<input name="password" type="password" class="form-control bg-soft-gray" required>
			</div>
			<div class="mb-3">
				<label for="" class="form-label fw-bold lh-sm" required>Password Confirmation</label>
				<input name="password_confirmation" type="password" class="form-control bg-soft-gray">
			</div>
			<div class="d-flex flex-row justify-content-between align-items-end mt-3">
				<a href="http://">Does not have an account?</a>
				<button type="submit" class="btn bg-brown text-light">Login</button>
			</div>
		</form>
	</section>
<?= $this->endSection() ?>
		