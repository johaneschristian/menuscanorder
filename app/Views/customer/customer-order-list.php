<?= $this->extend('base_templates/customer-base-template') ?>

<?= $this->section('title') ?>
<title>Customer Order List</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-5">
  <form class="card shadow mb-3 ps-3 pe-3 pb-3 row d-flex flex-row justify-content-between align-items-center">
    <div class="input-group mt-3 me-3 w-mdc-50 w-100">
      <input name="business_name" type="text" class="form-control bg-soft-gray" placeholder="Search Business Name" value="<?= esc($search) ?>">
      <button class="btn bg-brown text-white" type="submit" id="search-button">Search</button>
    </div>
    <div class="input-group mt-3 w-mdc-25 w-100">
      <select name="status_id" class="form-select bg-soft-gray" onchange="this.form.submit()">
        <option value="" selected>All Orders</option>
        <?php foreach ($statuses as $status) : ?>
          <option value="<?= esc($status->id) ?>" <?= $status->id === $selected_status_id ? "selected" : "" ?>><?= esc($status->status) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </form>
  <?php if (empty($orders) && (!empty($selected_status_id) || !empty($search))) : ?>
    <div class="w-100 h5 text-center mt-5">No orders matches your query.</div>
  <?php elseif (empty($orders)) : ?>
    <div class="w-100 h5 text-center mt-5">You currently do not have orders.</div>
  <?php else: ?>
    <div class="card d-md-block d-none w-100 p-3 shadow bg-brown text-white">
      <div class="row w-100">
        <div class="col-4 fw-bold">
          Order ID
        </div>
        <div class="col-2 fw-bold">
          Business Name
        </div>
        <div class="col-2 fw-bold">
          Status
        </div>
        <div class="col-2 fw-bold">
          Duration
        </div>
        <div class="col-2 fw-bold">
          Amount
        </div>
      </div>
    </div>
    <?php foreach ($orders as $order) : ?>
      <div class="card clickable w-100 p-3 mt-3 shadow-sm" onclick='window.location.href=`<?= base_url("/customer/orders/detail/{$order->order_id}") ?>`'>
        <div class="row fw-bold d-flex flex-md-row flex-column align-items-md-center align-items-start">
          <div class="col-md-4">
            <div class="row">
              <span><?= esc($order->order_id) ?></span>
            </div>
            <div class="row">
              <div class="sub-text text-muted d-md-block d-none">View Order</div>
              <div class="sub-text text-muted d-md-none">Order ID</div>
            </div>
          </div>
          <div class="col-md-2  mt-md-0 mt-2 d-flex flex-md-column flex-row-reverse justify-content-end">
            <div class="row">
              <span class=" d-block"><?= esc($order->business_name) ?></span>
              <div class="sub-text text-muted d-md-none">Business Name</div>
            </div>
          </div>
          <div class="col-md-2  p-md-0 mt-md-0 mt-2">
            <span class="badge rounded-pill bg-warning text-dark"><?= esc($order->status_name) ?></span>
          </div>
          <div class="col-md-2  p-md-0 mt-md-0 mt-2">
            <div class="row">
              <span class="d-md-block d-none"><?= esc($order->duration) ?></span>
              <span class=" d-md-none d-block"><?= esc($order->duration) ?>, <?= esc($order->start_date) ?></span>
              <div class="sub-text text-muted d-md-none">Dine-in Duration</div>
            </div>
            <div class="row">
              <span class="sub-text text-muted d-md-block d-none"><?= esc($order->start_date) ?></span>
            </div>
          </div>
          <div class="col-md-2  p-md-0 mt-md-0 mt-2">
            <span>AUD<?= esc(number_format($order->total_price, 2, '.')) ?></span>
            <div class="sub-text text-muted d-md-none">Total Cost</div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    <?= $pager->links() ?>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>