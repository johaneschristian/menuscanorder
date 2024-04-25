<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script src="<?= base_url('js/business/seatManagement.js') ?>" defer></script>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <title>Seat Management</title>
  </head>
  <script defer>
    document.addEventListener('DOMContentLoaded', () => fetch('www.google.com'));
  </script>
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
                    <path d="M5.5 4a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5zm1 7a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z"/>
                    <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                    <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                  </svg>
                </span>
                <span>Menu</span>
              </a>
              <a href="">
                <span class="icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                  </svg>
                </span>
                <span>Orders</span>
              </a>
              <a href="">
                <span class="icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tv-fill" viewBox="0 0 16 16">
                    <path d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5M2 2h12s2 0 2 2v6s0 2-2 2H2s-2 0-2-2V4s0-2 2-2"/>
                  </svg>
                </span>
                <span>Kitchen View</span>
              </a>
              <a href="">
                <span class="icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-kanban-fill" viewBox="0 0 16 16">
                    <path d="M2.5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm5 2h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1m-5 1a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1zm9-1h1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1"/>
                  </svg>
                </span>
                <span>Seat Management</span>
              </a>
              <a href="">
                <span class="icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                  </svg>
                </span>
                <span>Profile</span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-auto w-mdc-83 w-100">
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
                    <input type="text" class="form-control bg-soft-gray" name="search" placeholder="Search Table Number" value='<?= $searched_table_number === NULL ? "" : $searched_table_number?>'>
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
                  <?php if($searched_table_number === NULL): ?>
                    <?php for($tableNum = ($current_page-1) * 10; $tableNum < ($current_page-1) * 10 + 10 && $tableNum < $business->num_of_tables; $tableNum++): ?>
                      <tr>
                        <td id="table-num-<?= esc($tableNum+1) ?>"><?= esc($tableNum+1) ?></td>
                        <td>
                          <img id="table-num-<?= esc($tableNum+1) ?>-qr" src='<?= base_url("business/seat-management/generate-qr/{$business->business_id}/" . ($tableNum + 1)) ?>' style="width: 50px;" alt="" srcset="">
                        </td>
                        <td>
                          <button class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#qr-code-view-modal" onclick="toggleQRView(<?= esc($tableNum+1) ?>)"><i class="bi bi-eye-fill"></i></button>
                          <button class="btn btn-sm btn-danger mb-1" onclick="downloadQRImage(<?= esc($tableNum+1) ?>)"><i class="bi bi-download"></i></button>
                          <button class="btn btn-sm btn-warning mb-1" onclick="printQRImage(<?= esc($tableNum+1) ?>)"><i class="bi bi-printer"></i></button>
                        </td>
                      </tr>
                    <?php endfor; ?>
                  <?php else: ?>
                    <?php if($searched_table_number <= $business->num_of_tables): ?>
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
                  <?php endif; ?>
                </tbody>
              </table>
              <nav>
                <ul class="pagination justify-content-center">
                  <li class='page-item <?= $current_page === 1 ? "disabled" : "" ?>'>
                    <a class="page-link" href='<?= base_url("/business/seat-management/?page=1") ?>' tabindex="-1" aria-disabled="true">First</a>
                  </li>

                  <?php for($page = $current_page > $total_pages - 3 ? $total_pages - 6 : $current_page-3 ; $page < $current_page; $page++): ?>
                    <?php if($page > 0): ?>
                      <li class="page-item"><a class="page-link" href='<?= base_url("/business/seat-management/?page=$page") ?>'><?= esc($page) ?></a></li>
                    <?php endif; ?>
                  <?php endfor; ?>

                  <li class="page-item"><a class="page-link active" href="#"><?= esc($current_page) ?></a></li>

                  <?php for($page = $current_page+1; $current_page > 3 ? ($page < $current_page + 4) : ($page < 8); $page++): ?>
                    <?php if($page <= $total_pages): ?>
                      <li class="page-item"><a class="page-link" href='<?= base_url("/business/seat-management/?page=$page") ?>'><?= esc($page) ?></a></li>
                    <?php endif; ?>
                  <?php endfor; ?>
              
                  <li class='page-item <?= $current_page === $total_pages ? "disabled" : "" ?>'>
                    <a class="page-link" href="<?= base_url("/business/seat-management/?page=$total_pages") ?>">Last</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>