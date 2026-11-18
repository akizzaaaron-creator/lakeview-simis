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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(145deg, #0a3d47 0%, #0f7a8a 100%);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      padding: 1.5rem;
    }

    .login-card {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 25px 60px rgba(0,0,0,.3);
      width: 100%;
      max-width: 400px;
      overflow: hidden;
    }

    /* Header strip */
    .login-header {
      background: #0a3d47;
      padding: 2.2rem 2rem 1.8rem;
      text-align: center;
      color: #fff;
    }
    .login-header i {
      font-size: 2.6rem;
      color: #f59e0b;
      display: block;
      margin-bottom: .6rem;
    }
    .login-header h4 {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: .3rem;
    }
    .login-header p {
      font-size: .78rem;
      opacity: .65;
    }

    /* Form body */
    .login-body {
      padding: 2rem;
    }

    .field-label {
      display: block;
      font-size: .82rem;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: .4rem;
    }

    .input-wrap {
      display: flex;
      align-items: center;
      border: 1.5px solid #e2e8f0;
      border-radius: .55rem;
      overflow: hidden;
      transition: border-color .18s, box-shadow .18s;
      margin-bottom: 1.2rem;
    }
    .input-wrap:focus-within {
      border-color: #0f7a8a;
      box-shadow: 0 0 0 3px rgba(15,122,138,.12);
    }
    .input-icon {
      padding: 0 .85rem;
      color: #94a3b8;
      font-size: 1rem;
      flex-shrink: 0;
    }
    .input-wrap input {
      flex: 1;
      border: none;
      outline: none;
      font-size: .9rem;
      color: #1e293b;
      padding: .65rem .5rem .65rem 0;
      background: transparent;
      font-family: inherit;
    }
    .input-wrap input::placeholder { color: #cbd5e1; }
    .toggle-pw {
      background: none;
      border: none;
      padding: 0 .85rem;
      color: #94a3b8;
      cursor: pointer;
      font-size: 1rem;
      flex-shrink: 0;
      line-height: 1;
    }
    .toggle-pw:hover { color: #0f7a8a; }

    .error-box {
      background: #fef2f2;
      border-left: 4px solid #fca5a5;
      color: #991b1b;
      border-radius: .4rem;
      padding: .75rem 1rem;
      font-size: .84rem;
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .btn-signin {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      width: 100%;
      background: #0f7a8a;
      color: #fff;
      border: none;
      border-radius: .55rem;
      padding: .75rem;
      font-size: .95rem;
      font-weight: 600;
      cursor: pointer;
      transition: background .18s, transform .1s;
      font-family: inherit;
      margin-top: .4rem;
    }
    .btn-signin:hover  { background: #0a5a67; }
    .btn-signin:active { transform: scale(.98); }

    /* Responsive tweak for very small screens */
    @media (max-width: 440px) {
      body { padding: 1rem; }
      .login-header { padding: 1.75rem 1.5rem 1.5rem; }
      .login-body   { padding: 1.5rem; }
    }
  </style>
</head>
<body>

<div class="login-card">

  <div class="login-header">
    <i class="bi-mortarboard-fill"></i>
    <h4><?= e(APP_NAME) ?></h4>
    <p>Student Information and Management System</p>
  </div>

  <div class="login-body">

    <?php foreach (flash_get() as $type => $msgs): foreach ($msgs as $m): ?>
      <div class="error-box"><i class="bi-info-circle"></i><?= e($m) ?></div>
    <?php endforeach; endforeach; ?>

    <?php if (!empty($errors['login'])): ?>
      <div class="error-box">
        <i class="bi-exclamation-circle"></i>
        <?= e($errors['login']) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= APP_URL ?>/index.php?page=login">
      <?= csrf_field() ?>

      <label class="field-label" for="username">Username</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="bi-person"></i></span>
        <input type="text"
               id="username"
               name="username"
               value="<?= val('username', $old) ?>"
               placeholder="Enter your username"
               autofocus
               autocomplete="username">
      </div>

      <label class="field-label" for="pwField">Password</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="bi-lock"></i></span>
        <input type="password"
               id="pwField"
               name="password"
               placeholder="Enter your password"
               autocomplete="current-password">
        <button type="button" class="toggle-pw" id="togglePw" tabindex="-1">
          <i class="bi-eye" id="pwIcon"></i>
        </button>
      </div>

      <button type="submit" class="btn-signin">
        <i class="bi-box-arrow-in-right"></i> Sign In
      </button>
    </form>

  </div>
</div>

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
