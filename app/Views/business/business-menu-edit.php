<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script src="<?= base_url('js/business/createEditMenu.js') ?>"></script>
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
        <?php if(!$is_create): ?>
          <div class="modal fade" id="delete-confirmation-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <form action="" method="post">
                <div class="modal-content">
                  <div class="modal-body">
                    <p class="fw-bold fs-5">Are you sure you want to delete this menu item?</p>
                    <input type="hidden" name="menu_item_id" id="deleted-menu-item-id" value="<?= esc($menu->menu_item_id) ?>">
                    <span class="mt-3">This menu item will appear as <span class="fst-italic">deleted item</span> in the order history.</span>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Yes, delete this menu item.</button>
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        <?php endif; ?>
        <div class="p-5 d-flex flex-column align-items-center">
          <h1 class="fw-bold"><?= $is_create ? "Create" : "Edit" ?> Menu</h1>
          <form id="menu-item-form" action="<?= !$is_create ? base_url("/business/menu/{$menu->menu_item_id}/edit") : base_url("/business/menu/create") ?>" method="post" enctype="multipart/form-data" class="w-100 d-flex flex-md-row flex-column justify-content-center gap-3">
            <div class="w-mdc-25 w-100">
              <div class="card shadow">
                <img src='<?= !$is_create && $menu->image_url !== NULL ? base_url("/business/menu/{$menu->menu_item_id}/image") : "https://theme-assets.getbento.com/sensei/7c1964e.sensei/assets/images/catering-item-placeholder-704x520.png" ?>' alt="" srcset="" id="menu-item-image-preview" class="rounded" style="min-height: 300px; max-height: 400px;">
              </div>
              <input type="file" name="product_image" id="menu-item-image" style="display: none;" accept=".jpg,.png,.jpeg" onchange="displayUploadedImage()">
              <button type="button" class="btn btn-dark w-100 mt-3" onclick="document.querySelector(`#menu-item-image`).click()">Upload an Image</button>
            </div>
            <card class="card shadow w-mdc-50 w-100 p-3">
              <div class="mb-3">
                <label for="" class="form-label fw-bold">Product Name</label>
                <input type="text" name="name" class="form-control bg-soft-gray" placeholder="Enter menu name here" value="<?= !$is_create ? esc($menu->name) : "" ?>">
              </div>
              <label for="" class="form-label fw-bold">Price</label>
              <div class="input-group mb-3">
                <span class="input-group-text">AUD</span>
                <input type="number" step="any" name="price" class="form-control bg-soft-gray" placeholder="Enter price here" value="<?= !$is_create ? number_format(esc($menu->price), 2, '.') : ""?>">
              </div>
              <div class="mb-3">
                <label for="" class="form-label fw-bold lh-sm">Category</label>
                <select name="category_id" class="form-select bg-soft-gray">
                  <?php foreach ($categories as $category): ?>
                    <option value="<?= esc($category['category_id']) ?>" <?= !$is_create ? ($menu->category_id === $category['category_id'] ? "selected" : "") : "" ?>><?= esc($category['name']) ?></option>
                  <?php endforeach; ?>
                  <option value="others" <?= !$is_create ? ($menu->category_id === NULL ? "selected" : "") : "" ?>>Others</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label fw-bold">Description</label>
                <textarea class="form-control bg-soft-gray" id="description" name="description" rows="3" placeholder="Enter description here"><?= !$is_create ? ($menu->description === NULL ? "" : esc($menu->description)) : "" ?></textarea>
              </div>
              <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="is_available" id="is_available" <?= !$is_create ? ($menu->is_available ? "checked" : "") : "checked" ?>>
                <label for="" class="form-label">Product is available</label>
              </div>
              <div class="d-flex flex-row justify-content-end gap-1">
                <button type="submit" class="btn btn-<?= !$is_create ? 'warning': 'success' ?>"><?= !$is_create ? "Update " : "Create " ?>My Product</button>
                <?php if(!$is_create): ?>
                  <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-confirmation-modal">Delete this Product</button>
                <?php endif; ?>
              </div>
            </card>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>