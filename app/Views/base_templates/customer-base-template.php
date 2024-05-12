<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
  <script src="<?= base_url('js/helper.js') ?>" defer></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>" />
  <?= $this->renderSection('additional_css_js') ?>
  <?= $this->renderSection('title') ?>
  <script>
    var BASE_URL = "<?= base_url('') ?>";
  </script>
</head>

<body class="d-flex flex-column min-vh-100">
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-brown">
      <div class="container">
        <a class="navbar-brand" href="<?= base_url('') ?>">MenuScanOrder</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <?php if (is_null(auth()->user())) : ?>
              <li class="nav-item">
                <a class="nav-link <?= str_contains(service('request')->getUri()->getPath(), "login") ? "active" : "" ?>" href="<?= base_url('login') ?>">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= str_contains(service('request')->getUri()->getPath(), "register") ? "active" : "" ?>" href="<?= base_url('register') ?>">Register</a>
              </li>
            <?php else : ?>
              <li class="nav-item">
                <a class="nav-link <?= str_contains(service('request')->getUri()->getPath(), "orders") ? "active" : "" ?>" href="<?= base_url('customer/orders') ?>">Orders</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= str_contains(service('request')->getUri()->getPath(), "business") ? "active" : "" ?>" href="<?= base_url('customer/business') ?>">Business</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= str_contains(service('request')->getUri()->getPath(), "profile") ? "active" : "" ?>" href="<?= base_url('customer/profile') ?>">Profile</a>
              </li>
              <?php if (auth()->user()->is_admin) : ?>
                <li class="nav-item">
                  <a class="nav-link <?= str_contains(service('request')->getUri()->getPath(), "users") ? "active" : "" ?>" href="<?= base_url('admin/users') ?>">User Management</a>
                </li>
              <?php endif; ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <?php if (session()->getFlashData('success')) : ?>
      <div class="alert alert-success w-md-50 w-75 ms-auto me-auto mt-1"><?= esc(session()->getFlashData('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashData('error')) : ?>
      <div class="alert alert-danger w-md-50 w-75 ms-auto me-auto mt-1"><?= esc(session()->getFlashData('error')) ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
  </main>
  <footer class="bg-brown text-light py-4 mt-auto">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <p>&copy; 2024 MenuScanOrder. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>