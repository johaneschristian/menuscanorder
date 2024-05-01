<?= $this->extend('base_templates/customer-base-template') ?>

<?= $this->section('title') ?>
<title>All Users</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-5 d-flex flex-column align-items-center">
	<div class="card shadow p-3 w-100 d-flex flex-md-row flex-column justify-content-between align-items-center">
		<form action="" method="get" class="w-mdc-50 w-75">
			<div class="input-group mb-md-0 mb-3 me-3 p-0">
				<input
					type="text"
					class="form-control bg-soft-gray"
					name="search"
					placeholder="Enter your search"
					value="<?= esc($search) ?>"
				/>
				<button
					class="btn bg-brown text-white"
					type="submit"
					id="search-button"
				>
					Search
				</button>
			</div>
		</form>
		<a href='<?= base_url("/admin/users/create") ?>' class="btn bg-brown text-white" style="width: fit-content;">Add User</a>
	</div>
	<div class="card mt-4 p-3 shadow w-100 overflow-x-auto">
		<table class="table table-hover align-middle">
			<thead class="align-middle">
				<tr>
					<th>No</th>
					<th>Email</th>
					<th>Name</th>
					<th>Type</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($users as $key => $user): ?>
				<tr>
					<td><?= esc($key+1) ?></td>
					<td id="user-user_id_1-email"><?= esc($user->email) ?></td>
					<td id="user-user_id_1-name"><?= esc($user->name) ?></td>
					<td><span id="user-user_id_1-type"><?= $user->is_admin ? "admin" : ($user->has_business ? "business" : "customer") ?></span></td>
					<td>
						<span
							id="user-user_id_1-status"
							class="badge rounded-pill bg-<?= $user->is_archived ? "warning text-dark" : "success" ?>"
							><?= $user->is_archived ? "archived" : "active"?></span
						>
					</td>
					<td>
						<a href='<?= base_url("/admin/users/{$user->id}") ?>' class="btn btn-sm btn-primary me-2 mb-1">
							<i class="bi bi-eye-fill"></i>
						</a>
						<a href='<?= base_url("/admin/users/{$user->id}/edit") ?>'
							class="btn btn-sm btn-danger mb-1"
						>
							<i class="bi bi-pencil"></i>
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<?= $pager->links() ?>
	</div>
</div>
<?= $this->endSection() ?>
