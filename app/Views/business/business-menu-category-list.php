<?= $this->extend('base_templates/business-base-template') ?>

<?= $this->section('additional_css_js') ?>
<script src="<?= base_url('js/business/categoryList.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('title') ?>
<title>My Categories</title>
<?= $this->endSection() ?>

<?= $this->section('business_name') ?>
<?= esc($business_name) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="modal fade" id="create-category-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" method="post">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <div class="modal-content">
        <div class="modal-body">
          <p class="fw-bold fs-5">Create Category</p>
          <label for="edited-category-name" class="form-label">Category Name</label>
          <div class="input-group mb-3">
            <input name="name" type="text" class="form-control" id="created-category-name" name="name" aria-describedby="basic-addon3" required>
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
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <div class="modal-content">
        <div class="modal-body">
          <p class="fw-bold fs-5">Edit Category</p>
          <label for="edited-category-id" class="form-label">Category ID</label>
          <div class="input-group mb-3">
            <input type="text" class="form-control page-item bg-disabled" id="edited-category-id" name="category_id" aria-describedby="basic-addon3" readonly>
          </div>
          <label for="edited-category-name" class="form-label">Category Name</label>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="edited-category-name" name="name" aria-describedby="basic-addon3" required>
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
    <form action="<?= base_url('business/categories/delete') ?>" method="post">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
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
        <?php foreach ($categories as $categoryIndex => $category) : ?>
          <tr>
            <td><?= $categoryIndex + 1 ?></td>
            <td id="menu-category-<?= esc($category->category_id) ?>"><?= esc($category->name) ?></td>
            <td><?= esc($category->menu_count) ?></td>
            <td>
              <button class="btn btn-sm btn-primary me-2 mb-1" data-bs-toggle="modal" data-bs-target="#edit-category-modal" onclick="setCategoryEditModal(`<?= esc($category->category_id) ?>`)"><i class="bi bi-pencil-fill"></i></button>
              <button class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#delete-confirmation-modal" onclick="setCategoryDeleteModal(`<?= esc($category->category_id) ?>`)"><i class="bi bi-dash-circle-fill"></i></button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links() ?>
  </div>
</div>
<?= $this->endSection() ?>
