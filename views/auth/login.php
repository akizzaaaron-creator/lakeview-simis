<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
$old    = $old    ?? [];
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
  <style>
    *, *::before, *::after { box-sizing: border-box; }
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(145deg, #0a3d47 0%, #0f7a8a 100%);
      font-family: 'Segoe UI', system-ui, sans-serif;
    }
    .login-wrap {
      width: 100%;
      max-width: 420px;
      padding: 1.5rem;
    }
    .login-card {
      background: #fff;
      border-radius: .9rem;
      box-shadow: 0 20px 50px rgba(0,0,0,.25);
      overflow: hidden;
    }
    .login-top {
      background: #0a3d47;
      padding: 2rem 2rem 1.5rem;
      text-align: center;
      color: #fff;
    }
    .login-top i {
      font-size: 2.4rem;
      color: #f59e0b;
      display: block;
      margin-bottom: .6rem;
    }
    .login-top h4 {
      margin: 0 0 .2rem;
      font-size: 1.15rem;
      font-weight: 700;
    }
    .login-top p {
      margin: 0;
      font-size: .82rem;
      opacity: .7;
    }
    .login-body {
      padding: 2rem;
    }
    .form-label {
      font-size: .82rem;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: .3rem;
    }
    .form-control {
      border-color: #e2e8f0;
      border-radius: .5rem;
      font-size: .9rem;
      padding: .55rem .85rem;
    }
    .form-control:focus {
      border-color: #0f7a8a;
      box-shadow: 0 0 0 3px rgba(15,122,138,.12);
    }
    .input-group-text {
      background: #f8fafc;
      border-color: #e2e8f0;
      color: #94a3b8;
    }
    .btn-signin {
      background: #0f7a8a;
      border: none;
      color: #fff;
      width: 100%;
      padding: .65rem;
      border-radius: .5rem;
      font-size: .95rem;
      font-weight: 600;
      cursor: pointer;
      transition: background .18s;
    }
    .btn-signin:hover { background: #0a5a67; color: #fff; }
    .alert-err {
      background: #fef2f2;
      border-left: 4px solid #fca5a5;
      color: #991b1b;
      border-radius: .4rem;
      padding: .75rem 1rem;
      font-size: .85rem;
      margin-bottom: 1rem;
    }
    .demo-box {
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      padding: 1.25rem 2rem;
    }
    .demo-box p {
      font-size: .78rem;
      font-weight: 700;
      color: #475569;
      margin-bottom: .5rem;
      text-transform: uppercase;
      letter-spacing: .05em;
    }
    .demo-row {
      display: flex;
      align-items: center;
      gap: .6rem;
      padding: .25rem 0;
      font-size: .82rem;
      color: #64748b;
    }
    .demo-row code {
      background: #e8f5f7;
      color: #0f7a8a;
      padding: .15em .5em;
      border-radius: .3rem;
      font-size: .8rem;
      font-weight: 600;
    }
    .demo-row span { color: #94a3b8; }
  </style>
</head>
<body>
<div class="login-wrap">
  <div class="login-card">

    <div class="login-top">
      <i class="bi-mortarboard-fill"></i>
      <h4><?= e(APP_NAME) ?></h4>
      <p>Student Information and Management System</p>
    </div>

    <div class="login-body">
      <?php foreach (flash_get() as $type => $msgs): foreach ($msgs as $m): ?>
        <div class="alert-err"><?= e($m) ?></div>
      <?php endforeach; endforeach; ?>

      <?php if (!empty($errors['login'])): ?>
        <div class="alert-err"><i class="bi-exclamation-circle me-1"></i><?= e($errors['login']) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= APP_URL ?>/index.php?page=login">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi-person"></i></span>
            <input type="text" name="username" class="form-control"
                   value="<?= val('username', $old) ?>"
                   placeholder="Enter your username"
                   autofocus autocomplete="username">
          </div>
        </div>
        <div class="mb-4">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi-lock"></i></span>
            <input type="password" name="password" id="pwField" class="form-control"
                   placeholder="Enter your password"
                   autocomplete="current-password">
            <button class="btn btn-outline-secondary" type="button" id="togglePw" tabindex="-1">
              <i class="bi-eye" id="pwIcon"></i>
            </button>
          </div>
        </div>
        <button type="submit" class="btn-signin">
          <i class="bi-box-arrow-in-right me-1"></i> Sign In
        </button>
      </form>
    </div>

    <div class="demo-box">
      <p>Demo accounts (password: <code>password</code>)</p>
      <div class="demo-row"><code>admin</code>      <span>Administrator</span></div>
      <div class="demo-row"><code>admissions</code> <span>Admissions Officer</span></div>
      <div class="demo-row"><code>finance</code>    <span>Finance Officer</span></div>
      <div class="demo-row"><code>academic</code>   <span>Academic Officer</span></div>
      <div class="demo-row"><code>management</code> <span>Management</span></div>
    </div>

  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
