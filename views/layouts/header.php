<?php /** @var string $title */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ? $title . ' · ' . APP_SHORT : APP_NAME) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= APP_URL ?>/public/css/style.css?v=20261115" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
</head>
<body>

<!-- ── DESKTOP SIDEBAR (fixed, always visible on lg+) ── -->
<aside class="simis-sidebar" id="desktopSidebar">
  <?php require APP_ROOT . '/views/layouts/sidebar_inner.php'; ?>
</aside>

<!-- ── MOBILE DRAWER (Bootstrap offcanvas, shown only on < lg) ── -->
<div class="offcanvas offcanvas-start simis-mobile-drawer"
     id="mobileSidebar" tabindex="-1" aria-label="Navigation">
  <div class="offcanvas-header border-0 pb-0">
    <button type="button"
            class="btn-close btn-close-white ms-auto"
            data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <?php require APP_ROOT . '/views/layouts/sidebar_inner.php'; ?>
  </div>
</div>

<!-- ── MAIN AREA (sits to the right of the fixed sidebar) ── -->
<div class="simis-main">

  <!-- Topbar -->
  <header class="simis-topbar">
    <div class="d-flex align-items-center gap-2">
      <!-- Hamburger – only on mobile -->
      <button class="btn btn-icon d-lg-none"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#mobileSidebar"
              aria-label="Open navigation">
        <i class="bi-list fs-4"></i>
      </button>
      <span class="topbar-title"><?= e($title ?: 'Dashboard') ?></span>
    </div>

    <div class="d-flex align-items-center gap-2">
      <span class="d-none d-md-inline text-muted" style="font-size:.8rem"><?= e(APP_SHORT) ?></span>
      <div class="dropdown">
        <button class="btn btn-user dropdown-toggle"
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="avatar-circle"><?= strtoupper(substr(current_user()['full_name'], 0, 1)) ?></span>
          <span class="d-none d-sm-inline ms-1"><?= e(current_user()['full_name']) ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end mt-1">
          <li class="px-3 py-2">
            <div class="fw-semibold" style="font-size:.88rem"><?= e(current_user()['full_name']) ?></div>
            <div class="text-muted" style="font-size:.75rem"><?= e(ROLES[current_user()['role']] ?? '') ?></div>
          </li>
          <li><hr class="dropdown-divider my-1"></li>
          <li>
            <a class="dropdown-item text-danger"
               href="<?= APP_URL ?>/index.php?page=logout">
              <i class="bi-box-arrow-right me-2"></i>Sign out
            </a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <!-- Page content -->
  <main class="simis-content">
    <?php display_flash(); ?>
