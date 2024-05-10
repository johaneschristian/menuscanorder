<?= $this->extend('base_templates/business-base-template') ?>

<?= $this->section('title') ?>
<title>Business Profile Edit</title>
<?= $this->endSection() ?>

<?= $this->section('business_name') ?>
<?= esc($business->business_name) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column align-items-center p-md-5 p-3">
  <h1 class="w-100 text-center">Affiliated Business Information</h1>
  <form action="" method="post" class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <div class="" id="affiliated-business-form">
      <div class="mb-3">
        <label for="" class="form-label fw-bold lh-sm">Business ID</label>
        <input type="text" class="form-control bg-disabled" value="<?= esc($business->business_id) ?>" readonly>
      </div>
      <div class="mb-3">
        <label for="affiliated-business-name" class="form-label fw-bold lh-sm">Business Name</label>
        <input name="business_name" id="affiliated-business-name" type="text" class="form-control" value="<?= esc($business->business_name) ?>">
      </div>
      <div class="mb-3">
        <label for="affiliated-business-address" class="form-label fw-bold lh-sm">Business Address</label>
        <textarea name="address" id="affiliated-business-address" type="text" rows="1" class="form-control"><?= esc($business->address) ?></textarea>
      </div>
      <div class="mb-3">
        <label for="affiliated-business-table-quantity" class="form-label fw-bold lh-sm">Dine-In Business Size</label>
        <div class="input-group">
          <input name="num_of_tables" id="affiliated-business-table-quantity" type="number" step="1" class="form-control" value="<?= esc($business->num_of_tables) ?>">
          <span class="input-group-text bg-soft-gray">tables</span>
        </div>
      </div>
      <div class="mb-3">
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" name="is_open" id="is_open" <?= $business->is_open ? "checked" : ""?>>
          <label for="" class="form-label">Business is Open</label>
        </div>
      </div>
    </div>
    <div class="d-flex flex-sm-row flex-column mt-sm-0 mt-1 justify-content-end gap-1">
      <button class="btn bg-brown text-light">Update Business Information</button>
      <a href="<?= base_url('/business/orders') ?>" class="btn btn-outline-primary">Cancel</a>
    </div>
  </form>
</div>
<?= $this->endSection() ?>