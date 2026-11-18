<?php /** @var array $programme @var array $old @var array $errors @var bool $editing */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0"><?= $editing ? 'Edit Programme' : 'Add Programme' ?></h4>
  <a href="<?= APP_URL ?>/index.php?page=programmes" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
</div>

<form method="post" action="<?= APP_URL ?>/index.php?page=programmes&action=<?= $editing ? 'update&id=' . (int)$programme['programme_id'] : 'store' ?>" class="card">
  <div class="card-body">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label required">Code</label>
        <input type="text" name="programme_code" class="form-control <?= isset($errors['programme_code']) ? 'is-invalid' : '' ?>" value="<?= val('programme_code', $old) ?>" placeholder="ICT" maxlength="20" style="text-transform:uppercase">
        <?= err('programme_code', $errors) ?>
      </div>
      <div class="col-md-9">
        <label class="form-label required">Programme Name</label>
        <input type="text" name="programme_name" class="form-control <?= isset($errors['programme_name']) ? 'is-invalid' : '' ?>" value="<?= val('programme_name', $old) ?>">
        <?= err('programme_name', $errors) ?>
      </div>
      <div class="col-md-6">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control"><?= val('description', $old) ?></textarea>
      </div>
      <div class="col-md-3">
        <label class="form-label">Duration</label>
        <input type="text" name="duration" class="form-control" value="<?= val('duration', $old) ?>" placeholder="2 Years (4 Semesters)">
      </div>
      <div class="col-md-3">
        <label class="form-label required">Status</label>
        <select name="status" class="form-select">
          <?php foreach (['active', 'inactive'] as $st): ?>
            <option value="<?= $st ?>" <?= ($old['status'] ?? 'active') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('status', $errors) ?>
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-end gap-2">
    <a href="<?= APP_URL ?>/index.php?page=programmes" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi-check-lg me-1"></i><?= $editing ? 'Update Programme' : 'Add Programme' ?></button>
  </div>
</form>
