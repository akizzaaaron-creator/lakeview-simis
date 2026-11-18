<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/** @var array $old @var array $errors */
$old = $old ?? [];
$errors = $errors ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In · <?= e(APP_SHORT) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= APP_URL ?>/public/css/style.css" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
</head>
<body>
<div class="login-page">
  <div class="login-card card">
    <div class="card-body">

      <!-- Brand -->
      <div class="text-center mb-4">
        <i class="bi-mortarboard-fill login-brand-icon"></i>
        <h5 class="fw-bold mb-1" style="color:#1e293b"><?= e(APP_NAME) ?></h5>
        <p class="text-muted mb-0" style="font-size:.85rem">Sign in to continue</p>
      </div>

      <?php display_flash(); ?>

      <?php if (!empty($errors['login'])): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
          <i class="bi-exclamation-circle-fill flex-shrink-0"></i>
          <span><?= e($errors['login']) ?></span>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= APP_URL ?>/index.php?page=login">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi-person"></i></span>
            <input type="text" name="username"
                   class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                   value="<?= val('username', $old) ?>"
                   autofocus autocomplete="username"
                   placeholder="Enter username">
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi-lock"></i></span>
            <input type="password" name="password" id="pwField"
                   class="form-control"
                   autocomplete="current-password"
                   placeholder="Enter password">
            <button class="btn btn-outline-secondary" type="button" id="togglePw" tabindex="-1">
              <i class="bi-eye" id="pwIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
          <i class="bi-box-arrow-in-right me-1"></i> Sign In
        </button>
      </form>

      <hr class="my-4">
      <p class="mb-2 fw-semibold" style="font-size:.8rem;color:#475569">Demo accounts — password: <code>password</code></p>
      <div class="d-flex flex-column gap-1" style="font-size:.8rem;color:#64748b">
        <div><code>admin</code> — Administrator</div>
        <div><code>admissions</code> — Admissions Officer</div>
        <div><code>finance</code> — Finance Officer</div>
        <div><code>academic</code> — Academic Officer</div>
        <div><code>management</code> — Management</div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('togglePw').addEventListener('click', function () {
    var f = document.getElementById('pwField');
    var i = document.getElementById('pwIcon');
    f.type = f.type === 'password' ? 'text' : 'password';
    i.classList.toggle('bi-eye');
    i.classList.toggle('bi-eye-slash');
  });
</script>
</body>
</html>
