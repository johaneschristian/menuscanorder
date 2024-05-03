<?= $this->extend('base_templates/business-base-template') ?>

<?= $this->section('additional_css_js') ?>
<script src="<?= base_url('js/business/seatManagement.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('title') ?>
<title>Seat Management</title>
<?= $this->endSection() ?>

<?= $this->section('business_name') ?>
<?= esc($business_name) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="modal fade" id="qr-code-view-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" method="post">
      <div class="modal-content">
        <div class="modal-body text-center">
          <p class="h3 fw-bold">QR Code for Table <span id="qr-view-table-number">1</span></p>
          <img id="qr-view-qr-code" src="" class="w-50" alt="" srcset="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="downloadQRImageFromModal()">Download</button>
          <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="bulk-edit-table-number-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" method="post">
      <div class="modal-content">
        <form action="" method="get">
          <div class="modal-body">
            <p class="h5 fw-bold">Update Table Quantity</p>
            <label for="table-quantity" class="form-label">New Quantity</label>
            <div class="input-group mb-3">
              <input type="number" step="1" class="form-control" id="table-quantity" name="new_table_quantity" aria-describedby="basic-addon3" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </form>
  </div>
</div>
<div class="p-5">
  <h2 class="mb-4">Seat Management</h2>
  <div class=" d-flex flex-column align-items-center">

    <div class="w-100 card shadow mb-3 p-3 row d-flex flex-md-row flex-column justify-content-between align-items-center">
      <div class="col-md-6">
        <form action="" method="get">
          <div class="input-group mb-md-0 mb-3 me-3 p-0">
            <input type="text" class="form-control bg-soft-gray" name="search" placeholder="Search Table Number" value='<?= is_null($searched_table_number) ? "" : $searched_table_number ?>'>
            <button class="btn bg-brown text-white" type="submit" id="search-button">Search</button>
          </div>
        </form>
      </div>
      <div class="col-md-6 text-md-end text-center">
        <button class="btn bg-brown text-white" type="button" id="search-button" data-bs-toggle="modal" data-bs-target="#bulk-edit-table-number-modal">Bulk Edit Table Number</button>
      </div>
    </div>
    <div class="card p-3 shadow w-mdc-75 w-100">
      <table class="table table-hover align-middle text-center">
        <thead class="align-middle">
          <tr>
            <th>Table Number</th>
            <th>QR Code</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (is_null($searched_table_number)) : ?>
            <?php for ($tableNum = ($current_page - 1) * 10; $tableNum < ($current_page - 1) * 10 + 10 && $tableNum < $business->num_of_tables; $tableNum++) : ?>
              <tr>
                <td id="table-num-<?= esc($tableNum + 1) ?>"><?= esc($tableNum + 1) ?></td>
                <td>
                  <img id="table-num-<?= esc($tableNum + 1) ?>-qr" src='<?= base_url("business/seat-management/generate-qr/{$business->business_id}/" . ($tableNum + 1)) ?>' style="width: 50px;" alt="" srcset="">
                </td>
                <td>
                  <button class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#qr-code-view-modal" onclick="toggleQRView(<?= esc($tableNum + 1) ?>)"><i class="bi bi-eye-fill"></i></button>
                  <button class="btn btn-sm btn-danger mb-1" onclick="downloadQRImage(<?= esc($tableNum + 1) ?>)"><i class="bi bi-download"></i></button>
                  <button class="btn btn-sm btn-warning mb-1" onclick="printQRImage(<?= esc($tableNum + 1) ?>)"><i class="bi bi-printer"></i></button>
                </td>
              </tr>
            <?php endfor; ?>
          <?php elseif ($searched_table_number <= $business->num_of_tables) : ?>
            <tr>
              <td id="table-num-$searched_table_number"><?= esc($searched_table_number) ?></td>
              <td>
                <img id="table-num-$searched_table_number-qr" src="<?= base_url('images/business/dummy-qr-code.png') ?>" style="width: 50px;" alt="" srcset="">
              </td>
              <td>
                <button class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#qr-code-view-modal" onclick="toggleQRView(1)"><i class="bi bi-eye-fill"></i></button>
                <button class="btn btn-sm btn-danger mb-1" onclick="downloadQRImage(1)"><i class="bi bi-download"></i></button>
                <button class="btn btn-sm btn-warning mb-1" onclick="printQRImage(1)"><i class="bi bi-printer"></i></button>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php if (is_null($searched_table_number)) : ?>
        <nav>
          <ul class="pagination justify-content-center">
            <li class='page-item <?= $current_page === 1 ? "disabled" : "" ?>'>
              <a class="page-link" href="<?= base_url('/business/seat-management/?page=1') ?>" tabindex="-1" aria-disabled="true">First</a>
            </li>

            <?php for ($page = $current_page - 3; $page < $current_page; $page++) : ?>
              <?php if ($page > 0) : ?>
                <li class="page-item"><a class="page-link" href='<?= base_url("/business/seat-management/?page=$page") ?>'><?= esc($page) ?></a></li>
              <?php endif; ?>
            <?php endfor; ?>

            <li class="page-item"><a class="page-link active" href="#"><?= esc($current_page) ?></a></li>

            <?php for ($page = $current_page + 1; $page < $current_page + 4; $page++) : ?>
              <?php if ($page <= $total_pages) : ?>
                <li class="page-item"><a class="page-link" href='<?= base_url("/business/seat-management/?page=$page") ?>'><?= esc($page) ?></a></li>
              <?php endif; ?>
            <?php endfor; ?>

            <li class='page-item <?= $current_page === $total_pages ? "disabled" : "" ?>'>
              <a class="page-link" href="<?= base_url("/business/seat-management/?page=$total_pages") ?>">Last</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>