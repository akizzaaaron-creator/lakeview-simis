<?php /** @var array $old @var array $errors @var bool $editing @var int $currentUserId */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0"><?= $editing ? 'Edit User' : 'Add User' ?></h4>
  <a href="<?= APP_URL ?>/index.php?page=users" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
</div>

<form method="post" action="<?= APP_URL ?>/index.php?page=users&action=<?= $editing ? 'update&id=' . (int)$old['user_id'] : 'store' ?>" class="card">
  <div class="card-body">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label required">Username</label>
        <input type="text" name="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" value="<?= val('username', $old) ?>" autocomplete="off">
        <?= err('username', $errors) ?>
      </div>
      <div class="col-md-5">
        <label class="form-label required">Full Name</label>
        <input type="text" name="full_name" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" value="<?= val('full_name', $old) ?>">
        <?= err('full_name', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Status</label>
        <select name="status" class="form-select">
          <?php foreach (['active', 'inactive'] as $st): ?>
            <option value="<?= $st ?>" <?= ($old['status'] ?? 'active') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= val('email', $old) ?>">
        <?= err('email', $errors) ?>
      </div>
      <div class="col-md-6">
        <label class="form-label required">Role</label>
        <select name="role" class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>">
          <option value="">- select role -</option>
          <?php foreach (ROLES as $k => $lbl): ?>
            <option value="<?= e($k) ?>" <?= ($old['role'] ?? '') === $k ? 'selected' : '' ?>><?= e($lbl) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('role', $errors) ?>
      </div>
      <div class="col-md-6">
        <label class="form-label <?= $editing ? '' : 'required' ?>"><?= $editing ? 'New Password' : 'Password' ?></label>
        <input type="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" autocomplete="new-password" placeholder="<?= $editing ? 'Leave blank to keep current' : 'Min 8 characters' ?>">
        <?= err('password', $errors) ?>
        <?php if ($editing): ?><small class="text-muted">Leave blank to keep existing password.</small><?php endif; ?>
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-end gap-2">
    <a href="<?= APP_URL ?>/index.php?page=users" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi-check-lg me-1"></i><?= $editing ? 'Update User' : 'Create User' ?></button>
  </div>
</form>
