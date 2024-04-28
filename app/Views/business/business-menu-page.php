<?= $this->extend('base_templates/business-base-template') ?>

<?= $this->section('additional_css_js') ?>
<script src="<?= base_url('js/business/menuPage.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('title') ?>
<title>My Menus</title>
<?= $this->endSection() ?>

<?= $this->section('business_name') ?>
Warteg Bahari Restaurant
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="modal fade" id="delete-confirmation-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" method="post">
      <div class="modal-content">
        <div class="modal-body">
          <p class="fw-bold fs-5">Are you sure you want to delete <span id="deleted-menu-item-name"></span> menu item?</p>
          <input type="hidden" name="menu_item_id" id="deleted-menu-item-id">
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
<div class="container p-3">
  <div class="card shadow mb-3 ps-3 pe-3 pb-3 row d-flex flex-md-row flex-column justify-content-between align-items-center">
    <form action="" method="get" class="w-mdc-75 d-flex flex-md-row flex-column flex-sm-column">
      <div class="input-group mt-3 me-3 p-0 w-mdc-75 w-100">
        <input type="text" class="form-control bg-soft-gray" name="menu_name" placeholder="Search Menu Name" value="<?= esc($search) ?>">
        <button class="btn bg-brown text-white" type="submit" id="search-button">Search</button>
      </div>
      <select class="form-select mt-3 w-mdc-25 w-100 bg-soft-gray" name="category_id" onchange="this.form.submit()">
        <option value="all" <?= $category_id === "all" ? "selected" : "" ?>>All Category</option>
        <?php foreach ($categories as $category) : ?>
          <option value="<?= esc($category->category_id) ?>" <?= $category_id === $category->category_id ? "selected" : "" ?>><?= esc($category->name) ?></option>
        <?php endforeach; ?>
        <option value="others" <?= $category_id === "others" ? "selected" : "" ?>>Others</option>
      </select>
    </form>
    <div class="w-mdc-25 mt-3 d-flex flex-row justify-content-md-end justify-content-center gap-1">
      <button type="button" class="btn bg-brown text-white" style="width: fit-content;" onclick="window.location.href = `<?= base_url('business/categories/') ?>`">Modify Category</button>
      <button type="button" class="btn bg-brown text-white" style="width: fit-content;" onclick="window.location.href = `<?= base_url('business/menu/create') ?>`">Add Menu</button>
    </div>
  </div>
  <div class="row d-flex justify-content-center gy-3">
    <?php foreach ($menus as $menu) : ?>
      <div class="col-auto">
        <div class="card shadow-sm" style="width: 18rem; height: 500px;">
          <img src='<?= $menu->image_url ? base_url("/business/menu/{$menu->menu_item_id}/image") : "" ?>' class="card-img-top h-50" style="object-fit: cover;" alt="" onerror="this.src='https://theme-assets.getbento.com/sensei/7c1964e.sensei/assets/images/catering-item-placeholder-704x520.png'">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="">
              <h5 id=<?= "menu-{$menu->menu_item_id}-name" ?> class="card-title m-0"><?= esc($menu->name) ?></h5>
              <div>
                <span class="badge rounded-pill bg-<?= esc($menu->is_available ? "success" : "danger") ?>"><?= esc($menu->is_available ? "Available" : "Not Available") ?></span>
                <span class="badge rounded-pill bg-dark">AUD<?= esc(number_format($menu->price, 2, '.')) ?></span>
              </div>
              <p class="card-text trunc-4 mt-3"><?= esc($menu->description) ?></p>
            </div>
            <div class="d-flex flex-row justify-content-end gap-1">
              <a href="#" class="btn btn-warning" onclick='window.location.href = `<?= base_url("business/menu/{$menu->menu_item_id}/edit") ?>`'>Edit</a>
              <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirmation-modal" onclick='setDeletionModal("<?= esc($menu->menu_item_id) ?>")'>Delete</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>

<?= $pager->links() ?>
<?= $this->endSection() ?>