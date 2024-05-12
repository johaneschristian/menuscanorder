<?= $this->extend('base_templates/customer-base-template') ?>

<?= $this->section('title') ?>
<title>User Details</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column align-items-center p-3">
  <h1>User Details</h1>
  <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
    <span class="fw-bold fs-4 mb-3">Personal Information</span>
    <div class="detail-group d-flex flex-column mb-3">
      <span class="fw-bold lh-sm">User ID</span>
      <span class="fs-5"><?= esc($user->id) ?></span>
    </div>
    <div class="detail-group d-flex flex-column mb-3">
      <span class="fw-bold lh-sm">Email</span>
      <span class="fs-5"><?= esc($user->email) ?></span>
    </div>
    <div class="detail-group d-flex flex-column mb-3">
      <span class="fw-bold lh-sm">Name</span>
      <span class="fs-5"><?= esc($user->name) ?></span>
    </div>
    <div class="detail-group d-flex flex-column mb-3">
      <span class="fw-bold lh-sm">Account Type</span>
      <span class="fs-5"><?= $user->is_admin ? "admin" : ($user->has_business ? "business" : "customer") ?></span>
    </div>
    <div class="detail-group d-flex flex-column">
      <span class="fw-bold lh-sm">Status</span>
      <span class="fs-5"><?= $user->is_archived ? "archived" : "active" ?></span>
    </div>
  </div>

  <?php if ($user->has_business) : ?>
    <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
      <span class="fw-bold fs-4 mb-3">Affiliated Business Information</span>
      <div class="detail-group d-flex flex-column mb-3">
        <span class="fw-bold lh-sm">Business Name</span>
        <span class="fs-5"><?= $user->has_business ? esc($user->business->business_name) : "" ?></span>
      </div>
      <div class="detail-group d-flex flex-column mb-3">
        <span class="fw-bold lh-sm">Business Address</span>
        <span class="fs-5"><?= $user->has_business ? esc($user->business->address) : "" ?></span>
      </div>
      <div class="detail-group d-flex flex-column mb-3">
        <span class="fw-bold lh-sm">Dine-In Business Size</span>
        <span class="fs-5"><?= $user->has_business ? esc($user->business->num_of_tables) : "" ?> Tables</span>
      </div>
      <div class="detail-group d-flex flex-column mb-3">
        <span class="fw-bold lh-sm">Business Subscription Status</span>
        <span class="fs-5"><?= ($user->has_business && $user->business->business_is_archived) ? "archived" : "active" ?></span>
      </div>
    </div>
  <?php endif; ?>

  <div class="row card shadow p-3 mt-3 w-mdc-50 w-100">
    <div class="w-100 d-flex flex-row justify-content-end">
      <a href='<?= base_url("/admin/users/{$user->id}/edit") ?>' type="button" class="btn btn-warning">Edit User Information</a>
    </div>
  </div>
</div>
<?= $this->endSection() ?>