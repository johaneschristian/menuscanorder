<?= $this->extend('base_templates/business-base-template') ?>

<?= $this->section('title') ?>
<title>Business Profile Edit</title>
<?= $this->endSection() ?>

<?= $this->section('business_name') ?>
Warteg Bahari Restaurant
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column align-items-center p-5">
  <h1>Affiliated Business Information</h1>
  <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5">
    <div class="" id="affiliated-business-form">
      <div class="mb-3">
        <label for="" class="form-label fw-bold lh-sm">Business ID</label>
        <input type="text" class="form-control bg-disabled" value="9bd43657-90fa-40bf-be17-41e0b2a6a1d9" readonly>
      </div>
      <div class="mb-3">
        <label for="affiliated-business-name" class="form-label fw-bold lh-sm">Business Name</label>
        <input id="affiliated-business-name" type="text" class="form-control" value="Warteg Bahari">
      </div>
      <div class="mb-3">
        <label for="affiliated-business-address" class="form-label fw-bold lh-sm">Business Address</label>
        <textarea id="affiliated-business-address" type="text" rows="1" class="form-control">43820 Tremblay Circle, Reidview, Queensland 2369, Australia</textarea>
      </div>
      <div class="mb-3">
        <label for="affiliated-business-table-quantity" class="form-label fw-bold lh-sm">Dine-In Business Size</label>
        <div class="input-group">
          <input id="affiliated-business-table-quantity" type="number" step="1" class="form-control" value="20">
          <span class="input-group-text bg-soft-gray">tables</span>
        </div>
      </div>
    </div>
    <div class="d-flex flex-sm-row flex-column mt-sm-0 mt-1 justify-content-end gap-1">
      <button class="btn bg-brown text-light">Update Business Information</button>
      <button class="btn btn-outline-primary">Cancel</button>
    </div>
  </div>
</div>
<?= $this->endSection() ?>