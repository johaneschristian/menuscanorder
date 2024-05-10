<?= $this->extend('base_templates/customer-base-template') ?>

<?= $this->section('title') ?>
<title>Profile Edit</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="modal fade" id="reset-password-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form action="<?= base_url('change-password') ?>" method="post">
			<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
			<div class="modal-content">
				<div class="modal-body">
					<p class="fw-bold fs-5">Change Password</p>
					<label for="password" class="form-label">Old Password</label>
					<div class="input-group mb-3">
						<input type="password" class="form-control" id="old-password" name="old_password" required>
					</div>
					<label for="new-password" class="form-label">New Password</label>
					<div class="input-group mb-3">
						<input type="password" class="form-control" id="new-password" name="password" required>
					</div>
					<label for="new-password-confirmation" class="form-label">New Password Confirmation</label>
					<div class="input-group mb-3">
						<input type="password" class="form-control" id="new-password-confirmation" name="password_confirmation" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-warning">Change Password</button>
					<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="d-flex flex-column align-items-center p-3">
	<h1>Edit User Details</h1>
	<form action="" method="post" class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-md-5 pe-md-5 ps-3 pe-3 mt-md-3 mt-1">
		<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
		<span class="fw-bold fs-4 mb-3">Personal Information</span>
		<div class="mb-3">
			<label for="" class="form-label fw-bold lh-sm">Email</label>
			<input type="email" class="form-control bg-disabled" value="<?= esc($user->email) ?>" readonly>
		</div>
		<div class="mb-3">
			<label for="" class="form-label fw-bold lh-sm">Name</label>
			<input name="name" type="text" class="form-control" value="<?= esc($user->name) ?>">
		</div>
		<div class="w-100 d-flex flex-sm-row flex-column justify-content-between mt-md-1 mt-5">
			<div class="">
				<button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#reset-password-modal">Change User Password</button>
			</div>
			<div class="d-flex flex-sm-row flex-column mt-sm-0 mt-1 justify-content-end gap-1">
				<button type="submit" class="btn bg-brown text-light">Update User Information</button>
				<a href="<?= base_url('') ?>" class="btn btn-outline-primary">Cancel</a>
			</div>
		</div>
	</form>
</div>
<?= $this->endSection() ?>
