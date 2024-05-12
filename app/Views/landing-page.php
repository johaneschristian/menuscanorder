<?= $this->extend('base_templates/customer-base-template') ?>

<?= $this->section('title') ?>
<title>MenuScanOrder</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="container p-5">
  <div class="row d-flex flex-row justify-content-center">
    <div class="col-lg-4 d-flex flex-column justify-content-center mb-5">
      <span class="display-2">Sit, Scan, Order.</span>
      <p class="fs-4 fw-light">Enhance your overall dining experience with straightforward ordering upon arrival, equipped with the ability to track the status of your order.</p>
      <div class="">
        <button class="btn bg-brown text-light clickable">Get Started</button>
      </div>
    </div>
    <div class="col-lg-4">
      <img src="<?= base_url('images/online-ordering-business.jpg') ?>" alt="" class="shadow-lg rounded w-100">
    </div>
  </div>
</section>
<section class="container p-3 d-flex flex-column align-items-center gap-3 w-100 mb-5">
  <span class="fs-1 fw-bold">Why join us?</span>
  <div class="container d-flex flex-md-row flex-column justify-content-center gap-3 w-100">
    <div class="card shadow-lg bg-brown text-light w-mdc-25">
      <div class="card-body">
        <div class="card-title h3 mb-3">Digital Menu</div>
        <p class="fw-light">Access the wide range of menu from your pocket, allowing you to reorder at any time. This also improves business flexibility in updating their menu, removing the overhead cost of reprinting menu books.</p>
      </div>
    </div>
    <div class="card shadow-lg bg-brown text-light w-mdc-25">
      <div class="card-body">
        <div class="card-title h3 mb-3">Seamless Ordering</div>
        <p class="fw-light">Removes the need to call staff when ordering, which eliminates the need to wait for a waiter during peak hours and frees up more staff to focus on food preparation and delivery. </p>
      </div>
    </div>
    <div class="card shadow-lg bg-brown text-light w-mdc-25">
      <div class="card-body">
        <div class="card-title h3 mb-3">Order Tracking</div>
        <p class="fw-light">Check the status of your food at any time, increasing transparency in the dining experience. This allows both customers and businesses to stay on top of their orders.</p>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>