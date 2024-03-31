<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script src="<?= base_url('js/business/categoryList.js') ?>" defer></script>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <title>Menu Page</title>
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
        <div class="container p-3">
          <div class="d-flex flex-md-row flex-column justify-content-center mb-3 gap-3 w-100">
            <div class="progress-board mt-3 w-mdc-25 w-100">
              <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                  <h5 class="card-title">New Order</h5>
                  <span class="card-text display-1">8</span>
                </div>
              </div>
            </div>
            <div class="progress-board mt-3 w-mdc-25 w-100">
              <div class="card text-dark bg-warning shadow-sm">
                <div class="card-body">
                  <h5 class="card-title">In-Progress</h5>
                  <span class="card-text display-1">11</span>
                </div>
              </div>
            </div>
            <div class="progress-board mt-3 w-mdc-25 w-100">
              <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                  <h5 class="card-title">Served</h5>
                  <span class="card-text display-1">273</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row d-flex justify-content-center gy-3">
            <div class="col-auto">
              <div
                class="card shadow-sm"
                style="min-height: 300px; max-width: 18rem"
              >
                <div
                  class="card-body d-flex flex-column justify-content-between align-items-center"
                >
                  <h5 class="card-title m-0">Table 001</h5>
                  <span class="badge rounded-pill bg-dark mt-2"
                    >Ordered on 03/03/2024 18:21</span
                  >
                  <span class="badge rounded-pill bg-danger mt-2">New Order</span>
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Nasi Goreng</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="collapse" id="note-order123">
                    Lorem Ipsum is simply dummy text of the printing and typesetting
                    industry. Lorem Ipsum has been the industry's standard dummy
                    text ever since the 1500s, when an unknown printer took a galley
                    of type and scrambled it to make a type specimen book. It has
                    survived not only five centuries, but also the leap into
                    electronic typesetting, remaining essentially unchanged. It was
                    popularised in the 1960s with the release of Letraset sheets
                    containing Lorem Ipsum passages, and more recently with desktop
                    publishing software like Aldus PageMaker including versions of
                    Lorem Ipsum.
                  </div>
                  <div>
                    <a href="#" class="btn btn-warning mt-3">Mark as In Progress</a>
                    <button
                      type="button"
                      class="btn btn-outline-primary mt-3"
                      data-bs-toggle="collapse"
                      data-bs-target="#note-order123"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-pencil-square"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                        />
                        <path
                          fill-rule="evenodd"
                          d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
                        />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <div
                class="card shadow-sm"
                style="min-height: 300px; max-width: 18rem"
              >
                <div
                  class="card-body d-flex flex-column justify-content-between align-items-center"
                >
                  <h5 class="card-title m-0">Table 001</h5>
                  <span class="badge rounded-pill bg-dark"
                    >Ordered on 03/03/2024 18:21</span
                  >
                  <span class="badge rounded-pill bg-warning text-dark"
                    >In Progress</span
                  >
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Nasi Goreng</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                  <a href="#" class="btn btn-success mt-3">Mark as Served</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <div
                class="card shadow-sm"
                style="min-height: 300px; max-width: 18rem"
              >
                <div
                  class="card-body d-flex flex-column justify-content-between align-items-center"
                >
                  <h5 class="card-title m-0">Table 001</h5>
                  <span class="badge rounded-pill bg-dark"
                    >Ordered on 03/03/2024 18:21</span
                  >
                  <span class="badge rounded-pill bg-danger">New Order</span>
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Nasi Goreng</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                  <a href="#" class="btn btn-warning mt-3">Mark as In Progress</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <div
                class="card shadow-sm"
                style="min-height: 300px; max-width: 18rem"
              >
                <div
                  class="card-body d-flex flex-column justify-content-between align-items-center"
                >
                  <h5 class="card-title m-0">Table 001</h5>
                  <span class="badge rounded-pill bg-dark"
                    >Ordered on 03/03/2024 18:21</span
                  >
                  <span class="badge rounded-pill bg-danger">New Order</span>
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Nasi Goreng</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                  <a href="#" class="btn btn-warning mt-3">Mark as In Progress</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <div
                class="card shadow-sm"
                style="min-height: 300px; max-width: 18rem; width: 14rem"
              >
                <div
                  class="card-body d-flex flex-column justify-content-between align-items-center"
                >
                  <h5 class="card-title m-0">Table 001</h5>
                  <span class="badge rounded-pill bg-dark"
                    >Ordered on 03/03/2024 18:21</span
                  >
                  <span class="badge rounded-pill bg-danger">New Order</span>
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Nasi Goreng</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                  <a href="#" class="btn btn-warning mt-3">Mark as In Progress</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <div
                class="card shadow-sm"
                style="min-height: 300px; max-width: 18rem"
              >
                <div
                  class="card-body d-flex flex-column justify-content-between align-items-center"
                >
                  <h5 class="card-title m-0">Table 001</h5>
                  <span class="badge rounded-pill bg-dark"
                    >Ordered on 03/03/2024 18:21</span
                  >
                  <span class="badge rounded-pill bg-danger">New Order</span>
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Nasi Goreng</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                  <a href="#" class="btn btn-warning mt-3">Mark as In Progress</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <div
                class="card shadow-sm"
                style="min-height: 300px; max-width: 18rem"
              >
                <div
                  class="card-body d-flex flex-column justify-content-between align-items-center"
                >
                  <h5 class="card-title m-0">Table 001</h5>
                  <span class="badge rounded-pill bg-dark"
                    >Ordered on 03/03/2024 18:21</span
                  >
                  <span class="badge rounded-pill bg-danger">New Order</span>
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Nasi Goreng</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                  <a href="#" class="btn btn-warning">Mark as In Progress</a>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <div
                class="card shadow-sm"
                style="min-height: 300px; max-width: 18rem"
              >
                <div
                  class="card-body d-flex flex-column justify-content-between align-items-center"
                >
                  <h5 class="card-title m-0">Table 001</h5>
                  <span class="badge rounded-pill bg-dark"
                    >Ordered on 03/03/2024 18:21</span
                  >
                  <span class="badge rounded-pill bg-danger">New Order</span>
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Nasi Goreng</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                  <a href="#" class="btn btn-warning mt-3">Mark as In Progress</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>