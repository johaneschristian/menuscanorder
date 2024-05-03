<?= $this->extend('base_templates/business-base-template') ?>

<?= $this->section('additional_css_js') ?>
<script src="<?= base_url('js/business/createEditMenu.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('title') ?>
<title>Menu Edit</title>
<?= $this->endSection() ?>

<?= $this->section('business_name') ?>
<?= esc($business_name) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if(!$is_create): ?>
  <div class="modal fade" id="delete-confirmation-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="<?= base_url('business/menu/delete') ?>" method="post">
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
  <div class="p-md-5 p-3 d-flex flex-column align-items-center">
    <h1 class="fw-bold"><?= $is_create ? "Create" : "Edit" ?> Menu</h1>
    <form id="menu-item-form" action="<?= !$is_create ? base_url("/business/menu/{$menu->menu_item_id}/edit") : base_url("/business/menu/create") ?>" method="post" enctype="multipart/form-data" class="w-100 d-flex flex-md-row flex-column justify-content-center gap-3">
      <div class="w-mdc-25 w-md-50 w-100">
        <div class="card shadow">
          <img src='<?= !$is_create && !is_null($menu->image_url) ? base_url("/business/menu/{$menu->menu_item_id}/image") : "https://theme-assets.getbento.com/sensei/7c1964e.sensei/assets/images/catering-item-placeholder-704x520.png" ?>' alt="" srcset="" id="menu-item-image-preview" class="rounded" style="min-height: 300px; max-height: 400px;">
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
              <option value="<?= esc($category->category_id) ?>" <?= !$is_create ? ($menu->category_id === $category->category_id ? "selected" : "") : "" ?>><?= esc($category->name) ?></option>
            <?php endforeach; ?>
            <option value="others" <?= !$is_create ? (is_null($menu->category_id) ? "selected" : "") : "" ?>>Others</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="" class="form-label fw-bold">Description</label>
          <textarea class="form-control bg-soft-gray" id="description" name="description" rows="3" placeholder="Enter description here"><?= !$is_create ? (is_null($menu->description) ? "" : esc($menu->description)) : "" ?></textarea>
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
<?= $this->endSection() ?>