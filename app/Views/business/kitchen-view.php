<?= $this->extend('base_templates/business-base-template') ?>

<?= $this->section('additional_css_js') ?>
<script src="<?= base_url('js/business/kitchenView.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('title') ?>
<title>Kitchen View</title>
<?= $this->endSection() ?>

<?= $this->section('business_name') ?>
<?= esc($business_name) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container p-3">
  <div class="d-flex flex-md-row flex-column justify-content-center mb-3 gap-3 w-100">
    <div class="progress-board mt-3 w-mdc-25 w-100">
      <div class="card text-white bg-danger shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Received</h5>
          <span class="card-text display-1" id="order-item-summary-received">8</span>
        </div>
      </div>
    </div>
    <div class="progress-board mt-3 w-mdc-25 w-100">
      <div class="card text-dark bg-warning shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Being Prepared</h5>
          <span class="card-text display-1" id="order-item-summary-being-prepared">11</span>
        </div>
      </div>
    </div>
    <div class="progress-board mt-3 w-mdc-25 w-100">
      <div class="card text-white bg-success shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Served</h5>
          <span class="card-text display-1" id="order-item-summary-served">273</span>
        </div>
      </div>
    </div>
  </div>
  <div id="order-items-holder" class="row d-flex justify-content-center text-center gy-3">
    <h1>No Ongoing Orders</h1>
  </div>
</div>
<?= $this->endSection() ?>