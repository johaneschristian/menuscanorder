<?= $this->extend('base_templates/customer-base-template') ?>

<?= $this->section('title') ?>
<title>Business Registration</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
	<div class="container">
		<section class="d-flex flex-column align-items-center p-3">
			<h1>Create Business</h1>
			<span class="w-mdc-50 w-75 text-center"><i class="bi bi-info-circle me-1"></i>To access the business features, you must first register your business and affiliate it to your account.</span>
			<form action="" method="post" class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
				<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
				<div class="" id="affiliated-business-form">
					<div class="mb-3">
						<label for="affiliated-business-name" class="form-label fw-bold lh-sm">Business Name</label>
						<input id="affiliated-business-name" name="business_name" type="text" class="form-control">
					</div>
					<div class="mb-3">
						<label for="affiliated-business-address" class="form-label fw-bold lh-sm">Business Address</label>
						<textarea id="affiliated-business-address" name="address" type="text" rows="1" class="form-control"></textarea>
					</div>
					<div class="mb-3">
						<label for="affiliated-business-table-quantity" class="form-label fw-bold lh-sm">Dine-In Business Size</label>
						<div class="input-group">
							<input id="affiliated-business-table-quantity" name="num_of_tables" type="number" step="1" class="form-control">
							<span class="input-group-text bg-soft-gray">tables</span>
						</div>
					</div>
				</div>
				<div class="d-flex flex-sm-row flex-column mt-sm-0 mt-1 justify-content-end gap-1">
					<button class="btn bg-brown text-light">Register Business</button>
					<button class="btn btn-outline-primary">Cancel</button>
				</div>
			</form>
		</section>
	</div>
<?= $this->endSection() ?>
		
