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
    <title>Category Page</title>
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
        <div class="modal fade" id="create-category-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form action="" method="post">
              <div class="modal-content">
                <div class="modal-body">
                  <p class="fw-bold fs-5">Create Category</p>
                  <label for="edited-category-name" class="form-label">Category Name</label>
                  <div class="input-group mb-3">
                    <input name="category_name" type="text" class="form-control" id="created-category-name" name="category_name" aria-describedby="basic-addon3" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn bg-brown text-light">Save</button>
                  <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal fade" id="edit-category-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form action="<?= base_url('business/categories/update') ?>" method="post">
              <div class="modal-content">
                <div class="modal-body">
                  <p class="fw-bold fs-5">Edit Category</p>
                  <label for="edited-category-id" class="form-label">Category ID</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control page-item bg-disabled" id="edited-category-id" name="category_id" aria-describedby="basic-addon3" readonly>
                  </div>
                  <label for="edited-category-name" class="form-label">Category Name</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" id="edited-category-name" name="category_name" aria-describedby="basic-addon3" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal fade" id="delete-confirmation-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form action="" method="post">
              <div class="modal-content">
                <div class="modal-body">
                  <p class="fw-bold fs-5">Are you sure you want to delete <span id="deleted-category-name"></span>?</p>
                  <input type="hidden" name="category_id" id="deleted-category-id">
                  <span class="mt-3">This will assign all current menu items under the category to others.</span>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-danger">Yes, I want to delete this category.</button>
                  <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="p-5">
          <h2 class="mb-4">My Categories</h2>
          <div class="card shadow mb-3 p-3 row d-flex flex-md-row flex-column justify-content-between align-items-center">
            <div class="col-md-6">
              <form action="" method="get">
                <div class="input-group mb-md-0 mb-3 me-3 p-0">
                  <input type="text" class="form-control bg-soft-gray" name="search" placeholder="Search Category Name" value="<?= esc($search) ?>">
                  <button class="btn bg-brown text-white" type="submit" id="search-button">Search</button>
                </div>
              </form>
            </div>
            <div class="col-md-6 text-md-end text-center">
              <button class="btn bg-brown text-white" type="button" id="search-button" data-bs-toggle="modal" data-bs-target="#create-category-modal">Add Category</button>
            </div>
          </div>
          <div class="card p-3 shadow">
            <table class="table table-hover align-middle">
              <thead class="align-middle">
                <tr>
                  <th>No</th>
                  <th>Category Name</th>
                  <th>Number of Items</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($categories as $categoryIndex => $category): ?>
                  <tr>
                    <td><?= esc($categoryIndex + 1) ?></td>
                    <td id="menu-category-<?= esc($category->category_id) ?>"><?= esc($category->name) ?></td>
                    <td><?= esc($category->menu_count) ?></td>
                    <td>
                      <button class="btn btn-sm btn-primary me-2 mb-1" data-bs-toggle="modal" data-bs-target="#edit-category-modal" onclick="setCategoryEditModal(`<?= esc($category->category_id) ?>`)"><i class="bi bi-pencil-fill"></i></button>
                      <button class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#delete-confirmation-modal" onclick="setCategoryDeleteModal(`<?= esc($category->category_id) ?>`)"><i class="bi bi-dash-circle-fill" ></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <nav>
              <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active" aria-current="page">
                  <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#">Next</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>