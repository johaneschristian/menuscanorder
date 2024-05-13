<?= $this->extend('base_templates/customer-base-template') ?>

<?= $this->section('title') ?>
<title><?= !isset($user) ? "Create" : "Edit" ?> User</title>
<?= $this->endSection() ?>

<?= $this->section('additional_css_js') ?>
<script src="<?= base_url('js/admin/adminCreateEditUser.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column align-items-center p-3">
  <?php if (isset($user)) : ?>
    <div class="modal fade" id="reset-password-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form action='<?= base_url("/admin/users/{$user->id}/edit/password") ?>' method="post">
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
          <div class="modal-content">
            <div class="modal-body">
              <p class="fw-bold fs-5">Reset Password</p>
              <label for="new-password" class="form-label">Password</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control" id="new-password" name="password" required>
              </div>
              <label for="new-password-confirmation" class="form-label">Password Confirmation</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control" id="new-password-confirmation" name="password_confirmation" required>
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
  <?php endif; ?>
  <h1><?= !isset($user) ? "Create" : "Edit" ?> User</h1>
  <form action="" method="post" class="w-100 d-flex flex-column align-items-center" id="user-details-form">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5">
      <span class="fw-bold fs-4 mb-3">Personal Information</span>
      <div class="mb-3">
        <label for="" class="form-label fw-bold lh-sm">Email</label>
        <input name="email" type="email" class="form-control <?= isset($user) ? 'bg-disabled' : '' ?>" value="<?= !isset($user) ? '' : esc($user->email) ?>" <?= isset($user) ? 'readonly' : '' ?>>
      </div>
      <div class="mb-3">
        <label for="" class="form-label fw-bold lh-sm">Name</label>
        <input name="name" type="email" class="form-control" value="<?= !isset($user) ? '' : esc($user->name) ?>">
      </div>
      <div class="mb-3">
        <label for="" class="form-label fw-bold lh-sm">Account Type</label>
        <select name="account_type" class="form-select">
          <option value="user" <?= isset($user) && $user->is_admin ? "" : "selected" ?>>user</option>
          <option value="admin" <?= isset($user) && $user->is_admin ? "selected" : "" ?>>admin</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="" class="form-label fw-bold lh-sm">Subscription Status</label>
        <select name="subscription_status" class="form-select">
          <option value="active" <?= isset($user) && $user->is_archived ? "" : "selected" ?>>active</option>
          <option value="archived" <?= isset($user) && $user->is_archived ? "selected" : "" ?>>archived</option>
        </select>
      </div>
      <?php if (!isset($user)) : ?>
        <div class="mb-3">
          <label for="" class="form-label fw-bold lh-sm">Password</label>
          <input name="password" type="password" class="form-control">
        </div>
        <div class="mb-3">
          <label for="" class="form-label fw-bold lh-sm">Password Confirmation</label>
          <input name="password_confirmation" type="password" class="form-control">
        </div>
      <?php endif; ?>
    </div>
    <div class="card shadow w-mdc-50 w-100 pt-3 pb-3 ps-5 pe-5 mt-3">
      <span class="fw-bold fs-4 mb-3">Affiliated Business Information</span>
      <div class="<?= isset($user) && $user->has_business ? "" : "d-none" ?>" id="affiliated-business-form">
        <?php if (isset($user) && $user->has_business) : ?>
          <div class="mb-3">
            <label for="" class="form-label fw-bold lh-sm">Business ID</label>
            <input type="text" class="form-control bg-disabled" value="<?= isset($user) && $user->has_business ? esc($user->business->business_id) : "" ?>" readonly>
          </div>
        <?php endif; ?>
        <div class="mb-3">
          <label for="affiliated-business-name" class="form-label fw-bold lh-sm">Business Name</label>
          <input name="business_name" id="affiliated-business-name" type="text" class="form-control" value="<?= isset($user) && $user->has_business ? esc($user->business->business_name) : "" ?>">
        </div>
        <div class="mb-3">
          <label for="affiliated-business-address" class="form-label fw-bold lh-sm">Business Address</label>
          <textarea name="address" id="affiliated-business-address" type="text" rows="1" class="form-control"><?= isset($user) && $user->has_business ? esc($user->business->address) : "" ?></textarea>
        </div>
        <div class="mb-3">
          <label for="affiliated-business-table-quantity" class="form-label fw-bold lh-sm">Dine-In Business Size</label>
          <div class="input-group">
            <input name="num_of_tables" id="affiliated-business-table-quantity" type="number" step="1" class="form-control" value="<?= isset($user) && $user->has_business ? esc($user->business->num_of_tables) : "" ?>">
            <span class="input-group-text bg-soft-gray">tables</span>
          </div>
        </div>
        <div class="mb-3">
          <label for="affiliated-business-subcription-status" class="form-label fw-bold lh-sm">Business Subscription Status</label>
          <select name="business_subscription_status" id="affiliated-business-subcription-status" class="form-select">
            <option value="active" <?= (isset($user) && $user->has_business && $user->business->business_is_archived) ? "" : "selected" ?>>active</option>
            <option value="archived" <?= (isset($user) && $user->has_business && $user->business->business_is_archived) ? "selected" : "" ?>>archived</option>
          </select>
        </div>
      </div>
      <?php if (!isset($user) || !$user->has_business) : ?>
        <button id="add-business-button" type="button" class="btn btn-success" onclick="toggleAffiliatedBusinessForm(true)">Create a Business for User</button>
        <button id="remove-business-button" type="button" class="btn btn-secondary d-none" onclick="toggleAffiliatedBusinessForm(false)">Remove Business from User</button>
      <?php endif; ?>
    </div>
  </form>
  <div class="card shadow w-mdc-50 w-100 p-3 d-flex flex-sm-row flex-column justify-content-between mt-3">
    <?php if (isset($user)) : ?>
      <div class="">
        <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#reset-password-modal">Reset User Password</button>
      </div>
    <?php endif; ?>
    <div class="d-flex flex-sm-row flex-column mt-sm-0 mt-1 justify-content-end gap-1 <?= isset($user) ? '' : 'w-100' ?>">
      <button class="btn bg-brown text-light" onclick="document.querySelector('#user-details-form').submit()"><?= isset($user) ? "Update" : "Create" ?> User</button>
      <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-primary">Cancel</a>
    </div>
  </div>
</div>
<?= $this->endSection() ?>