<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <title>Order Details</title>
</head>
<body>
  <div class="row flex-md-row flex-column">
    <div class="col w-mdc-17 w-100">
      <div class="sidebar container-fluid text-white p-3">
        <div class="navbar d-flex flex-row justify-content-between">
          <h4 class="logo mt-3">Warteg Bahari Restaurant</h4>
          <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-collapse" aria-controls="navbarNav" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="sidebar-collapse">
          <img src="<?= base_url('images/business/menuscanorder.png') ?>" class="w-mdc-100 w-100">
          <div class="sidebar-links">
            <a href="">
              <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-album" viewBox="0 0 16 16">
                  <path d="M5.5 4a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5zm1 7a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z" />
                  <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                  <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                </svg>
              </span>
              <span>Menu</span>
            </a>
            <a href="">
              <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                </svg>
              </span>
              <span>Orders</span>
            </a>
            <a href="">
              <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tv-fill" viewBox="0 0 16 16">
                  <path d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5M2 2h12s2 0 2 2v6s0 2-2 2H2s-2 0-2-2V4s0-2 2-2" />
                </svg>
              </span>
              <span>Kitchen View</span>
            </a>
            <a href="">
              <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-kanban-fill" viewBox="0 0 16 16">
                  <path d="M2.5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm5 2h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1m-5 1a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1zm9-1h1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1" />
                </svg>
              </span>
              <span>Seat Management</span>
            </a>
            <a href="">
              <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                </svg>
              </span>
              <span>Business Profile</span>
            </a>
            <a href="">
              <i class="bi bi-box-arrow-left"></i>
              <span>Go Back to Customer App</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-auto w-mdc-83 w-100">
      <div class="modal fade" id="confirmationModal" data-bs-keyboard="false" tabindex="-1" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form action="<?= base_url('/business/orders/complete') ?>" method="post" class="modal-content">
            <div class="modal-header">
              <span class="modal-title fs-5 fw-bold mt-3">Completion Confirmation</span>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="order_id" value="<?= esc($order->order_id) ?>">
              Are you sure you want to complete this order?
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Yes, I would like to complete the order.</button>
              <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
      <div class="container mt-5 w-100">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url("/business/orders") ?>">Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Details</li>
          </ol>
        </nav>
        <div class="card shadow p-md-5 p-3">
          <span class="h2 fw-bold">Order Details</span>
          <table class="table w-mdc-50 w-100 h6 align-middle">
            <tr>
              <td>Order ID</td>
              <td>: <?= esc($order->order_id) ?></td>
            </tr>
            <tr>
              <td>Order Time</td>
              <td>: <?= esc($order->formatted_creation_time) ?></td>
            </tr>
            <tr>
              <td>Business Name</td>
              <td>: <?= esc($order->business_name) ?></td>
            </tr>
            <tr>
              <td>Order Table</td>
              <td>: <?= esc($order->table_number) ?></td>
            </tr>
            <tr>
              <td>Order Status</td>
              <td>: <?= esc($order->status_name) ?></td>
            </tr>
          </table>
          <p class="h4 fw-bold mt-3 mb-3">Order Summary</p>
          <table class="table table-hover">
            <thead class="align-middle">
              <tr>
                <th>Number</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Item Price (AUD)</th>
                <th>Subtotal (AUD)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($order->order_summary as $index => $order_item_summary) : ?>
                <tr>
                  <td><?= esc($index + 1) ?></td>
                  <td class="d-flex flex-column">
                    <span><?= esc($order_item_summary['menu_item_name']) ?></span>
                    <?php foreach ($order_item_summary['notes'] as $notes) : ?>
                      <span class="small text-secondary">- <?= esc($notes) ?></span>
                    <?php endforeach; ?>
                  </td>
                  <td><?= esc($order_item_summary['num_of_items']) ?></td>
                  <td><?= esc(number_format($order_item_summary['price_when_bought'], 2, '.')) ?></td>
                  <td><?= esc(number_format($order_item_summary['subtotal'], 2, '.')) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="d-flex justify-content-end align-items-end">
            <h6 class="me-1 h6">Order Total:</h6>
            <h4 class="fw-bold"><?= esc(number_format($order->total_price, 2, '.')) ?> (AUD)</h4>
          </div>
          <div class="d-flex justify-content-end align-items-end">
            <div class="d-flex justify-content-end align-items-end mt-3">
              <?php if($order->status_name !== "Completed"): ?>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#confirmationModal">Complete Order</button>
              <?php endif; ?>
              <button type="button" class="btn btn-outline-danger" onclick='window.location.href=`<?= base_url("/business/orders") ?>`'>Cancel</button>
            </div>
          </div>
        </div>
        <div class="card shadow p-md-5 p-3 mt-3 mb-3">
          <span class="h4 fw-bold">Order Item Details</span>
          <div class="card d-md-block d-none w-100 p-3 mt-2 shadow-sm bg-brown text-white">
            <div class="row w-100">
              <div class="col-1 fw-bold table-header-card">
                No
              </div>
              <div class="col-4 fw-bold table-header-card">
                Item
              </div>
              <div class="col-2 fw-bold table-header-card">
                Quantity
              </div>
              <div class="col-3 fw-bold table-header-card">
                Time Ordered
              </div>
              <div class="col-2 fw-bold table-header-card">
                Status
              </div>
            </div>
          </div>
          <?php foreach ($order->order_items as $index => $order_item) : ?>
            <div class="card w-100 p-3 mt-2 shadow-sm">
              <div class="row d-flex flex-md-row flex-column align-items-md-center align-items-start">
                <div class="col-md-1 d-flex flex-md-column flex-column-reverse">
                  <div class="row">
                    <div class="fw-bold"><?= esc($index + 1) ?></div>
                  </div>
                  <div class="row">
                    <div class="sub-text text-muted d-md-none">No.</div>
                  </div>
                </div>
                <div class="col-md-4 mt-md-0 mt-2 d-flex flex-column justify-content-end">
                  <div class="row">
                    <span class="fw-bold d-md-block d-none"><?= esc($order_item->menu_item_name) ?></span>
                  </div>
                  <div class="row d-flex flex-md-column flex-column-reverse">
                    <div>
                      <span class="fw-bold d-md-none d-block"><?= esc($order_item->menu_item_name) ?></span>
                      <?php if ($order_item->notes !== NULL) : ?>
                        <span class="small text-secondary">- <?= esc($order_item->notes) ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="sub-text text-muted d-md-none w-auto">Item</div>
                  </div>
                </div>
                <div class="col-md-2 fw-bold p-md-0 mt-md-0 mt-2">
                  <span class="fw-bold d-md-block d-none"><?= esc($order_item->num_of_items) ?></span>
                </div>
                <div class="col-md-3 fw-bold p-md-0 mt-md-0 mt-2">
                  <div class="row">
                    <span class="d-md-block d-none"><?= esc($order_item->formatted_item_order_time) ?></span>
                    <div class="sub-text text-muted d-md-none">Time Ordered</div>
                    <span class="fw-bold d-md-none d-block"><?= esc($order_item->formatted_item_order_time) ?>, <?= esc($order_item->formatted_item_order_date) ?></span>
                  </div>
                  <div class="row">
                    <span class="sub-text text-muted d-md-block d-none"><?= esc($order_item->formatted_item_order_date) ?></span>
                  </div>
                </div>
                <div class="col-md-2 fw-bold p-md-0 mt-md-0 mt-2">
                  <div class="sub-text text-muted d-md-none">Status</div>
                  <span class="badge rounded-pill bg-warning text-dark text-wrap"><?= esc($order_item->status_name) ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>